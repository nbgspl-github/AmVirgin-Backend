<div class="col-12 mb-2 animated zoomIn" id="item_{{$id}}">
	<div class="card" style="border: 1px solid #aeb4ba;">
		<div class="card-body rounded p-0">
			<div class="row">
				<div class="col-6">
					<div class="embed-responsive embed-responsive-16by9 rounded-lg border" style=" max-height: 500px!important; min-height: 500px;">
						<iframe class="embed-responsive-item" src="{{\App\Storage\SecuredDisk::access()->url($payload->getTrailer())}}" id="trailer" style=" max-height: 325px!important; min-height: 325px;">
							<span class="text-center my-auto" id="blankVideo"><i class="ion ion-videocamera text-muted" style="font-size: 80px;"></i></span>
						</iframe>
					</div>
				</div>
				<div class="col-6">
					<div class="row">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>