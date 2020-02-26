<div class="modal fade" tabindex="-1" role="dialog" id="durationPicker">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Choose duration</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-3">
						<div class="form-group">
							<label for="">Days</label>
							<select name="daysSelect" class="form-control" id="days" onchange="handleDays(this.value);">
								<option value="00" selected>00</option>
								@for ($i = 0; $i <=9 ; $i++)
									<option value="0{{$i}}">0{{$i}}</option>
								@endfor
								@for ($i = 10; $i <=99 ; $i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-3">
						<div class="form-group">
							<label for="">Hours</label>
							<select name="hoursSelect" class="form-control" id="hours" onchange="handleHours(this.value);">
								<option value="00" selected>00</option>
								@for ($i = 0; $i <=9 ; $i++)
									<option value="0{{$i}}">0{{$i}}</option>
								@endfor
								@for ($i = 10; $i <=23 ; $i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-3">
						<div class="form-group">
							<label for="">Minutes</label>
							<select name="minutesSelect" class="form-control" required data-parsley-type="number" min="0" max="60" id="minutes" onchange="handleMinutes(this.value);">
								<option value="00" selected>00</option>
								@for ($i = 1; $i <=9 ; $i++)
									<option value="0{{$i}}">0{{$i}}</option>
								@endfor
								@for ($i = 10; $i <=59 ; $i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-3">
						<div class="form-group">
							<label for="">Seconds</label>
							<select name="secondsSelect" class="form-control" required data-parsley-type="number" min="0" max="60" id="seconds">
								<option value="00" selected>00</option>
								@for ($i = 1; $i <=9 ; $i++)
									<option value="0{{$i}}">0{{$i}}</option>
								@endfor
								@for ($i = 10; $i <=59 ; $i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" onclick="handleDurationChosen(document.getElementById('days').value,document.getElementById('hours').value,document.getElementById('minutes').value,document.getElementById('seconds').value)">Done</button>
			</div>
		</div>
	</div>
</div>