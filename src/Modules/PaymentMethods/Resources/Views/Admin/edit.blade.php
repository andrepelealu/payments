@extends('Admins::Admin.layouts.dashboard')

@section('moduleTitle')
    Update Payment Method
@stop

@section('content')

    <div class="row">
        <div class="small-12 large-6 columns">
            <div class="module-header">
                <p class="module-title"> Update Payment Method </p>
            </div>
            {!! Form::model($paymentmethod, ['route' => ['mc-admin.paymentmethods.update', $paymentmethod->id], 'method' => 'PUT']) !!}
            @include('PaymentMethods::Admin.form', ['submit' => 'Save Payment Method'])
            {!! Form::close() !!}
        </div>
    </div>
@stop