@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					<div class="row">
						<div class="col-8 w-100">
							<h5 class="page-title animatable">News Articles</h5>
						</div>
						<div class="col-4 my-auto">
							<form action="{{route('admin.news.articles.index')}}">
								<div class="form-row float-right">
									<div class="col-auto my-1">
										<input type="text" name="query" class="form-control" id="inlineFormCustomSelect" value="{{request('query')}}" placeholder="Type & hit enter">
									</div>
									<div class="col my-auto">
										<div class="btn-group" role="group">
											<button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Add
											</button>
											<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
												<a class="dropdown-item" href="{{route('admin.news.articles.content.create')}}">Article</a>
												<a class="dropdown-item" href="{{route('admin.news.articles.videos.create')}}">Video
                                                    Article</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable table-responsive">
                    <table id="datatable" class="table table-hover pr-0 pl-0 "
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Thumbnail</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Type</th>
                            <th>Published</th>
                            <th>Views</th>
                            <th>Shares</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <x-blank-table-indicator :data="$articles"/>
                        @foreach ($articles as $article)
							<tr>
								<td>{{($articles->firstItem()+$loop->index)}}</td>
								<td>
									@if($article->thumbnail!=null)
										<img src="{{$article->thumbnail}}" style="max-height: 100px" alt="" class="img-thumbnail"/>
									@else
										{{\App\Library\Utils\Extensions\Str::NotAvailable}}
									@endif
								</td>
								<td>
									<span class="badge badge-secondary badge-pill">{{$article->category->name??\App\Library\Utils\Extensions\Str::NotAvailable}}</span>
								</td>
								<td>{{\App\Library\Utils\Extensions\Str::ellipsis($article->title,50)}}</td>
								<td>{{$article->author??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
								<td>{{$article->type->description}}</td>
								<td>{{$article->published?'Yes':'No'}}</td>
								<td>{{$article->views}}</td>
								<td>{{$article->shares}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.news.articles.edit',$article->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary" href="javascript:_delete('{{$article->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-minus-circle-outline"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{$articles->links()}}
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		_delete = key => {
			alertify.confirm("Are you sure? This action is irreversible!",
				yes => {
					axios.delete(`/admin/news/articles/${key}`).then(response => {
						alertify.alert(response.data.message, () => {
							location.reload();
						});
					}).catch(e => {
						alertify.confirm('Something went wrong. Retry?', yes => {
							_delete(key);
						});
					});
				}
			)
		}
	</script>
@stop