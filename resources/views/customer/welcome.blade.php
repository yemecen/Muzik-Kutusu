@extends('main')
@section('title','Anasayfa')

@section('content')
<div class="container">
    <div class="row">
        
        @if(!Session::has('account'))
            <div class="jumbotron">
              <h1>&#9835;&#9834;&#9836;&#9834;&#9834;&#9835;</h1>
              <p>Spotify müzik kutusuna dönüştür veya çalmasını istediğin şarkıyı seç
              </p>
              <p><a class="btn btn-success btn-lg" href="login" role="button">Giriş Yap</a></p>
            </div>
        @endif
        @if(Session::has('account'))
    
            <!--<div class="alert alert-success" role="alert">
            {{--Session::get('account')--}}
            </div>!-->

        @endif
        @if(Session::has('status') && Session::get('status')=='playing')
    
            <div class="alert alert-success" role="alert">
            Seçtiğin şarkı şu an çalıyor.
            </div>

        @endif
        @if(Session::has('status') && Session::get('status')=='queue')
    
            <div class="alert alert-info" role="alert">
            Seçtiğin şarkı çalma sırasına eklendi.
            </div>

        @endif

    </div>

    <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div id="imaginary_container"> 
                            <form  action='{{route('search')}}' method='post'/>
                            {!! csrf_field() !!}
                            <div class="input-group stylish-input-group input-group-lg">
                                <input type="text" class="form-control" name="search" 
                                     placeholder="Müzik Kutusu Ara" >
                                <span class="input-group-addon">
                                    <button type="submit">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>  
                                </span>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
    </div>
    
    <div class="row">
       @foreach($jukebox as $item)
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            @if($item->images=='-')
                                <img src="img/Spotify_Icon.png" class="responsive">  
                            @else    
                                <img src="{{$item->images}}" class="responsive">
                            @endif
                          <div class="caption text-center">
                            <h3><a href="playlist/{{$item->id}}"> &#9835; <span class="badge" id="{{$item->id}}"></span> {{$item->name}}</a></h3>
                          </div>
                        </div>
                    </div>
            @endforeach

    </div>

</div>
@endsection()

@section('script')
<script>
    $(document).ready(function(){

       $.each($('.thumbnail div h3 a span'),function (index, value) { 

            let jukeboxId=$(this).attr('id');
        
            $.ajax({
                  type: "GET",
                  url: "http://localhost/zipoti/public/jukebox/"+jukeboxId+"/CountTrack",
                  cache: false,
                  context:this,
                  success: function(data){
                    if(data != "0")
                    $(this).text(data);
                  
                  }
                });

        });//each

    });
</script>
@endsection()