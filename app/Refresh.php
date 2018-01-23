<?php

/**
*custom class keke
*not a model
*15-12-2017-22:56
*/

namespace App;

use SpotifyWebAPI;
use App\Account;
use Session;

class Refresh
{
    /**
    *o anki session da ki account'un token bilgisini günceller 
    */
	public static function refreshToken(){
        $api = new SpotifyWebAPI\Session(
                    '53ceb60188cb4708bf9e98372eebb605',
                    '2b1ae47979084b9385eec1829d87ab3a',
                    'http://localhost/zipoti/public/callback'
                );

        $api->refreshAccessToken(Session::get('account')[0]['refresh_token']);

        $newAccessToken=$api->getAccessToken();

        $id=Session::get('account')[0]['id'];
        $account=new Account;
        $account=Account::find($id);
        $account->access_token=$newAccessToken;
        $account->save();

        Session::flush();

        $account=Account::where('id',$id)->get();

        Session::set('account', $account);
        
    }

    /**
    *Sadece Play işleminde. refreshToken'den farkı, play işleminde jukebox account'nın token bilgisini günceller.
    */
    public static function refreshTokenByAccountId($accountId){

        $api = new SpotifyWebAPI\Session(
                    '53ceb60188cb4708bf9e98372eebb605',
                    '2b1ae47979084b9385eec1829d87ab3a',
                    'http://localhost/zipoti/public/callback'
                );
        
        $refreshAccessToken=Account::where('id',$accountId)->first();
        
        $api->refreshAccessToken($refreshAccessToken->refresh_token);

        $newAccessToken=$api->getAccessToken();

        $account=new Account;
        $account=Account::find($refreshAccessToken->id);
        $account->access_token=$newAccessToken;
        $account->save();

    }

}