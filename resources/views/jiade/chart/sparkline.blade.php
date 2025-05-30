@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <!-- row -->

    <div class="row">
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Line Chart</h4>
                </div>
                <div class="card-body">
                    <span id="sparklinedash"></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Site Traffic</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="sparkline8"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Site Traffic</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="sparkline9"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bar Chart</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="spark-bar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Stacked Bar Chart</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="StackedBarChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tristate charts</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="tristate"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Composite Line Chart</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="sparkline-composite-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Composite Bar Chart</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="composite-bar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bullet Chart</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="bullet-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Pie Chart</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="sparkline11"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Box Plot</h4>
                </div>
                <div class="card-body">
                    <div class="ico-sparkline">
                        <div id="boxplot"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection