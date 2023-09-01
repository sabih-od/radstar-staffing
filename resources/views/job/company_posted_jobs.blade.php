@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Company Posted Jobs')])
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            @include('includes.company_dashboard_menu')

            <div class="col-lg-9"> 
                <div class="myads">
         
                    
                    
                    <h3>{{__('Company Posted Jobs')}}</h3>
                    <ul class="searchList">
                        <!-- job start --> 
                        @if(isset($jobs) && count($jobs))
                        @foreach($jobs as $job)
                        @php $company = $job->getCompany(); @endphp
                        @if(null !== $company)
                        <li id="job_li_{{$job->id}}">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="jobimg">{{$company->printCompanyImage()}}</div>
                                    <div class="jobinfo">
                                        <h3><a href="{{route('job.detail', [$job->slug])}}" title="{{$job->title}}">{{$job->title}}</a></h3>
                                        <div class="companyName"><a href="{{route('company.detail', $company->slug)}}" title="{{$company->name}}">{{$company->name}}</a></div>
                                        <div class="location">
                                            <label class="fulltime" title="{{$job->getJobShift('job_shift')}}">{{$job->getJobShift('job_shift')}}</label>
                                            - <span>{{$job->getCity('city')}}</span></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>


                                <div class="col-md-5">
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-primary me-2" href="{{route('list.applied.users', [$job->id])}}">{{__('List Candidates')}}</a>
                                    <div class="btn-group dropdown">
                                    
                                    <button class="btn btn-bars dropdown-toggle ml-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="{{route('list.favourite.applied.users', [$job->id])}}">{{__('List Short Listed Candidates')}}</a></li>                                    
                                        <li><a class="dropdown-item" href="{{route('list.hired.users', [$job->id])}}">{{__('List Hired Candidates')}}</a></li>
                                        <li><a class="dropdown-item" href="{{route('rejected-users', [$job->id])}}">{{__('List Rejected Candidates')}}</a></li>
                                        <li><a class="dropdown-item" href="{{route('edit.front.job', [$job->id])}}">{{__('Edit')}}</a></li>
                                        <li><a class="dropdown-item" href="javascript:;" onclick="deleteJob({{$job->id}});">{{__('Delete')}}</a></li>
                                    </ul>
                                    </div>
                                 </div>


                                    
                                </div>
                            </div>
                        </li>
                        <!-- job end --> 
                        @endif
                        @endforeach
                        @endif
                    </ul>
					
					
					 <!-- Pagination Start -->

                    <div class="pagiWrap">

                        <div class="row">

                            <div class="col-md-5">

                                <div class="showreslt">

                                    {{__('Showing Jobs')}} : {{ $jobs->firstItem() }} - {{ $jobs->lastItem() }} {{__('Total')}} {{ $jobs->total() }}

                                </div>

                            </div>

                            <div class="col-md-7 text-right">

                                @if(isset($jobs) && count($jobs))

                                {{ $jobs->appends(request()->query())->links() }}

                                @endif

                            </div>

                        </div>

                    </div>

                    <!-- Pagination end --> 
					
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
<script type="text/javascript">
    function deleteJob(id) {
    var msg = 'Are you sure?';
    if (confirm(msg)) {
    $.post("{{ route('delete.front.job') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            if (response == 'ok')
            {
            $('#job_li_' + id).remove();
            } else
            {
            alert('Request Failed!');
            }
            });
    }
    }
</script>
@endpush