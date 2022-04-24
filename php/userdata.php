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


$sql = "SELECT login, imie, nazwisko, email, nr_tel FROM klienci WHERE login = '{$_SESSION['client']["login"]}'";
$query = mysqli_query($conn, $sql);
$result = mysqli_fetch_array($query);

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

		<?php include "_navbar.php" ?>

		<div class="userinfo">
			<?php
			echo <<<HTML
			<div class="info1">
			<p> Login: </p>
			<p> Imię: </p>
			<p> Nazwisko: </p>
			<p> Adres e-mail: </p>
			<p> Numer telefonu: </p>
			</div>
			<div class="info2">
			<p> {$result['login']} </p>
			<p> {$result['imie']}</p>
			<p> {$result['nazwisko']}</p>
			<p> {$result['email']}</p>
			<p> {$result['nr_tel']} </p>
			</div>
			HTML;
			?>
			<a href="edituser.php">
				<button>Edytuj dane osobowe</button>
			</a>
			<a href="deleteuser.php">
				<button>Usuń konto</button>
			</a>


		</div>
</body>

</html>