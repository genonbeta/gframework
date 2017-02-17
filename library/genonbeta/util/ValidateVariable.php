<?php

namespace genonbeta\util;

class ValidateVariable
{
    public static function validateEmail($variable)
    {
        return filter_var($variable, FILTER_VALIDATE_EMAIL);
    }

    public static function valiteFullName($variable)
    {
        return preg_match("/^[a-zA-Z ]*$/", $variable);
    }

    public static function valiteName($variable)
    {
        return preg_match("/^[a-zA-Z]*$/", $variable);
    }
}
