@extends('templates.Login')
@section('content')
    <!-- begin login-header -->
    <div class="login-header">
        <div class="brand">
            Iniciar Sesión
            <small>Sistema de reservación</small>
        </div>
        <div class="icon">
            <i class="fa fa-sign-in"></i>
        </div>
    </div>
    <!-- end login-header -->

    <!-- begin login-content -->
    <div class="login-content">
        <form class="fmargin-bottom-0" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="form-group m-b-15">
                <input id="email" type="email" class="form-control input-lg" name="email" placeholder="Correo" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group m-b-15">
                <input id="password" type="password" class="form-control input-lg" placeholder="Contraseña" name="password" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif

            </div>
            <div class="checkbox m-b-30">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordarme
                </label>
            </div>
            <div class="login-buttons">
                <button type="submit" class="btn btn-success btn-block btn-lg">Iniciar</button>
                <button type="button" onclick="checkLoginState()" class="btn btn-info btn-block btn-lg"> <i class="fa fa-facebook-official" aria-hidden="true"></i> Iniciar</button>
                
            </div>
            <div class="m-t-20 m-b-40 p-b-40 text-inverse">
                No eres miembro todavía? Pulse  <a href="{{ route('register') }}" class="text-success">aquí</a> para registrarse..
            </div>
            <hr />
            <p class="text-center">
                &copy; IGNITE - Gaming Center 2018
            </p>
        </form>
    </div>
    
    <!-- end login-content -->
@endsection
{{--
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

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

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
--}}
