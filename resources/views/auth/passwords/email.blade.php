@extends('layouts.app')

@section('body_content')
<div class="text-center full-width">
  <form class="form-signin" method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <h1 class="h3 mb-3 font-weight-normal">Elfelejtett jelszó</h1>

    <label for="email" class="sr-only">E-mail cím</label>
    <input type="email" id="email" name="email" class="form-control{{ $errors->has('email') ? ' has-error' : '' }}" value="{{ old('email') }}" placeholder="E-mail cím" required autofocus>

    <div class="mb-3 text-left">
      @if ($errors->has('email'))
        <span class="help-block text-left">
          <strong>{{ $errors->first('email') }}</strong>
        </pspan>
      @endif
    </div>
    
    <button class="btn btn-lg btn-primary btn-block" type="submit">OK</button>
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
</style>
@endsection

<!--
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>-->