@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-8">
		<registration :user="{{auth()->user()}}">
		</registration>
	</div>
</div>
@endsection