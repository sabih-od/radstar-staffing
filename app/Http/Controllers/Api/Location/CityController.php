<?php

namespace App\Http\Controllers\Api\Location;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Location\CityRepository;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected $cityRepository;
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }
    /**
     * @OA\Get(
     *     path="/cities/{stateId}",
     *     summary="Cities Data",
     *     tags={"City"},
     *     @OA\Parameter(
     *         name="stateId",
     *         in="path",
     *         required=true,
     *         description="The ID of the state for which you want to retrieve cities.",
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


    public function getCities($stateId){
        try {
            return APIResponse::success("Cities fetched Successfully", $this->cityRepository->getStateId($stateId));
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }

}
