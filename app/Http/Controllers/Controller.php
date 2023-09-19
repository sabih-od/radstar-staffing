<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="RadStar Staffing API Documentation"
 * )
 */

class Controller extends BaseController
{
    /**
     * @OA\PathItem(path="/api")
     */
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;
}
