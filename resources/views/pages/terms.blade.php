<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 13:20
 */
?>

@extends('layouts.main')
@section('content')
<div id="terms-page">
    <div class="terms-header">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">
    </div>

    <h3>{{ session('config.texts')->pt->tos->text_title }}</h3>
    {!!  session('config.texts')->pt->tos->text_content !!}
</div>
@include('partials._footer')
@endsection

