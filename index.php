<?php

//////// SETUP ////////

// YOUR .json TRELLO BOARD FILE //
$filename = "board.json";

// THE DIRECTORY WHERE YOU WANT TO SAVE ALL THE FILES //
$output_dir = getcwd() . "/download/";

///////////////////////





// NO CHANGES SHALL BE MADE BEYOND THIS POINT //
function availObject($var) {

	global $output_dir;

	if (is_array($var)) {
		$keys = array_keys($var);
		for ($i = 0; $i < count($var); $i++) {
			availObject($var[$keys[$i]]);
		}
	} elseif (is_string($var)) {
		if (strstr($var, "attachments.s3.amazonaws")) {
			try {
				// Curl settings
				$ci = curl_init();
				curl_setopt($ci, CURLOPT_URL, $var);
				curl_setopt($ci, CURLOPT_FILE, fopen($output_dir . str_replace(array("/", ":"), "", $var), "wb")); 
				
				// Display its output
		        $response = curl_exec($ci);

				// Capture any errors
			    if ($response === FALSE) {
			    	throw new Exception(curl_error($ci), curl_errno($ci));
			    }
			    
				curl_close($ci);
				
		    } catch(Exception $e) {
    	        trigger_error(sprintf('Curl failed #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
    	    }
		}
	}
}

$json = json_decode(file_get_contents($filename), true);

if (!file_exists($output_dir)) mkdir($output_dir);

$success = availObject($json);

// END // 

?>