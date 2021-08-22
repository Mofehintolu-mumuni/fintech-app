<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\TokenUtil;
use App\Interfaces\HttpStatusCode;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Services\ApiJsonResponserService;


class AuthController extends Controller {

    use TokenUtil;

    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

     /** 
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login | route('login')",
     *     description="Login ",
     *     tags={"Wafi app"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 @OA\Property(property="email",type="string"),
     *                 @OA\Property(property="password",type="string"),
     *                 example={
     *                  "email": "mark@example.com",
     *                  "password": "i am mark"
     *                  }
     *            )
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="q",
     *          description="Login",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function login(LoginRequest $request) {
        
        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return ApiJsonResponserService::sendData(HttpStatusCode::BAD_RESPONSE, 'User login not successful', null);
        }

        $authenticatedUser = $this->userRepository->getUser($request);

        $userToken = $this->createToken($authenticatedUser);

        return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'Login successful', $userToken);

    }


     /** 
     * @OA\Post(
     *     path="/api/register",
     *     summary="Login | route('register')",
     *     description="Login ",
     *     tags={"Wafi app"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 @OA\Property(property="name",type="string"),
     *                 @OA\Property(property="email",type="string"),
     *                 @OA\Property(property="password",type="string"),
     *                 example={
     *                  "name" : "mark",
     *                  "email": "mark@example.com",
     *                  "password": "i am mark"
     *                  }
     *            )
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="q",
     *          description="Login",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function register(RegisterRequest $request) {
        $createdUser = $this->userRepository->createUser($request->all());
        
        if(is_null($createdUser)) {
            return ApiJsonResponserService::sendData(HttpStatusCode::BAD_RESPONSE, 'User not created successfuly', null);
        }

        $userToken = $this->createToken($createdUser);

       return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'User created successfuly', $userToken);
    
    }


    public function logout(Request $request) {
        $userToken = $this->revokeToken(Auth::user());

        if(!$userToken) {
            return ApiJsonResponserService::sendData(HttpStatusCode::BAD_RESPONSE, 'Logout not successfull', []);
        }

        return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'Logout successfull', []);

    }

}