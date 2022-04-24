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

$dataset = [];

$sql = "SELECT `imie`,`nazwisko`,`email`,`nr_tel`,specjalizacje.nazwa FROM lekarze INNER JOIN specjalizacje ON lekarze.id_specjalizacji=specjalizacje.id_specjalizacji";
$query = mysqli_query($conn, $sql);
while (($row = mysqli_fetch_array($query)) !== null) {
	$dataset[] = $row;
};

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
			<table class="docinfotable">
				<tr>
					<th>Specjalizacja</th>
					<th>Imię</th>
					<th>Nazwisko</th>
					<th>Adres e-mail</th>
					<th>Numer telefonu</th>
				</tr>
				<?php
				foreach ($dataset as $data) {
					echo <<<HTML
					<tr>
					<th>{$data['nazwa']}</th>
					<th>{$data['imie']}</th>
					<th>{$data['nazwisko']}</th>
					<th>{$data['email']}</th>
					<th>{$data['nr_tel']}</th>
					</tr>
				
					HTML;
				}
				?>


			</table>
		</div>
</body>

</html>