@extends('Pages::Frontend.layouts.default')

@section('metaTitle', 'Payment Page')
@section('metaDescription', '')

@section('content')
    <div class="row">
        <div class="small-12 columns">
            <h1>Payment Details</h1>
            @include('Payments::Frontend.forms.' . kebab_case($paymentMethod->name) . '-form', ['paymentMethod' => $paymentMethod, 'object' => $object])
        </div>
    </div>
@stop

@section('scripts')
    @parent
    @include('Payments::Frontend.scripts.' . kebab_case($paymentMethod->name) . '-scripts', $paymentMethod)
@stop
