@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    @include('admin.extras.header', ['title'=>'Create a link slider'])
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6 mx-auto">
                            <form action="{{route('admin.sliders.link.store')}}" data-parsley-validate="true"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Title<span class="text-primary">*</span></label>
                                    <input type="text" name="title" class="form-control" required
                                           placeholder="Type title here" minlength="1" maxlength="100"
                                           value="{{old('title')}}"/>
                                </div>
                                <div class="form-group">
                                    <label>Description<span class="text-primary">*</span></label>
                                    <textarea name="description" class="form-control"
                                              placeholder="Type description here">{{old('description')}}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Target link<span class="text-primary">*</span></label>
                                    <input type="text" name="targetLink" id="targetLink" class="form-control"
                                           placeholder="Type target url here" value="{{old('target')}}"/>
                                    <small class="text-muted">Example - https://google.co.in</small>
                                </div>
                                <div class="form-group">
                                    <label>Rating<span class="text-primary">*</span></label>
                                    <select name="rating" class="form-control">
                                        @for($i=0;$i<=5;$i++)
                                            <option value="{{$i}}"
                                                    @if(old('rating')==$i) selected @endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Active<span class="text-primary">*</span></label>
                                    <select name="active" class="form-control">
                                        @if(old('active')==true)
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        @else
                                            <option value="1">Yes</option>
                                            <option value="0" selected>No</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Banner<span class="text-primary">*</span></label>
                                    <input type="file" name="banner" id="banner"
                                           data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M">
                                </div>
                                <div class="form-row">
                                    <div class="col-6">
                                        <button type="submit"
                                                class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
                                            Save
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route("admin.sliders.index")}}"
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
        let lastFile = null;
        let targetTypes = {
            ExternalLink: '{{\App\Models\Slider::TargetType['ExternalLink']}}',
            VideoKey: '{{\App\Models\Slider::TargetType['VideoKey']}}'
        };
        let elements = {
            targetKey: null,
            targetLink: null
        };

        $(document).ready(() => {
            $('#banner').dropify({});
            elements = {
                targetKey: $('#targetKey'),
                targetLink: $('#targetLink'),
            };
        });

        handleTypeChanged = (value) => {
            if (value === targetTypes.ExternalLink) {
                disable(elements.targetKey);
                enable(elements.targetLink);
            } else {
                enable(elements.targetKey);
                disable(elements.targetLink);
            }
        };

        enable = (e) => {
            e.prop('disabled', false);
        };

        disable = (e) => {
            e.prop('disabled', true);
        };
    </script>
@stop