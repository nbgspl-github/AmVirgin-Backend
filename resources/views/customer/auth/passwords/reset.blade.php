<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<title>Password Recovery</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="csrf-token" content="{{csrf_token()}}">
	<link rel="shortcut icon" href="{{asset("images/logo.png")}}">
	<link href="{{asset("assets/admin/css/bootstrap.min.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("assets/admin/css/icons.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("assets/admin/css/style.css")}}" rel="stylesheet" type="text/css">
	@notify_css
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
						<div class="card shadow-sm">
							<div class="card-body">

								<h3 class="text-center m-b-15">
									<a href="#" class="logo logo-admin"><img src="{{asset("assets/admin/images/logo.png")}}" height="50" alt="logo"></a>
								</h3>

								<h4 class="text-muted text-center font-18"><b>Reset your password</b></h4>

								<div class="p-2">
									<form class="form-horizontal m-t-20" method="POST" action="{{ route('customer.password.submit') }}" autocomplete="off">
										@csrf
										<input type="hidden" name="token" value="{{request('token')}}">
										@if(request()->has('mobile'))
											<input type="hidden" name="mobile" value="{{request('mobile')}}">
										@else
											<input type="hidden" name="email" value="{{request('email')}}">
										@endif

										<div class="form-group row">
											<div class="col-md-12">
												<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off" placeholder="Password" minlength="8" maxlength="64">
												@error('password')
												<span class="invalid-feedback"
														role="alert"><strong>{{ $message }}</strong></span>
												@enderror
											</div>
										</div>

										<div class="form-group row">
											<div class="col-md-12">
												<input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="off" placeholder="Confirm Password" minlength="8" maxlength="64">
											</div>
										</div>

										<div class="form-group mb-0 text-center row m-t-20">
											<div class="col-12">
												<button class="btn btn-primary btn-block waves-effect waves-light" type="submit">
													Submit
												</button>
											</div>
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
@include('admin.app.partials.scripts')
</body>
</html>