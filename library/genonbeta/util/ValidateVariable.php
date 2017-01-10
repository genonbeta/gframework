<?php

namespace genonbeta\util;

class ValidateVariable
{
    public function validateEmail($variable)
    {
        return filter_var($variable, FILTER_VALIDATE_EMAIL);
    }

    public function valiteFullName($variable)
    {
        return preg_match("/^[a-zA-Z ]*$/", $variable);
    }

    public function valiteName($variable)
    {
        return preg_match("/^[a-zA-Z]*$/", $variable);
    }
}
