@extends('layouts.app')

@section('body_content')
<div class="text-center full-width">
  <form class="form-signin" method="POST" action="{{ route('password.request') }}">
    {{ csrf_field() }}
    
    <input type="hidden" name="token" value="{{ $token }}">

    <h1 class="h3 mb-3 font-weight-normal">Jelszó visszaállítása</h1>

    <label for="email" class="sr-only">E-mail cím</label>
    <input type="email" id="email" name="email" class="form-control{{ $errors->has('email') ? ' has-error' : '' }}" value="{{ old('email') }}" placeholder="E-mail cím" required>
    
    <label for="password" class="sr-only{{ $errors->has('password') ? ' has-error' : '' }}">Jelszó</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Jelszó" required>
    
    <label for="password-confirm" class="sr-only">Jelszó újra</label>
    <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Jelszó újra" required>
    
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
      @if ($errors->has('password_confirmation'))
        <pspan class="help-block text-left">
          <strong>{{ $errors->first('password_confirmation') }}</strong>
        </pspan>
      @endif
    </div>
    
    <button class="btn btn-lg btn-primary btn-block" type="submit">Visszaállítom</button>
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
  #email {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
  #password {
    margin-bottom: -1px;
    border-radius: 0;
    border-radius: 0;
  }
  #password-confirm {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }
</style>
@endsection


<!--
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>-->