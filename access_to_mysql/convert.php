<?php
$READY = 0;
$TABLE = 1;

$state = $READY;
$table = "";


$tables = array();
$fields = array();

$handle = @fopen("./access-table-dump.txt", "r");
if ($handle) {
	$state = 0;//Start
	$lines_to_skip = 0;
	$empty_lines = 0;
	while (($buffer = fgets($handle, 4096)) !== false) {
		switch ($state) {
			case $READY:
				if(strpos($buffer, "Table: ") === 0) {
					$page_pos = strpos($buffer, "Page: ");
					$table = trim(substr($buffer, 7, $page_pos-7));
					$state = $TABLE;
					$lines_to_skip = 6;
					//print $table . "\n";
					$fields = array();
				}
				break;
			case $TABLE:
				if($lines_to_skip > 0) {
					$lines_to_skip--;
				} else {
					//Expect fields
					if(trim($buffer) == "") {

						$empty_lines++;
						if($empty_lines > 3) {
							$state = $READY;
							//End of previous table
							$tables[$table] = $fields;
							$fields = array();
						}
					} else {
						$empty_lines = 0;
						//print $buffer;
						$fields[] = process_field($buffer);	

					}
				}
				break;
			default:
				break;
		}
		
	}
	if (!feof($handle)) {
		echo "Error: unexpected fgets() fail\n";
	}
	fclose($handle);

	
	print_sql($tables);


}


function process_field($line) {
	$pieces = explode("             ", $line);
	$tmp = array();
	foreach ($pieces as $piece) {
		$val = trim($piece);
		if($val != "") {
			$tmp[] = $val;
		}
	}

	$type = $tmp[1];
	$size = $tmp[2];

	$ret_type = "";
	//Ruels for types
	if($type == "Short Text") {
		$ret_type = "VARCHAR(" . $size . ")";
	} else if ($type == "Long Text") {
		$ret_type = "TEXT";
	} else if ($type == "Attachment Data") {
		$ret_type = "TEXT";
	} else if($type == "Long Integer") {
		if($size == 4) {
			$ret_type = "INT";	
		} else if($size == 8) {
			$ret_type = "BIGINT";
		} else {
			print "!!!!!!HANDLE : "  . $type . " : " . $size . "\n";
		}
	} else if ($type == "Date With Time") {
		$ret_type = "DATETIME";
	} else if ($type == "Anchor") {
		//http://stackoverflow.com/questions/219569/best-database-field-type-for-a-url
		$ret_type = "VARCHAR(2083)"; 
	} else {
		print "HANDLE >>> " . $type . "\n";
	}


	$ret  = array();
	$ret[0] = str_replace(" ", "_", $tmp[0]);//Fix field names
	$ret[1] = $ret_type;

	return $ret;
}

function print_sql($tables) {
	foreach ($tables as $table_name => $flds) {
		print "CREATE TABLE " . $table_name . " (\n";
		$field_count = count($flds);
		for($i = 0; $i < $field_count; $i++) {
			print "\t" . $flds[$i][0] . " " . $flds[$i][1];
			if($i < $field_count-1) {
				print ",";
			}
			print "\n";
		}
		print ");\n";
	}
}

?>
