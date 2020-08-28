@extends('Pages::Frontend.layouts.default')

@section('metaTitle', 'Subscribe')
@section('metaDescription', '')

@section('content')
    <div class="row">
        <div class="small-12 columns">
            <h1>{{ $package->present()->getName }}</h1>
            <p>{!! $package->present()->getDescription !!}</p>
            {!! Form::open(['route' => ['subscriptions.package.post', $package->id]]) !!}
                @foreach($paymentMethods as $paymentMethod)
                    {!! Form::radio('paymentmethod_id', $paymentMethod->id, null, ['id' => 'paymentmethod_' . $paymentMethod->id]) !!}
                    {!! Form::label('paymentmethod_' . $paymentMethod->id, $paymentMethod->name) !!}
                @endforeach
                {!! Form::submit('Subscribe') !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
