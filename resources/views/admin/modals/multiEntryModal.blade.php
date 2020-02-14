<div class="modal fade" id="{{$key}}_multiEntryModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="{{$key}}_modalTitle">Input multiple entries</h5>
			</div>
			<div class="modal-body">
				<ul class="list-group" id="{{$key}}_listGroup">

				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary float-left" id="{{$key}}_addMoreButton">Add more</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="{{$key}}_doneButton">Done</button>
			</div>
		</div>
	</div>
</div>