<?php

namespace App\Service;

class PasswordManager
{
    public function generateRandomPassword(int $length): string
    {
        $characters = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $plainPassword = '';

        for ($i = 0; $i < $length; $i++) {
            $plainPassword .= $characters[array_rand($characters)];
        }

        return $plainPassword;
    }
}
