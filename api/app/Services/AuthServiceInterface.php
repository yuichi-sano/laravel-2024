<?php

namespace App\Services;

interface AuthServiceInterface
{
    public function signUp(
        int $companyId,
        string $email, string $password,
        string $nameFamily, string $nameFirst,
        string $nameFamilyKana, string $nameFirstKana,
        string $tel
    );
    public function signIn(string $email, string $password);
    public function signOut();
}
