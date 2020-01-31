<div class="col-12 mb-2 animated zoomIn" id="item_@{{id}}">
	<div class="card" style="border: 1px solid #aeb4ba;">
		<div class="card-body rounded">
			<div class="row">
				<div class="col-6">
					<div class="embed-responsive embed-responsive-16by9 bg-muted border-dark" style=" max-height: 500px!important; min-height: 500px; border-radius: 4px">
						<iframe class="embed-responsive-item" src="" id="trailer" style=" max-height: 325px!important; min-height: 325px;">
							<span class="text-center my-auto" id="blankVideo"><i class="ion ion-videocamera text-muted" style="font-size: 80px;"></i></span>
						</iframe>
					</div>
				</div>
				<div class="col-6">
					<div class="form-row">
						<div class="col-12">
							<div class="form-group mb-1">
								<label>Title</label>
								<textarea class="form-control" name="title" required placeholder="Title of this video in about 256 characters"></textarea>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<div class="form-group mb-1">
								<label>Description</label>
								<textarea class="form-control" name="description" required placeholder="Description for this video in about 2000 characters" rows="5">{{isset($chosen)&&$chosen->description}}</textarea>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-4">
							<div class="form-group mb-1">
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
						<div class="col-4">
							<div class="form-group mb-1">
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
						<div class="col-4">
							<div class="form-group mb-1">
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>