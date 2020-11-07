@extends('layouts.app')

@section('content')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">

<div class="container">



    <div class="row justify-content-center" > 
        <div class="col-md-8">
            <div class="card wrapper fadeInDown" id="formContent" style=" margin: 0px auto;">
                <div class="card-header33" style="margin: 15px;">
                    <img src="{{ asset('dist/img/fan_arch_140x.webp') }}" alt="AdminLTE Logo" class="brand-image elevation-3"
                    style="opacity: .8;width:55px;"> 

                </div>

                <div class="card-body" style=" width: 100%; ">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <div class="form-group">
                            {{-- <label for="email" class="col-form-label text-md-right">eee{{ __('E-Mail Address') }}</label> --}}

                            <div class="">
                                <input placeholder="Email" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group"> 
                            {{-- <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label> --}}

                            <div class="">
                                <input placeholder="Password" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
