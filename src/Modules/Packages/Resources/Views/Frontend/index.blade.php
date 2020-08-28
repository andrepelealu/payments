@extends('Pages::Frontend.layouts.default')

@section('metaTitle', 'Subscribe')
@section('metaDescription', '')

@section('content')
    <div class="row">
        <div class="small-12 columns">
            <div class="row small-up-1 medium-up-3" id="subscriptions">
                @foreach($packages as $package)
                    <div class="column column-block">
                        <div class="subscription {{ (auth()->check() && auth()->user()->subscription_package_id == $package->id ? 'active' : '') }}">
                            <h4>{!! $package->present()->getPrice !!}/{!! $package->present()->getHumanFrequency !!}</h4>
                            <h3>{{ $package->present()->getName }}</h3>
                            <p>{!! $package->present()->getDescription !!}</p>
                            @if(auth()->check() && $package->isSubscribedToPackage(auth()->user()))
                                <p><a href="{{ route('subscriptions.cancel') }}" class="button">Cancel</a></p>
                            @else
                                @if(auth()->check() && is_null(auth()->user()->subscription_package_id))
                                    <p><a href="{{ route('subscriptions.package', $package->id) }}" class="button">Choose</a></p>
                                @else
                                    <p><a href="{{ route('subscriptions.upgrade', $package->id) }}" class="button">Upgrade</a></p>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop
