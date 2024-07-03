<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Supports\Cognito\CognitoClient;
use Illuminate\Database\Eloquent\Collection;

class AuthService implements AuthServiceInterface
{
    protected CognitoClient $cognito;
    protected UserRepositoryInterface $userRepository;

    public function __construct(
        CognitoClient $cognito,
        UserRepositoryInterface $userRepository
    )
    {
        $this->cognito = $cognito;
        $this->userRepository = $userRepository;
    }

    public function signUp(
        int $companyId,
        string $email, string $password,
        string $nameFamily, string $nameFirst,
        string $nameFamilyKana, string $nameFirstKana,
        string $tel
    ){
        $cognitoUserSub = $this->cognito->register($email, $password);
        $this->userRepository->create(
            [
                'name_family' => $nameFamily,
                'name_first' => $nameFirst,
                'name_family_kana' => $nameFamilyKana,
                'name_first_kana' => $nameFirstKana,
                'tel'             => $tel,
                'cognito_sub' => $cognitoUserSub,
                'is_active'   => true,
                'company_id'  => $companyId,
            ]
        );
    }

    public function signIn(string $email, string $password)
    {
        $response = $this->cognito->auth($email, $password);

        return $response['IdToken'];
    }

    public function signOut()
    {
        // TODO: Implement logout() method.
    }
}
