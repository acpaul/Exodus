<?php
$conn = mysqli_connect("", "HolyMassCatholic","JYTPmedia@18","HolyMassCatholic");
if (!$conn) {
    die("Connection failed: " .mysqli_connect_error());
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
$data = json_decode(file_get_contents("php://input"));
$limit= $data->limit;
$offset = $data->offset;
$searchWord= $data->searchWord;


// Get data from database
       $sql_Church = "SELECT * FROM ChurchNames WHERE churchName LIKE '%$searchWord%' OR churchAddress LIKE '%$searchWord%' LIMIT ". $limit . " OFFSET ". $offset;
       $full_Data_Church = mysqli_query($conn, $sql_Church);
       $result = array();
       
	  if($full_Data_Church->num_rows > 0){

            while($row = $full_Data_Church->fetch_assoc()) {
                  $churchId=$row["churchId"];
                  $resultTimes;

// Sunday Data
    $resultTimes["Sunday"] = getDaysData($conn,$churchId,Sunday);

// Monday Data
    $resultTimes["Monday"] = getDaysData($conn,$churchId,Monday);

// Tuesday Data
    $resultTimes["Tuesday"] = getDaysData($conn,$churchId,Tuesday);

// Wednesday Data
    $resultTimes["Wednesday"] = getDaysData($conn,$churchId,Wednesday);

// Thursday Data
    $resultTimes["Thursday"] = getDaysData($conn,$churchId,Thursday);

// Friday Data
    $resultTimes["Friday"] = getDaysData($conn,$churchId,Friday);

// Saturday Data
    $resultTimes["Saturday"] = getDaysData($conn,$churchId,Saturday);

    $row["holymass_Times"]=$resultTimes;
    array_push($result,$row); 

      }
        $json = array("status" => 1, "msg" => "Success", "data" => $result);
   }else
        $json = array("status" => 0, "msg" => "Church list not available","data" => $result);
  }else
	$json = array("status" => 0, "msg" => "Request method not accepted");

function getDaysData($con,$churchId,$day)
{    
     $sql_day_Time = "SELECT * FROM HolyMassTimes WHERE churchId=$churchId AND days ='$day'"; 
     $day_Times = mysqli_query($con, $sql_day_Time);
     $result_day_Times = array();

     while($row_day_Times = $day_Times ->fetch_assoc()) {
      array_push($result_day_Times,$row_day_Times);
     }

   return $result_day_Times;
}

  @mysqli_close($conn);
/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
  
?>