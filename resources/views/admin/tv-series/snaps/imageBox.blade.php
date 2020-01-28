<div class="col-6 mb-2" id="item_@{{id}}">
	<div class="card" style="border: 1px solid #aeb4ba;">
		<div class="card-body rounded p-0">
			<div class="row">
				<div class="col-12 text-center">
					<img id="preview_@{{id}}" class="img-fluid" style="max-height: 450px!important; min-height: 450px;" data-id="@{{id}}"/>
					<input type="file" class="d-none" onchange="handleImage(event, this.getAttribute('data-id'))" data-id="@{{id}}" id="input_@{{id}}" name="images[]"/>
					<button data-id="@{{id}}" type="button" onmouseenter="handleEnter(this.getAttribute('data-id'),'switch');" onmouseleave="handleLeave(this.getAttribute('data-id'),'switch');" onclick="handleFileDialog(this.getAttribute('data-id'));" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="bottom: 5%; right: 4%; border-radius: 40px; width: 50px; height: 50px;" @include('admin.extras.tooltip.top', ['title' => 'Pick an image'])>
						<i class="fa fa-camera-retro font-20 pt-1"></i></button>
					<button data-id="@{{id}}" type="button" onmouseenter="handleEnter(this.getAttribute('data-id'), 'remove');" onmouseleave="handleLeave(this.getAttribute('data-id'),'remove');" onclick="handleDelete(this.getAttribute('data-id'));" class="btn btn-primary position-absolute shadow-sm shadow-primary" style="top: 5%; right: 4%; border-radius: 32px; width: 50px; height: 50px;"><i class="ion-close-round font-20 pt-1"></i></button>
				</div>
			</div>
		</div>
	</div>
</div>