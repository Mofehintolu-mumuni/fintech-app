<?php

namespace App\Http\Traits;

trait TokenUtil {

    public function createToken(object $userInstance): string{

        $accessToken = $userInstance->createToken("access_token")->plainTextToken;

        return $accessToken;
    }

    public function revokeToken(object $userInstance): bool{
        $userInstance->currentAccessToken()->delete();
        return true;
    }

}