@extends('layouts.front.app', ['headerShrink' => 'header-alt shrink'])

@section('content')
    <div class="page-wrapper" style="display: flex;align-items: center;justify-content: center;">
        <div class="access-forms" style="width: 300px;margin: 90px 0px;">
            @include('admin.notify')
            <form method="post" class="form-alt selected" id="form-register" action="{{ route('shop.register.submit', ['languageSlug' => $languageSlug]) }}">
                @csrf
                <div class="form-head">
                    <h3>{{ __('shop::front.login.register') }}</h3>
                </div>

                <div class="form-body">
                    <div class="form-row">
                        <label class="form-label" for="first_name">
                            {{ __('shop::front.login.first_name') }}
                            <span class="asterisk">*</span>
                        </label>
                        <div class="input-container">
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" autofocus>
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="form-label" for="last_name">
                            {{ __('shop::front.login.last_name') }}
                            <span class="asterisk">*</span>
                        </label>
                        <div class="input-container">
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">
                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label" for="email">
                            {{ __('shop::front.login.email') }}
                            <span class="asterisk">*</span>
                        </label>
                        <div class="input-container">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label" for="phone">
                            {{ __('shop::front.login.phone') }}
                            <span class="asterisk">*</span>
                        </label>
                        <div class="input-container">
                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                            @error('phone')
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
                        <div class="input-container"><input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="form-label" for="password-confirm">
                            {{ __('shop::front.login.confirm_password') }}
                            <span class="asterisk">*</span>
                        </label>
                        <div class="input-container">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="checkboxes-wrapper">
                        @if(!is_null($termsOfUse))
                            @php
                                $termsOfUseTranslated = $termsOfUse->parent->translate($languageSlug);
                            @endphp
                            @if(!is_null($termsOfUseTranslated))
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" id="terms">

                                    <span class="checkmark"></span>

                                    <span class="check-text">Прочетох и съм съгласен с това, което е описано в  <a href="{{ $termsOfUseTranslated->parent->href() }}" target="_blank"><strong>{{ $termsOfUseTranslated->title }}</strong></a></span>
                                </label>
                            @endif
                        @endif

                        @if(!is_null($privacyPolicy))
                            @php
                                $privacyPolicyTranslated = $privacyPolicy->parent->translate($languageSlug);
                            @endphp
                            @if(!is_null($privacyPolicyTranslated))
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" id="privacy">

                                    <span class="checkmark"></span>

                                    <span class="check-text">Прочетох и съм съгласен с това, което е описано в  <a href="{{ $privacyPolicyTranslated->parent->href() }}" target="_blank"><strong>{{ $privacyPolicyTranslated->title }}</strong></a></span>
                                </label>
                            @endif
                        @endif
                    </div>
                    <button type="submit" class="submit-button">{{ __('shop::front.login.register_submit') }}</button>

                    <p>
                        {{ __('shop::front.login.already_have_account') }}
                        <a href="{{ route('shop.login', ['languageSlug' => $languageSlug]) }}" class="btn btn-outline">{{ __('shop::front.login.login') }}</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection
