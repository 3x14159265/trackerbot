@extends('app')

{{-- Web site Title --}}
@section('title') Register :: @parent @endsection

{{-- Content --}}
@section('content')
    <div class="row text-center">
        <div class="col-lg-6 col-lg-3-offset col-sm-6 col-sm-offset-3">
            <div class="login-box">
                <div class="row">
                    {{-- <div class="page-header"> --}}
                    <div class="text-center col-lg-12">
                        <h2>REGISTER</h2>
                    </div>
                </div>

        <div class="row text-center">
            {!! Form::open(array('url' => url('auth/register'), 'method' => 'post', 'files'=> true)) !!}
            <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
                {!! Form::label('name', 'Name', array('class' => 'control-label')) !!}
                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
            <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                {!! Form::label('email', 'Email', array('class' => 'control-label')) !!}
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
                    <button type="submit" class="btn btn-primary">
                        Register
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
