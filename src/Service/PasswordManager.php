<?php

namespace App\Service;

use Exception;

class PasswordManager
{
    public function generateRandomPassword(int $length = 6): string
    {
        if ($length <= 0) {
            throw new Exception(
                'Argument 1 of App\Service\PasswordManager::generateRandomPassword() must be a positive integer',
                1
            );
        }

        $characters = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $plainPassword = '';

        for ($i = 0; $i < $length; $i++) {
            $plainPassword .= $characters[array_rand($characters)];
        }

        return $plainPassword;
    }
}
