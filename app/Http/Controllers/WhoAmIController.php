<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class WhoAmIController extends Controller
{
    /**
     * Just a dummy function that shows as which user the request is authenticated
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // We probably don't need to check again, but just in case someone creates a new route to this function outside the middleware /shrug
        if (!$request->auth) {
            throw new UnauthorizedHttpException(json_encode('Unauthenticated'));
        }

        return response()->json(
            [
                'authenticated_as' => $request->auth->toArray(),
            ]
        );
    }
}
