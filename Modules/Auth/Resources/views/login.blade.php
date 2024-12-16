@extends('auth::layouts.master')

@section('content')
    <div class="be-wrapper be-login">
        <div class="be-content">
            <div class="main-content container-fluid">
                <div class="splash-container">
                    <div class="card card-border-color card-border-color-primary">
                        <div class="card-header"><img class="logo-img" src="/assets/img/logo-xx.png" alt="logo"
                                width="102" height="27"><span class="splash-description">Please enter your user
                                information.</span></div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    {{-- <input class="form-control" id="username" type="text" placeholder="Username" autocomplete="off"> --}}
                                    <input id="email" type="text"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        placeholder="Email or Username"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    {{-- <input class="form-control" id="password" type="password" placeholder="Password"> --}}
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="Password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group row login-tools">
                                    <div class="col-6 login-remember">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="remember">Remember Me</label>
                                        </div>
                                    </div>
                                    @if (Route::has('password.request'))
                                    <div class="col-6 login-forgot-password"><a href="pages-forgot-password.html">Forgot
                                            Password?</a></div>
                                    @endif
                                </div>
                                <div class="form-group login-submit">
                                    <button type="submit" class="btn btn-primary btn-xl">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                                        
                            </form>
                        </div>
                    </div>
                    @if (Route::has('register'))
                    <div class="splash-footer"><span>Don't have an account? <a href="pages-sign-up.html">Sign Up</a></span>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
