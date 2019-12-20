<div class="modal fade" id="progressModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Uploading files...</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-auto mx-auto">
						<div id="progressCircle" data-percent="0" class="medium m-0" data-progressBarColor="#cf3f43">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="cancelUpload();">Cancel</button>
			</div>
		</div>
	</div>
</div>