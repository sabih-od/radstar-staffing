<?php

namespace App\Http\Controllers\Api\location;

use App\Repositories\Location\CountryRepository;
use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Services\LocationService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $countryRepository , $countryService;
    /**
     * @var LocationService
     */

    public function __construct
    (
        CountryRepository $countryRepository,
        LocationService $countryService
    )
    {
        $this->countryRepository = $countryRepository;
        $this->countryService = $countryService;
    }

    public function getCountries()
    {
        try {
            return APIResponse::success("County fetched Successfully", $this->countryRepository->all());
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }


}
