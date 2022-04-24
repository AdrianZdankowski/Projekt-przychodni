<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php
    if (isset($style)) {
        echo "<link rel='stylesheet' href='$style' />";
    }
    ?>

    <link href="https://fonts.googleapis.com/css2?family=PT+Mono&display=swap" rel="stylesheet" />
    <?php
    if (basename($_SERVER["SCRIPT_FILENAME"]) === 'forgetpswd.php') {
        echo "<script src='/js/copytoclipboard.js' defer></script>";
    }

    ?>
    <title>
        <?php
        switch (basename($_SERVER["SCRIPT_FILENAME"])) {
            case 'index.php':
                echo "Strona logowania PNPKMED";
                break;
            case 'menu.php':
                echo "Strona główna | {$_SESSION['client']["login"]}";
                break;
            case 'userdata.php':
                echo "Dane użytkownika | {$_SESSION['client']["login"]}";
                break;
            case 'forgetpswd.php':
                echo "Przywracanie hasła";
                break;
            case 'doctordata.php':
                echo "Nasi lekarze";
                break;
            case 'registration.php':
                echo "Zarejestruj się w PNPKMED!";
                break;
            case 'deleteuser.php':
                echo "Strona usuwania konta";
                break;
            case 'apptinfo.php':
                echo "Wizyty użykownika {$_SESSION['client']["login"]}";
                break;

            default:
                echo "PNPKMED";
                break;
        }
        ?>
    </title>
</head>