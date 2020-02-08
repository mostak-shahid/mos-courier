<?php
$host = "localhost"; /* Host name */
$user = "root"; /* User */
$password = ""; /* Password */
$dbname = "tcourier"; /* Database name */

$con = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
    $searchQuery = " and (cn like '%".$searchValue."%' or 
        booking like '%".$searchValue."%' or 
        delivery_status like '%".$searchValue."%' or 
        brand like'%".$searchValue."%' ) ";
}

## Total number of records without filtering
$sel = mysqli_query($con,"select count(*) as allcount from wp_orders");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($con,"select count(*) as allcount from wp_orders WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
// $empQuery = "select * from wp_orders WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empQuery = "select * from wp_orders WHERE 1";
if (@$searchQuery) $empQuery .= " ".$searchQuery;
if (@$columnName AND @$columnSortOrder) $empQuery .= " ORDER BY ".$columnName." ".$columnSortOrder;
$empQuery .= " limit ".$row.",".$rowperpage;

$empRecords = mysqli_query($con, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
            "post_id"=>'<input type="checkbox" name="orders[]" id="order_'.$row['post_id'].'" class="administrator" value="'.$row['post_id'].'"> ',
            "ID"=>$row['ID'],
            "cn"=>$row['cn'],
            "booking"=>$row['booking'],
            "delivery_status"=>$row['delivery_status'],
            "brand"=>$row['brand'],
            "action"=>'<button type="button" class="btn btn-info btn-xs view-order-desc" data-id="'.$row['post_id'].'">View</button>',
        );
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