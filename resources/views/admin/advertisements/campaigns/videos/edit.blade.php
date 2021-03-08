@extends('admin.app.app')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    @include('admin.extras.header', ['title'=>'Edit campaign video'])
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="videoForm" action="{{route('admin.campaigns.videos.update',$campaign->id)}}"
                                  data-parsley-validate="true" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>@required(Thumbnail)</label>
                                    <input type="file" name="thumbnail" id="thumbnail" class="form-control"
                                           data-max-file-size="2M" data-allowed-file-extensions="jpg png jpeg"
                                           data-default-file="{{$campaign->thumbnail}}"/>
                                    <small class="text-muted">Max 2 MegaBytes</small>
                                </div>
                                <div class="form-group">
                                    <label for="title">Title<span class="text-primary">*</span></label>
                                    <input id="title" type="text" name="title" class="form-control" required
                                           placeholder="Type title here" minlength="2" maxlength="100"
                                           value="{{old('title',$campaign->title)}}"/>
                                </div>
                                <div class="form-group">
                                    <label>@required(Video)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="video" id="video"
                                               accept=".mp4, .mkv">
                                        <label class="custom-file-label" for="video">{{$campaign->video}}</label>
                                        <small class="text-muted">Max 10 MegaBytes</small>
                                    </div>
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
    <script>
        $(document).on('change', '.custom-file-input', function (event) {
            $(this).next('.custom-file-label').html(event.target.files[0].name);
        })
        $(document).ready(() => {
            $('#thumbnail').dropify();
            $('#videoForm').submit(function (event) {
                event.preventDefault();
                submitSource(this);
            });
        });

        function submitSource(event) {
            const config = {
                onUploadProgress: uploadProgress,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            };
            const formData = new FormData(event);
            showProgressDialog(true, () => {
                axios.post(`/admin/campaigns/video/{{$campaign->id}}`, formData, config,).then(response => {
                    showProgressDialog(false);
                    alertify.alert(response.data.message, () => {
                        location.href = `/admin/campaigns`;
                    });
                }).catch(error => {
                    showProgressDialog(false);
                    alertify.alert('Something went wrong. Please try again.');
                });
            });
        }

        uploadProgress = (event) => {
            let percentCompleted = Math.round((event.loaded * 100) / event.total);
            setProgress(percentCompleted);
        }
    </script>
@stop