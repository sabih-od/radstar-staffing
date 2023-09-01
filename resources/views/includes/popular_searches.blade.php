<div class="section greybg">
    <div class="container">
        <div class="topsearchwrap">

        <h3>{{__('Browse Jobs By Functional Area')}}</h3>

                <div class="srchint">
                    <ul class="row catelist">
                        @if(isset($topFunctionalAreaIds) && count($topFunctionalAreaIds)) @foreach($topFunctionalAreaIds as $functional_area_id_num_jobs)
                        <?php
                        $functionalArea = App\FunctionalArea::where('functional_area_id', '=', $functional_area_id_num_jobs->functional_area_id)->lang()->active()->first();
                        ?>
                         @if(null !== $functionalArea)

                        <li class="col-md-4 col-sm-6"><a href="{{route('job.list', ['functional_area_id[]'=>$functionalArea->functional_area_id])}}" title="{{$functionalArea->functional_area}}">{{$functionalArea->functional_area}} <span>({{$functional_area_id_num_jobs->num_jobs}})</span></a>
                        </li>

                        @endif @endforeach @endif
                    </ul>
                    <!--Categories end-->
                </div>

          

            
        </div>
    </div>
</div>