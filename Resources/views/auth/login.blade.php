@extends('layouts.front.app', ['headerShrink' => 'header-alt shrink'])

@section('content')
    @include('shop::front.partials.registered_user_head')
    <div class="page-wrapper" style="display: flex;align-items: center;justify-content: center;">
        <div class="access-forms" style="width: 300px;margin: 90px 0px;">
            @include('admin.notify')
            <form method="post" class="form-alt selected" id="form-login" action="{{ route('shop.login', ['languageSlug' => $languageSlug]) }}">
                @csrf
                <div class="form-head">
                    <h3>{{ __('shop::front.login.login') }}</h3>
                </div>

                <div class="form-body">

                    <div class="form-row">
                        <label class="form-label" for="email">
                            {{ __('shop::front.login.email') }}
                            <span class="asterisk">*</span>
                        </label>

                        <div class="input-container ">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label" for="password">
                            {{ __('shop::front.login.password') }}
                            <span class="asterisk">*</span>
                        </label>

                        <div class="input-container ">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                            @enderror

                            <div class="tooltip-box">
                                <span>{{ __('shop::front.login.hide_password') }}</span>
                                <span>{{ __('shop::front.login.show_password') }}</span>

                                <img class="toggle-password" src="{{ asset('front/assets/icons/eye-slash.svg') }}" alt="">
                                <img class="toggle-password" src="{{ asset('front/assets/icons/eye.svg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="submit-button">{{ __('shop::front.login.login_submit') }}</button>

                    <p>
                        @if (Route::has('shop.password.request'))
                            <a href="{{ route('shop.password.request', ['languageSlug' => $languageSlug]) }}" data-target="reset-password">
                                {{ __('shop::front.login.forgot_your_password') }}
                            </a>
                        @endif
                    </p>

                    <a href="{{ route('shop.register', ['languageSlug' => $languageSlug]) }}" class="btn btn-outline">{{ __('shop::front.login.create_account') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
