@extends('main')
@section('title','Jukebox')
@section('anothernav')
	@include('partials._anothernav')
@endsection()

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-5">
				<form class="form-inline" action="{{route('jukebox.store')}}" method="post">
				  {!! csrf_field() !!}
				  <div class="form-group">
				    <label for="exampleInputName2">Müzik Kutusu</label>
				    <input type="text" class="form-control" id="jukeboxname" name="jukeboxname" placeholder="">
				  </div>
				 
				  <button type="submit" class="btn btn-default">Kaydet</button>
				</form>
		</div>
	</div>
</div>
@endsection()