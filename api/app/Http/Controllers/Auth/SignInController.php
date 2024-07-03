<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\SignInRequest;
use App\Services\AuthServiceInterface;
use Illuminate\Http\JsonResponse;


class SignInController extends Controller
{

    protected $authService;

    /**
     * create a new instance
     *
     * @param AuthServiceInterface $authService
     */
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Post login API-LG-010
     *
     * @param SignInRequest $request
     * @return JsonResponse
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        $idToken = $this->authService->signIn($request->email, $request->password);

        return $this->response($idToken, 200);
    }

    /**
     * Post login API-LG-010
     *
     * @param SignInRequest $request
     * @return JsonResponse
     */
    public function signUp(RegisterUserRequest $request): JsonResponse
    {
        $idToken = $this->authService->signUp(
            companyId: $request->companyId,
            email: $request->email, password: $request->password,
            nameFamily: $request->nameFamily, nameFirst: $request->nameFirst,
            nameFamilyKana: $request->nameFamilyKana,nameFirstKana: $request->nameFirstKana,
            tel: $request->tel
        );

        return $this->response($idToken, 200);
    }

    /**
     * Logout API-LG-020
     *
     * @return JsonResponse
     */
    public function signOut(): JsonResponse
    {
        $result = $this->authService->logout();
        return $this->response($result, 200);
    }
}


