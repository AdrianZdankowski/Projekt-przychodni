<?php
function validate($input, $min, $max, $inputName, $error_name)
{
    if ((strlen($input) < $min) || (strlen($input) > $max)) {
        $_SESSION['error'][$error_name] = "<p class='error2'>{$inputName} musi zawierać od {$min} do {$max} znaków!</p>";
        return false;
    }
    return true;
}
