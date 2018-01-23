@extends('main')
@section('title','Devices')
@section('anothernav')
	@include('partials._anothernav')
@endsection()

@section('content')
<div class="container">
	<div class="row">
		@if(isset($connected) && $connected)
			<div class="alert alert-success" role="alert">
              <p>Device connected</p>
            </div>
		@elseif(isset($nodevice) && $nodevice)
			<div class="alert alert-success" role="alert">
              <p>No active devices!</p>
            </div>
		@elseif(!empty($device))
			@foreach($device as $item)
				<form  action='{{route('device.connectDevice')}}' method='post'/>
				{!! csrf_field() !!}
					<div class="col-sm-6 col-md-4">
					    <div class="thumbnail">
					      <div class="caption">
					        <h3>{{$item->name}}

					        {{--@if($item->is_active=='1')
							<span class="label label-success">on</span>
							@else
							<span class="label label-danger">off</span>
					        @endif--}}
					        </h3>
					        <p>{{$item->type}}</p>
					        <p> <input type="hidden" name="device_id" value="{{$item->id}}">
					        	@if($yarak==$item->id)
					        	<button class="btn btn-success" type="submit">Cihaz'ın Bağlantısını Kopar</button>
					            @else
					            <button class="btn btn-primary" type="submit">Cihaz'a Bağla</button>
					            @endif
					            <!--<button class="btn btn-default" type="submit">Delete</button>!-->
					        </p>
					      </div>
					    </div>
					</div>
				</form>
			@endforeach

		@endif
	</div>
</div>
@endsection()