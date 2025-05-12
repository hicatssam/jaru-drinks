<?php

// بيانات الطلب المُرسلة من JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['customerName']) || !isset($data['drinksOrder'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

// بيانات JSONBin
$binId = "6821c5448960c979a597d7f0"; // مثل: 6640ee0d9d31234a1bcdef56
$apiKey = "$2a$10$FWR/czUfu8ZF.apRdDu.gep2wfsIkscHiQSNPex7bSIQygZdh2FdW"; // مفتاح JSONBin

// 1. سحب الطلبات القديمة
$ch = curl_init("https://api.jsonbin.io/v3/b/$binId/latest");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-Master-Key: $apiKey"
]);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true);
$currentData = $responseData['record'] ?? [];


// 2. إنشاء الطلب الجديد
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

// 3. إضافة الطلب إلى القائمة
$currentData[] = $order;

// 4. إرسال البيانات الجديدة إلى JSONBin
$ch = curl_init("https://api.jsonbin.io/v3/b/$binId");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "X-Master-Key: $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($currentData));

$updateResponse = curl_exec($ch);
curl_close($ch);

// 5. الرد للواجهة
echo json_encode([
    'status' => 'success',
    'message' => 'Order saved to JSONBin successfully',
    'orderNumber' => $orderNumber
]);
?>
