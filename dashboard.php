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

$active = "dashboard";

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
							<h3>Dashboard</h3>
						</div>
						<div class="col-12 col-md-6 order-md-2 order-first">
							<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<section class="section">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Overview</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-4 mb-30">
									<div class="card-box height-100-p widget-style1">
										<div class="d-flex flex-wrap align-items-center">
											<div class="widget-data">
												<div class="mb-0">Users</div>
												<h1 class="weight-600 font-14"><?php echo $userCount; ?></h1>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-4 mb-30">
									<div class="card-box height-100-p widget-style1">
										<div class="d-flex flex-wrap align-items-center">
											<div class="widget-data">
												<div class="mb-0">Appointments</div>
												<h1 class="weight-600 font-14"><?php echo $appointmentCount; ?></h1>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-4 mb-30">
									<div class="card-box height-100-p widget-style1">
										<div class="d-flex flex-wrap align-items-center">
											<div class="widget-data">
												<div class="mb-0">Feedbacks</div>
												<h1 class="weight-600 font-14"><?php echo $feedbackCount; ?></h1>
											</div>
										</div>
									</div>
								</div>

							</div>

							<?php

							$factory = (new Factory)
								->withServiceAccount(__DIR__ . '/secret/firebase_credentials.json')
								->withDatabaseUri('https://hash-ec65c-default-rtdb.firebaseio.com/');

							$database = $factory->createDatabase();

							// Retrieve inventory data from Firebase
							$inventoryRef = $database->getReference('Inventory');
							$inventory = $inventoryRef->getValue();
							?>

							<div class="row mt-4">
								<div class="col-md-12">
									<div class="title">
										<h3>Manage Inventory</h3>
									</div>
								</div>
								<div class="col-md-12">
									<!-- Inventory Table -->
									<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Item Name</th>
													<th>Stock</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Prep Medicine</td>
													<td id="prep_medicine_stock">
														<?= $inventory['prep_medicine']['stocks'] ?></td>
													<td>
														<button class="btn btn-warning"
															onclick="updateStock('prep_medicine')">Update</button>
													</td>
												</tr>
												<tr>
													<td>Test Kits</td>
													<td id="test_kits_stock"><?= $inventory['test_kits']['stocks'] ?>
													</td>
													<td>
														<button class="btn btn-warning"
															onclick="updateStock('test_kits')">Update</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<!-- SweetAlert2 script -->
							<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

							<script>
								// Set currentItem globally to store which item is being updated
								let currentItem = '';

								// Show SweetAlert input and set the current stock value
								function updateStock(item) {
									currentItem = item;
									let currentStock = document.getElementById(item + '_stock').innerText;

									// Display SweetAlert input dialog
									Swal.fire({
										title: 'Update Stock for ' + item,
										input: 'number',
										inputValue: currentStock,
										inputPlaceholder: 'Enter new stock quantity',
										showCancelButton: true,
										confirmButtonText: 'Update',
										cancelButtonText: 'Cancel',
										preConfirm: (newStock) => {
											// If a new stock value is provided, proceed to update the Firebase via PHP
											if (newStock) {
												updateFirebaseStock(item, newStock);
											}
										}
									});
								}

								// Function to update Firebase database with new stock value
								function updateFirebaseStock(item, stock) {
									// Perform AJAX request to update the stock in Firebase
									fetch('update_stock.php', {
										method: 'POST',
										headers: {
											'Content-Type': 'application/json',
										},
										body: JSON.stringify({
											item: item,
											stock: stock
										})
									})
										.then(response => response.json())
										.then(data => {
											if (data.success) {
												// Update the stock in the HTML table
												document.getElementById(item + '_stock').innerText = stock;
												Swal.fire('Updated!', 'The stock for ' + item + ' has been updated.', 'success');
											} else {
												Swal.fire('Error', 'There was an error updating the stock.', 'error');
											}
										})
										.catch(error => {
											Swal.fire('Error', 'Failed to update stock: ' + error.message, 'error');
										});
								}
							</script>

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