@extends('layouts.master')
@section('content')

<!-- //LOCATION: remax/public/search 
-->
<div class="mainContent">
@if (isset($houses))
@include('include.res', compact($houses))
@yield('houses')
@endif
		</div>
		@stop
