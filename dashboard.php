<?php

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;

$factory = (new Factory)
	->withServiceAccount(__DIR__ . '/secret/firebase_credentials.json')
	->withDatabaseUri('https://hash-ec65c-default-rtdb.firebaseio.com/');

$database = $factory->createDatabase();

$userCount = 0;
$appointmentCount = 0;
$feedbackCount = 0;

try {
	$users = $database->getReference('users')->getValue();
	if ($users) {
		foreach ($users as $userId => $userDetails) {
			$userCount++;
		}
	}

	$appointments = $database->getReference('Appointment')->getValue();
	if ($appointments) {
		foreach ($appointments as $userId => $userAppointments) {
			foreach ($userAppointments as $appointmentId => $appointmentDetails) {
				$appointmentCount++;
			}
		}
	}

	$feedbacks = $database->getReference('Feedbacks')->getValue();
	if ($feedbacks) {
		foreach ($feedbacks as $userId => $userFeedbacks) {
			foreach ($userFeedbacks as $feedbackId => $feedbackDetails) {
				$feedbackCount++;
			}
		}
	}

} catch (FirebaseException $e) {
	$errorMessage = "Error fetching data: " . $e->getMessage();
}

?>


<!DOCTYPE html>
<html>

<head>
	<?php include "partials/head.php"; ?>
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
								<h3>Dashboard</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
					<div class="row">
						<div class="col-xl-4 mb-30">
							<div class="card-box height-100-p widget-style1">
								<div class="d-flex flex-wrap align-items-center">
									<div class="widget-data">
										<div class="h4 mb-0">Users</div>
										<div class="weight-600 font-14"><?php echo $userCount; ?></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4 mb-30">
							<div class="card-box height-100-p widget-style1">
								<div class="d-flex flex-wrap align-items-center">
									<div class="widget-data">
										<div class="h4 mb-0">Appointments</div>
										<div class="weight-600 font-14"><?php echo $appointmentCount; ?></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4 mb-30">
							<div class="card-box height-100-p widget-style1">
								<div class="d-flex flex-wrap align-items-center">
									<div class="widget-data">
										<div class="h4 mb-0">Feedbacks</div>
										<div class="weight-600 font-14"><?php echo $feedbackCount; ?></div>
									</div>
								</div>
							</div>
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
</body>

</html>