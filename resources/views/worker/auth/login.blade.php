@extends('layouts.login')
@section('content')
    <div class="col-xl-12 tab-content">
        <div id="sign-up" class="auth-form tab-pane fade show active  form-validation">


            <form action="{{ route('role.login', ['role' => 'worker']) }}" method="POST">
                @csrf

                <div class="text-center mb-4">
                    <h3 class="text-center mb-2 text-dark">Worker</h3>
                    <span>Login</span>
                </div>
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <input type="hidden" name="role" value="organizer">
                <div class="mb-3">
                    <label for="username" class="form-label required">Username</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="Enter username"
                        value="{{ old('username') }}" required>

                </div>

                <div class="mb-3 position-relative">
                    <label for="password" class="form-label required">Password</label>
                    <input type="password" id="dlab-password" name="password" id="password" class="form-control"
                        placeholder="Enter password" required>
                    <span class="show-pass eye">
                        <i class="fa fa-eye-slash"></i>
                        <i class="fa fa-eye"></i>
                    </span>
                </div>

                <div class="form-row d-flex justify-content-between mt-4 mb-2">
                    <!-- <div class="mb-3">
                                <div class="form-check custom-checkbox mb-0">
                                    <input type="checkbox" name="remember" class="form-check-input" id="customCheckBox1">
                                    <label class="form-check-label" for="customCheckBox1">Remember me</label>
                                </div>
                            </div> -->

                    <div class="mb-4">
                        <!-- <a href="{{ url('page-forgot-password') }}" class="btn-link text-primary">Forgot Password?</a> -->
                    </div>
                </div>

                <button type="submit" class="btn btn-block btn-primary">Sign In</button>
            </form>

            <!-- <div class="new-account mt-3 text-center">
                <p class="font-w500">Don't have an account ? <a class="text-primary" href="{{ route('organizer.register') }}"
                        data-toggle="tab">Register now</a></p>
            </div> -->
        </div>
        <!-- <div class="d-flex align-items-center justify-content-center">
                    <a href="javascript:void(0);" class="text-primary">Terms</a>
                    <a href="javascript:void(0);" class="text-primary mx-5">Plans</a>
                    <a href="javascript:void(0);" class="text-primary">Contact Us</a>
                </div> -->
    </div>
@endsection