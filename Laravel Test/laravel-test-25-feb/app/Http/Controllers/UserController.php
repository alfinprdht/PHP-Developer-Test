<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCreditCard;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationDate;
use App\Models\UserPhoto;

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
                'email' => 'required|email|unique:user,email',
                'password' => 'required',
                'photos.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:8000',
                'creditcard_type' => 'required|string|max:50',
                'creditcard_number' => ['required', new CardNumber($request->get('creditcard_number')), 'unique:user_credit_card,creditcard_number'],
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
                'password' => bcrypt($request->get('password')),
            ]);
            $user->save();

            $userCreditCard = new UserCreditCard([
                'creditcard_type' => $request->get('creditcard_type'),
                'creditcard_number' => $request->get('creditcard_number'),
                'creditcard_name' => $request->get('creditcard_name'),
                'creditcard_expired' => $request->get('creditcard_expired'),
                'creditcard_ccv' => $request->get('creditcard_ccv'),
            ]);

            $user->userCreditCard()->save($userCreditCard);

            if (!empty($request->file('photos'))) {
                foreach ($request->file('photos') as $imagefile) {
                    $path = $imagefile->store('public/images');
                    $userPhoto = new UserPhoto([
                        'filename' => $path
                    ]);
                    $user->userPhoto()->save($userPhoto);
                }
            }

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

        $user = User::addSelect('user_id', 'name', 'email', 'address')
            ->when(!empty($q), function ($query) use ($q) {
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
            ->with(['UserPhoto' => function ($query) {
                $query->select('filename', 'user_id');
            }])
            ->with(['UserCreditCard' => function ($query) {
                $query->select('creditcard_type', 'creditcard_number', 'creditcard_name', 'creditcard_expired', 'user_id');
            }])
            ->get()->toArray();

        $user = array_map(function ($v) {
            $row = $v;
            $photo = [];
            if (!empty($row['user_photo'])) {
                foreach ($row['user_photo'] as $photos) {
                    $photo[] = $photos['filename'];
                }
            }
            $row['photo'] = $photo;
            unset($row['user_photo']);
            $row['creditcard'] = [
                'type' => $row['user_credit_card']['creditcard_type'],
                'number' => substr($row['user_credit_card']['creditcard_number'], -4),
                'name' => $row['user_credit_card']['creditcard_name'],
                'expired' => $row['user_credit_card']['creditcard_expired']
            ];;
            unset($row['user_credit_card']);
            return $row;
        }, $user);

        return response()
            ->json([
                "count" => count($user),
                "rows" => $user
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

            $user = User::addSelect('user_id', 'name', 'email', 'address')
                ->with(['UserPhoto' => function ($query) {
                    $query->select('filename', 'user_id');
                }])
                ->with(['UserCreditCard' => function ($query) {
                    $query->select('creditcard_type', 'creditcard_number', 'creditcard_name', 'creditcard_expired', 'user_id');
                }])
                ->where('user_id', $id)->first()->toArray();

            if (empty($user)) {
                return response()
                    ->json('User not found', 404);
            }

            $photo = [];
            if (!empty($user['user_photo'])) {
                foreach ($user['user_photo'] as $photos) {
                    $photo[] = $photos['filename'];
                }
            }
            $user['photo'] = $photo;
            unset($user['user_photo']);

            $userCreditCard = $user['user_credit_card'];
            if (!empty($userCreditCard)) {
                $user['creditcard'] = [
                    'type' => $user['user_credit_card']['creditcard_type'],
                    'number' => substr($user['user_credit_card']['creditcard_number'], -4),
                    'name' => $user['user_credit_card']['creditcard_name'],
                    'expired' => $user['user_credit_card']['creditcard_expired']
                ];;
                unset($user['user_credit_card']);
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
                'photos.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:8000',
                'creditcard_type' => 'required|string|max:50',
                'creditcard_number' => ['required', new CardNumber($request->get('creditcard_number')), 'unique:user_credit_card,creditcard_number'],
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
                    'password' => bcrypt($request->get('password')),
                ]);

            User::find($request->get('user_id'))
                ->userCreditCard()
                ->update([
                    'creditcard_type' => $request->get('creditcard_type'),
                    'creditcard_number' => $request->get('creditcard_number'),
                    'creditcard_name' => $request->get('creditcard_name'),
                    'creditcard_expired' => $request->get('creditcard_expired'),
                    'creditcard_ccv' => $request->get('creditcard_ccv'),
                ]);

            if (!empty($request->file('photos'))) {
                foreach ($request->file('photos') as $imagefile) {
                    $path = $imagefile->store('public/images');
                    $userPhoto = new UserPhoto([
                        'filename' => $path
                    ]);
                    $user->userPhoto()->save($userPhoto);
                }
            }

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
