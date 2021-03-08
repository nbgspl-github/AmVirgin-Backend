@extends('admin.app.app')
@section('styles')
    <link href="{{asset("assets/admin/plugins/summernote/summernote-bs4.css")}}" rel="stylesheet"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    @include('admin.extras.header', ['title'=>'Create campaign article'])
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{route('admin.campaigns.article.store')}}" data-parsley-validate="true"
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="title">Title<span class="text-primary">*</span></label>
                                    <input id="title" type="text" name="title" class="form-control" required
                                           placeholder="Type title here" minlength="2" maxlength="100"
                                           value="{{old('title')}}"/>
                                </div>
                                <div class="form-group">
                                    <label>@required(Thumbnail)</label>
                                    <input type="file" name="thumbnail" id="thumbnail" class="form-control"
                                           data-max-file-size="2M" data-allowed-file-extensions="jpg png jpeg"/>
                                </div>
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="col-6">
                                        <button type="submit"
                                                class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
                                            Save
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route("admin.campaigns.index")}}"
                                           class="btn btn-secondary waves-effect btn-block shadow-sm">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{asset("assets/admin/plugins/summernote/summernote-bs4.min.js")}}"></script>
    <script>
        $(document).ready(() => {
            $('#thumbnail').dropify();
            $('#content').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        })
    </script>
@stop