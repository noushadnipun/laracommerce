@extends('frontend.layouts.master')

@section('page-content')

<!-- customer login start -->

<div class="customer_login mt-60">
    <div class="container">
        <div class="row">
            <!--login area start-->
            <div class="col-lg-6 col-md-6">
                <div class="account_form">
                    <h2>login</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <p>   
                            <label for="email">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class=" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </p>
                        <p>   
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password" class=" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </p>   
                        <div class="login_submit">
                            <a href="{{ route('password.request') }}">Lost your password?</a>
                                <label class="" for="remember">
                                    {{ __('Remember Me') }}
                                    <input class="" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                </label>
                            <button type="submit">login</button>
                            
                        </div>

                    </form>
                    </div>    
            </div>
            <!--login area start-->

            <!--register area start-->
            <div class="col-lg-6 col-md-6">
                <div class="account_form register">
                    <h2>Register</h2>
                    <form action="#">
                        <p>   
                            <label>Email address  <span>*</span></label>
                            <input type="text">
                        </p>
                        <p>   
                            <label>Passwords <span>*</span></label>
                            <input type="password">
                        </p>
                        <div class="login_submit">
                            <button type="submit">Register</button>
                        </div>
                    </form>
                </div>    
            </div>
            <!--register area end-->
        </div>
    </div>    
</div>

<!-- customer login end -->



@endsection