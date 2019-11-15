@extends('layouts.appNoBody')

@section('content')
    <div class="card o-hidden border-0 shadow-none my-lg-3 bg-white">
        <div class="card-body p-0 shadow-none bg-transparent">
            <!-- Nested Row within Card Body -->
            <div class="row bg-transparent shadow-none py-4">
                <div class="col-lg-8 mx-auto bg-white rounded-lg shadow-sm">
                    <div class="p-5">
                        <div class="row">
                            <div class="col-3">
                                <img src="{{asset("public/img/logo.png")}}" height="40px"/>
                            </div>
                            <div class="col-9">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-5 text-right">Admin Sign Up</h1>
                                </div>
                            </div>
                        </div>
                        <form class="user">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                           placeholder="First Name">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" id="exampleLastName"
                                           placeholder="Last Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                       placeholder="Email Address">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user"
                                           id="exampleInputPassword" placeholder="Password">
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user"
                                           id="exampleRepeatPassword" placeholder="Repeat Password">
                                </div>
                            </div>
                            <a href="login.html" class="btn btn-primary btn-user btn-block">
                                Register Account
                            </a>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <a href="index.html" class="btn btn-google btn-user btn-block">Register with
                                        Google</a>
                                </div>
                                <div class="col-6">
                                    <a href="index.html" class="btn btn-facebook btn-user btn-block">Register with
                                        Facebook</a>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="forgot-password.html">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="login.html">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection