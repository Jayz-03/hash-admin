<?php

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;

$factory = (new Factory)
    ->withServiceAccount(__DIR__ . '/secret/firebase_credentials.json')
    ->withDatabaseUri('https://hash-ec65c-default-rtdb.firebaseio.com/');

$database = $factory->createDatabase();

// Retrieve inventory data from Firebase
$inventoryRef = $database->getReference('Inventory');
$inventory = $inventoryRef->getValue();

?>

<!DOCTYPE html>
<html>

<head>
    <?php include "partials/head.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert@1.1.3/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
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
                                <h3>Manage Inventory</h3>
                            </div>
                        </div>
                    </div>
                </div>

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
                                <td id="prep_medicine_stock"><?= $inventory['prep_medicine']['stocks'] ?></td>
                                <td>
                                    <button class="btn btn-warning"
                                        onclick="updateStock('prep_medicine')">Update</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Test Kits</td>
                                <td id="test_kits_stock"><?= $inventory['test_kits']['stocks'] ?></td>
                                <td>
                                    <button class="btn btn-warning" onclick="updateStock('test_kits')">Update</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="footer-wrap pd-20 mb-20 card-box">
                © HASH - HIV & Aids Support House 2024
            </div>
        </div>
    </div>

    <!-- Modal for Stock Update -->
    <div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-labelledby="stockUpdateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockUpdateModalLabel">Update Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateStockForm">
                        <div class="mb-3">
                            <label for="newStock" class="form-label">Enter new stock quantity</label>
                            <input type="number" class="form-control" id="newStock" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveStockBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentItem = '';

        // Show modal and set current item
        function updateStock(item) {
            currentItem = item;
            let currentStock = document.getElementById(item + '_stock').innerText;

            // Set current stock in the input field
            document.getElementById('newStock').value = currentStock;

            // Show the modal
            let myModal = new bootstrap.Modal(document.getElementById('stockUpdateModal'));
            myModal.show();
        }

        // Handle the form submission to update stock
        document.getElementById('saveStockBtn').addEventListener('click', function () {
            let newStock = document.getElementById('newStock').value;

            if (newStock !== '') {
                // Confirm the action using SweetAlert1
                swal({
                    title: "Are you sure?",
                    text: "You are about to update the stock for " + currentItem + " to " + newStock,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, update it!",
                    cancelButtonText: "No, cancel",
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        // Perform the stock update in Firebase
                        updateFirebaseStock(currentItem, newStock);
                    }
                });

                // Close the modal after action
                let myModal = bootstrap.Modal.getInstance(document.getElementById('stockUpdateModal'));
                myModal.hide();
            } else {
                swal("Error", "Stock quantity cannot be empty.", "error");
            }
        });

        function updateFirebaseStock(item, stock) {
            // Create the update payload
            let updates = {
                "Inventory": {
                    [item]: {
                        "stocks": stock
                    }
                }
            };

            // Log the updates for debugging
            console.log("Updating Firebase with data: ", updates);

            // Send the updated stock value to Firebase via the backend PHP script
            fetch('update_stock.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(updates)
            })
                .then(response => response.json())
                .then(data => {
                    // Log the response for debugging
                    console.log("Response from PHP:", data);

                    if (data.success) {
                        // Update the display if successful
                        document.getElementById(item + '_stock').innerText = stock;
                        swal("Updated!", "The stock for " + item + " has been updated.", "success");
                    } else {
                        swal("Error", "There was an error updating the stock: " + data.error, "error");
                    }
                })
                .catch(error => {
                    console.log("Error in fetch request: ", error);
                    swal("Error", "Failed to update stock: " + error.message, "error");
                });
        }

    </script>

    <?php include "partials/scripts.php"; ?>
</body>

</html>