<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

$servername = "localhost";
$username = "admin";
$password = "admin";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$no_of_records_per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 10;

$myArray = array();
$offset = ($pageno) * $no_of_records_per_page;

$conn=mysqli_connect($servername, $username, $password,"phprestapi");
// Check connection
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die();
}


$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'id';
$sortDirection = isset($_GET['sortDirection']) ? $_GET['sortDirection'] : 'asc';

$orderBy = ' order by '.$sortBy.' '.$sortDirection;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$condition = '';
if($search != ""){
  $condition = " where firstname like '%".$search."%' or lastname like '%".$search."%' or email like '%".$search."%' or reg_date like '%".$search."%' ";
}

$total_pages_sql = "SELECT COUNT(*) FROM tbl_users_list ".$condition;
$result = mysqli_query($conn,$total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);

$sql = "SELECT * FROM tbl_users_list ".$condition.$orderBy." LIMIT $offset, $no_of_records_per_page";

$sth = mysqli_query($conn, $sql);
$rows = array();

while($r = mysqli_fetch_assoc($sth)) {
  $rows['rows'][] = $r;
}

$rows['count'] = $total_rows;
$rows['total_pages'] = $total_pages;
$rows['pageno'] = $pageno;
$rows['rows_per_page'] = $no_of_records_per_page;
print json_encode($rows);
mysqli_close($conn);

?>
