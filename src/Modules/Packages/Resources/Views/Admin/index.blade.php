@extends('Admins::Admin.layouts.dashboard')

@section('moduleTitle')
    Packages
@stop

@section('content')

    <div class="module-header">
        <div class="row align-middle">
            <div class="small-12 large-6 columns">
                <p class="module-title">Packages</p>
            </div>
            <div class="small-12 large-6 columns">
                <ul class="button-list">
                    <li>
                        <a href="{{ route('mc-admin.packages.create') }}" class="button warning">Create Package</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="table-block">
                <div class="table-block-header">@include('Packages::Admin.sub-menu')</div>
                <div class="table-block-content">
                    <table class="data-table" id="sortable">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Last Updated</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($packages->count() > 0)
                            @foreach ($packages as $package)
                                <tr id="packages_{{ $package->id }}">
                                    <td class="handle"><i class="fa fa-arrows-v"></i></td>
                                    <td data-label="Name">
                                        @if($package->trashed())
                                            {{ $package->present()->getName }}
                                        @else
                                            <a href="{{ route('mc-admin.packages.edit', $package->id) }}"> {{ $package->present()->getName }}</a>
                                        @endif
                                    </td>
                                    <td data-label="Status">{!!  $package->present()->getStatusLabel !!}</td>
                                    <td data-label="Price">{!! $package->present()->getPrice !!}</td>
                                    <td data-label="Updated At">
                                        <span class="secondary">
                                            {{ $package->present()->getUpdatedAt }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($package->trashed())
                                            <a href="{{ route('mc-admin.packages.confirm-restore', $package->id) }}" class="icon-button trigger-reveal success"><i class="far fa-sync-alt"></i></a>
                                        @else
                                            <a href="{{ route('mc-admin.packages.edit', $package->id) }}" class="icon-button info"><i class="far fa-edit"></i></a>
                                            <a href="{{ route('mc-admin.packages.confirm-delete', $package->id) }}" class="icon-button trigger-reveal alert"><i class="far fa-trash-alt"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="no-results">
                                <td colspan="6" class="text-center">There are no {{ $filter }} packages available.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop