@extends('layouts.header')
@section('content')
    <div class="row py-4">
        <div class="col-xl-12">
            <div class="card m-b-30 shadow-sm">
                <div class="card-body">
                    <div class="row px-2 mb-3">
                        <div class="col-sm-6"><h4 class="mt-0 header-title pt-1">All Users</h4></div>
                        <div class="col-sm-6">
                            <button type="button" class="float-right btn btn-outline-primary waves-effect waves-light" onclick="window.location.href='http'">
                                Add User
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Action(s)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>231</td>
                                <td>
                                    Aviral Singh
                                </td>
                                <td>
                                    &lt;Unavailable&gt;
                                </td>
                                <td>Unnamed Road, Techzone 4, Amrapali Dream Valley, Greater Noida, Uttar Pradesh
                                    201009, India<br>Jaypee Greens Pari Chowk, Tugalpur Village, Greater Noida, Uttar
                                    Pradesh 201310, India
                                </td>
                                <td>
                                    <a style="text-decoration: underline" class="text-primary" href="">View Details</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop