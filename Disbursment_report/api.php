<?php
header("Content-Type: application/json");
include 'db1.php';

$from = isset($_GET['from']) ? $_GET['from'] : null;
$to = isset($_GET['to']) ? $_GET['to'] : null;

if ($from && $to) {
    $stmt = $conn->prepare("CALL disbursed_report_Summary(?, ?)");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
   // Define date variables
$today = date('Y-m-d');
$currentMonthStart = date('Y-m-01');
$prevMonthStart = date('Y-m-01', strtotime('first day of last month'));
$prevMonthEnd = date('Y-m-t', strtotime('last day of last month'));
$prevMonthToToday = date('Y-m-d', strtotime("-1 month", strtotime($today)));

$sql = "
    SELECT 'GRAND TOTAL' AS label, SUM(IFNULL(Total_Sum_Amount, 0)) AS amount
    FROM loan_disbursed_report_summary
    WHERE DATE(dt) BETWEEN '$currentMonthStart' AND '$today'
    UNION
    SELECT 'Till Date' AS label, SUM(IFNULL(Total_Sum_Amount, 0)) AS amount
    FROM loan_disbursed_report_summary
    WHERE DATE(dt) BETWEEN '$prevMonthStart' AND '$prevMonthToToday'
    UNION
    SELECT 'Till End' AS label, SUM(IFNULL(Total_Sum_Amount, 0)) AS amount
    FROM loan_disbursed_report_summary
    WHERE MONTH(dt) = MONTH('$prevMonthStart') AND YEAR(dt) = YEAR('$prevMonthStart')
    UNION

     
    SELECT 'Today' AS label, SUM(IFNULL(Total_Sum_Amount, 0)) AS amount
    FROM loan_disbursed_report_summary
    WHERE DATE(dt) = '$today';
";



    $result = $conn->query($sql);
}

if (!$result) {
    echo json_encode(['error' => $conn->error]);
    $conn->close();
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
