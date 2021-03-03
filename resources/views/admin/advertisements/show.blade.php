@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    <div class="row">
                        <div class="col-8">
                            <h5 class="page-title animatable">View advertisement details</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body animatable">
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <form action="" method="POST"
                                  data-parsley-validate="true" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="banner">Logo</label>
                                    <input type="file" name="banner" id="banner" data-show-remove="false"
                                           data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2M"
                                           data-default-file="{{$advertisement->banner}}">
                                </div>
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input id="subject" type="text" name="subject" class="form-control" required
                                           placeholder="Subject" minlength="2" maxlength="255"
                                           value="{{old('subject',$advertisement->subject)}}"/>
                                </div>
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea class="form-control" name="content" id="content" rows="10"
                                              required>{{old('content',$advertisement->content)}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input id="date" type="datetime-local" name="date" class="form-control"
                                           required placeholder="Valid From"
                                           value="{{date("Y-m-d\TH:i:s",strtotime(old('date',$advertisement->date)))}}"/>
                                </div>
                                <div class="form-group mb-0">
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="{{route("admin.announcements.index")}}"
                                               class="btn btn-secondary waves-effect m-l-5 btn-block">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(() => {
            $('#banner').dropify({})
        });
    </script>
@stop
