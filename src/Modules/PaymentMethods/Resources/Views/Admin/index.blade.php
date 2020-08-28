@extends('Admins::Admin.layouts.dashboard')

@section('moduleTitle')
    Payment Methods
@stop

@section('content')

    <div class="module-header">
        <div class="row align-middle">
            <div class="small-12 columns">
                <p class="module-title">Payment Methods</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="table-block">
                <div class="table-block-header"></div>
                <div class="table-block-content">
                    <table class="data-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Mode</th>
                            <th>Last Updated</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($paymentmethods->count() > 0)
                            @foreach ($paymentmethods as $paymentmethod)
                                <tr>
                                    <td data-label="Name">
                                        <a href="{{ route('mc-admin.paymentmethods.edit', $paymentmethod->id) }}">
                                            {{ $paymentmethod->present()->getName }}
                                        </a>
                                    </td>
                                    <td data-label="Status">{!! $paymentmethod->present()->getActiveLabel !!}</td>
                                    <td data-label="Mode">{!! $paymentmethod->present()->getModeLabel !!}</td>
                                    <td data-label="Last Updated">
                                        <span class="secondary">{{ $paymentmethod->present()->getUpdatedAt }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('mc-admin.paymentmethods.edit', $paymentmethod->id) }}" class="icon-button info"><i class="far fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="no-results">
                                <td colspan="5" class="text-center">There are no payment methods available.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop