<?php
namespace Tatami\Security\Passwords;
/**
 * Description of IPasswordHasher
 *
 * @author Martin
 */
interface IPasswordHasher 
{
    public function hashPassword($password);
    public function checkPassword($password, $stored_hash);
}