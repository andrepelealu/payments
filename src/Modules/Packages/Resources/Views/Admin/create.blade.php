@extends('Admins::Admin.layouts.dashboard')

@section('moduleTitle')
    Create Package
@stop

@section('content')

	<div class="module-header">
		<div class="row align-middle">
			<div class="small-12 columns">
				<p class="module-title"> Create Package </p>
			</div>
		</div>
	</div>

    <div class="row">
        <div class="small-12 columns">
            {!! Form::open(['route' => 'mc-admin.packages.store']) !!}
                @include('Packages::Admin.form', ['submit' => 'Create Package'])
            {!! Form::close() !!}
        </div>
    </div>

@stop