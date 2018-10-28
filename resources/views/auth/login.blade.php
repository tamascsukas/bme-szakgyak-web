@extends('layouts.app')

@section('body_content')
<div class="text-center full-width">
  <form class="form-signin" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    <h1 class="h3 mb-3 font-weight-normal">Bejelentkezés</h1>

    <label for="email" class="sr-only">E-mail cím</label>
    <input type="email" id="email" name="email" class="form-control{{ $errors->has('email') ? ' has-error' : '' }}" value="{{ old('email') }}" placeholder="E-mail cím" required autofocus>
    
    <label for="password" class="sr-only{{ $errors->has('password') ? ' has-error' : '' }}">Jelszó</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Jelszó" required>
    
    <div class="mb-3 text-left">
      @if ($errors->has('email'))
        <span class="help-block text-left">
          <strong>{{ $errors->first('email') }}</strong>
        </pspan>
      @endif
      @if ($errors->has('password'))
        <pspan class="help-block text-left">
          <strong>{{ $errors->first('password') }}</strong>
        </pspan>
      @endif
    </div>
    
    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Emlékezz rám
      </label>
    </div>
    
    <button class="btn btn-lg btn-primary btn-block" type="submit">Belépek</button>

    <div class="mt-3">
      <a class="btn btn-link" href="{{ route('password.request') }}">
        Elfelejtettem a jelszavam!
      </a>

      <br>

      <a class="btn btn-link" href="{{ route('register') }}">
        Még nincs fiókom, regisztrálok egyet.
      </a>
    </div>
  </form>
</div>
@endsection

@section('js_content')
@endsection

@section('header_content')
<style type="text/css">
  html,
  body {
    height: 100%;
  }

  body {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
  }

  .text-center.full-width {
    width: 100%;
  }

  .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
  }
  .form-signin .checkbox {
    font-weight: 400;
  }
  .form-signin .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 10px;
    font-size: 16px;
  }
  .form-signin .form-control:focus {
    z-index: 2;
  }
  .form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
  .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }
</style>
@endsection