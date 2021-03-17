@extends('admin.app.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-0">
                    @include('admin.extras.header', ['title'=>'Live TV'])
                </div>
                <div class="card-body animatable table-responsive">
                    <div class="row pr-3">
                        <div class="col-6"><h4 class="mt-0 header-title ml-3 mb-4">All live streams</h4></div>
                        <div class="col-6"><a
                                    class="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig"
                                    href="">Add Stream</a></div>
                    </div>
                    <table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0"
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Poster</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Action(s)</th>
                        </tr>
                        </thead>

                        <tbody>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="application/javascript">
        $(document).ready(() => {
            $('#datatable').DataTable();
        });

        /**
         * Returns route for Genre/Update/Status route.
         * @param id
         * @returns {string}
         */
        updateStatusRoute = (id) => {
            return 'genres/' + id + '/status';
        };

        /**
         * Returns route for Genre/Delete route.
         * @param id
         * @returns {string}
         */
        deleteGenreRoute = (id) => {
            return 'genres/' + id;
        };

        /**
         * Callback for active status changes.
         * @param id
         * @param state
         */
        toggleStatus = (id, state) => {
            axios.put(updateStatusRoute(id),
                {id: id, status: state})
                .then(response => {
                    if (response.status === 200) {
                        toastr.success(response.data.message);
                    } else {
                        toastr.error(response.data.message);
                    }
                })
                .catch(reason => {
                    console.log(reason);
                    toastr.error('Failed to update status.');
                });
        };

        /**
         * Callback for delete genre trigger.
         * @param genreId
         */
        deleteGenre = (genreId) => {
            window.genreId = genreId;
            alertify.confirm("Are you sure you want to delete this genre? ",
                (ev) => {
                    ev.preventDefault();
                    axios.delete(deleteGenreRoute(genreId))
                        .then(response => {
                            if (response.data.code === 200) {
                                $('#genre_row_' + genreId).remove();
                                toastr.success(response.data.message);
                            } else {
                                toastr.error(response.data.message);
                            }
                        })
                        .catch(error => {
                            toastr.error('Something went wrong. Please try again in a while.');
                        });
                },
                (ev) => {
                    ev.preventDefault();
                });
        }
    </script>
@stop