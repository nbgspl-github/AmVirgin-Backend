`
<div id="video_row_{$rowId}" class="form-row p-3 border shadow-sm mb-3" style="border-radius: 16px; border-color: #acacac">
	<div class="col-md-1">
		<div class="form-group mb-0">
			<label>Season</label>
			<select name="season" class="form-control" required>
				@for ($i = 1; $i <= 100; $i++)
					<option value="{{$i}}">{{$i}}</option>
				@endfor
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group mb-0">
			<label>Episode number</label>
			<select name="episode" class="form-control" required>
				@for ($i = 1; $i <= 100; $i++)
					<option value="{{$i}}">{{$i}}</option>
				@endfor
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group mb-0">
			<label>Language</label>
			<select name="language" class="form-control" required>
				@foreach ($languages as $language)
					@if($language->getCode()=='hi')
						<option value="{{$language->getKey()}}" selected>{{$language->getName()}}</option>
					@else
						<option value="{{$language->getKey()}}">{{$language->getName()}}</option>
					@endif
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group mb-0">
			<label>Quality</label>
			<select name="quality" class="form-control" required>
				@foreach ($qualities as $quality)
					<option value="{{$quality->getKey()}}">{{$quality->getName()}}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group mb-0">
			<label>Video</label>
			<input type="file" id="video" class="form-control w-100 d-flex flex-grow-0" style="padding-left: 4px; padding-top: 4px" required accept="video/*">
		</div>
	</div>
</div>`