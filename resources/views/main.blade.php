<!DOCTYPE html>
<html>
    <head>
        @include('partials._head')
    </head>
    <body>      

    <div class="container">
    <div class="row">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                
                <div class="navbar-header">                 
                      <a class="navbar-brand" href="/zipoti/public">
                        <img src="https://image.ibb.co/j54h1b/2018_01_04.png" height="30px" width="25px">
                      </a>
                </div>
      
                <ul class="nav navbar-nav navbar-right">
                    @if(Session::has('account'))
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Session::get('account')[0]["user_name"]}} <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="jukebox">Müzik Kutusu</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{route('logout')}}">Çıkış Yap</a></li>
                      </ul>
                    </li>
                     @else
                    <li><a href="login"><img src="img/Spotify_Icon.png" height="20px" width="20px"> Giriş Yap</a></li>
                     @endif
                </ul>
                    
            </div>
        </nav>
    </div>
</div> 
            @yield('anothernav')
            
            @yield('content')
        
            @yield('script')
            
    </body>
</html>
