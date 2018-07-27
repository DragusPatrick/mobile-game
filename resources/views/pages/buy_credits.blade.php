<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 04/12/2017
 * Time: 19:16
 */
?>

@extends('layouts.main')
@section('content')
    <div id="signin-page" style="height: 150px">
        <div class="logo">
            <img src="{{ url('/images/logo.png') }}" class="index-logo">
        </div>
        <div id="general-header">
            <div class="heading">{!! session('config.texts')->pt->credits->text_play_more !!}</div>
            <div class="text">{!! session('config.texts')->pt->credits->text_buy_more !!}</div>
        </div>

    </div>
    <div class="buy-credits-container">
        <form action="/buy-credits" method="post">
            {{ csrf_field() }}
            @foreach($credits as $creditPackage)
                <!-- Credit item -->
                    <button type="submit" class="red-btn buy-btn full-width relative" name="package_id" value="{{ $creditPackage->id }}">
                        <span class="buy-icon"><img src="{{ url('/images/credit-icon-asset.jpg') }}" /></span>
                        <span class="buy-amount">{{ $creditPackage->name }}</span>
                        <span class="buy-price">
                                <img src="{{ url('/images/credit-cost-asset2.png') }}" />
                                <span class="buy-price-text">{{ $creditPackage->cost }}</span>
                            </span>
                    </button>
                    <!-- /Credit item -->
                @endforeach
        </form>
    </div>
    <br />
    <div class="clearfix"></div>
    <div class="pull-footer-up">
        @include('partials._footer')
    </div>
@endsection
