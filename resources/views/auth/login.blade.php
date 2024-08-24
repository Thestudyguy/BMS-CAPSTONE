@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 80vh;">
            {{-- <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
            <div class="col-sm-6 login-vector d-flex justify-content-center align-items-center"
                style="height: 100%; 
        box-shadow: 11px 14px 33px -8px rgba(0,0,0,0.75);
-webkit-box-shadow: 11px 14px 33px -8px rgba(0,0,0,0.75);
-moz-box-shadow: 11px 14px 33px -8px rgba(0,0,0,0.75);">
                <div class="row justify-content-center">
                    <div class="col-sm-12 text-lg login-title text-dark lead fw-bold h-100">
                        <center>BMS | LogIn</center>
                    </div>
                    <div class="col-sm-12 login-svg">
                        <img class="w-100" src="{{ asset('images/login-image.png') }}" alt="">
                    </div>
                </div>
            </div>
            {{--  --}}
            <div class="col-sm-6 login-form p-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        @if ($errors->any())
                        <div class="alert alert-danger text-danger bg-transparent">
                            @foreach ($errors->all() as $error)
                                <strong>{{ $error }}</strong>
                            @endforeach
                        </div>
                    @endif
                        <label for="Email">User Name</label>
                        {{-- <input id="email" type="email" class="form-control rounded-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus> --}}
                        <input id="username" class="form-control @error('UserName') is-invalid @enderror" type="text"
                            placeholder="Username" name="UserName" value="{{ old('UserName') }}" autocomplete="UserName"
                            autofocus>
                    </div>
                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input id="password" type="password"
                            class="form-control rounded-0 @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password">
                    </div>
                    <button type="submit" class="btn btn-primary rounded-0 mt-3">LogIn</button>
                </form>
            </div>
        </div>
    </div>
@endsection
