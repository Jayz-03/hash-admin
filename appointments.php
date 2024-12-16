<?php

session_start();

require_once "connectDB.php";

// if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
// 	$sql = "SELECT * FROM admins WHERE admin_id = '" . $_SESSION['id'] . "'";
// 	$result = mysqli_query($link, $sql);
// 	$row = mysqli_fetch_assoc($result);

// } else {
// 	header("location: login");
// 	exit;
// }

$active = "appointments";
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include "partials/head.php"; ?>
</head>

<body>

	<div id="app">
		<?php include "partials/sidebar.php"; ?>

		<div id="main">
			<header class="mb-3">
				<a href="#" class="burger-btn d-block d-xl-none">
					<i class="bi bi-justify fs-3"></i>
				</a>
			</header>

			<div class="page-heading">
				<div class="page-title">
					<div class="row">
						<div class="col-12 col-md-6 order-md-1 order-last">
							<h3>Appointments</h3>
						</div>
						<div class="col-12 col-md-6 order-md-2 order-first">
							<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Components</a></li>
									<li class="breadcrumb-item active" aria-current="page">Appointments</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<section class="section">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Appointment Information List</h4>
						</div>
						<div class="card-body">
							<div class="pb-20">

								<?php

								require __DIR__ . '/vendor/autoload.php';

								use Kreait\Firebase\Factory;
								use Kreait\Firebase\Exception\FirebaseException;

								// Initialize Firebase
								$factory = (new Factory)
									->withServiceAccount(__DIR__ . '/secret/firebase_credentials.json')
									->withDatabaseUri('https://hash-ec65c-default-rtdb.firebaseio.com/');

								$database = $factory->createDatabase();

								try {
									$appointments = $database->getReference('Appointment')->getValue();
								} catch (FirebaseException $e) {
									$errorMessage = "Error fetching data: " . $e->getMessage();
								}

								?>

								<table class="data-table table stripe hover nowrap">
									<thead>
										<tr>
											<th>Date</th>
											<th>Service</th>
											<th>Status</th>
											<th>Time</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($appointments) {
											foreach ($appointments as $userId => $userAppointments) {
												foreach ($userAppointments as $appointmentId => $appointmentDetails) {
													echo "<tr>";
													echo "<td>" . $appointmentDetails['date'] . "</td>";
													echo "<td>" . $appointmentDetails['service'] . "</td>";
													echo "<td>" . $appointmentDetails['status'] . "</td>";
													echo "<td>" . $appointmentDetails['time'] . "</td>";
													echo "</tr>";
												}
											}
										} else {
											echo "<tr><td colspan='5'>No appointments found.</td></tr>";
										}
										?>
									</tbody>
								</table>

							</div>
						</div>
					</div>
				</section>
			</div>

			<?php include "partials/footer.php"; ?>
		</div>
	</div>
	<?php include "partials/scripts.php"; ?>
</body>

</html>