<?php
session_start();
require_once "config.php";

if ((isset($_SESSION['loggedin'])) && ($_SESSION['loggedin'] == true)) {
	header('Location: /php/menu.php');
	exit();
}

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA);

$style = "/css/style.css";
include "_header.php";

if ($conn === false) {
	die(mysqli_connect_error());
}


if (isset($_POST['login'])) {

	$validation_OK = true;

	$login = $_POST['login'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$name = $_POST['name'];
	$surname = $_POST['surname'];
	$nr_tel = $_POST['nr_tel'];
	$email = $_POST['email'];

	include "_validation.php";

	validate($login, 3, 24, "Login", "login");
	validate($password, 4, 32, "Hasło", "password");
	validate($name, 2, 32, "Imię", "name");
	validate($surname, 2, 48, "Nazwisko", "surname");
	validate($nr_tel, 6, 9, "Numer telefonu", "nr_tel");

	if (!ctype_alnum($login)) {
		$_SESSION['error']["login"] = "<p class='error2'>Nazwa użytkownika może zawierać tylko znaki alfanumeryczne!</p>";
		$validation_OK = false;
	}


	if ($validation_OK === true) {
		$existsLoginSQL = "SELECT login FROM klienci WHERE BINARY login='$login'";
		$existsLoginQuery = mysqli_query($conn, $existsLoginSQL);
		$existsLoginResult = mysqli_fetch_array($existsLoginQuery);
	}

	if (!empty($existsLoginResult)) {
		$_SESSION['error']["login"] = "<p class='error2'>Nazwa użytkownika jest zajęta!</p>";
		$validation_OK = false;
	}

	if (preg_match('/[\'^£[$%&*()}{@#~?><>,|=_+¬-]/', $name)) {
		$validation_OK = false;
		$_SESSION['error']["name"] = "<p class='error2'>Imię może zawierać tylko litery!</p>";
	}

	if (preg_match('/[\'^£[$%&*()}{@#~?><>,|=_+¬-]/', $surname)) {
		$validation_OK = false;
		$_SESSION['error']["surname"] = "<p class='error2'>Nazwisko może zawierać tylko litery!</p>";
	}

	if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		$_SESSION['error']["email"] = "<p class='error2'>E-mail nie jest poprawny</p>";
		$validation_OK = false;
	} else {
		$existsEmailSQL = "SELECT email FROM klienci WHERE email = '$email' UNION SELECT email FROM lekarze WHERE email = '$email'";
		$existsEmailQuery = mysqli_query($conn, $existsEmailSQL);
		$existsEmailResult = mysqli_fetch_array($existsEmailQuery);
	}

	if (!empty($existsEmailResult)) {
		$_SESSION['error']["email"] = "<p class='error2'>E-mail jest już w użyciu!</p>";
		$validation_OK = false;
	}

	if (!ctype_digit($nr_tel)) {
		$_SESSION['error']["nr_tel"] = "<p class='error2'>Numer telefonu musi składać się tylko z cyfr!</p>";
		$validation_OK = false;
	} else {
		$existingPhonesSQL = "SELECT `nr_tel` FROM `klienci` WHERE nr_tel = '$nr_tel' UNION SELECT nr_tel FROM lekarze WHERE nr_tel = '$nr_tel'";
		$existingPhonesQuery = mysqli_query($conn, $existingPhonesSQL);
		$existingPhonesResult = mysqli_fetch_array($existingPhonesQuery);
	}

	if (!empty($existingPhonesResult)) {
		$_SESSION['error']["nr_tel"] = "<p class='error2'>Numer telefonu jest już w użyciu!</p>";
		$validation_OK = false;
	}

	if ($password != $password2) {
		$_SESSION['error']['password2'] = "<p class='error2'>Hasła nie są identyczne!</p>";
		$validation_OK = false;
	} else {
		$passwordHash = password_hash($password, PASSWORD_DEFAULT);
	}

	if (validate($login, 3, 24, "Login", "login") === false || validate($password, 4, 32, "Hasło", "password") === false || validate($name, 2, 32, "Imię", "name") === false || validate($surname, 2, 48, "Nazwisko", "surname") === false || validate($nr_tel, 6, 9, "Numer telefonu", "nr_tel") === false) {
		$validation_OK = false;
	}


	if ($validation_OK === true) {

		$addUserSQL = "INSERT INTO klienci VALUES (NULL,'$login','$passwordHash','$name','$surname','$email','$nr_tel')";
		mysqli_query($conn, $addUserSQL);
		header('Location: /index.php');
	}
}
?>

<body>
	<div id="logo">
		<h1>PNPKMED&trade;</h1>
	</div>
	<div id="container">
		<div id="box">
			<div id="panel">
				<form method="POST">
					<h2>Panel rejestracji</h2>
					<br />
					<input type="text" name="login" placeholder="Nazwa użytkownika (3-32)" /> <br />
					<?php
					if (isset($_SESSION['error']["login"])) {
						echo $_SESSION['error']["login"];
						unset($_SESSION['error']["login"]);
					}
					?>
					<input type="password" name="password" placeholder="Hasło (4-32)" /> <br />
					<?php
					if (isset($_SESSION['error']["password"])) {
						echo $_SESSION['error']["password"];
						unset($_SESSION['error']["password"]);
					}
					?>
					<input type="password" name="password2" placeholder="Powtórz hasło" /> <br />
					<?php
					if (isset($_SESSION['error']["password2"])) {
						echo $_SESSION['error']["password2"];
						unset($_SESSION['error']["password2"]);
					}
					?>
					<input type="text" name="name" placeholder="Imię (2-32)" /> <br />
					<?php
					if (isset($_SESSION['error']["name"])) {
						echo $_SESSION['error']["name"];
						unset($_SESSION['error']["name"]);
					}
					?>
					<input type="text" name="surname" placeholder="Nazwisko (2-48)" /> <br />
					<?php
					if (isset($_SESSION['error']["surname"])) {
						echo $_SESSION['error']["surname"];
						unset($_SESSION['error']["surname"]);
					}
					?>
					<input maxlength="9" type="text" name="nr_tel" placeholder="Numer telefonu (6-9)" /> <br />
					<?php
					if (isset($_SESSION['error']["nr_tel"])) {
						echo $_SESSION['error']["nr_tel"];
						unset($_SESSION['error']["nr_tel"]);
					}
					?>
					<input type="email" name="email" placeholder="Adres e-mail" /> <br />
					<?php
					if (isset($_SESSION['error']["email"])) {
						echo $_SESSION['error']["email"];
						unset($_SESSION['error']["email"]);
					}

					?>
					<br />
					<input type="submit" id="logbutton" value="Zarejestruj się teraz!">
					<p><a href="../index.php">Wróć</a></p>
				</form>
			</div>
		</div>
	</div>
</body>

</html>