<?php
namespace App\Auth;

use Cake\Auth\AbstractPasswordHasher;
use Cake\Utility\Security;

/**
 * Custom Password Hasher
 */
class LegacyPasswordHasher extends AbstractPasswordHasher
{
    public function hash($password)
    {
        return Security::hash($password, 'sha3-512');
    }

    public function check($password, $hashedPassword)
    {
        return Security::hash($password, 'sha3-512') === $hashedPassword;
    }
}