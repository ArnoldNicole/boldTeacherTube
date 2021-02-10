@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<main-app :user="{{auth()->user()}}"></main-app>
</div>
@endsection
