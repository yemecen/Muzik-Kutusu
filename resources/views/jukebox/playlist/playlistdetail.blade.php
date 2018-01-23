@extends('main')
@section('title','Play List')

@section('content')
<div class="container">
	<div class="row">
		<table class="table table-striped">
				<thead>
				    <tr>
				      <th>#</th>
				      <th>Sanatçı</th>
				      <th>Şarkı</th>
				      <th></th>
				    </tr>
				 </thead>
				 <tbody>
		@if(!empty($track))
			@foreach($track['items'] as $key=>$item)
				<tr>
					<td>{{$key+1}}</td>
					<td>{{$item['track']['artists'][0]['name']}}</td>
					<td>{{$item['track']['name']}}</td>
					<td> <a href="{{route('playlist.playSong',$item['track']['album']['uri']."_".$item['track']['id']."_".$playlistid)}}">
			        <span class="glyphicon glyphicon-play"></span>
			        </a>
			        </td>
				</tr>
			@endforeach
		@endif
				</tbody>
		</table>
	</div>
</div>
@endsection()