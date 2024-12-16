<?php

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['item']) && isset($data['stock'])) {
    $item = $data['item'];
    $stock = $data['stock'];

    try {
        // Firebase initialization
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/secret/firebase_credentials.json')
            ->withDatabaseUri('https://hash-ec65c-default-rtdb.firebaseio.com/');

        $database = $factory->createDatabase();

        // Update the stock in Firebase
        $updateData = [
            'stocks' => $stock
        ];

        // Update the specific item in the Firebase database
        $database->getReference('Inventory/' . $item)->update($updateData);

        // Respond with a success message
        echo json_encode(['success' => true]);
    } catch (FirebaseException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
}
?>
