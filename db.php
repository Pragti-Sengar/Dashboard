

<?php
$host = 'lp-staging-db-cluster.cluster-cghlikcoeupd.ap-south-1.rds.amazonaws.com'; // or your resolved one
$username = 'root';
$password = 'ISJ6wvfZ5OeyV7k9Gdti';
$database = 'coinlend_prod_lendingplate';
$port = 3306;

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>