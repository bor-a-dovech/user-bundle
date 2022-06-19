<?php

namespace Glavnivc\UserBundle\Security\Credentials;

interface CheckCredentialsServiceInterface
{
    public function checkCredentials(string $login, string $pass):bool;
}