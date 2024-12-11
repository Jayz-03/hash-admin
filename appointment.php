<!DOCTYPE html>
<html>

<head>
	<?php include "partials/head.php"; ?>
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
</head>

<body>

	<?php include "partials/navigation.php"; ?>

	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h3>Appointments</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<div class="card-box mb-30">
						<div class="pd-20">
							<p class="mb-0">Appointment Information List</p>
						</div>
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
			</div>
			<div class="footer-wrap pd-20 mb-20 card-box">
				© HASH - HIV & Aids Support House 2024
			</div>
		</div>
	</div>
	<?php include "partials/scripts.php"; ?>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<!-- buttons for Export datatable -->
	<script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
	<script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
	<script src="src/plugins/datatables/js/buttons.print.min.js"></script>
	<script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
	<script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
	<script src="src/plugins/datatables/js/pdfmake.min.js"></script>
	<script src="src/plugins/datatables/js/vfs_fonts.js"></script>
	<!-- Datatable Setting js -->
	<script src="vendors/scripts/datatable-setting.js"></script>
</body>

</html>