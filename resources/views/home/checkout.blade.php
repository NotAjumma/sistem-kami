@extends('layouts.login')
@section('content')
    <div class="col-xl-12 tab-content">
        <div id="sign-up" class="auth-form tab-pane fade show active  form-validation">

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form action="{{ route('webform.booking') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nama" required>
                <input type="email" name="email" placeholder="Emel" required>
                <input type="text" name="no_ic" placeholder="No. IC" required>
                <input type="text" name="phone" placeholder="Telefon">
                <select name="shirt_size">
                    <option value="">Pilih saiz baju</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                </select>
                <input type="number" name="bilangan_joran" placeholder="Bilangan Joran" value="1" min="1">
                <button type="submit">Tempah Sekarang</button>
            </form>


            <!-- <div class="new-account mt-3 text-center">
                    <p class="font-w500">Already have an account? <a class="text-primary" href="{{ asset('page-login') }}"
                            data-toggle="tab">Sign in</a></p>
                </div> -->
        </div>
        <!-- <div class="d-flex align-items-center justify-content-center">
                <a href="javascript:void(0);" class="text-primary">Terms</a>
                <a href="javascript:void(0);" class="text-primary mx-5">Plans</a>
                <a href="javascript:void(0);" class="text-primary">Contact Us</a>
            </div> -->
    </div>
@endsection