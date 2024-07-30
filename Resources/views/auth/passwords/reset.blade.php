@extends('layouts.front.app', ['headerShrink' => 'header-alt shrink'])

@section('content')
    <div class="page-wrapper" style="display: flex;align-items: center;justify-content: center;">
        <div class="access-forms" style="width: 300px;margin: 90px 0px;">
            @include('admin.notify')
            <form method="post" class="form-alt selected" id="form-login" action="{{ route('shop.password.update', ['languageSlug' => $languageSlug]) }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-head">
                    <h3>{{ __('shop::front.login.reset_from_heading') }}</h3>
                </div>

                <div class="form-body">

                    <div class="form-row">
                        <label class="form-label" for="email">
                            {{ __('shop::front.login.email') }}
                            <span class="asterisk">*</span>
                        </label>

                        <div class="input-container ">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label" for="password">
                            {{ __('shop::front.login.new_password') }}
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

                    <div class="form-row">
                        <label class="form-label" for="password">
                            {{ __('shop::front.login.confirm_new_password') }}
                            <span class="asterisk">*</span>
                        </label>

                        <div class="input-container ">
                            <input id="new-password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">

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
                    <button type="submit" class="submit-button">{{ __('shop::front.login.reset_password_btn') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
