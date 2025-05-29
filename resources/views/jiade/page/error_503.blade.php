@extends('layouts.fullwidth')
@section('content')
<div class="col-md-6">
    <div class="error-page">
        <div class="error-inner text-center">
            <div class="dz-error" data-text="503">503</div>
            <h2 class="error-head mb-0"><i class="fa fa-times-circle text-danger"></i>Service Unavailable</h2>
            <p>Sorry, we are under maintenance!</p>
            <a href="{{ url('index') }}" class="btn btn-secondary">BACK TO HOMEPAGE</a>
        </div>
    </div>
</div>
@endsection
