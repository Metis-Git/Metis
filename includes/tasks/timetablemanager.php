<?php

function secureDate($input) {
	$split = explode("-", $input);
	$week = null;
	$year = null;
	if(empty($split[0])) {
		$week = date("W");
	} else {
		$week = $split[0];
	}

	if(empty($split[1])) {
		$year = date("Y");
	} else {
		$year = $split[1];
	}
	$date = date("d-m-Y", strtotime($year."W".$week."1"));
	if($date == "01-01-1970") {
		$date =  date("d-m-Y", strtotime('last monday', strtotime('tomorrow')));
	}
	return $date;
}
function changeWeek($date, $next) {
	if($next) {
		return date("d-m-Y", strtotime("next week", strtotime($date)));
	} else {
		return date("d-m-Y", strtotime("last week", strtotime($date)));
	}
}
## Returns the Index of the given Date (1-5)
function getDayIndex($date) {
	return date("N", strtotime($date));
}

## Returns all Day-Objects for the given Date
function getDays($date, $conn) {
	$days = array();
	foreach($_SESSION["user"]["classes"] as $class) {
		$dayIndex = getDayIndex($date);
		$stmt = $conn -> prepare(file_get_contents("dayquery.sql"));
		$stmt -> bind_param("iiss", $class, $dayIndex, $date, $date);
		$stmt -> execute();
		$result = $stmt -> get_result();
		if($rows = $result -> fetch_assoc()) {
			$days[$rows["classId"]] = $rows["dayId"];
		}
	}
	return $days;
}
function displayDate($date) {
	return date("D", strtotime($date)) + ", " + date("W", strtotime($date)) + ".KW";
}

## Returns all Courses for the given Date
function getCourses($date, $conn) {
	$courses = array();
	foreach(getDays($date, $conn) as $class => $day) {
		$stmt = $conn -> prepare(file_get_contents("coursequery.sql"));
		$stmt -> bind_param("i", $class);
		$stmt -> execute();
		$result = $stmt -> get_result();
		
		while($rows = $result -> fetch_assoc()) {
			$index = $rows["courseIndex"];
			if(empty($courses[$index])) {
				$courses[$index] = $rows;
			}
		
			echo "<br>";
		}
	}
	return $courses;
}


function getWeek($date, $conn) {
	$week = date("W", strtotime($date));
	$year = date("Y", strtotime($date));

	// get all dates from week
	$dto = new DateTime();
	$dto->setISODate($year, $week);
	$ret['week_start'] = $dto->format('Y-m-d');
	
	$days = array();
	for($i = 0; $i < 5; $i++) {
		$date = $dto -> format("Y-m-d");
		$days[$i] = array("date" => $date, "courses" => getCourses($date, $conn));
		$dto -> modify("+1 day");
	}
	return $days;
}
?>