<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Device;
use App\Jukebox;
use App\Refresh;
use Session;

class DeviceController extends Controller
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
        
                $devices = Curl::to('https://api.spotify.com/v1/me/player/devices')
                    ->withHeader($authorization)
                    ->asJsonResponse(true)
                    ->get();

               
                if (isset($devices['error']) && $devices['error']['status']==401) 
                {
                      
                    Refresh::refreshToken();
          
                    $accessToken=Session::get('account')[0]['access_token'];
       
                    $authorization='Authorization: Bearer '.$accessToken;
          
                    $devices = Curl::to('https://api.spotify.com/v1/me/player/devices')
                    ->withHeader($authorization)
                    ->asJsonResponse(true)
                    ->get();
                    
                }
                if (!empty($devices['devices'])) 
                {
                    
                    $count=Device::where('device_id',$devices['devices'][0]['id'])->count();
            
                    if($count==0){
                        
                        foreach ($devices['devices'] as $key => $value) {
                            $newDevice=new Device;
                            $newDevice->device_id=$value['id'];
                            $newDevice->is_active=$value['is_active'];
                            $newDevice->name=$value['name'];
                            $newDevice->type=$value['type'];
                            $newDevice->account_id=Session::get('account')[0]['id'];
                            $newDevice->save();
                        }
                    
                    }

                    $device=Device::where('account_id',Session::get('account')[0]['id'])->get();
                    
                    $jukebox=Jukebox::where('account_id',Session::get('account')[0]['id'])->first();
                    
                    $yarak=$jukebox->device_id;

                    return view('jukebox.devices.index')->withDevice($device)->withYarak($yarak);
                }
                if (empty($devices['devices']))
                {
                    $nodevice=true;
                    return view('jukebox.devices.index')->withNodevice($nodevice);
                }
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
    public function connectDevice(Request $request){
        
        $device_id=$request->device_id;

        $jukebox=new Jukebox;
        $jukebox=Jukebox::where('account_id',Session::get('account')[0]['id'])->first();
        $jukebox->device_id=$device_id;
        $jukebox->save();

        $connected=true;

        return view('jukebox.devices.index')->withConnected($connected);
    }
}