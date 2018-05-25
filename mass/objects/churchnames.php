<?php
class ChurchNames{
 
    // database connection and table name
    private $conn;
    private $table_name = "churchnames";
 
    // object properties
    public $churchId;
    public $churchName;
    public $rite;
    public $diocese;
    public $churchImageName;
    public $churchAddress;
	public $churchCountry;
	public $churchState;
	public $churchDistrict;
	public $churchCity;
	public $churchGoogleLocation;
	public $churchLatitude;
	public $churchLongitude;
	public $churchContactNumber;
	public $churchWebsite;
	public $distance_in_km;
    public $createdDate;
	public $churchTime;
	
	public $latt;
	public $longt;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	// read products
function read(){    
 
    // prepare query statement
$sql = " SELECT a.churchId, a.churchName, a.churchImageName, b.times as churchTime,a.churchAddress, 111.045 * DEGREES(ACOS(COS(RADIANS(" . $this->latt . ")) \n"
    . " * COS(RADIANS(churchLatitude)) \n"
    . " * COS(RADIANS(ChurchLongitude) - RADIANS(" . $this->longt . ")) \n"
    . " + SIN(RADIANS(" . $this->latt . ")) \n"
    . " * SIN(RADIANS(churchLatitude)))) \n"
    . " AS distance_in_km \n"
    . " FROM ChurchNames as a, HolyMassTimes as b where a.churchId = b.churchId and b.days = DAYNAME(CURDATE()) \n"
    . " ORDER BY distance_in_km ASC \n"
    . " LIMIT 0,15";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(1, $this->id);
    // execute query
    $stmt->execute();
 
    return $stmt;
}
}