<div id="video_row" class="p-3 border shadow-sm mt-3" style="border-radius: 10px; border-color: #c4cad0!important;">
	<div class="form-row">
		<div class="col-12 col-md-1 my-auto mx-0 no-gutters text-center text-md-left">
			<button type="button" onclick="deleteRow(this);" class="btn btn-outline-primary shadow-primary mb-4 mb-md-0" style="border-radius: 64px; padding-top: 8px; padding-bottom: 8px"><i type="" class="mdi mdi-close"></i></button>
		</div>
		<div class="col-12 col-md-11">
			<div class="form-row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Title</label>
						<textarea class="form-control" name="title[]" required placeholder="Title of this video in about 256 characters"></textarea>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Description</label>
						<textarea class="form-control" name="description[]" required placeholder="Description for this video in about 2000 characters"></textarea>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-1">
					<div class="form-group mb-0">
						<label>Season</label>
						@if(null($chosen->season))
							<select name="season[]" class="form-control" required>
								@for ($i = 1; $i <= 25; $i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						@else
							<select name="season[]" class="form-control" required>
								@for ($i = 1; $i <= 25; $i++)
									@if ($i==$chosen->season)
										<option value="{{$i}}" selected>{{$i}}</option>
									@else
										<option value="{{$i}}">{{$i}}</option>
									@endif
								@endfor
							</select>
						@endif
					</div>
				</div>
				<div class="col-md-1 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Episode</label>
						@if(null($chosen->episode))
							<select name="episode[]" class="form-control" required>
								<option value="" selected disabled>Choose...</option>
								@for ($i = 1; $i <= 500; $i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						@else
							<select name="episode[]" class="form-control" required>
								@for ($i = 1; $i <= 500; $i++)
									@if ($i==$chosen->episode)
										<option value="{{$i}}" selected>{{$i}}</option>
									@else
										<option value="{{$i}}">{{$i}}</option>
									@endif
								@endfor
							</select>
						@endif
					</div>
				</div>
				<div class="col-md-2 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Language</label>
						@if (null($chosen->languageId))
							<select name="language[]" class="form-control" required>
								@foreach ($languages as $language)
									@if($language->getCode()=='hi')
										<option value="{{$language->getKey()}}" selected>{{$language->getName()}}</option>
									@else
										<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
									@endif
								@endforeach
							</select>
						@else
							<select name="language[]" class="form-control" required>
								@foreach ($languages as $language)
									@if($language->getKey()==$chosen->languageId)
										<option value="{{$language->getKey()}}" selected>{{$language->getName()}}</option>
									@else
										<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
									@endif
								@endforeach
							</select>
						@endif
					</div>
				</div>
				<div class="col-md-2 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Quality</label>
						<select name="quality[]" class="form-control" required>
							<option value="" selected disabled>Choose...</option>
							@foreach ($qualities as $quality)
								<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-3 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Duration</label>
						<input name="duration[]" type="text" id="duration" class="form-control" required placeholder="Duration in hh:mm:ss">
					</div>
				</div>
				<div class="col-md-3 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Video</label>
						<input name="video[]" type="file" id="video" class="form-control" style="padding-left: 4px; padding-top: 4px" required accept="video/*">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>