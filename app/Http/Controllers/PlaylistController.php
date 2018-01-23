<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ixudra\Curl\Facades\Curl;
use App\Playlist;
use App\Jukebox;
use App\Account;
use App\Refresh;
use App\TrackQueue;
use DB;
use Session;
use App\Jobs\PlaySong;
use Carbon\Carbon;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accessToken=Session::get('account')[0]['access_token'];
       
        $authorization='Authorization: Bearer '.$accessToken;
        
        $playlists = Curl::to('https://api.spotify.com/v1/me/playlists')
                    ->withHeader($authorization)
                    ->asJsonResponse(true)
                    ->get();

            if (isset($playlists['error']) && $playlists['error']['status']==401) 
            {
                Refresh::refreshToken();
              
                $accessToken=Session::get('account')[0]['access_token'];
           
                $authorization='Authorization: Bearer '.$accessToken;
                
                $playlists = Curl::to('https://api.spotify.com/v1/me/playlists')
                        ->withHeader($authorization)
                        ->asJsonResponse(true)
                        ->get();
            }

            foreach ($playlists['items'] as $key => $value) 
            {

                    $playlistcheck=Playlist::where('playlist_id',$value['id'])->count();
            
                    if ($playlistcheck==0) 
                    {
                        $newPlaylist=new Playlist;
                        $newPlaylist->playlist_id=$value['id'];
                        $newPlaylist->href=$value['href'];
                        $newPlaylist->images= count($value['images'])!=0 ? $value['images'][0]['url'] : "https://image.ibb.co/fpT6Bb/Ekran_Al_nt_s.png";
                        $newPlaylist->name=$value['name'];
                        $newPlaylist->owner_id=$value['owner']['id'];
                        $newPlaylist->is_share='0';
                        $newPlaylist->account_id=Session::get('account')[0]['id'];
                        $newPlaylist->save();
                    }
            }
   
        $playlist=Playlist::where('account_id',Session::get('account')[0]['id'])->get();

        return view('jukebox.playlist.index')->withPlaylist($playlist);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function shareList(Request $request)
    {
        $playlist_id=$request->playlist_id;

        $playlist=new Playlist;
        $playlist=Playlist::where('id',$playlist_id)->first();
        $playlist->is_share="1";
        $playlist->save();

        $shared=true;

        return view('jukebox.playlist.index')->withShared($shared);
    }
    
    public function getPlayList($jukeboxid){
        
        $jukebox=Jukebox::where('id',$jukeboxid)
                          ->first();
       
        $account_id= $jukebox->account_id;

        $playlist=Playlist::where('account_id',$account_id)
        ->where('is_share','like','%1%')
        ->get();

        /*Sıra da olan şarkıları bilgilerini alıyoruz*/
        $trackqueue=TrackQueue::where('jukebox_id',$jukeboxid)->where('played',0)->orderBy('id')->get();
        
        $trackqueuecollection=collect([]);

        foreach ($trackqueue as $key => $value) 
        {
            $split=explode("_",$value->track);
            $track_id=trim($split[1]);

            $accessToken=Session::get('account')[0]['access_token'];
       
            $authorization='Authorization: Bearer '.$accessToken;

            $selectedTrackUrl="https://api.spotify.com/v1/tracks/".$track_id;

            $trackInfo=Curl::to($selectedTrackUrl)
                            ->withHeader($authorization)
                            ->asJsonResponse(true)
                            ->get();

            if (isset($trackInfo['error']) && $trackInfo['error']['status']==401) 
            {               
                Refresh::refreshToken();

                $accessToken=Session::get('account')[0]['access_token'];
       
                $authorization='Authorization: Bearer '.$accessToken;

                $selectedTrackUrl="https://api.spotify.com/v1/tracks/".$track_id;

                $trackInfo=Curl::to($selectedTrackUrl)
                                ->withHeader($authorization)
                                ->asJsonResponse(true)
                                ->get();
            } 

            $trackqueuecollection->push(['Artist'=>$trackInfo['artists'][0]['name'],'Track'=>$trackInfo['name']]);                
        }
        /*Sıra da olan şarkıları bilgilerini alıyoruz*/
        
        //dd($trackqueuecollection);

        return view('jukebox.playlist.playlist')->withPlaylist($playlist)->withTrackqueuecollection($trackqueuecollection);
    }

    public function getPlayListDetail($playlistid){
        
        $playlist_info=Playlist::where('id',$playlistid)
                          ->first();
        $owner=$playlist_info->owner_id;
        $playlist_id=$playlist_info->playlist_id;
        $playlistAccount_id=$playlist_info->account_id;

        $url="https://api.spotify.com/v1/users/".$owner."/playlists/".$playlist_id."/tracks";

        $accessToken=Session::get('account')[0]['access_token'];
       
        $authorization='Authorization: Bearer '.$accessToken;
        
        $track = Curl::to($url)
                    ->withHeader($authorization)
                    ->asJsonResponse(true)
                    ->get();

            if (isset($track['error']) && $track['error']['status']==401) 
            {
                Refresh::refreshToken();
              
                $accessToken=Session::get('account')[0]['access_token'];
           
                $authorization='Authorization: Bearer '.$accessToken;

                $track = Curl::to($url)
                        ->withHeader($authorization)
                        ->asJsonResponse(true)
                        ->get();
            }
        //dd($track);            
        //echo $track['items'][1]['track']['artists'][0]['name']."<br>";
        //echo $track['items'][1]['track']['name'];
   
        /*foreach ($track['items'] as $key => $value) {
            echo $value['track']['artists'][0]['name']."-".$value['track']['name']."<br>";
            
        }*/
        return view('jukebox.playlist.playlistdetail')->withTrack($track)->withPlaylistid($playlistid);
    }

    public function playSong($track)
    {
        $split=explode("_",$track);

            if (isset($split[0]) && isset($split[1])) 
            {
                $context_uri=trim($split[0]);
                $track_id=trim($split[1]);
                $playlistid=trim($split[2]);
            }

        $playlist_info=Playlist::where('id',$playlistid)->first();

        $playlistAccountAccessToken=Account::where('id',$playlist_info->account_id)->first();
       
        $authorization='Authorization: Bearer '.$playlistAccountAccessToken->access_token;

        $url="https://api.spotify.com/v1/me/player";

        $isPlaying=Curl::to($url)
                        ->withHeader($authorization)
                        ->asJsonResponse(true)
                        ->get(); 

            if(isset($isPlaying['error']) && $isPlaying['error']['status']==401)
            {  
                Refresh::refreshTokenByAccountId($playlist_info->account_id);
               
                $playlistAccountAccessToken=Account::where('id',$playlist_info->account_id)->first();
           
                $authorization='Authorization: Bearer '.$playlistAccountAccessToken->access_token;

                $isPlaying=Curl::to($url)
                                ->withHeader($authorization)
                                ->asJsonResponse(true)
                                ->get();    
            }
        
        
        if($isPlaying['is_playing'])
        {
            $split=explode("_",$track);

            if (isset($split[0]) && isset($split[1])) 
            {
                $context_uri=trim($split[0]);
                $track_id=trim($split[1]);
                $playlistid=trim($split[2]);
            }

            $playlist_info=Playlist::where('id',$playlistid)->first();

            $playlistAccountAccessToken=Account::where('id',$playlist_info->account_id)->first();
           
            $authorization='Authorization: Bearer '.$playlistAccountAccessToken->access_token;

            $url="https://api.spotify.com/v1/me/player/currently-playing";

            $offset=Curl::to($url)
                            ->withHeader($authorization)
                            ->asJsonResponse(true)
                            ->get();

                if(isset($offset['error']) && $offset['error']['status']==401)
                {  
                    Refresh::refreshTokenByAccountId($playlist_info->account_id);
                   
                    $playlistAccountAccessToken=Account::where('id',$playlist_info->account_id)->first();
               
                    $authorization='Authorization: Bearer '.$playlistAccountAccessToken->access_token;

                    $offset=Curl::to($url)
                                ->withHeader($authorization)
                                ->asJsonResponse(true)
                                ->get();    
                }
            
            $duration_ms=$offset['item']['duration_ms'];

            $progess_ms=$offset['progress_ms'];

            $remaining_time_s=intval((($duration_ms-$progess_ms)/1000)+5);
            
            /*yeni*/
            $selectedTrackUrl="https://api.spotify.com/v1/tracks/".$track_id;

            $selectedTrack=Curl::to($selectedTrackUrl)
                            ->withHeader($authorization)
                            ->asJsonResponse(true)
                            ->get();

            $totalDuration=0;

            $jukebox=Jukebox::where('id',$playlist_info->account_id)->first();

            $totalDuration=DB::table('trackqueue')->where('jukebox_id',$jukebox->id)->where('played', 0)->sum('trackduration');
            $totalDuration=$totalDuration==null ? 0 : $totalDuration;

            /*New TrackQueue; kuyruğa girerken ayrıca bu tabloya insert edilir. Sıradaki şarkılar bu talodan alınacak*/
            $trackqueueid=DB::table('trackqueue')->insertGetId(['jukebox_id'=>$jukebox->id,'delay'=>($remaining_time_s+$totalDuration),'trackduration'=> intval($selectedTrack['duration_ms']/1000),'played'=> 0,'track'=>$track]);
            
            $track=$track."_".$trackqueueid;
            /*yeni*/
            
            $job=(new PlaySong($track))
                ->delay(Carbon::now()->addSeconds($remaining_time_s+$totalDuration));
            dispatch($job);

            return redirect("/")->with('status','queue');
        }
        else
        {
            $split=explode("_",$track);

            if (isset($split[0]) && isset($split[1])) 
            {
                $context_uri=trim($split[0]);
                $track_id=trim($split[1]);
                $playlistid=trim($split[2]);
            }

            $playlist_info=Playlist::where('id',$playlistid)->first();

            $playlistAccountAccessToken=Account::where('id',$playlist_info->account_id)->first();
           
            $authorization='Authorization: Bearer '.$playlistAccountAccessToken->access_token;

            $url="https://api.spotify.com/v1/tracks/".$track_id;

            $offset=Curl::to($url)
                            ->withHeader($authorization)
                            ->asJsonResponse(true)
                            ->get();

                if(isset($offset['error']) && $offset['error']['status']==401)
                {  
                    Refresh::refreshTokenByAccountId($playlist_info->account_id);
                   
                    $playlistAccountAccessToken=Account::where('id',$playlist_info->account_id)->first();
               
                    $authorization='Authorization: Bearer '.$playlistAccountAccessToken->access_token;

                    $offset=Curl::to($url)
                                ->withHeader($authorization)
                                ->asJsonResponse(true)
                                ->get();    
                }
            
            $track_number=$offset['track_number']-1;     

            $result=Curl::to("https://api.spotify.com/v1/me/player/play")
                        ->withHeader($authorization)
                        ->withData(array( 'context_uri' => $context_uri, 'offset'=>array('position'=>$track_number)))
                        ->asJson()
                        ->put(); 
            
            return redirect("/")->with('status','playing');
        }

        
        /******************orjin********************************/
        /*$split=explode("_",$track);

            if (isset($split[0]) && isset($split[1])) 
            {
                $context_uri=trim($split[0]);
                $track_id=trim($split[1]);
                $playlistid=trim($split[2]);
            }

        $playlist_info=Playlist::where('id',$playlistid)->first();

        $playlistAccountAccessToken=Account::where('id',$playlist_info->account_id)->first();
       
        $authorization='Authorization: Bearer '.$playlistAccountAccessToken->access_token;

        $url="https://api.spotify.com/v1/tracks/".$track_id;

        $offset=Curl::to($url)
                        ->withHeader($authorization)
                        ->asJsonResponse(true)
                        ->get();

            if(isset($offset['error']) && $offset['error']['status']==401)
            {  
                Refresh::refreshTokenByAccountId($playlist_info->account_id);
               
                $playlistAccountAccessToken=Account::where('id',$playlist_info->account_id)->first();
           
                $authorization='Authorization: Bearer '.$playlistAccountAccessToken->access_token;

                $offset=Curl::to($url)
                            ->withHeader($authorization)
                            ->asJsonResponse(true)
                            ->get();    
            }
        
        $track_number=$offset['track_number']-1;     

        $result=Curl::to("https://api.spotify.com/v1/me/player/play")
                    ->withHeader($authorization)
                    ->withData(array( 'context_uri' => $context_uri, 'offset'=>array('position'=>$track_number)))
                    ->asJson()
                    ->put(); 
        
        return redirect("/");*/            
    }
}
