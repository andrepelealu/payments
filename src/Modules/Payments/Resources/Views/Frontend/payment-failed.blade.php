@extends('Pages::Frontend.layouts.default')

@section('metaTitle', 'Payment Failed')
@section('metaDescription', '')

@section('content')

    <div class="row">
        <div class="small-12 columns">
            <h1>Payment Failed: {{ $transaction->reference }}</h1>
            <ul>
                <li>{{ $transaction->error_code }}</li>
                <li>{{ $transaction->error_message }}</li>
            </ul>
        </div>
    </div>

@stop
