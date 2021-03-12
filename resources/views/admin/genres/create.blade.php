@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    @include('admin.extras.header', ['title'=>'Add a Genre'])
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6 mx-auto">
                            <form action="{{route('admin.genres.store')}}" data-parsley-validate="true" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Name<span class="text-primary">*</span></label>
                                    <input type="text" name="name" class="form-control" required
                                           placeholder="Type here the genre's name or title" minlength="1"
                                           maxlength="100" value="{{old('name')}}"/>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="description" class="form-control"
                                           placeholder="Type here the genre's description"
                                           value="{{old('description')}}"/>
                                </div>
                                <div class="form-group">
                                    <label>Poster</label>
                                    <input type="file" name="poster" id="poster"
                                           data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M">
                                </div>
                                <div class="form-group">
                                    <label>Active</label>
                                    @if (old('active',-1)==1)
                                        <select class="form-control" name="active">
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    @elseif(old('active',-1)==0)
                                        <select class="form-control" name="active">
                                            <option value="1">Yes</option>
                                            <option value="0" selected>No</option>
                                        </select>
                                    @else
                                        <select class="form-control" name="active">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    @endif
                                </div>
                                <div class="form-row">
                                    <div class="col-6">
                                        <button type="submit"
                                                class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
                                            Save
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route("admin.genres.index")}}"
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
        $(document).ready(function () {
            $('#poster').dropify({});
        })
    </script>
@stop