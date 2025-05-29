@extends('layouts.fullwidth')
@section('content')
<div class="col-md-6">
    <div class="error-page">
        <div class="error-inner text-center">
            <div class="dz-error" data-text="403">403</div>
            <h2 class="error-head mb-0"><i class="fa fa-times-circle text-danger me-2"></i>Forbidden Error!</h2>
            <p>You do not have permission to view this resource.</p>
            <a href="{{ url('index') }}" class="btn btn-secondary">BACK TO HOMEPAGE</a>
        </div>
    </div>
</div>
@endsection
