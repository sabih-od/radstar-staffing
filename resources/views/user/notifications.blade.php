@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Seeker Followers')])
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-md-9 col-sm-8">
                <div class="myads">
                    <h3>{{__('Notifications')}}</h3>
                    <ul class="searchList">
                        <!-- job start -->
                        @foreach(\Illuminate\Support\Facades\Auth::user()->notifications as $notification)
                            <li>
                                <div class="row">
                                    <div class="col-md-9 col-sm-9">
{{--                                        <div class="jobimg">IMAGE</div>--}}
                                        <div class="jobinfo">
                                            <h3 style="color: black">
                                                @php
                                                    if ($notification->topic == 'employer') {
                                                        $href = route('company.detail', $notification->topic_id);
                                                    } else if ($notification->topic == 'candidate') {
                                                        $href = route('user.profile', $notification->topic_id);
                                                    } else if ($notification->topic == 'job-apply') {
                                                        $href = route('applicant.profile', $notification->topic_id);
                                                    } else if ($notification->topic == 'job') {
                                                        $slug = \App\Job::find($notification->topic_id)->slug;
                                                        $href = route('job.detail', $slug);
                                                    } else {
                                                        $href = '#';
                                                    }
                                                @endphp
                                                <a href="{{$href}}">
                                                    {{$notification->content}}
                                                </a>
                                            </h3>
                                            <div class="location">{{\Carbon\Carbon::parse($notification->created_at)->format('D m Y h:i A')}}</div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
{{--                                    <div class="col-md-3 col-sm-3">--}}
{{--                                        <div class="listbtn"><a href="#">{{__('View Profile')}}</a></div>--}}
{{--                                    </div>--}}
                                </div>
{{--                                <p>{{$notification->content}}</p>--}}
                            </li>
                        <!-- job end -->
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
