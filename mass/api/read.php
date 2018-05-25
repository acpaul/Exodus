<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/churchnames.php';
 
// instantiate database and church name object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$church = new ChurchNames($db);

// set lat and long property of church to be edited
$church->latt = isset($_GET['lat']) ? $_GET['lat'] : die();
$church->longt = isset($_GET['log']) ? $_GET['log'] : die();
//76.873909 log & 8.5607423 lat
 
// query products
$stmt = $church->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $church_arr=array();
    $church_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $product_item=array(
            "id" => $churchId,
            "name" => $churchName,
            "image" => $churchImageName,
            "distance" => $distance_in_km,
            "time" => $churchTime,
			"address"=>$churchAddress
        );
 
        array_push($church_arr["records"], $product_item);
    }
 
    echo json_encode($church_arr);
}
 
else{
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>