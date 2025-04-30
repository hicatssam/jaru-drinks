<?php

$data = json_decode(file_get_contents('php://input'), true);


if (!$data || !isset($data['customerName']) || !isset($data['drinksOrder'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

$file = 'orders.json';


if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}


$currentData = json_decode(file_get_contents($file), true);


$orderNumber = count($currentData) + 1;

$totalPrice = 0;
foreach ($data['drinksOrder'] as $drink) {
    $totalPrice += $drink['price'] * $drink['quantity'];
}


$order = [
    'orderNumber' => $orderNumber,
    'id' => uniqid(),
    'customerName' => $data['customerName'],
    'drinksOrder' => $data['drinksOrder'],
    'totalPrice' => $totalPrice,
    'date' => date('Y-m-d') . ' (' . date('l') . ')',
    'time' => date('h:i A')
];


$currentData[] = $order;
file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT));


echo json_encode(['status' => 'success', 'message' => 'Order saved successfully', 'orderNumber' => $orderNumber]);
?>
