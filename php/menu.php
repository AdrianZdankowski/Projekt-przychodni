<?php

session_start();
require_once "config.php";

$style = "/css/menu.css";


if (!isset($_SESSION['loggedin'])) {
	header('Location: /index.php');
	exit();
};

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA);

if ($conn === false) {
	die(mysqli_connect_error());
}


$specializationsSQL = "SELECT * FROM specjalizacje";
$specializationQuery = mysqli_query($conn, $specializationsSQL);
while (($row = mysqli_fetch_array($specializationQuery)) !== null) {
	$specializations[$row['id_specjalizacji']] = $row;
};


$doctorsSQL = "SELECT imie,nazwisko,id_specjalizacji,id_lekarza FROM lekarze";
$doctorQuery = mysqli_query($conn, $doctorsSQL);
while (($row = mysqli_fetch_array($doctorQuery)) !== null) {
	$doctors[$row['id_specjalizacji']][] = $row;
};


if (isset($_POST['illness'])) {

	$validation_OK = true;

	$illness = $_POST['illness'];
	$description = $_POST['description'];
	if (isset($_POST['specializations'])) {
		$selectedDoctor = $_POST['specializations'];
	} else {
		$selectedDoctor = null;
	}
	$apptdate = $_POST['apptdate'];
	$appttime = $_POST['appttime'];

	$minTime = "06:00";
	$maxTime = "18:00";

	if (isset($appttime) && !empty($appttime)) {
		$checkTime = explode(":", $appttime);
	}

	include "_validation.php";

	$validation_OK = validate($illness, 3, 40, "Dolegliwość", "illness") && validate($description, 0, 125, "Opis choroby", "description");


	if (empty($selectedDoctor)) {
		$validation_OK = false;
		$_SESSION['error']['selectedDoctor'] = "<p class='error2'>Lekarz musi być wybrany!</p>";
	}


	if (empty($apptdate)) {
		$validation_OK = false;
		$_SESSION['error']['apptdate'] = "<p class='error2'>Data musi być wybrana!</p>";
	} elseif ($apptdate < date("Y-m-d")) {
		$validation_OK = false;
		$_SESSION['error']['apptdate'] = "<p class='error2'>Data nie może być wcześniejsza od dzisiaj!</p>";
	} else {

		$checkDoctorSQL = "SELECT id_lekarza, data_wizyty, godzina_wizyty FROM wizyta WHERE data_wizyty = '$apptdate' AND godzina_wizyty = '$appttime' AND id_lekarza='$selectedDoctor'";
		$checkDoctorQuery = mysqli_query($conn, $checkDoctorSQL);
		$checkDoctorResult = mysqli_fetch_array($checkDoctorQuery);

		$checkClientSQL = "SELECT id_klienta, data_wizyty, godzina_wizyty FROM wizyta WHERE data_wizyty = '$apptdate' AND godzina_wizyty = '$appttime' AND id_klienta={$_SESSION['client']['id_klienta']}";
		$checkClientQuery = mysqli_query($conn, $checkClientSQL);
		$checkClientResult = mysqli_fetch_array($checkClientQuery);
	}

	if (!empty($checkDoctorResult)) {
		$validation_OK = false;
		$_SESSION['ApptExists'] = "<p class='error2'>Wybrany termin jest już zajęty!</p>";
	}

	if (!empty($checkClientResult)) {
		$validation_OK = false;
		$_SESSION['ApptExists'] = "<p class='error2'>Masz umówioną inną wizytę w tym czasie!</p>";
	}

	if (strtotime($appttime) < strtotime($minTime) || strtotime($appttime) > strtotime($maxTime)) {
		$validation_OK = false;
		$_SESSION['error']['appttime'] = "<p class='error2'>Godzina musi być w zakresie od 6:00 do 18:00!</p>";
	}
	if (isset($checkTime)) {
		if (strcmp($checkTime[1], "00") != 0 && strcmp($checkTime[1], "30") != 0) {
			$validation_OK = false;
			$_SESSION['error']['appttime'] = "<p class='error2'>Odstęp pomiędzy wizytami musi wynosić minimum 30 minut!</p>";
		}
	}
	if ($validation_OK === true) {
		$addvisitsql = "INSERT INTO wizyta VALUES (NULL,{$_SESSION['client']['id_klienta']},'$selectedDoctor','$apptdate','$appttime','$illness','$description')";
		mysqli_query($conn, $addvisitsql);
		$_SESSION['visitCreated'] = "<p id='success'>Wizyta została umówiona</p>";
	}
}
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

		<div id="appointment">
			<h2 id="appt-header">Formularz umawiania wizyty</h2>
			<?php
			if (isset($_SESSION['ApptExists'])) {
				echo "<div id='display-errors'>";
				echo $_SESSION['ApptExists'];
				echo "</div>";
				unset($_SESSION['ApptExists']);
			}

			if (isset($_SESSION['error']['illness']) || isset($_SESSION['error']['description']) || isset($_SESSION['error']['selectedDoctor']) ||  isset($_SESSION['error']['apptdate']) || isset($_SESSION['error']['appttime'])) {
				echo "<div id='display-errors'>";
				$errors = array('illness', 'description', 'selectedDoctor', 'apptdate', 'appttime');
				foreach ($errors as $error) {
					if (isset($_SESSION['error'][$error])) {
						echo $_SESSION['error'][$error];
						unset($_SESSION['error'][$error]);
					}
				}

				echo "</div>";
			}

			if (isset($_SESSION['visitCreated'])) {
				echo $_SESSION['visitCreated'];
				unset($_SESSION['visitCreated']);
			}
			?>
			<form id="appt-form" method="POST">
				<h3>Powód wizyty</h3>

				<div class="menuformdiv">
					<label for="illness">Dolegliwość (3-40)
						<input class="menuformInput" type="text" name="illness" placeholder="Choroba/Dolegliwość">

					</label>

					<textarea id="descriptionInput" id="description" name="description" placeholder="Opis (Opcjonalny)"></textarea>

				</div>
				<label>Wybór lekarza
					<select class="menuformdiv" name="specializations">
						<option value="" selected hidden disabled>Wybierz lekarza</option>
						<?php

						foreach ($specializations as $specialization) {

							echo "<optgroup label='{$specialization['nazwa']}'>";
							foreach ($doctors[$specialization['id_specjalizacji']] as $doctor) {

								echo "<option value='{$doctor['id_lekarza']}'>{$doctor['imie']} {$doctor['nazwisko']}</option>";
							}
							echo "</optgroup>";
						}

						?>
					</select>

				</label>
				<h3>Termin wizyty</h3>
				<p id="time-window-info">Przyjmujemy cały tydzień w godzinach 6:00-18:00</p>
				<div class="menuformdiv">
					<label for="apptdate">Data wizyty
						<input class="menuformInput" type="date" id="dateinput" name="apptdate" min="<?php echo date('Y-m-d') ?>">

					</label>
					<label for="appttime">Godzina wizyty
						<input class="menuformInput" type="time" name="appttime" min="6:00" max="18:00" step="1800">

					</label>
				</div>
				<input id="apptsubmit" type="submit" value="Umów wizytę">
			</form>
		</div>
	</div>
</body>

</html>