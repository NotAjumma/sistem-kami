@extends('layouts.login')
@section('content')
    <div class="col-xl-12 tab-content">
        <div id="sign-up" class="auth-form tab-pane fade show active  form-validation">

            <form action="{{ route('organizer.submit_register') }}" method="post">
                @csrf
                <div class="text-center mb-4">
                    <h3 class="text-center mb-2 text-dark">Sign up</h3>
                    <span>Register your organizer account</span>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-3">
                    <label for="name" class="form-label required">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"
                        placeholder="Enter your Full Name">
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label required">Username</label>
                    <input type="text" class="form-control" name="username" id="username" value="{{ old('username') }}"
                        placeholder="Enter your username">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label required">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}"
                        placeholder="Enter your Email">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label required">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}"
                        placeholder="Enter your Phone Number">
                </div>
                <div class="mb-3 position-relative">
                    <label class="form-label required">Password</label>
                    <input type="password" id="dlab-password" class="form-control" name="password" value=""
                        placeholder="Enter your password">
                    <!-- <span class="show-pass eye">
                            <i class="fa fa-eye-slash"></i>
                            <i class="fa fa-eye"></i>

                        </span> -->
                </div>
                <div class="mb-3 position-relative">
                    <label class="form-label required">Re-enter Password</label>
                    <input type="password" id="dlab-password-confirm" class="form-control" name="password_confirmation" value=""
                        placeholder="Re-enter your password">
                    <!-- <span class="show-pass-confirm eye">
                            <i class="fa fa-eye-slash"></i>
                            <i class="fa fa-eye"></i>

                        </span> -->
                </div>
                <button type="submit" class="btn btn-block btn-primary">Sign me up</button>

            </form>
            <div class="new-account mt-3 text-center">
                <p class="font-w500">Already have an account? <a class="text-primary"
                        href="{{ route('organizer.login') }}">Login</a></p>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center justify-content-center">
                    <a href="javascript:void(0);" class="text-primary">Terms</a>
                    <a href="javascript:void(0);" class="text-primary mx-5">Plans</a>
                    <a href="javascript:void(0);" class="text-primary">Contact Us</a>
                </div> -->
    </div>
@endsection

<script>
</script>