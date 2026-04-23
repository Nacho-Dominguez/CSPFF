<?php
function makeArray( $link, $table, $column, $where = null ) {
  $result = $link->query("SELECT `$column` FROM `$table` $where ORDER BY `$column`");
  for($i=0; $i<$result->num_rows; $i+=1) {
    $rows = $result->fetch_array(MYSQLI_NUM);
    $newArray[$i] = $rows[0];
  }
  $result->close();
  return $newArray;
}

function reorder($link, $table, $idArray, $columnArray) {
	// Scramble Data column by column
	for($i=1; $i<count($columnArray); $i+=1 ) {
		printf("Updating column %s...",$columnArray[$i]);
		$columnData = makeArray($link,$table,$columnArray[$i]);
		for($j=0; $j<count($idArray); $j+=1) {
			$link->query("UPDATE `$table` SET `$columnArray[$i]`='$columnData[$j]' WHERE `$columnArray[0]`=$idArray[$j]");
		}
		printf("done\n");
	}
}

$server="localhost";
$username="root";
$password="Broncos7";
$db="aliveat25_joomla";

// Open mysql connction
$link = new mysqli($server,$username,$password,$db);
if(mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}

// Student:
$table="jos_student";
$studentColumnArray=array( "student_id",
                    "first_name",
                    "last_name",
                    "email",
                    "date_of_birth",
                    "address_1",
                    "county",
                    "city",
                    "zip",
                    "home_phone",
                    "userid",);
// Make Array of Student ids
$idArray = makeArray($link,$table,$studentColumnArray[0]);
reorder($link,$table,$idArray,$studentColumnArray);

// Instructors:
$table="jos_users";
$instructorColumnArray=array(
					0=>"id",
                    1=>"name",
                    2=>"username",
                    3=>"email",
                    4=>"address_1",
                    5=>"address_2",
                    6=>"city",
                    7=>"state",
                    8=>"zip",
                    9=>"home_phone",
                    10=>"work_phone",
                    11=>"work_ext",
                    12=>"nsc",
                    13=>"control",
					14=>"single_fee",
                    15=>"multiple_fee",
                    16=>"permissions",
                    17=>"password",
                    18=>"usertype",
                    19=>"block",
                    20=>"sendEmail",
                    21=>"gid",
                    22=>"registerDate",
                    23=>"lastvisitDate",
                    24=>"activation",
                    25=>"params");
$idArray = makeArray($link,$table,$instructorColumnArray[0],"WHERE usertype='instructor'");
reorder($link,$table,$idArray,$instructorColumnArray);

// Close mysql connection
$link->close();
?>
