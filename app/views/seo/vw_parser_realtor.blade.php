@extends('layouts.master')
@section('content')

<div class="mainContent">
	<div class="row">
		<div class="large-12 columns">
			<h1 class="docs header">Parser from realtor.com</h1>
			
			<div class="panel">
				
				{{Form::open(array('route' => 'parser-realtor-save', 'method'=>'POST'))}}
			
					{{Form::label('city', 'City')}}
					{{Form::text('city', 'Skokie_IL')}}
					<br/>			
					
					{{Form::label('numb', 'Number Of Houses')}}
					{{Form::text('numb', '200')}}
					<br/>


					{{ Form::submit('Parse', array('class'=>'secondary button small radius'))}}
					
							


			{{Form::close()}}


	<br>
	<hr>

				{{Form::open(array('route' => 'parser-realtor-save2', 'method'=>'POST'))}}
			
					{{Form::label('url', 'URL')}}
					{{Form::text('url')}}
					<br/>			
					
					

					{{ Form::submit('Parse URL', array('class'=>'secondary button small radius'))}}
					
							


			{{Form::close()}}



			</div>
						
		</div>
	</div>
</div>
@stop

<!-- //LOCATION: remax/public/about 
-->