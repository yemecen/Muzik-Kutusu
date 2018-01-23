<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SpotifyWebAPI;
use Ixudra\Curl\Facades\Curl;
use App\Account;
use Session;

class AuthorizationController extends Controller
{
    
    /**s
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {      
        $api = new SpotifyWebAPI\Session(
                    '',
                    '',
                    'http://localhost/zipoti/public/callback'
                );
        $options = [
            'scope' => [
                'playlist-read-private',
                'user-read-private',
                'user-modify-playback-state',
                'user-read-playback-state',
                'user-read-birthdate',
                'user-read-email',
            ],
        ];

        return redirect($api->getAuthorizeUrl($options));
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
    public function callback(Request $request)
    {
        $api = new SpotifyWebAPI\Session(
            '',
            '',
            'http://localhost/zipoti/public/callback'
        );

        $code=$request->input('code');
        
        $api->requestAccessToken($code);

        $accessToken=$api->getAccessToken();
        $refreshToken=$api->getRefreshToken();
        
        $authorization='Authorization: Bearer '.$accessToken;
        

        $account_me = Curl::to('https://api.spotify.com/v1/me')
                    ->withHeader($authorization)
                    ->asJsonResponse(true)
                    ->get();
          
           $count=Account::where('user_name',$account_me['id'])->count();
    
           if($count==0)
           {
                            $account=new Account;
                            $account->user_name = $account_me["id"];
                            $account->email= !empty($account_me["email"]) ? $account_me["email"]:"-";
                            $account->images= !empty($account_me["images"][0]["url"]) ? $account_me["images"][0]["url"]:"-";
                            $account->birthdate=$account_me["birthdate"];
                            $account->external_url=$account_me["external_urls"]["spotify"];
                            $account->country=$account_me["country"];
                            $account->product=$account_me["product"];
                            $account->access_token=$accessToken; 
                            $account->refresh_token=$refreshToken; 
                            $account->account_type=intval(0); 
                            $account->save();

                            $username=$account_me["id"];
                            $account=Account::where('user_name',$username)->get();

                            Session::set('account',$account);
                    
                            return redirect('/');
            }
            else
            {
                            $username=$account_me["id"];
                            $account=Account::where('user_name',$username)->get();

                            Session::set('account',$account);    

                            return redirect('/');
            }
                   
    }
    
    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}
