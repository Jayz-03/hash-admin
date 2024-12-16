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

$active = "feedbacks";
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
							<h3>Feedbacks</h3>
						</div>
						<div class="col-12 col-md-6 order-md-2 order-first">
							<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Components</a></li>
									<li class="breadcrumb-item active" aria-current="page">Feedbacks</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<section class="section">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Feedback Messages List</h4>
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
									$feedbacks = $database->getReference('Feedbacks')->getValue();
								} catch (FirebaseException $e) {
									$errorMessage = "Error fetching data: " . $e->getMessage();
								}

								// Function to convert timestamp to a human-readable format
								function timeAgo($timestamp)
								{
									$time = new DateTime('@' . round($timestamp / 1000)); // Convert milliseconds to seconds
									$now = new DateTime();
									$interval = $now->diff($time);

									if ($interval->y > 0) {
										return $interval->y . " year" . ($interval->y > 1 ? "s" : "") . " ago";
									} elseif ($interval->m > 0) {
										return $interval->m . " month" . ($interval->m > 1 ? "s" : "") . " ago";
									} elseif ($interval->d > 0) {
										return $interval->d . " day" . ($interval->d > 1 ? "s" : "") . " ago";
									} elseif ($interval->h > 0) {
										return $interval->h . " hour" . ($interval->h > 1 ? "s" : "") . " ago";
									} elseif ($interval->i > 0) {
										return $interval->i . " minute" . ($interval->i > 1 ? "s" : "") . " ago";
									} else {
										return "just now";
									}
								}

								?>

								<table class="data-table table stripe hover nowrap">
									<thead>
										<tr>
											<th>Feedback Message</th>
											<th>Service</th>
											<th>Time Submitted</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($feedbacks) {
											foreach ($feedbacks as $userId => $userFeedbacks) {
												foreach ($userFeedbacks as $feedbackId => $feedbackDetails) {
													// Convert the timestamp to a readable format
													$timeAgo = timeAgo($feedbackDetails['timestamp']);

													echo "<tr>";
													echo "<td>" . $feedbackDetails['feedbackMessage'] . "</td>";
													echo "<td>" . $feedbackDetails['service'] . "</td>";
													echo "<td>" . $timeAgo . "</td>";
													echo "</tr>";
												}
											}
										} else {
											echo "<tr><td colspan='3'>No feedbacks found.</td></tr>";
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