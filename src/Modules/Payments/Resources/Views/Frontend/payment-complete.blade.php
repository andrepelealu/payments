@extends('Pages::Frontend.layouts.default')

@section('metaTitle', 'Payment Complete')
@section('metaDescription', '')

@section('content')

    <div class="row">
        <div class="small-12 columns">
            <h1>Payment Complete: {{ $transaction->reference }}</h1>
        </div>
    </div>

@stop
