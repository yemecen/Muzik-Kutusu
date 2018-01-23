<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ixudra\Curl\Facades\Curl;
use App\Playlist;
use App\Account;
use App\Refresh;
use App\TrackQueue;
use Carbon\Carbon;
use Log;

class PlaySong implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $track;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($track)
    {
        $this->track=$track;    
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $split=explode("_",$this->track);

            if (isset($split[0]) && isset($split[1])) 
            {
                $context_uri=trim($split[0]);
                $track_id=trim($split[1]);
                $playlistid=trim($split[2]);
                $trackqueueid=trim($split[3]);
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
        /*Yeni*/
        $trackqueue=new TrackQueue;
        $trackqueue=TrackQueue::find($trackqueueid);
        $trackqueue->played=1;
        $trackqueue->save();

        Log::info($this->track);

    }
}
