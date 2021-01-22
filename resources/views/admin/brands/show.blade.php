@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>"Brand Details - {$brand->name}"])
				</div>
				<form id="uploadForm" action="{{route('admin.brands.update',$brand->id)}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header bg-secondary">
										Brand refers to a company manufacturing a particular product.
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="name">@required (Name)</label>
											<input id="name" type="text" name="name" class="form-control" required placeholder="Type a name" value="{{old('name',$brand->name)}}"/>
										</div>
										<div class="form-group">
											<label for="seller">Requested By @if($brand->isBrandOwner) (Owner) @endif</label>
											<input id="seller" type="text" name="seller" class="form-control bg-white" value="{{old('seller',$brand->seller->name??\App\Library\Utils\Extensions\Str::NotAvailable)}}" readonly/>
										</div>
										<div class="form-group">
											<label for="logo">Logo</label>
											<input type="file" name="logo" id="logo" data-default-file="{{$brand->logo}}" data-show-remove="false">
										</div>
										<div class="form-group">
											<label for="sampleMRPTagImage">Sample MRP Tag</label>
											<input type="file" name="sampleMRPTagImage" id="sampleMRPTagImage" data-default-file="{{$brand->sampleMRPTagImage}}" data-show-remove="false" disabled="disabled">
										</div>
										<div class="form-group">
											<label for="website">Website</label>
											<input id="website" type="url" name="website" class="form-control" placeholder="Type brand website" value="{{old('website',$brand->website)}}"/>
										</div>
										<div class="form-group">
											<label for="document_type">Document Type</label>
											<input id="document_type" type="text" name="documentType" class="form-control bg-white" value="{{old('documentType',$brand->documentType->description)}}" readonly/>
										</div>
										<div class="form-group">
											<label for="documentProof">Document Proof</label>
											<input type="file" name="documentProof" id="documentProof" data-default-file="{{$brand->documentProof}}" data-show-remove="false" disabled="disabled">
										</div>
										<div class="form-group">
											<label for="status_x">Status</label>
											<select name="status" id="status_x" class="form-control selectpicker">
												@foreach(\App\Library\Enums\Brands\Status::asArray() as $key=>$value)
													<option value="{{$value}}" @if($value==$brand->status) selected @endif>{{$key}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-primary">
											Save
										</button>
									</div>
									<div class="col-6">
										<a href="{{route('admin.brands.index')}}" class="btn btn-secondary waves-effect btn-block shadow-secondary">
											Cancel
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	<script>
		$(document).ready(() => {
			$('#logo').dropify({});
			$('#documentProof').dropify({});
			$('#sampleMRPTagImage').dropify({});
		});
	</script>
@stop