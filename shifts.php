<html>
<head><meta charset="UTF-8"></head>
<body>
<style>
	table, th, td {
	    border: 2px solid black;
	    border-collapse: collapse;
	}
	th, td {
	    padding: 6px;
	    text-align: left;
	}
</style>
<?php
	
	require '/src/shiftplanning.php';
	
	$shiftplanning = new shiftplanning(
		array(
			'key' => 'b82d66017d579c1e17b8e80d0b48151c78bb6ed8'
		)
	);
	
		$session = $shiftplanning->getSession();
	if(!$session) {
		$response = $shiftplanning->doLogin(
			array(
				'username' => 'milan.lukic@hotmail.com',
				'password' => 'HumanityTest1985',
			)
		);
	}
	
	$shifts = $shiftplanning->setRequest(
		array(
			'token' => $shiftplanning->getAppToken(),
			'module' => 'schedule.shifts',
			'start_date' => 'today',
			'end_date' => 'today',
			'mode' => 'overview'
		)
	);
	
	$tableData = array();
	foreach($shifts['data'] as $shift) {
		$obj = new stdClass;
		$obj->name = $shift['employees'][0]['name'];
		$obj->time = $shift['start_date']['time'] . '-' .$shift['end_date']['time'];
		
		if ($shift['location']) {
			$obj->location = $shift['location']['name']; //Don't know why it doesn't display location
		} else {
			$obj->location = "no data";
		}
		array_push($tableData, $obj);
	}
	$tg = new MyTable($tableData);
	$tg->getTable();
	
	class MyTable {
	public $data;
	public function __construct($data) {
		$this->data = $data;
	}
	public function getTable() {
		if (sizeof($this->data) == 0) {
		echo "No information for table";
		return;
		}
		$first_row = array_shift($this->data);
		array_unshift($this->data, $first_row);
		$theaders = array();
		foreach($first_row as $primary => $number) {
			array_push($theaders, $primary);
		}
		$result = "<table><tr>";
		for ($i = 0; $i < count($theaders); $i++) {
			$result = $result . "<th>" . $theaders[$i] . "</th>";
		}
		$result = $result . "</tr>";
		foreach($this->data as $row) {
			$result = $result . "<tr>";
			foreach($row as $el) {
				$result = $result . "<td>" . $el . "</td>";
			}
			$result = $result . "</tr>";
		}
		$result = $result . "</table>";
		echo $result;
	}
}	
?>
</body>
</html>
