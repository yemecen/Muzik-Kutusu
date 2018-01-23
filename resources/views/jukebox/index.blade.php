@extends('main')
@section('title','Jukebox')
@section('anothernav')
	@include('partials._anothernav')
@endsection()

@section('content')
<div class="container">
	<div class="row">
		@if(empty($jukebox))
		 	<div class="alert alert-success" role="alert">
            	Kayıtlı Müzik Kutusu Yok. <a href="{{route('jukebox.create')}}"><span class="label label-danger">Yeni Müzik Kutusu Eklemek için tıklayın!</span></a>
            </div>
		@else		
			
					<div class="col-sm-6 col-md-4">
					    <div class="thumbnail">
					      <div class="caption">
					        <h3>Müzik Kutusu: {{$jukebox->name}}</h3>
					        <p>Bağlı Olduğu Aygıt: <b>{{empty($device_name) ? "Bağlı ayğıt yok" : $device_name}}</b></p>
					        
					      </div>
					    </div>
					</div>
				
			
		@endif	
	</div>
</div>
@endsection()