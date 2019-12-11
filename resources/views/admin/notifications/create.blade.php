@extends('admin.layouts.header')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.layouts.pageHeader', ['breadcrumbs' =>['Dashboard'=>route('home'),'Notifications'=>'#'],'title'=>'Notifications'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="" data-parsley-validate="true">
								@csrf
								<div class="form-group">
									<label for="title" class="animatable">Title<span class="text-primary">*</span></label>
									<input id="title" type="text" name="title" class="form-control animatable" required placeholder="Notification title" minlength="1" maxlength="100" onchange="title=this.value"/>
								</div>
								<div class="form-group">
									<label for="content" class="animatable">Content<span class="text-primary">*</span></label>
									<textarea id="content" name="content" class="form-control animatable" required placeholder="Notification content" minlength="1" maxlength="150" rows="10" onchange="content=this.value"></textarea>
								</div>
								<div class="form-group">
									<label for="url" class="animatable">Image</label>
									<input id="url" type="text" name="url" class="form-control animatable" placeholder="URL Link" minlength="1" maxlength="100" onchange="url=this.value"/>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-md-12">
											<button id="send" type="button" class="animatable btn btn-block btn-primary waves-effect waves-light" onclick="send();">
												Update
											</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">

		let notificationSendRoute = '{{route('notifications.send')}}';

		let sendButton = null;

		$(document).ready(() => {
			$('#datatable').DataTable();

			sendButton = $('#send');

			anime.timeline({loop: false}).add({
				targets: ['.animatable'],
				translateY: [40, 0],
				translateZ: 0,
				opacity: [0, 1],
				easing: "easeOutExpo",
				duration: 2000,
				delay: (el, i) => 100 * i
			});
		});

		send = (event) => {
			let payload = {
				'title': $('#title').val(),
				'content': $('#content').val(),
				'url': $('#url').val(),
			};
			sendButton.attr('disabled', true);
			axios.post(notificationSendRoute, payload).then(response => {
				sendButton.attr('disabled', false);
				const status = response.status;
				const message = response.data.message;
				if (status === 200) {
					toastr.success(message);
				} else {
					toastr.info(message);
				}
			}).catch(error => {
				sendButton.attr('disabled', false);
				toastr.error('Something went wrong. Please try again in a while.');
			});
		};
	</script>
@stop