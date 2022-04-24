<?php
session_start();
if ((isset($_SESSION['loggedin'])) && ($_SESSION['loggedin'] == true)) {
	header('Location: /php/menu.php');
	exit();
}
$style = "css/style.css";
include "php/_header.php";

?>


<body>
	<div id="logo">
		<h1>PNPKMED&trade;</h1>
	</div>
	<div id="container">
		<div id="box">
			<div id="panel">
				<form method="POST" action="/php/_login.php">
					<h2 id="hpanel">Panel logowania</h2>


					<input type="text" id="login" name="login" placeholder="Login" required />
					<input type="password" id="pswd" name="haslo" placeholder="Hasło" required />
					<button type="submit" id="logbutton" name="logbutton">Zaloguj się</button>

					<?php

					if (isset($_SESSION['loginerror'])) {
						echo $_SESSION['loginerror'];
						unset($_SESSION['loginerror']);
					}
					if (isset($_SESSION['user_deleted'])) {
						echo $_SESSION['user_deleted'];
						unset($_SESSION['user_deleted']);
					}
					?>


					<p id="register"><a href="/php/registration.php">Zarejestruj się teraz!</a></p>
					<a href="/php/forgetpswd.php">
						<p>Zapomniałeś hasła?</p>
					</a>

				</form>
			</div>
		</div>
	</div>
</body>

</html>