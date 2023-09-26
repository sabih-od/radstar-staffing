@extends('admin.layouts.admin_layout')
@section('content')
<style type="text/css">
    .table td, .table th {
        font-size: 12px;
        line-height: 2.42857 !important;
    }
</style>
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}">Home</a> <i class="fa fa-circle"></i> </li>
                <li> <span>Jobs</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title">Manage Jobs <small>Jobs</small> </h3>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-settings font-dark"></i> <span class="caption-subject font-dark sbold uppercase">Jobs</span> </div>
                        <div class="actions"> <a href="{{ route('create.job') }}" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-plus"></i> Add New Job</a> </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <form method="post" role="form" id="job-search-form">
                                <table class="table table-striped table-bordered table-hover"  id="jobDatatableAjax">
                                    <thead>
                                        <tr role="row" class="filter">
                                            <td>{!! Form::select('company_id', ['' => 'Select Company']+$companies, null, array('id'=>'company_id', 'class'=>'form-control')) !!}</td>
                                            <td><input type="text" class="form-control" name="title" id="title" autocomplete="off" placeholder="Job title"></td>
                                            <td><input type="text" class="form-control" name="description" id="description" autocomplete="off" placeholder="Job description"></td>
                                            <td>
                                                <?php $default_country_id = Request::query('country_id', $siteSetting->default_country_id); ?>
                                                {!! Form::select('country_id', ['' => 'Select Country']+$countries, $default_country_id, array('id'=>'country_id', 'class'=>'form-control')) !!}
                                                <span id="default_state_dd">
                                                    {!! Form::select('state_id', ['' => 'Select State'], null, array('id'=>'state_id', 'class'=>'form-control')) !!}
                                                </span>
                                                <span id="default_city_dd">
                                                    {!! Form::select('city_id', ['' => 'Select City'], null, array('id'=>'city_id', 'class'=>'form-control')) !!}
                                                </span></td>
                                            <td><select name="is_active" id="is_active" class="form-control">
                                                    <option value="-1">Is Active?</option>
                                                    <option value="1" selected="selected">Active</option>
                                                    <option value="0">In Active</option>
                                                </select>
                                                <select name="is_featured" id="is_featured" class="form-control">
                                                    <option value="-1">Is Featured?</option>
                                                    <option value="1">Featured</option>
                                                    <option value="0">Not Featured</option>
                                                </select></td>
                                        </tr>
                                        <tr role="row" class="heading">
                                            <th>Company</th>
                                            <th>Job title</th>
                                            <th>Job description</th>
                                            <th>City</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
</div>

<!-- Modal -->
<div class="modal fade" id="modal_candidate_list" tabindex="-1" role="dialog" aria-labelledby="modal_candidate_listTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modal_candidate_listTitle">Candidates</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
{{--                <div class="row">--}}
                    <div class="col-12">
                        <table class="table table-bordered">
{{--                            <thead>--}}
{{--                                <tr>--}}
{{--                                    <th></th>--}}
{{--                                </tr>--}}
{{--                            </thead>--}}

                            <tbody id="tbody_candidate_list">
{{--                                <tr>--}}
{{--                                    <td></td>--}}
{{--                                </tr>--}}
                            </tbody>
                        </table>
                    </div>
{{--                </div>--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            //btn_list_shortlisted_candidates
            $('body').on('click', '.btn_list_shortlisted_candidates', function () {
                let job_id = $(this).data('job');
                let url = "{{ route('admin.fetch_shortlisted_candidates', 'temp') }}";
                url = url.replace('temp', job_id);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (data) {
                        $('#tbody_candidate_list').html('');

                        data.forEach((item, index) => {
                            let applicant_url = "{{ route('applicant.profile', 'temp') }}";
                            applicant_url = applicant_url.replace('temp', item.application_id);

                            $('#tbody_candidate_list').append(`<tr>
                                                                    <td>`+index + 1+`</td>
                                                                    <td><a target="_blank" href="`+applicant_url+`">`+item.name+`</a></td>
                                                                </tr>`);
                        });
                    }
                });

                $('#modal_candidate_list').modal('show');
            });

            //btn_list_hired_candidates
            $('body').on('click', '.btn_list_hired_candidates', function () {
                let job_id = $(this).data('job');
                let url = "{{ route('admin.fetch_hired_candidates', 'temp') }}";
                url = url.replace('temp', job_id);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (data) {
                        $('#tbody_candidate_list').html('');

                        data.forEach((item, index) => {
                            let applicant_url = "{{ route('applicant.profile', 'temp') }}";
                            applicant_url = applicant_url.replace('temp', item.application_id);

                            $('#tbody_candidate_list').append(`<tr>
                                                                    <td>`+index + 1+`</td>
                                                                    <td><a target="_blank" href="`+applicant_url+`">`+item.name+`</a></td>
                                                                </tr>`);
                        });
                    }
                });

                $('#modal_candidate_list').modal('show');
            });

            //btn_list_rejected_candidates
            $('body').on('click', '.btn_list_rejected_candidates', function () {
                let job_id = $(this).data('job');
                let url = "{{ route('admin.fetch_rejected_candidates', 'temp') }}";
                url = url.replace('temp', job_id);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (data) {
                        $('#tbody_candidate_list').html('');

                        data.forEach((item, index) => {
                            let applicant_url = "{{ route('user.profile', 'temp') }}";
                            applicant_url = applicant_url.replace('temp', item.application_id);

                            $('#tbody_candidate_list').append(`<tr>
                                                                    <td>`+index + 1+`</td>
                                                                    <td><a target="_blank" href="`+applicant_url+`">`+item.name+`</a></td>
                                                                </tr>`);
                        });
                    }
                });

                $('#modal_candidate_list').modal('show');
            });

        });
    </script>
    <script>
        $(document).ready(function () {
            $.urlParam = function(name){
                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                return results[1] || 0;
            }

            if ($.urlParam('company_id')) {
                $('#company_id').val($.urlParam('company_id'));
                $('#company_id').trigger('change');
            } else {
                // alert('not found');
            }
        });
        $(function () {
            var oTable = $('#jobDatatableAjax').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                searching: false,
                /*
                 "order": [[1, "asc"]],
                 paging: true,
                 info: true,
                 */
                ajax: {
                    url: '{!! route('fetch.data.jobs') !!}',
                    data: function (d) {
                        d.company_id = $('#company_id').val();
                        d.title = $('#title').val();
                        d.description = $('#description').val();
                        d.country_id = $('#country_id').val();
                        d.state_id = $('#state_id').val();
                        d.city_id = $('#city_id').val();
                        d.is_active = $('#is_active').val();
                        d.is_featured = $('#is_featured').val();
                    }
                }, columns: [
                    {data: 'company_id', name: 'company_id'},
                    {data: 'title', name: 'title'},
                    {data: 'description', name: 'description'},
                    {data: 'city_id', name: 'city_id'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
            $('#job-search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#company_id').on('change', function (e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#title').on('keyup', function (e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#description').on('keyup', function (e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#country_id').on('change', function (e) {
                oTable.draw();
                e.preventDefault();
                filterDefaultStates(0);
            });
            $(document).on('change', '#state_id', function (e) {
                oTable.draw();
                e.preventDefault();
                filterDefaultCities(0);
            });
            $(document).on('change', '#city_id', function (e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#is_active').on('change', function (e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#is_featured').on('change', function (e) {
                oTable.draw();
                e.preventDefault();
            });
            filterDefaultStates(0);
        });

        function deleteJob(id, is_default) {
            var msg = 'Are you sure?';
            if (confirm(msg)) {
                $.post("{{ route('delete.job') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                        .done(function (response) {
                            if (response == 'ok')
                            {
                                var table = $('#jobDatatableAjax').DataTable();
                                table.row('jobDtRow' + id).remove().draw(false);
                            } else
                            {
                                alert('Request Failed!');
                            }
                        });
            }
        }
        function makeActive(id) {
            $.post("{{ route('make.active.job') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#jobDatatableAjax').DataTable();
                            table.row('jobDtRow' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
        function makeNotActive(id) {
            $.post("{{ route('make.not.active.job') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#jobDatatableAjax').DataTable();
                            table.row('jobDtRow' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
        function makeFeatured(id) {
            $.post("{{ route('make.featured.job') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#jobDatatableAjax').DataTable();
                            table.row('jobDtRow' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
        function makeNotFeatured(id) {
            $.post("{{ route('make.not.featured.job') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#jobDatatableAjax').DataTable();
                            table.row('jobDtRow' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
        function filterDefaultStates(state_id)
        {
            var country_id = $('#country_id').val();
            if (country_id != '') {
                $.post("{{ route('filter.default.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                        .done(function (response) {
                            $('#default_state_dd').html(response);
                        });
            }
        }
        function filterDefaultCities(city_id)
        {
            var state_id = $('#state_id').val();
            if (state_id != '') {
                $.post("{{ route('filter.default.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                        .done(function (response) {
                            $('#default_city_dd').html(response);
                        });
            }
        }
    </script>
@endpush
