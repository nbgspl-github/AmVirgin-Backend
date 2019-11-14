@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('Register') }}</h4>
                        <h6 class="card-subtitle"> All with bootstrap element classies </h6>
                        <form class="mt-4" action="{{ route('register') }}" method="POST">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="email" class="form-control" id="name" aria-describedby="emailHelp"
                                       placeholder="Enter email">
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with
                                    anyone else.</small>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="name" aria-describedby="emailHelp"
                                       placeholder="Enter email">
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with
                                    anyone else.</small>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1"
                                       placeholder="Password">
                            </div>
                            <div class="custom-control custom-checkbox mr-sm-2 mb-3">
                                <input type="checkbox" class="custom-control-input" id="checkbox0" value="check">
                                <label class="custom-control-label" for="checkbox0">Check Me Out !</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>


            {{--        <div class="col-md-8">--}}
            {{--            <div class="card">--}}
            {{--                <div class="card-header"></div>--}}

            {{--                <div class="card-body">--}}
            {{--                    <form method="POST" action="">--}}
            {{--                        @csrf--}}

            {{--                        <div class="form-group row">--}}
            {{--                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>--}}

            {{--                            <div class="col-md-6">--}}
            {{--                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>--}}

            {{--                                @error('name')--}}
            {{--                                    <span class="invalid-feedback" role="alert">--}}
            {{--                                        <strong>{{ $message }}</strong>--}}
            {{--                                    </span>--}}
            {{--                                @enderror--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                        <div class="form-group row">--}}
            {{--                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

            {{--                            <div class="col-md-6">--}}
            {{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">--}}

            {{--                                @error('email')--}}
            {{--                                    <span class="invalid-feedback" role="alert">--}}
            {{--                                        <strong>{{ $message }}</strong>--}}
            {{--                                    </span>--}}
            {{--                                @enderror--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                        <div class="form-group row">--}}
            {{--                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

            {{--                            <div class="col-md-6">--}}
            {{--                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">--}}

            {{--                                @error('password')--}}
            {{--                                    <span class="invalid-feedback" role="alert">--}}
            {{--                                        <strong>{{ $message }}</strong>--}}
            {{--                                    </span>--}}
            {{--                                @enderror--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                        <div class="form-group row">--}}
            {{--                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>--}}

            {{--                            <div class="col-md-6">--}}
            {{--                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                        <div class="form-group row mb-0">--}}
            {{--                            <div class="col-md-6 offset-md-4">--}}
            {{--                                <button type="submit" class="btn btn-primary">--}}
            {{--                                    {{ __('Register') }}--}}
            {{--                                </button>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </form>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}
        </div>
    </div>
@endsection
