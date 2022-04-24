<?php

session_start();
require_once "config.php";

if (!isset($_SESSION['loggedin'])) {
	header('Location: /index.php');
	exit();
};

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA);

if ($conn === false) {
	die(mysqli_connect_error());
}

include "_validation.php";


if (isset($_POST['newpassword1'])) {

	$newpswd_OK = true;

	$newpassword1 = $_POST['newpassword1'];
	$newpassword2 = $_POST['newpassword2'];

	if (empty($newpassword1) || empty($newpassword2)) {
		$newpswd_OK = false;
		$_SESSION['error']['e_newpassword'] = "<p class='error2'>Nie wpisałeś hasła!</p>";
	}

	if (strcmp($newpassword1, $newpassword2) != 0) {
		$newpswd_OK = false;
		$_SESSION['error']['e_newpassword'] = "<p class='error2'>Hasła nie są identyczne!</p>";
	} else {
		$newpswd_OK = validate($newpassword1, 4, 32, "Hasło", "e_newpassword");
	}

	if ($newpswd_OK === true) {
		$existingPswdSQL = "SELECT haslo FROM klienci WHERE id_klienta = '{$_SESSION['client']['id_klienta']}'";
		$existingPswdQuery = mysqli_query($conn, $existingPswdSQL);
		$existingPswdResult = mysqli_fetch_array($existingPswdQuery);
	}
	if (isset($existingPswdResult)) {
		if (password_verify($newpassword1, $existingPswdResult['haslo'])) {
			$newpswd_OK = false;
			$_SESSION['error']['e_newpassword'] = "<p class='error2'>Już masz takie hasło!</p>";
		} else {
			$newpasswordHash = password_hash($newpassword1, PASSWORD_DEFAULT);
		}
	}

	if ($newpswd_OK === true) {
		$updatePasswordSQL = "UPDATE klienci SET haslo = '$newpasswordHash' WHERE id_klienta = '{$_SESSION['client']['id_klienta']}'";
		$updatePasswordQuery = mysqli_query($conn, $updatePasswordSQL);
		header('Location: /php/userdata.php');
	}
}


if (!empty($_POST['newphone_nr'])) {

	$newphone_OK = true;
	$newphone_nr = $_POST['newphone_nr'];

	if (!ctype_digit($newphone_nr)) {
		$newphone_OK = false;
		$_SESSION['error']["newphone_nr"] = "<p class='error2'>Numer telefonu musi składać się tylko z cyfr!</p>";
	} else {
		$newphone_OK = validate($newphone_nr, 6, 9, "Numer telefonu", "newphone_nr");
	}

	if ($newphone_OK === true) {
		$existingNumbersSQL = "SELECT `nr_tel` FROM `klienci` WHERE nr_tel = '$newphone_nr' UNION SELECT nr_tel FROM lekarze WHERE nr_tel = '$newphone_nr'";
		$existingNumbersQuery = mysqli_query($conn, $existingNumbersSQL);
		$clientNumbersResult = mysqli_fetch_array($existingNumbersQuery);
	}

	if (empty($clientNumbersResult)) {
		if ($newphone_OK === true) {
			$updatephoneSQL = "UPDATE klienci SET nr_tel = '$newphone_nr' WHERE id_klienta = '{$_SESSION['client']['id_klienta']}'";
			mysqli_query($conn, $updatephoneSQL);
			header('Location: /php/userdata.php');
		}
	} else {
		$_SESSION['error']["newphone_nr"] = "<p class='error2'>Numer telefonu jest zajęty!</p>";
	}
}

if (!empty($_POST['newmail'])) {

	$newmail_OK = true;
	$newmail = $_POST['newmail'];

	if (filter_var($newmail, FILTER_VALIDATE_EMAIL) === false) {
		$_SESSION['error']["newmail"] = "E-mail nie jest poprawny";
		$newmail_OK = false;
	} else {
		$existingMailsSQL = "SELECT email FROM klienci WHERE email = '$newmail' UNION SELECT email FROM lekarze WHERE email = '$newmail'";
		$existingMailsQuery = mysqli_query($conn, $existingMailsSQL);
		$clientMailsResult = mysqli_fetch_array($existingMailsQuery);
	}


	if (empty($clientMailsResult)) {
		if ($newmail_OK === true) {
			$updateMailSQL = "UPDATE klienci SET email = '$newmail' WHERE id_klienta = '{$_SESSION['client']['id_klienta']}'";
			mysqli_query($conn, $updateMailSQL);
			header('Location: /php/userdata.php');
		}
	} else {
		$_SESSION['error']["newmail"] = "<p class='error2'>Adres email jest zajęty!</p>";
	}
}


$style = "/css/menu.css";
include "_header.php";

?>

<body>
	<div id="logo">
		<h1>PNPKMED&trade;</h1>
		<p id="hellomsg">
			Witaj
			<?php echo $_SESSION['client']["login"] ?>
		</p>
	</div>
	<div id="container">
		<div class="infopanel">

			<form class="edit_user_form" method="POST">
				<h2>Zmiana hasła użytkownika <?php echo $_SESSION['client']["login"] ?></h2>
				<div class="edit_userdiv">

					<input type="password" class="edit_userdiv_input" name="newpassword1" placeholder="Nowe hasło (4-32)">
					<input type="password" class="edit_userdiv_input" name="newpassword2" placeholder="Powtórz nowe hasło">
					<?php
					if (isset($_SESSION['error']['e_newpassword'])) {
						echo $_SESSION['error']['e_newpassword'];
						unset($_SESSION['error']['e_newpassword']);
					}
					?>
					<input id="logbutton" type="submit" value="Zmień hasło">
				</div>
			</form>
			<form class="edit_user_form" method="POST">
				<h2>Edytowanie danych konta <?php echo $_SESSION['client']["login"] ?></h2>
				<p id="edit_userdiv_info">Puste pola nie zostaną zaaktualizowane!</p>
				<div class="edit_userdiv">

					<input id="phoneinputfield" type="text" class="edit_userdiv_input" name="newphone_nr" placeholder="Nowy numer telefonu (6-9)">
					<?php
					if (isset($_SESSION['error']["newphone_nr"])) {
						echo "{$_SESSION['error']["newphone_nr"]}";
						unset($_SESSION['error']["newphone_nr"]);
					}
					?>
					<input type="text" class="edit_userdiv_input" name="newmail" placeholder="Nowy adres email">
					<?php
					if (isset($_SESSION['error']["newmail"])) {
						echo "<p class='error2'>{$_SESSION['error']["newmail"]}</p>";
						unset($_SESSION['error']["newmail"]);
					}
					?>
					<input id="logbutton" type="submit" value="Zmień dane">
				</div>

			</form>

		</div>
		<?php include "_navbar.php" ?>


</body>

</html>