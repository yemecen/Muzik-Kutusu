<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jukebox;
use DB;

use Ixudra\Curl\Facades\Curl;
use App\Playlist;
use App\Account;
use App\Refresh;
use App\TrackQueue;
use Session;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       /**
        *Karmaşık Orm kullanımı, Çalışıyor
        $jukebox=DB::table('account')
        ->select(DB::raw('count(*) AS topqueue'),'trackqueue.played','jukebox.id','account.images','jukebox.name')
        ->join('jukebox','jukebox.account_id','=','account.id')
        ->join('trackqueue','trackqueue.jukebox_id','=','jukebox.id')
        ->groupBy('jukebox.id','trackqueue.played','account.images','jukebox.name')
        ->having('trackqueue.played','=',0)
        ->get();
        */
        
        $jukebox=DB::table('account')
        ->join('jukebox','jukebox.account_id','=','account.id')
        ->get();  
   
        return view('customer.welcome')->withJukebox($jukebox);
        //dd($jukebox);
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

    public function search(Request $request)
    {
        $search=$request->search;
        
        //$jukebox=Jukebox::where('name','like','%'.$search.'%')->get();

        $jukebox=DB::table('account')
        ->join('jukebox','jukebox.account_id','=','account.id')
        ->where('name','like','%'.$search.'%')
        ->get();  
        
        return view('customer.welcome')->withJukebox($jukebox);
    } 

     public function test()
    {
        
    }

}
