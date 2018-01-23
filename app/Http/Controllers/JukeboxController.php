<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Jukebox;
use App\Device;
use App\TrackQueue;
use Session;

class JukeboxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account_id=Session::get('account')[0]['id'];
        
        $count=Jukebox::where('account_id',$account_id)->count();
    
        if($count!=0)
        {

            $jukebox=Jukebox::where('account_id',$account_id)->get()->first();
            
            $device_id=$jukebox->device_id;

            $device=Device::where('id',$device_id)->first();
            
            $device_name=empty($device->name)? null:$device->name;

        return view('jukebox.index')->withJukebox($jukebox)->withDeviceName($device_name);
        }
        else
        {
         return view('jukebox.index');   
        }
       
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jukebox.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jukebox=new Jukebox;
        $jukebox->name=$request->jukeboxname;
        $jukebox->account_id=Session::get('account')[0]['id'];
        $jukebox->device_id="";
        $jukebox->save();

        return redirect()->route('jukebox.index');
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

    public function countTrack($jukeboxId)
    {
        $count=TrackQueue::where('jukebox_id',$jukeboxId)
        ->where('played',0)
        ->count();
    
        return $count;
    }
}
