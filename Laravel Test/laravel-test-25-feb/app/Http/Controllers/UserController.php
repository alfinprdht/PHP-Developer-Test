<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationDate;
use Symfony\Component\HttpFoundation\Response;

class UserController
{

    /**
     * Routes POST {{url}}/user
     * @param Request
     * @return JsonResponse
     */

    public function register(Request $request): JsonResponse
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|email',
                'password' => 'required',
                // 'photos' => 'required',
                'creditcard_type' => 'required|string|max:50',
                'creditcard_number' => ['required', new CardNumber($request->get('creditcard_number'))],
                'creditcard_name' => 'required|string|max:255',
                'creditcard_expired' => ['required', new CardExpirationDate('my')],
                'creditcard_ccv' => ['required', new CardCvc($request->get('creditcard_number'))],
            ]);


            if ($validator->fails()) {
                return response()
                    ->json($validator->errors(), 400);
            }

            $user = new User([
                'name' => $request->get('name'),
                'address' => $request->get('address'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'creditcard_type' => $request->get('creditcard_type'),
                'photos' => $request->get('photos'),
                'creditcard_number' => $request->get('creditcard_number'),
                'creditcard_name' => $request->get('creditcard_name'),
                'creditcard_expired' => $request->get('creditcard_expired'),
                'creditcard_ccv' => $request->get('creditcard_ccv'),
            ]);
            $user->save();

            return response()
                ->json([
                    'user_id' => $user->user_id
                ], 200);
        } catch (Exception $e) {

            return response()
                ->json('Something went wrong. Please try again later.', 500);
        }
    }

    /**
     * Routes GET {{url}}/user/list
     * @param Request
     * @return JsonResponse
     */
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'nullable|string',
            'ob' => 'nullable|string|in:name,email',
            'sb' => 'nullable|string|in:asc,desc',
            'of' => 'nullable|integer',
            'lt' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()
                ->json($validator->errors(), 400);
        }

        $q = $request->get('q');
        $ob = $request->get('ob');
        $sb = $request->get('sb');
        $of = $request->get('of');
        $lt = $request->get('lt');

        $user = User::when(!empty($q), function ($query) use ($q) {
            return $query->where('name', 'like', '%' . $q . '%');
        })
            ->when(!empty($ob) && !empty($sb), function ($query) use ($ob, $sb) {
                return $query->orderBy($ob, $sb);
            })
            ->when(!empty($of), function ($query) use ($of) {
                return $query->skip($of);
            })
            ->when(!empty($lt), function ($query) use ($lt) {
                return $query->limit($lt);
            })
            ->get();

        return response()
            ->json([
                "count" => $user->count(),
                "rows" => $user->toArray()
            ], 200);
    }

    /**
     * Routes GET {{url}}/user/{id}
     * @param Request
     * @return JsonResponse
     */
    public function show(int $id)
    {

        try {

            $user = User::find($id);
            if (empty($user)) {
                return response()
                    ->json('User not found', 404);
            }

            return response()
                ->json($user, 200);
        } catch (Exception $e) {

            return response()
                ->json('Something went wrong. Please try again later.', 500);
        }
    }

    /**
     * Routes PATCH {{url}}/user
     * @param Request
     * @return JsonResponse
     */
    public function patch(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id' => 'exists:user,user_id|required|numeric',
                'name' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|email',
                'password' => 'required',
                // 'photos' => 'required',
                'creditcard_type' => 'required|string|max:50',
                'creditcard_number' => ['required', new CardNumber($request->get('creditcard_number'))],
                'creditcard_name' => 'required|string|max:255',
                'creditcard_expired' => ['required', new CardExpirationDate('my')],
                'creditcard_ccv' => ['required', new CardCvc($request->get('creditcard_number'))],
            ]);


            if ($validator->fails()) {
                return response()
                    ->json($validator->errors(), 400);
            }

            $user = User::find($request->get('user_id'))
                ->update([
                    'name' => $request->get('name'),
                    'address' => $request->get('address'),
                    'email' => $request->get('email'),
                    'password' => $request->get('password'),
                    'creditcard_type' => $request->get('creditcard_type'),
                    'photos' => $request->get('photos'),
                    'creditcard_number' => $request->get('creditcard_number'),
                    'creditcard_name' => $request->get('creditcard_name'),
                    'creditcard_expired' => $request->get('creditcard_expired'),
                    'creditcard_ccv' => $request->get('creditcard_ccv'),
                ]);

            return response()
                ->json([
                    'succcess' => $user
                ], 200);
        } catch (Exception $e) {

            return response()
                ->json('Something went wrong. Please try again later.', 500);
        }
    }
}
