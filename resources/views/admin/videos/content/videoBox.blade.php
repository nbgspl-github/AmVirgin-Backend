<div class="col-12 mb-3 animated zoomIn" id="item_@{{id}}">
	<div class="card" style="border: 1px solid #aeb4ba;">
		<div class="card-body rounded">
			<input type="hidden" name="source[]" value="">
			<button data-id="@{{id}}" type="button" onclick="handleDelete(this.getAttribute('data-id'));" class="btn btn-primary position-absolute shadow-sm shadow-primary" style="top: -15px; right:-15px;border-radius: 32px; width: 50px; height: 50px; z-index: 10;"><i class="ion-close-round font-20 pt-1"></i>
			</button>
			<div class="row my-auto">
				<button data-id="@{{id}}" type="button" onclick="handleFileDialog(this.getAttribute('data-id'));" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="bottom: 30px; left:19%;border-radius: 32px; width: auto; height: 50px; z-index: 10;">Choose Video&nbsp;&nbsp;<i class="ion-videocamera"></i></button>
				<div class="col-6 my-auto">
					<div class="embed-responsive embed-responsive-16by9 border-dark" style="max-height: 300px!important; min-height: 300px; border-radius: 4px">
						<input type="file" data-id="@{{id}}" class="d-none" onchange="handleVideo(event,this.getAttribute('data-id'))" id="input_@{{id}}" name="video[]" accept=".mp4, .avi"/>
						<iframe class="embed-responsive-item my-auto" src="" id="preview_@{{id}}" style=" max-height: 300px!important; min-height: 300px;">
							<span class="text-center my-auto" id="blankVideo"><i class="ion ion-videocamera text-muted" style="font-size: 80px;"></i></span>
						</iframe>
					</div>
				</div>
				<div class="col-6 my-auto">
					<div class="form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Title</label>
								<textarea class="form-control" name="title[]" required placeholder="Title of this video in about 256 characters"></textarea>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Description</label>
								<textarea class="form-control" name="description[]" required placeholder="Description for this video in about 2000 characters" rows="4"></textarea>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-4">
							<div class="form-group">
								<label>Language</label>
								<select name="language[]" class="form-control" required>
									@foreach ($languages as $language)
										<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
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
										<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-6">
							<div class="form-group mb-0">
								<label>Subtitle file</label>
								<input name="subtitle[]" type="file" id="subtitle" class="form-control" placeholder="Duration in hh:mm:ss" value="" accept=".srt" style="padding: 4px">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Duration</label>
								<input name="duration[]" type="text" id="duration" class="form-control" required placeholder="Duration in hh:mm:ss" value="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>