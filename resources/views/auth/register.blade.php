@extends('templates.Register')
@section('content')
    <!-- begin register-header -->
    <h1 class="register-header">
        Registrar
        <small>Crea tu cuenta en ... Es gratís!.</small>
    </h1>
    <!-- end register-header -->
    <!-- begin register-content -->
    <div class="register-content">
        <form class="margin-bottom-0" method="POST" action="{{ route('user.store') }}">
            {{ csrf_field() }}
            <div class="row row-space-10">
                <div class="col-md-12 m-b-15">
                    <input id="name" type="text" placeholder="Nombre" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                    @endif
                </div>

            </div>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input id="email" type="email" class="form-control"  placeholder="Correo" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif

                </div>
            </div>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input id="telefono" placeholder="Teléfono" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                    @if ($errors->has('phone'))
                        <span class="help-block">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                    @endif
                </div>
            </div>

            <div class="row m-b-15">
                <div class="col-md-12">
                    <input id="password" type="password" placeholder="Contraseña" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input id="password-confirm" type="password" placeholder="Confirmar Contraseña" class="form-control" name="password_confirmation" required>
                </div>
            </div>
            <div class="checkbox m-b-30">
                <label>
                    <input type="checkbox" required />Al hacer clic en Registrarse, acepta nuestros  <a href="#">Terminos</a> y ha leído nuestra  <a href="#">Política de datos,</a>, Incluido <a href="#">El uso de cookies</a>.
                </label>
            </div>
            <div class="register-buttons">
                <button type="submit" class="btn btn-primary btn-block btn-lg">Registrar</button>
            </div>
            <div class="m-t-10 m-b-10 p-b-10 text-inverse">
                ¿Ya eres usuario?  Haga Click <a href="/login">Aquí</a> para ingresar.
            </div>
            <hr />
            <p class="text-center">
                &copy; IGNITE - Gaming Center 2018
            </p>
        </form>
    </div>
    <!-- end register-content -->
@endsection
{{--
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Registrar</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('user.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Correo</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                            <label for="telefono" class="col-md-4 control-label">Teléfono</label>

                            <div class="col-md-6">
                                <input id="telefono" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Contraseña</label>

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
                            <label for="password-confirm" class="col-md-4 control-label">Confirmar Contraseña</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Registrar
                                </button>
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
