<?php

namespace App\Http\Controllers\Api\location;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Location\StateRepository;
use Illuminate\Http\Request;

class StateController extends Controller
{

    protected $stateRepository;

    public function __construct(StateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }
    /**
     * @OA\Get(
     *     path="/states/{CountryId}",
     *     summary="Cities Data",
     *     tags={"State"},
     *     @OA\Parameter(
     *         name="CountryId",
     *         in="path",
     *         required=true,
     *         description="The ID of the country for which you want to retrieve states.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     responses={
     *         @OA\Response(
     *             response=200,
     *             description="OK",
     *             @OA\JsonContent(
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     example=true,
     *                     description="A boolean value."
     *                 ),
     *             ),
     *         ),
     *     },
     * )
     */
    public function getStates( $countryId){
        try {
        $states = $this->stateRepository->getCountryId($countryId);
            return APIResponse::success("States fetched Successfully", $states);
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }
}
