<h5 onclick=";">
    Documents
    <h6 style="font-size: 14px;" class="text-info">All documents must be uploaded for job application submission eligibility.</h6>
</h5>



        <div class="row" id="projects_div"></div>


<br>
<br>

<form action="{{route('candidate.documents.upload')}}" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="row">
        {{--drug_test_form--}}
        <div class="col-4">
            <div class="form-group">
                <label for="input_drug_test_form" style="cursor: pointer; color: grey;">
                    <h6>
                        @if ($drug_test_form_url)
                            <a target="_blank" href="{{$drug_test_form_url}}">Drug Test Form</a>
                            <span class="text-success"><i class="fas fa-check" aria-hidden="true"></i></span>
                        @else
                            Drug Test Form
                            <span class="text-danger"><i class="fas fa-x" aria-hidden="true"></i></span>
                        @endif
                    </h6>
                </label>
                <input id="input_drug_test_form" name="drug_test_form" type="file" class="">
            </div>
        </div>

        {{--education_verification_form--}}
        <div class="col-4">
            <div class="form-group">
                <label for="input_education_verification_form" style="cursor: pointer; color: grey;">
                    <h6>
                        @if ($education_verification_form_url)
                            <a target="_blank" href="{{$education_verification_form_url}}">Education Verification Form</a>
                            <span class="text-success"><i class="fas fa-check" aria-hidden="true"></i></span>
                        @else
                            Education Verification Form
                            <span class="text-danger"><i class="fas fa-x" aria-hidden="true"></i></span>
                        @endif
                    </h6>
                </label>
                <input id="input_education_verification_form" name="education_verification_form" type="file" class="">
            </div>
        </div>

        {{--employment_history_record--}}
        <div class="col-4">
            <div class="form-group">
                <label for="input_employment_history_record" style="cursor: pointer; color: grey;">
                    <h6>
                        @if ($employment_history_record_url)
                            <a target="_blank" href="{{$employment_history_record_url}}">Employment History Record</a>
                            <span class="text-success"><i class="fas fa-check" aria-hidden="true"></i></span>
                        @else
                            Employment History Record
                            <span class="text-danger"><i class="fas fa-x" aria-hidden="true"></i></span>
                        @endif
                    </h6>
                </label>
                <input id="input_employment_history_record" name="employment_history_record" type="file" class="">
            </div>
        </div>

        {{--release_authorization_record--}}
        <div class="col-4">
            <div class="form-group">
                <label for="input_release_authorization_record" style="cursor: pointer; color: grey;">
                    <h6>
                        @if ($release_authorization_record_url)
                            <a target="_blank" href="{{$release_authorization_record_url}}">Release Authorization Record</a>
                            <span class="text-success"><i class="fas fa-check" aria-hidden="true"></i></span>
                        @else
                            Release Authorization Record
                            <span class="text-danger"><i class="fas fa-x" aria-hidden="true"></i></span>
                        @endif
                    </h6>
                </label>
                <input id="input_release_authorization_record" name="release_authorization_record" type="file" class="">
            </div>
        </div>

        {{--hipaa--}}
        <div class="col-4">
            <div class="form-group">
                <label for="input_hipaa" style="cursor: pointer; color: grey;">
                    <h6>
                        @if ($hipaa_url)
                            <a target="_blank" href="{{$hipaa_url}}">HIPAA</a>
                            <span class="text-success"><i class="fas fa-check" aria-hidden="true"></i></span>
                        @else
                            HIPAA
                            <span class="text-danger"><i class="fas fa-x" aria-hidden="true"></i></span>
                        @endif
                    </h6>
                </label>
                <input id="input_hipaa" name="hipaa" type="file" class="">
            </div>
        </div>

        {{--physician_health_statement--}}
        <div class="col-4">
            <div class="form-group">
                <label for="input_physician_health_statement" style="cursor: pointer; color: grey;">
                    <h6>
                        @if ($physician_health_statement_url)
                            <a target="_blank" href="{{$physician_health_statement_url}}">Health Statement From Physician</a>
                            <span class="text-success"><i class="fas fa-check" aria-hidden="true"></i></span>
                        @else
                            Health Statement From Physician
                            <span class="text-danger"><i class="fas fa-x" aria-hidden="true"></i></span>
                        @endif
                    </h6>
                </label>
                <input id="input_physician_health_statement" name="physician_health_statement" type="file" class="">
            </div>
        </div>

        {{--photo_id--}}
        <div class="col-4">
            <div class="form-group">
                <label for="input_photo_id" style="cursor: pointer; color: grey;">
                    <h6>
                        @if ($photo_id_url)
                            <a target="_blank" href="{{$photo_id_url}}">Identification Photo</a>
                            <span class="text-success"><i class="fas fa-check" aria-hidden="true"></i></span>
                        @else
                            Identification Photo
                            <span class="text-danger"><i class="fas fa-x" aria-hidden="true"></i></span>
                        @endif
                    </h6>
                </label>
                <input id="input_photo_id" name="photo_id" type="file" class="">
            </div>
        </div>

        {{--us_passport--}}
        <div class="col-4">
            <div class="form-group">
                <label for="input_us_passport" style="cursor: pointer; color: grey;">
                    <h6>
                        @if ($us_passport_url)
                            <a target="_blank" href="{{$us_passport_url}}">U.S. Passport</a>
                            <span class="text-success"><i class="fas fa-check" aria-hidden="true"></i></span>
                        @else
                            U.S. Passport
                            <span class="text-danger"><i class="fas fa-x" aria-hidden="true"></i></span>
                        @endif
                    </h6>
                </label>
                <input id="input_us_passport" name="us_passport" type="file" class="">
            </div>
        </div>

        <div class="col-12 text-center">
            <div class="form-group">
                <button type="submit" style="border: none!important;" href="javascript:;" class="prolinkadd"> Submit </button>
            </div>
        </div>
    </div>
</form>

<div class="modal" id="add_project_modal" role="dialog"></div>

@push('styles')

<style type="text/css">

</style>

@endpush

@push('scripts')

    <script type="text/javascript">

    </script>

@endpush
