@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Light Gallery</h4>
                </div>
                <div class="card-body pb-1">
                    <div id="lightgallery" class="row">
                        <a href="{{ asset('images/big/img1.jpg') }}" data-src="{{ asset('images/big/img1.jpg') }}"
                            class="lg-item col-lg-3 col-md-6 mb-4">
                            <img src="{{ asset('images/big/img1.jpg') }}" class="rounded" alt="" style="width:100%;">
                        </a>
                        <a href="{{ asset('images/big/img2.jpg') }}" data-src="{{ asset('images/big/img2.jpg') }}"
                            class=" lg-item col-lg-3 col-md-6 mb-4">
                            <img src="{{ asset('images/big/img2.jpg') }}" class="rounded" alt="" style="width:100%;">
                        </a>
                        <a href="{{ asset('images/big/img3.jpg') }}" data-src="{{ asset('images/big/img3.jpg') }}"
                            class=" lg-item col-lg-3 col-md-6 mb-4">
                            <img src="{{ asset('images/big/img3.jpg') }}" class="rounded" alt="" style="width:100%;">
                        </a>
                        <a href="{{ asset('images/big/img4.jpg') }}" data-src="{{ asset('images/big/img4.jpg') }}"
                            class="lg-item col-lg-3 col-md-6 mb-4">
                            <img src="{{ asset('images/big/img4.jpg') }}" class="rounded" alt="" style="width:100%;">
                        </a>
                        <a href="{{ asset('images/big/img5.jpg') }}" data-src="{{ asset('images/big/img5.jpg') }}"
                            class="lg-item col-lg-3 col-md-6 mb-4">
                            <img src="{{ asset('images/big/img5.jpg') }}" class="rounded" alt="" style="width:100%;">
                        </a>
                        <a href="{{ asset('images/big/img6.jpg') }}" data-src="{{ asset('images/big/img6.jpg') }}"
                            class="lg-item col-lg-3 col-md-6 mb-4">
                            <img src="{{ asset('images/big/img6.jpg') }}" class="rounded" alt="" style="width:100%;">
                        </a>
                        <a href="{{ asset('images/big/img7.jpg') }}" data-src="{{ asset('images/big/img7.jpg') }}"
                            class="lg-item col-lg-3 col-md-6 mb-4">
                            <img src="{{ asset('images/big/img7.jpg') }}" class="rounded" alt="" style="width:100%;">
                        </a>
                        <a href="{{ asset('images/big/img8.jpg') }}" data-src="{{ asset('images/big/img8.jpg') }}"
                            class=" lg-item col-lg-3 col-md-6 mb-4">
                            <img src="{{ asset('images/big/img8.jpg') }}" class="rounded" alt="" style="width:100%;">
                        </a>
                    </div>
                </div>
            </div>
            <!-- /# card -->
        </div>
    </div>
</div>
@endsection
