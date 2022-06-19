<?php

namespace Glavnivc\UserBundle\Service;

class StringService
{
    /**
     * Является ли строка JSON.
     *
     * @param string $string
     * @return bool
     */
    function isJson(string $string) : bool
    {
        if (ctype_digit($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}