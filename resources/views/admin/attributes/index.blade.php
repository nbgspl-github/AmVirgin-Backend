@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Attributes','action'=>['link'=>route('admin.products.attributes.create'),'text'=>'Create an attribute']])
				</div>
                <div class="card-body animatable table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">Required</th>
                            <th class="text-center">Use In Layered Navigation</th>
                            <th class="text-center">Use To Create Variants</th>
                            <th class="text-center">Predefined</th>
                            <th class="text-center">Multi Value</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        <x-blank-table-indicator columns="9" :data="$attributes"/>
                        @foreach($attributes as $attribute)
							<tr>
								<td class="text-center">{{$attributes->firstItem()+$loop->index}}</td>
								<td class="text-center">{{$attribute->name}}</td>
								<td class="text-center">{{$attribute->code}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($attribute->required)}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($attribute->useInLayeredNavigation)}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($attribute->useToCreateVariants)}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($attribute->predefined)}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($attribute->multiValue)}}</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger shadow-sm" href="{{route('admin.products.attributes.edit',$attribute->id)}}" @include('admin.extras.tooltip.left', ['title' => 'Edit attribute details'])><i class="mdi mdi-pencil"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{$attributes->links()}}
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">

	</script>
@stop