<div id="video_row" class="p-3 border shadow-sm mt-3" style="border-radius: 10px; border-color: #c4cad0!important;">
	<div class="form-row">
		<div class="col-12 col-md-1 my-auto mx-0 no-gutters text-center text-md-left">
			<button type="button" onclick="deleteRow(this);" class="btn btn-outline-primary shadow-primary mb-4 mb-md-0" style="border-radius: 64px; padding-top: 8px; padding-bottom: 8px"><i type="" class="mdi mdi-close"></i></button>
		</div>
		<div class="col-12 col-md-11">
			<input type="hidden" name="videoSourceId" value="{{$chosen->sourceId}}">
			<div class="form-row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Title</label>
						<textarea class="form-control" name="title[]" required placeholder="Title of this video in about 256 characters">{{$chosen->title}}</textarea>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Description</label>
						<textarea class="form-control" name="description[]" required placeholder="Description for this video in about 2000 characters">{{$chosen->description}}</textarea>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-1">
					<div class="form-group mb-0">
						<label>Season</label>
						<select name="season[]" class="form-control" required>
							@for ($i = 1; $i <= 10; $i++)
								@if ($i==$chosen->season)
									<option value="{{$i}}" selected>{{$i}}</option>
								@else
									<option value="{{$i}}">{{$i}}</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-md-1 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Episode</label>
						<select name="episode[]" class="form-control" required>
							@for ($i = 1; $i <= 100; $i++)
								@if ($i==$chosen->episode)
									<option value="{{$i}}" selected>{{$i}}</option>
								@else
									<option value="{{$i}}">{{$i}}</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-md-2 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Language</label>
						<select name="language[]" class="form-control" required>
							@foreach ($languages as $language)
								@if($language->getKey()==$chosen->languageId)
									<option value="{{$language->getKey()}}" selected>{{$language->getName()}}</option>
								@else
									<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
								@endif
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Quality</label>
						<select name="quality[]" class="form-control" required>
							<option value="" selected disabled>Choose...</option>
							@foreach ($qualities as $quality)
								@if($quality->getKey()==$chosen->qualityId)
									<option value="{{$quality->getKey()}}" selected>{{$quality->getName()}}</option>
								@else
									<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
								@endif
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Duration</label>
						<input name="duration[]" type="text" id="duration" class="form-control" required placeholder="Duration in hh:mm:ss" value="{{$chosen->duration}}">
					</div>
				</div>
				<div class="col-md-4 mt-md-0 mt-2">
					<div class="form-group mb-0">
						<label>Video (choose new to overwrite)</label>
						<input name="video[]" type="file" id="video" class="form-control" style="padding-left: 4px; padding-top: 4px" accept="video/*" value="{{$chosen->video}}">
					</div>
				</div>

			</div>
		</div>
	</div>
</div>