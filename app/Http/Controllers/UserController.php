<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'     => 'required|string',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ],
            $this->messages
        );

        if ($validator->fails()) {
            $messages = json_encode($validator->errors()->messages());
            throw new UnprocessableEntityHttpException($messages);
        }

        $user = new User($request->all());
        $user->password = Hash::make($request->password);
        $user->token = str_random(32);

        try {
            $user->save();
            return response()->json($user->toArray());
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, json_encode($e->getMessage()));
        }
    }
}
