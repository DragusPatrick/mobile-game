<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 28/11/2017
 * Time: 13:50
 */
?>
@extends('layouts.main')

@section('header')
    <meta http-equiv="refresh" content="2;URL=/">
@endsection
@section('content')
    <div id="hidden-objects-1-page" class="container">
        <br /><br />
        <p style="text-align: center; color: red;">{{ session('error') }}</p>
        <br /><br />
    </div>
    @include('partials._footer')
@endsection
