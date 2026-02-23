@extends('layouts.admin.default')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-9 col-lg-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 d-block pb-0">
                            <h2 class="card-title mb-3">Package Chart</h2>
                        </div>

                        <div class="card-body position-relative">

                            <!-- Spinner -->
                            <div id="chart-spinner" class="text-center py-5">
                                <div class="spinner-border text-primary"></div>
                                <div class="mt-2">Loading data...</div>
                            </div>

                            <!-- Chart -->
                            <div style="position: relative; height: 400px;">
                                <canvas id="package-horizontal-bar-chart"></canvas>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 d-block pb-0">
                            <h2 class="card-title mb-3">Slot Chart</h2>
                        </div>

                        <div class="card-body position-relative">

                            <!-- Spinner -->
                            <div id="slot-chart-spinner" class="text-center py-5">
                                <div class="spinner-border text-primary"></div>
                                <div class="mt-2">Loading data...</div>
                            </div>

                            <!-- Chart -->
                            <div id="slot-horizontal-bar-chart"
                                class="ct-chart"
                                style="height:400px; display:none;">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-12">
            <div class="row">
                <div class="col-xl-12 col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h2 class="card-title mb-2">Add On Chart</h2>
                        </div>
                        <div class="card-body position-relative">

                            <!-- Spinner -->
                            <div id="addon-chart-spinner" class="text-center py-5">
                                <div class="spinner-border text-primary"></div>
                                <div class="mt-2">Loading data...</div>
                            </div>

                            <!-- Chart -->
                            <div id="addon-horizontal-bar-chart"
                                class="ct-chart"
                                style="height:400px; display:none;">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
@endpush