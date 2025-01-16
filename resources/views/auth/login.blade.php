@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 80vh;">
            {{-- <div class="col-sm-6 login-vector d-flex justify-content-center align-items-center"
                style="height: 100%;">
                <div class="row justify-content-center">
                    <div class="col-sm-12 text-lg login-title text-dark lead fw-bold h-100">
                        <center>BMS | LogIn</center>
                    </div>
                    <div class="col-sm-12 login-svg">
                        <center><img class="w-25" src="{{ asset('public/images/Rams_logo.png') }}" alt=""></center>
                    </div>
                </div>
            </div>
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
            </div> --}}
            <div class="col-sm-6 login-vector d-flex justify-content-center align-items-center"
                style="height: 100%;">
                <div class="row justify-content-center border p-5" style="background: linear-gradient(200.48deg, #063D58 31.33%, #EFC280 72.54%); height: 60%;
                border-top-left-radius: 20px;
                border-bottom-left-radius: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                ">
                    <div class="col-sm-12 login-svg">
                        <center><img class="w-50" src="{{ asset('images/Rams_logo.png') }}" alt=""></center>
                    </div>
                    <div class="col-sm-12 text-xl login-title text-light lead fw-bold h-100">
                        <center>BOOKKEEPING MANAGEMENT SYSTEM</center>
                    </div>
                    
                </div>
                <div class="col-sm-6 border login-form p-5" 
                style="background: linear-gradient(206.65deg, #073E59 61.1%, #F0C280 99.54%); height: 70%; display: flex; justify-content: center; align-items: center;
                border-top-right-radius: 20px;
                border-bottom-right-radius: 20px;
                "
                >
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
                            <label for="Email" class="text-light">User Name</label>
                            <input id="username" class="form-control @error('UserName') is-invalid @enderror" type="text"
                                placeholder="Username" name="UserName" value="{{ old('UserName') }}" autocomplete="UserName"
                                autofocus>
                        </div>
                        <div class="form-group">
                            <label for="Password" class="text-light">Password</label>
                            <input id="password" type="password"
                                class="form-control rounded-1 @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password" placeholder="password">
                        </div>
                        <button type="submit" class="btn btn-primary rounded-1 fw-bold mt-3">LogIn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- style="
                box-shadow: 0px 4px 4px 5px rgba(0, 0, 0, 0.25);
    border-radius: 0px 50 50 0px;
    background: linear-gradient(200.48deg, #063D58 31.33%, #EFC280 72.54%);
    position: relative;
    display: flex
;
    flex-direction: column;
    align-items: center;
    padding: 88px 88px 165px 88px;
    width: 350px;
    height: 60vh;
    box-sizing: border-box;
                " --}}