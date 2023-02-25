<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController
{

    /**
     * Routes POST {{url}}/user
     * @param Request
     * @return JsonResponse
     */

    public function register(Request $request): JsonResponse
    {
        return response()->json();
    }

    /**
     * Routes GET {{url}}/user/list
     * @param Request
     * @return JsonResponse
     */
    public function list(Request $request)
    {
    }

    /**
     * Routes GET {{url}}/user/{id}
     * @param Request
     * @return JsonResponse
     */
    public function show($id)
    {
    }

    /**
     * Routes PATCH {{url}}/user
     * @param Request
     * @return JsonResponse
     */
    public function patch(Request $request)
    {
    }
}
