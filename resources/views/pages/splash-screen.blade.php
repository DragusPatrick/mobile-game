@extends('layouts.main')

@section('header')
    <meta http-equiv="refresh" content="3;URL=/signin">
@endsection

@section('content')
    <div id="index">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">
        <h3 class="index-p">{{ session('config.texts')->pt->splash->text_presents }}</h3>
    </div>
@endsection
