<div class="col-6 mb-2 animated zoomIn" id="item_{{$id}}">
	<div class="card" style="border: 1px solid #aeb4ba;">
		<div class="card-body rounded p-0">
			<div class="row">
				<div class="col-12 text-center">
					<img id="preview_{{$id}}" class="img-fluid" style="max-height: 270px!important; min-height: 270px;" data-id="{{$id}}" src="{{$snap['file']}}"/>
					<button data-id="{{$id}}" data-key="{{$snap['id']}}" type="button" onmouseenter="handleEnter(this.getAttribute('data-id'), 'remove');" onmouseleave="handleLeave(this.getAttribute('data-id'),'remove');" onclick="handleAsyncDelete(this.getAttribute('data-id'),this.getAttribute('data-key'));" class="btn btn-primary position-absolute shadow-sm shadow-primary" style="top: 5%; right: 4%; border-radius: 32px; width: 50px; height: 50px;">
						<i class="ion-close-round font-20 pt-1"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>