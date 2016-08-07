@extends('app')
    @section('title') Login :: @parent
@endsection



{{-- Content --}}
@section('content')
    <div class="row text-center">
        <div class="col-lg-6 col-lg-3-offset col-sm-6 col-sm-offset-3">
            <div class="login-box">
                <div class="row">
                    {{-- <div class="page-header"> --}}
                    <div class="text-center col-lg-12">
                        <h2>LOGIN</h2>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-lg-12">
                        {!! Form::open(array('url' => url('auth/login'), 'method' => 'post', 'files'=> true)) !!}
                        <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                            {!! Form::label('email', "EMail", array('class' => 'control-label')) !!}
                            {!! Form::text('email', null, array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                        </div>
                        <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                            {!! Form::label('password', "Password", array('class' => 'control-label')) !!}
                            {!! Form::password('password', array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" style="margin-right: 15px;">
                                    Login
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <a href="{{ url('/password/email') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
