@extends('main')
@section('title','Play List')

@section('content')
<div class="container">
	<div class="row">
		@if(count($trackqueuecollection)>0)
			<div class="panel panel-default">
			  <div class="panel-body">
			    Sırada ki bekleyen şarkılar 
			  </div>
			</div>
			<table class="table table-striped">
					<thead>
					    <tr>
					      <th>#</th>
					      <th>Sanatçi</th>
					      <th>Şarkı</th>
					    </tr>
					 </thead>
					 <tbody>
							@if(!empty($trackqueuecollection))
								@foreach($trackqueuecollection as $key => $item)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$item['Artist']}}</td>
										<td>{{$item['Track']}}</td>
									</tr>
								@endforeach
							@endif
					</tbody>
			</table>
		@endif
	</div>

	<div class="row">
		<div class="panel panel-default">
		  <div class="panel-body">
		    Paylaşılan Şarkı Listeleri
		  </div>
		</div>
		@if(!empty($playlist))
			@foreach($playlist as $item)
			
					<div class="col-sm-6 col-md-4">
					    <div class="thumbnail">
					      <img src="{{$item->images}}" alt="" class="responsive" >
					      <div class="caption">
					        <h3><a href="detail/{{$item->id}}">{{ substr($item->name,0,25)}} {{ strlen($item->name) > 25 ? '...':"" }}</a></h3>
					    					        
					      </div>
					    </div>
					</div>
			
			@endforeach
		 @else
		    <div class="alert alert-info" role="alert">
            	Müzik kutusun da paylaşılan şarkı listesi yoktur.
            </div>	
		@endif
	</div>
</div>
@endsection()