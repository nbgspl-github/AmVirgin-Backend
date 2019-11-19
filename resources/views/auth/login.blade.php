<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<title>{{config("app.name","AmVirgin Dashboard")}}</title>
	<meta content="Admin Dashboard" name="description"/>
	<meta content="ThemeDesign" name="author"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>

	<link rel="shortcut icon" href="{{asset("images/logo.png")}}">

	<link href="{{asset("css/bootstrap.min.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("css/icons.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("css/style.css")}}" rel="stylesheet" type="text/css">

</head>


<body class="fixed-left">

<!-- Loader -->
<div id="preloader">
	<div id="status">
		<div class="spinner"></div>
	</div>
</div>

<!-- Begin page -->
<div class="accountbg">

	<div class="content-center">
		<div class="content-desc-center">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-5 col-md-8">
						<div class="card shadow-lg">
							<div class="card-body">

								<h3 class="text-center m-b-15">
									<a href="#" class="logo logo-admin"><img src="{{asset("images/logo.png")}}" height="50" alt="logo"></a>
								</h3>

								<h4 class="text-muted text-center font-18"><b>{{ __('Login') }}</b></h4>

								<div class="p-2">
									<form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}">
										@csrf
										<div class="form-group row">
											<div class="col-md-12">
												<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="dev.aviralx@gmail.com" required autocomplete="email" autofocus placeholder="Email" spellcheck="false">
												@error('email')
												<span class="invalid-feedback"
												      role="alert"><strong>{{ $message }}</strong></span>
												@enderror
											</div>
										</div>


										<div class="form-group row">
											<div class="col-md-12">
												<input id="password" type="password"
												       class="form-control @error('password') is-invalid @enderror"
												       name="password" required autocomplete="current-password"
												       placeholder="Password" value="1234567890">

												@error('password')
												<span class="invalid-feedback"
												      role="alert"><strong>{{ $message }}</strong></span>
												@enderror
											</div>
										</div>

										<div class="form-group row">
											<div class="col-12">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input"
													       id="customCheck1" name="remember">
													<label class="custom-control-label" for="customCheck1">Remember
														me</label>
												</div>
											</div>
										</div>

										<div class="form-group text-center row m-t-20">
											<div class="col-12">
												<button class="btn btn-primary btn-block waves-effect waves-light" type="submit">
													Log In
												</button>
											</div>
										</div>

										<div class="form-group m-t-15 mb-0 row">
											<a href="{{ route('password.request') }}" class="text-muted mx-auto"><i class="mdi mdi-lock"></i>Forgot your password?</a>
										</div>
									</form>
								</div>

							</div>
						</div>
					</div>
				</div>
				<!-- end row -->
			</div>
		</div>
	</div>
</div>

@include('layouts.scripts')

</body>
</html>