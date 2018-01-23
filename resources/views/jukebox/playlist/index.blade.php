@extends('main')
@section('title','Play List')
@section('anothernav')
	@include('partials._anothernav')
@endsection()

@section('content')
<div class="container">
	<div class="row">
		@if(isset($shared) && $shared)
			<div class="alert alert-success" role="alert">
              <p>Playlist shared</p>
            </div>
		@elseif(!empty($playlist))
			@foreach($playlist as $item)
			<form  action='{{route('playlist.shareList')}}' method='post'/>
					{!! csrf_field() !!}
					<div class="col-sm-6 col-md-4">
					    <div class="thumbnail">
					      <img src="{{$item->images}}" alt="" class="responsive" >
					      <div class="caption">
					        <h3>{{ substr($item->name,0,25)}} {{ strlen($item->name) > 25 ? '...':"" }}</h3>
					        <p></p>
					        <p>
					         	<input type="hidden" name="playlist_id" value="{{$item->id}}">
					            @if($item->is_share==1)
					            	<button class="btn btn-success" type="submit">Paylaşma</button> 
					            @else
					            	<button class="btn btn-primary" type="submit">Paylaş</button> 
					        	@endif
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