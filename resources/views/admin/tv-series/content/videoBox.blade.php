<div class="col-12 mb-3 animated zoomIn" id="item_@{{id}}">
	<div class="card" style="border: 1px solid #aeb4ba;">
		<div class="card-body rounded">
			<button type="button" onmouseenter="handleEnter(this.getAttribute('data-id'), 'remove');" onmouseleave="handleLeave(this.getAttribute('data-id'),'remove');" onclick="handleAsyncDelete(this.getAttribute('data-id'),this.getAttribute('data-key'));" class="btn btn-primary position-absolute shadow-sm shadow-primary" style="top: -15px; right:-15px;border-radius: 32px; width: 50px; height: 50px; z-index: 10;"><i class="ion-close-round font-20 pt-1"></i>
			</button>
			<div class="row my-auto">
				<div class="col-6 my-auto">
					<div class="embed-responsive embed-responsive-16by9 bg-muted border-dark" style=" max-height: 500px!important; min-height: 500px; border-radius: 4px">
						<button data-id="@{{id}}" type="button" onmouseenter="handleEnter(this.getAttribute('data-id'), 'remove');" onmouseleave="handleLeave(this.getAttribute('data-id'),'remove');" onclick="handleFileDialog(this.getAttribute('data-id'));" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="bottom: 32px; left:47%;border-radius: 32px; width: 50px; height: 50px; z-index: 10;"><i class="ion-videocamera font-20 pt-1"></i>
						</button>
						<input type="file" data-id="@{{id}}" class="d-none" onchange="handleVideo(event,this.getAttribute('data-id'))" id="input_@{{id}}" name="trailer" accept=".mp4, .avi"/>
						<iframe class="embed-responsive-item my-auto" src="" id="preview_@{{id}}" style=" max-height: 325px!important; min-height: 325px;">
							<span class="text-center my-auto" id="blankVideo"><i class="ion ion-videocamera text-muted" style="font-size: 80px;"></i></span>
						</iframe>
					</div>
				</div>
				<div class="col-6 my-auto">
					<div class="form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Title</label>
								<textarea class="form-control" name="title" required placeholder="Title of this video in about 256 characters"></textarea>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Description</label>
								<textarea class="form-control" name="description" required placeholder="Description for this video in about 2000 characters" rows="4">{{isset($chosen)&&$chosen->description}}</textarea>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-2">
							<div class="form-group">
								<label>Season</label>
								<select name="season[]" class="form-control" required>
									@for ($i = 1; $i <= 10; $i++)
										@if (isset($chosen)&&$i==$chosen->season)
											<option value="{{$i}}" selected>{{$i}}</option>
										@else
											<option value="{{$i}}">{{$i}}</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Episode</label>
								<select name="episode[]" class="form-control" required>
									@for ($i = 1; $i <= 100; $i++)
										@if (isset($chosen)&&$i==$chosen->episode)
											<option value="{{$i}}" selected>{{$i}}</option>
										@else
											<option value="{{$i}}">{{$i}}</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Language</label>
								<select name="language[]" class="form-control" required>
									@foreach ($languages as $language)
										@if(isset($chosen)&&$language->getKey()==$chosen->languageId)
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
										@if(isset($chosen)&&$quality->getKey()==$chosen->qualityId)
											<option value="{{$quality->getKey()}}" selected>{{$quality->getName()}}</option>
										@else
											<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Duration</label>
								<input name="duration[]" type="text" id="duration" class="form-control" required placeholder="Duration in hh:mm:ss" value="">
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<div class="form-group mb-0">
								<label>Subtitle file</label>
								<input name="duration[]" type="file" id="duration" class="form-control" required placeholder="Duration in hh:mm:ss" value="" style="padding: 4px">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>