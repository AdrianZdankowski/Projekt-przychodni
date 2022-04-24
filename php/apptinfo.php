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

$appointments = [];
$apptSQL = "SELECT id_klienta, dolegliwosc, data_wizyty, godzina_wizyty, lekarze.imie, lekarze.nazwisko FROM wizyta INNER JOIN lekarze ON wizyta.id_lekarza = lekarze.id_lekarza WHERE id_klienta = {$_SESSION['client']['id_klienta']}";
$apptQuery = mysqli_query($conn, $apptSQL);
while (($row = mysqli_fetch_array($apptQuery)) !== null) {
	$appointments[] = $row;
};

if (empty($appointments)) {
	$_SESSION['noappointments'] = "<p>Nie masz żadnej umówionej wizyty.</p>";
}



$style = "/css/menu.css";
include "_header.php";

?>

<body>
	<div id="logo">
		<h1>PNPKMED&trade;</h1>
		<p id="hellomsg">
			Witaj <?php echo $_SESSION['client']["login"] ?>
		</p>
	</div>
	<div id="container">

		<?php include "_navbar.php" ?>

		<div class="infopanel">

			<?php
			if (isset($_SESSION['noappointments'])) {
				echo $_SESSION['noappointments'];
				unset($_SESSION['noappointments']);
			} else {
				echo <<<HTML
				<table class='docinfotable'>
				<tr>
					<th>Data wizyty</th>
					<th>Godzina wizyty</th>
					<th>Powód wizyty</th>
					<th>Lekarz</th>
					</tr>
				HTML;

				foreach ($appointments as $appointment) {
					$time = substr("{$appointment['godzina_wizyty']}", 0, 5);

					echo <<<HTML
					<tr>
					<td>{$appointment['data_wizyty']}</td>
					<td>$time</td>
					<td>{$appointment['dolegliwosc']}</td>
					<td>{$appointment['imie']} {$appointment['nazwisko']}</td>
					</tr>
					
					HTML;
				}
				echo "</table>";
			}
			?>


		</div>
</body>

</html>