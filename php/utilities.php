<?php
function db_to_array ($arrayOld)
{
	$arrayNew = array();
	
	for ($count = 0; $row = $arrayOld->fetch_assoc();$count++)
	{
		$arrayNew[$count] = $row;
	}
	return $arrayNew;
}	
	
function check_input($data)
{
	/* array associative array of json */
	if(!is_array($data)) 
	{ 
		if (is_string($data)) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlentities($data, ENT_QUOTES,'UTF-8');
			/*$data = filter_var($data, FILTER_SANITIZE_STRING);*/
		}
		return $data;
	}	
	else
	{
		foreach($data as $key=>$val) {
			$data[$key] = check_input($val);
		}
		return $data;
	}
}

function decodeHtmlEntity ($array) {	
	$length = count($array);		
	for ($i = 0; $i < $length; $i++)
	{
		foreach($array[$i] as $key=>$value) {		
			$array[$i][$key] = html_entity_decode($array[$i][$key],ENT_QUOTES,'UTF-8');
			/*$array[$i][$key] = decode_entities($array[$i][$key]); */
		}			
	}
	return $array;
}

function _decodeAccented($encodedValue, $options = array()) {
    $options += array(
        'quote'     => ENT_NOQUOTES,
        'encoding'  => 'UTF-8',
    );
    return preg_replace_callback(
        '/&\w(acute|uml|tilde);/',
        create_function(
            '$m',
            'return html_entity_decode($m[0], ' . $options['quote'] . ', "' .
            $options['encoding'] . '");'
        ),
        $encodedValue
    );
}

function debugData ($dataToDebug) {
	echo '<pre>';
	print_r($dataToDebug);
	echo '</pre>';
}

?>