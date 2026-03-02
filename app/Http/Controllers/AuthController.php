<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
     
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->sendEmailVerificationNotification();
        $success['user'] =  $user;
   
        return $this->sendResponse($success, 'User registered successfully. Please check your email to verify your account.');
    }
  
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
  
        if (! $token = JWTAuth::attempt($credentials)) {
            return $this->sendError('Unauthorized.', ['error'=>'Email or password is incorrect']);
        }
  
        $success = $this->respondWithToken($token);
   
        return $this->sendResponse($success, 'User login successfully.');
    }
  
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            return response()->json([
                'status'  => 'success',
                'message' => 'User profile fetched',
                'data'    => $user
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token is invalid or expired',
                'error'   => $e->getMessage()
            ], 401);
        }
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return $this->sendResponse([], 'Successfully logged out.');
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $success = $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));
   
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }

    protected function sendResponse($result, $message, $code = 200)
    {
        return response()->json([
            'statuscode' => $code,
            'msg'        => $message,
            'data'       => $result,
        ], $code);
    }

    protected function sendError($error, $errorMessages = [], $code = 401)
    {
        return response()->json([
            'statuscode' => $code,
            'msg'        => $error,
            'data'       => $errorMessages,
        ], $code);
    }
}