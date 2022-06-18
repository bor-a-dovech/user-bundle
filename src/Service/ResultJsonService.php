<?php

namespace Glavnivc\UserBundle\Service;

use Glavnivc\UserBundle\Entity\Permission;
use Glavnivc\UserBundle\Entity\Role;
use Glavnivc\UserBundle\Entity\User;

/**
 * Класс, возвращающий json-результат в едином виде.
 */
class ResultJsonService
{
    public function error($message = null) : array
    {
        $result = [
            'result' => 'error',
            'message' => $message ?? 'Error',
        ];
        return $result;
    }

    public function ok() : array
    {
        return [
            'result' => 'ok',
        ];
    }

}