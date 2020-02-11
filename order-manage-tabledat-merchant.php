<?php
require_once('../../../wp-config.php');
$user = @$_GET['user'];
var_dump($user);
// $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

$conn = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

// Check connection
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$base_sql = "select m.post_id, p.post_title, max(case when m.meta_key = '_mos_courier_booking_date' then m.meta_value end) as booking, max(case when m.meta_key = '_mos_courier_delivery_status' then m.meta_value end) as status, max(case when m.meta_key = '_mos_courier_brand_name' then m.meta_value end) as brand, max(case when m.meta_key = '_mos_courier_receiver_name' then m.meta_value end) as receiver, max(case when m.meta_key = '_mos_courier_merchant_name' then m.meta_value end) as merchant_name, p.post_type from {$table_prefix}postmeta m join {$table_prefix}posts p on m.post_id = p.ID where p.post_type='courierorder' AND m.meta_value='{$user}'"; // group by m.post_id, p.post_type"; // ORDER BY `receiver` ASC limit 0,10
$groupBy = " group by m.post_id, p.post_type";
## Search 
$searchQuery = " ";
if($searchValue != ''){
    $searchQuery = " and (
        p.post_title like '%".$searchValue."%' or 
        m.meta_value like '%".$searchValue."%'
        ) ";
}

## Total number of records without filtering
$sql = $base_sql.$groupBy;
$result = $conn->query($sql);
$totalRecords = $result->num_rows;


## Total number of records with filtering
$sql = $base_sql . $searchQuery . $groupBy;
// var_dump($sql);
$result = $conn->query($sql);
$totalRecordwithFilter = $result->num_rows;

## Fetch records
// $empQuery = "select * from {$table_prefix}orders WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empQuery = $base_sql;
if (@$searchQuery) $empQuery .= $searchQuery;
$empQuery .= $groupBy;
if (@$columnName AND @$columnSortOrder) $empQuery .= " ORDER BY ".$columnName." ".$columnSortOrder;
$empQuery .= " limit ".$row.",".$rowperpage;

$result = $conn->query($empQuery);
$data = array();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        //$row["id"]
        $data[] = array(
                "post_id"=>$row['post_id'],
                "post_title"=>$row['post_title'],
                "booking"=>$row['booking'],
                "status"=>$row['status'],
                "brand"=>$row['brand'],
                "receiver"=>$row['receiver'],
                "action"=>'<a class="btn btn-info btn-xs" href="'.home_url().'/admin/?page=order-manage&order-id='.$row['post_id'].'">View</a>',
        );
    }
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
exit();