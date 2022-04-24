<?php
session_start();
if ((isset($_SESSION['loggedin'])) && ($_SESSION['loggedin'] == true)) {
	header('Location: menu.php');
	exit();
}

require_once "config.php";

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA);

if ($conn === false) {
	die(mysqli_connect_error());
}

function randompswd()
{
	$chars = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890';
	$newpswd = array();
	$charLength = strlen($chars) - 1;
	for ($i = 0; $i < 4; $i++) {
		$x = rand(0, $charLength);
		$newpswd[] = $chars[$x];
	}
	return implode($newpswd);
}

if (isset($_POST['login']) && isset($_POST['email'])) {
	$login = mysqli_real_escape_string($conn, $_POST['login']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);


	if (!empty($login) || !empty($email)) {

		$exist = "SELECT id_klienta, login, email FROM klienci WHERE login = '$login' AND email = '$email'";
		$query = mysqli_query($conn, $exist);
		$result = mysqli_fetch_array($query);
		if (!empty($result['login']) && !empty($result['email']) && $result["login"] === $login && $result["email"] === $email) {
			$_SESSION['pswd'] = randompswd();
		} else {
			$_SESSION['error']['pswd'] = "Nie ma takiego użytkownika w bazie, lub adres e-mail jest używany.";
		}
	}
}


if (isset($_SESSION['pswd'])) {
	$hashpswd = password_hash($_SESSION['pswd'], PASSWORD_DEFAULT);
	$chgpswd = "UPDATE klienci SET haslo = '$hashpswd' WHERE login = '$login'";
	mysqli_query($conn, $chgpswd);
}

$style = "/css/style.css";
include "_header.php";


?>


<body class="forgetpswd">
	<div id="logo">
		<h1>PNPKMED&trade;</h1>
	</div>
	<div id="container">
		<div id="box">
			<div id="panel">
				<form method="POST">
					<h2 id="hpanel">Panel generacji nowego hasła</h2>
					<input type="text" id="login" name="login" placeholder="Login" />
					<input type="text" name="email" placeholder="Adres e-mail" />
					<input type="submit" id="logbutton" name="logbutton" value="Stwórz nowe hasło" />



				</form>

				<?php
				if (isset($_SESSION['pswd'])) {
					echo <<<HTML
					<div id='newpswd_result'>
						<input type='text' value={$_SESSION['pswd']} id='newpassword'/>
						<button id='copytext'>Kopiuj hasło</button>
					</div>	
					HTML;

					unset($_SESSION['pswd']);
				}


				if (isset($_SESSION['error']['pswd'])) {
					echo "<p class='error'>{$_SESSION['error']['pswd']}<p>";
					unset($_SESSION['error']['pswd']);
				}
				?>
				<a href="/index.php">
					<p>Wróć</p>
				</a>
			</div>
		</div>
	</div>
</body>

</html>