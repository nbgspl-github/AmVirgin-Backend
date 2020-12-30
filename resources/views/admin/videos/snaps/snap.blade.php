<div class="col-3 mb-2 animated zoomIn">
	<div class="card" style="border: 1px solid #aeb4ba;">
		<div class="card-body rounded p-0">
			<div class="row">
				<div class="col-12 text-center">
					<img id="" class="img-fluid" style="max-height: 270px!important; min-height: 270px;" data-id="{{$snap->id}}" src="{{$snap->file}}" alt=""/>
					<button data-id="{{$snap->id}}" data-key="{{$snap->id}}" type="button" onclick="handleAsyncDelete(this.getAttribute('data-id'),this.getAttribute('data-key'));" class="btn btn-primary shadow-sm shadow-primary" style="border-radius: 32px; width: 50px; height: 50px;">
						<i class="ion-close-round font-20 pt-1"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>