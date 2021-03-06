@extends('admin.app.app')
@section('content')
    @include('admin.videos.actionBox')
    @include('admin.modals.durationPicker')
    @include('admin.modals.multiEntryModal',['key'=>'cast'])
    @include('admin.modals.multiEntryModal',['key'=>'director'])
    <div class="row" id="vue_app">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    @include('admin.extras.header', ['title'=>'Add a Video/Movie'])
                </div>
                <form id="uploadForm" action="{{route('admin.videos.store')}}" data-parsley-validate="true"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-10 col-lg-10 col-xl-8 mx-auto">
                                <div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
                                    <div class="card-header text-primary font-weight-bold bg-white">
                                        Type the following details to proceed to next step...
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="title">Title<span class="text-primary">*</span></label>
                                            <input id="title" type="text" name="title" class="form-control" required
                                                   placeholder="Type here the video/movie title" minlength="1"
                                                   maxlength="256" value="{{old('title')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="duration">Duration<span class="text-primary">*</span></label>
                                            <input id="duration" type="text" name="duration"
                                                   class="form-control bg-white" required placeholder="Choose duration"
                                                   value="{{old('duration')}}" readonly/>
                                        </div>
                                        <div class="form-group">
                                            <label for="cast">Cast<span class="text-primary">*</span></label>
                                            <input id="cast" type="text" name="cast" class="form-control" required
                                                   placeholder="Type here the series' cast(s) name"
                                                   minlength="1" maxlength="256" value="{{old('cast')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="director">Director<span class="text-primary">*</span></label>
                                            <input id="director" type="text" name="director" class="form-control"
                                                   required
                                                   placeholder="Type here the series' director(s) name"
                                                   minlength="1" maxlength="256" value="{{old('director')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Overview (Description)<span
                                                        class="text-primary">*</span></label>
                                            <textarea id="description" name="description" class="form-control" required
                                                      placeholder="Type short summary about the movie or video"
                                                      minlength="1" rows="5"
                                                      maxlength="2000">{{old('description')}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="genre">Choose a genre<span class="text-primary">*</span></label>
                                            <select id="genre" name="genre_id" class="form-control"
                                                    title="Choose..." required>
                                                @foreach($appGenres as $genre)
                                                    @if(old('genreId',-1)==$genre->getKey())
                                                        <option value="{{$genre->getKey()}}"
                                                                selected>{{$genre->name}}</option>
                                                    @else
                                                        <option value="{{$genre->getKey()}}">{{$genre->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="sectionId">Choose containing section(s)<span
                                                                class="text-primary">*</span></label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <button type="button" class="btn btn-primary btn-block"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal">
                                                        Click to choose...
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="exampleModal" tabindex="-1"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Containing
                                                                Sections</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-2">
                                                                <div class="col-6">
                                                                    <span>Section</span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <span>Rank</span>
                                                                </div>
                                                            </div>
                                                            @foreach($appVideoSections as $section)
                                                                <div class="row mb-2 no-gutters">
                                                                    <div class="col-6 pr-1">
                                                                        <input type="text" class="form-control"
                                                                               value="{{$section->title}}" readonly>
                                                                    </div>
                                                                    <div class="col-6 pl-1">
                                                                        <select name="sections[{{$section->id}}]" id=""
                                                                                class="form-control">
                                                                            @for($i=0;$i<10;$i++)
                                                                                <option value="{{$i}}">{{$i}}</option>
                                                                            @endfor
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                    data-dismiss="modal">Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="released">Release date<span
                                                        class="text-primary">*</span></label>
                                            <input id="released" type="date" name="released" class="form-control"
                                                   required placeholder="Choose release date"
                                                   onkeydown="return false;"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="rating">Rating<span class="text-primary">*</span></label>
                                            <input id="rating" type="number" name="rating" class="form-control" required
                                                   placeholder="Type rating for this movie/video" min="0.0" max="5.0"
                                                   value="0.0" step="0.1"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="pgRating">PG Rating<span class="text-primary">*</span></label>
                                            <select id="pgRating" name="pg_rating" class="form-control"
                                                    title="Choose..." required>
                                                <option value="G">G - General audience</option>
                                                <option value="PG">PG - Parental Guidance advised</option>
                                                <option value="PG-13">PG-13 - Parental Guidance required (not
                                                    appropriate for under 13)
                                                </option>
                                                <option value="R">R - Restricted</option>
                                                <option value="NC-17">NC-17 - No children 17 and under admitted</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="subscriptionType">Subscription type<span
                                                        class="text-primary">*</span></label>
                                            <select id="subscriptionType" name="subscription_type"
                                                    class="form-control" required
                                                    onchange="subscriptionTypeChanged(this.value);">
                                                <option value="free">Free</option>
                                                <option value="paid">Paid</option>
                                                <option value="subscription">Subscription</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price<span class="text-primary">*</span></label>
                                            <input id="price" type="number" name="price" class="form-control"
                                                   placeholder="Type price for this movie/video" min="0" max="10000"
                                                   step="1" readonly value="0"/>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="rank">Trending rank</label>
                                            <select id="rank" name="rank" class="form-control">
                                                @for ($i = 0; $i <= 10; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6 mx-auto">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit"
                                                class="btn btn-primary waves-effect waves-light btn-block shadow-primary"
                                                id="submitButton">
                                            Save & Proceed
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route("admin.videos.index")}}"
                                           class="btn btn-secondary waves-effect btn-block shadow-secondary">
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
    <script src="{{asset('assets/admin/utils/DurationPicker.js')}}"></script>
    <script src="{{asset('assets/admin/utils/MultiEntryModal.js')}}"></script>
    <script>
        let modal = null;
        let submitButton = null;
        let route = '/admin/tv-series';

        function finishUpload() {
            window.location.href = route;
        }

        $(document).ready(function () {
            modal = $('#okayBox');
            submitButton = $('#submitButton');
            setupDurationPicker({
                modalId: 'durationPicker',
                durationId: 'duration'
            });
            MultiEntryModal.setupMultiEntryModal({
                title: 'Cast & Crew',
                separator: '/',
                key: 'cast',
                boundEditBoxId: 'cast',
                modalId: 'cast_multiEntryModal',
                inputClass: 'cast_input',
                listGroupId: 'cast_listGroup',
                addMoreButtonId: 'cast_addMoreButton',
                doneButtonId: 'cast_doneButton',
                deleteButtonClass: 'cast_delete-button',
                exclude: ['/'],
                template: `<li class="list-group-item px-0 py-1 border-0 animated slideInDown">
								\t\t\t\t\t\t<div class="col-auto px-0">
								\t\t\t\t\t\t\t<div class="input-group mb-2">
								\t\t\t\t\t\t\t\t<input type="text" class="form-control cast_input" maxlength="20" onpaste="return false;" onkeydown="return filterCharacters(event);" placeholder="Type here..." value=@{{value}}>
								\t\t\t\t\t\t\t\t<div class="input-group-append">
								\t\t\t\t\t\t\t\t\t<div class="input-group-text text-white bg-primary cast_delete-button">&times;</div>
								\t\t\t\t\t\t\t\t</div>
								\t\t\t\t\t\t\t</div>
								\t\t\t\t\t\t</div>
								\t\t\t\t\t
							</li>`
            });
            MultiEntryModal.setupMultiEntryModal({
                title: 'Director(s)',
                separator: '/',
                key: 'director',
                boundEditBoxId: 'director',
                modalId: 'director_multiEntryModal',
                inputClass: 'director_input',
                listGroupId: 'director_listGroup',
                addMoreButtonId: 'director_addMoreButton',
                doneButtonId: 'director_doneButton',
                deleteButtonClass: 'director_delete-button',
                template: `<li class="list-group-item px-0 py-1 border-0 animated slideInDown">
								\t\t\t\t\t\t<div class="col-auto px-0">
								\t\t\t\t\t\t\t<div class="input-group mb-2">
								\t\t\t\t\t\t\t\t<input type="text" class="form-control director_input" maxlength="20" onpaste="return false;" onkeydown="return filterCharacters(event);" placeholder="Type here..." value=@{{value}}>
								\t\t\t\t\t\t\t\t<div class="input-group-append">
								\t\t\t\t\t\t\t\t\t<div class="input-group-text text-white bg-primary director_delete-button">&times;</div>
								\t\t\t\t\t\t\t\t</div>
								\t\t\t\t\t\t\t</div>
								\t\t\t\t\t\t</div>
								\t\t\t\t\t
							</li>`
            });
            $('#trending').change(function () {
                if (this.checked) {
                    $('#trendingRank').prop("required", true);
                } else {
                    $('#trendingRank').prop("required", false);
                }
            });
        });

        function subscriptionTypeChanged(type) {
            const elem = $('#price');
            if (type === 'free' || type === 'subscription') {
                elem.attr('readonly', true);
                elem.prop('required', false);
            } else {
                elem.attr('readonly', false);
                elem.prop('required', true);
            }
        }

        disableSubmit = (disable) => {
            submitButton.prop('disabled', disable);
        };

        function filterCharacters(event) {
            let contains = true;
            [191].forEach(key => {
                console.log(key + " = " + event.keyCode);
                if (key === event.keyCode) {
                    contains = false;
                }
            });
            return contains;
        }
    </script>
@stop
