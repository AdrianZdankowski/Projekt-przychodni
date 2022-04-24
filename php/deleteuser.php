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

$style = "/css/menu.css";
include "_header.php";

if (isset($_POST['password1'])) {

	$validation_OK = true;

	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];

	if (empty($password1) || empty($password2)) {
		$validation_OK = false;
		$_SESSION['pswd_nomatch'] = "<p class='error'>Nie wpisałeś hasła!</p>";
	}


	if ($password1 != $password2) {
		$validation_OK = false;
		$_SESSION['pswd_nomatch'] = "<p class='error'>Hasła nie są identyczne!</p>";
	} else {
		$existingPswdSQL = "SELECT haslo FROM klienci WHERE id_klienta = '{$_SESSION['client']['id_klienta']}'";
		$existingPswdQuery = mysqli_query($conn, $existingPswdSQL);
		$existingPswdResult = mysqli_fetch_array($existingPswdQuery);
	}

	if (isset($existingPswdResult)) {
		if ($password1 != password_verify($password1, $existingPswdResult['haslo'])) {
			$validation_OK = false;
			$_SESSION['pswd_nomatch'] = "<p class='error'>Wprowadzone hasło jest nieprawidłowe!</p>";
		}
	}




	if ($validation_OK === true) {
		$deleteAppointmentSQL = "DELETE FROM wizyta WHERE id_klienta ='{$_SESSION['client']["id_klienta"]}'";
		$deleteUserSQL = "DELETE FROM klienci WHERE id_klienta ='{$_SESSION['client']["id_klienta"]}'";
		mysqli_query($conn, $deleteAppointmentSQL);
		mysqli_query($conn, $deleteUserSQL);
		$_SESSION['user_deleted'] = "<p class='error'>Twoje konto zostało usunięte!</p>";
		unset($_SESSION['loggedin']);
		unset($_SERVER['client']);
		header('Location: /index.php');
	}
}

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

		<?php include "_navbar.php" ?>

		<div id="container">
			<?php
			echo <<<HTML
			<div id="deleteuserpanel">
			<p id="warning">Jeśli chcesz usunąć swoje konto {$_SESSION['client']["login"]} wpisz poniżej swoje hasło!</p>
			<form id="delusr-form" method="POST">
			<input type="password" name="password1" placeholder="Hasło" required>
			<input type="password" name="password2" placeholder="Powtórz hasło" required>
			<input type="submit" id="logbutton" value="Usuń konto">
			</form>
			HTML;
			?>
			<?php
			if (isset($_SESSION['pswd_nomatch'])) {
				echo "<div id='display-errors'>";
				echo $_SESSION['pswd_nomatch'];
				echo "</div>";
				unset($_SESSION['pswd_nomatch']);
			}

			if (isset($_SESSION['pswd_nomatch'])) {
				echo "<div id='display-errors'>";
				echo $_SESSION['pswd_nomatch'];
				echo "</div>";
				unset($_SESSION['pswd_nomatch']);
			}

			?>
		</div>
</body>

</html>