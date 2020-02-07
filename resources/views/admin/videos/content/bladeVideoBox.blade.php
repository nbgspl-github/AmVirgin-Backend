<div class="col-12 mb-3 animated zoomIn" id="item_{{$id}}">
	<div class="card" style="border: 1px solid #aeb4ba;">
		<div class="card-body rounded">
			<input type="hidden" name="source[]" value="{{$payload->sourceId}}">
			<button data-id="{{$id}}" data-key="{{$payload->sourceId}}" type="button" onclick="handleAsyncDelete(this.getAttribute('data-id'),this.getAttribute('data-key'));" class="btn btn-primary position-absolute shadow-sm shadow-primary" style="top: -15px; right:-15px;border-radius: 32px; width: 50px; height: 50px; z-index: 10;"><i class="ion-close-round font-20 pt-1"></i>
			</button>
			<div class="row my-auto">
				<div class="col-6 my-auto">
					<div class="embed-responsive embed-responsive-16by9 border-dark" style=" max-height: 500px!important; min-height: 500px; border-radius: 4px">
						<button data-id="{{$id}}" type="button" onclick="handleFileDialog(this.getAttribute('data-id'));" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="bottom: 32px; left:47%;border-radius: 32px; width: 50px; height: 50px; z-index: 10;"><i class="ion-videocamera font-20 pt-1"></i>
						</button>
						<input type="file" data-id="{{$id}}" class="d-none" onchange="handleVideo(event,this.getAttribute('data-id'))" id="input_{{$id}}" name="video[]" accept=".mp4, .avi"/>
						<iframe class="embed-responsive-item my-auto" src="{{\App\Storage\SecuredDisk::access()->url($payload->video)}}" id="preview_{{$id}}" style=" max-height: 325px!important; min-height: 325px;">
							<span class="text-center my-auto" id="blankVideo"><i class="ion ion-videocamera text-muted" style="font-size: 80px;"></i></span>
						</iframe>
					</div>
				</div>
				<div class="col-6 my-auto">
					<div class="form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Title</label>
								<textarea class="form-control" name="title[]" required placeholder="Title of this video in about 256 characters">{{$payload->title}}</textarea>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Description</label>
								<textarea class="form-control" name="description[]" required placeholder="Description for this video in about 2000 characters" rows="4">{{$payload->description}}</textarea>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-4">
							<div class="form-group">
								<label>Language</label>
								<select name="language[]" class="form-control" required>
									@foreach ($languages as $language)
										@if($payload->languageId==$language->getKey())
											<option value="{{$language->getKey()}}" selected>{{$language->getName()}}</option>
										@else
											<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Quality</label>
								<select name="quality[]" class="form-control" required>
									<option value="" selected disabled>Choose...</option>
									@foreach ($qualities as $quality)
										@if($payload->qualityId==$quality->getKey())
											<option value="{{$quality->getKey()}}" selected>{{$quality->getName()}}</option>
										@else
											<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Duration</label>
								<input name="duration[]" type="text" id="duration" class="form-control" required placeholder="Duration in hh:mm:ss" value="{{$payload->duration}}">
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<div class="form-group mb-0">
								<label>Subtitle file (Choose new to overwrite)</label>
								<input name="subtitle[]" type="file" id="subtitle" class="form-control" placeholder="Duration in hh:mm:ss" value="" accept=".srt" style="padding: 4px">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>