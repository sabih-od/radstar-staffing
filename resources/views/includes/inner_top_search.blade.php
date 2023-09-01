<form action="{{route('job.list')}}" method="get">
	<!-- Page Title start -->
	<div class="container">
	<div class="pageSearch">
			
				<div class="row">
					<div class="col-lg-7">

						@if(Auth::guard('company')->check())
						<h3 class="seekertxt">   {{__('One million success stories')}}. <span>{{__('Search Jobseekers Today')}}.</span></h3>
						@else
						<h3 class="mb-3">{{__('One million success job stories')}}. <span>{{__('Start yours today')}}.</span></h3>				
						@endif

						<div class="searchform">
						<div class="input-group">
							<input type="text"  name="search" id="jbsearch" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('Enter Skills or job title')}}" autocomplete="off" />
							
							<button type="submit" class="btn"><i class="fas fa-search"></i></button>
						</div>
						</div>
					</div>
				</div>
			
	</div>
	</div>
	<!-- Page Title end -->
</form>
