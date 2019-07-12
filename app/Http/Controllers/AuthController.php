<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'    => 'required|email',
                'password' => 'required|string',
            ],
            $this->messages
        );

        if ($validator->fails()) {
            $messages = json_encode($validator->errors()->messages());
            throw new UnprocessableEntityHttpException($messages);
        }

        $user = User::where('email', $request->email)->first();

        // Returning the same message for both user not found or wrong password to give less information on failure.
        // This can be handled better, but it should do for now.
        if (!$user) {
            throw new NotFoundHttpException(json_encode('User not found or wrong password'));
        }

        if (!Hash::check($request->password, $user->password)) {
            throw new NotFoundHttpException(json_encode('User not found or wrong password'));
        }

        return response()->json([
            'token' => $this->createJwtToken($user),
        ]);
    }

    /**
     * Create a new token.
     *
     * @param  User $user
     * @return string
     */
    private function createJwtToken(User $user)
    {
        $payload = [
            'iss' => env('JWT_ISSUER'),
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + env('JWT_EXPIRE'),
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'));

        $token = Crypt::encrypt($token);

        return $token;
    }
}
