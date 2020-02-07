<div class="col-6 mb-2 animated zoomIn" id="item_@{{id}}">
	<div class="card" style="border: 1px solid #aeb4ba;">
		<div class="card-body rounded p-0">
			<div class="row">
				<div class="col-12 text-center">
					<i class="ion ion-image text-muted text-center my-auto position-absolute" style="font-size: 80px; top: 35%; left: 44%" id="blank_@{{ id }}"></i>
					<img id="preview_@{{id}}" class="img-fluid" style="max-height: 300px!important; min-height: 300px;" data-id="@{{id}}"/>
					<input type="file" class="d-none" onchange="handleImage(event, this.getAttribute('data-id'))" data-id="@{{id}}" id="input_@{{id}}" name="image[]"/>
					<button data-id="@{{id}}" type="button" onmouseenter="handleEnter(this.getAttribute('data-id'),'switch');" onmouseleave="handleLeave(this.getAttribute('data-id'),'switch');" onclick="handleFileDialog(this.getAttribute('data-id'));" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="bottom: 10px; right: 25px; border-radius: 40px; width: 50px; height: 50px;" @include('admin.extras.tooltip.top', ['title' => 'Pick an image'])>
						<i class="fa fa-camera-retro font-20 pt-1"></i></button>
					<button data-id="@{{id}}" type="button" onmouseenter="handleEnter(this.getAttribute('data-id'), 'remove');" onmouseleave="handleLeave(this.getAttribute('data-id'),'remove');" onclick="handleDelete(this.getAttribute('data-id'));" class="btn btn-primary position-absolute shadow-sm shadow-primary" style="top: 10px; right: 25px; border-radius: 32px; width: 50px; height: 50px;"><i class="ion-close-round font-20 pt-1"></i></button>
				</div>
			</div>
		</div>
	</div>
</div>