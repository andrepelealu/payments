@extends('Admins::Admin.layouts.dashboard')

@section('moduleTitle')
   Update Package
@stop

@section('content')

	<div class="module-header">
		<div class="row align-middle">
			<div class="small-12 columns">
				<p class="module-title"> Update Package </p>
			</div>
		</div>
	</div>

    <div class="row">
        <div class="small-12 columns">
            {!! Form::model($package, ['route' => ['mc-admin.packages.update', $package->id], 'method' => 'PUT']) !!}
                @include('Packages::Admin.form', ['submit' => 'Save Package'])
            {!! Form::close() !!}
         </div>
    </div>

@stop