<?php


$n=10; 
function getLetter() { 
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $index = rand(0, strlen($characters) - 1); 
    return $characters[$index]; 
} 
  
function getSerial($pattern) { 
	// A= letter 1= number
	$array = str_split($pattern,1);
	$string ="";
	foreach($array as $char){
		switch($char){
			case "A":
				$string .= getLetter();
			break;
			case "1":
				$string .= rand (1, 9);
			break;
			default:
				$string .= $char;
		}
	}
    return $string; 
} 


function getModel() { 

	$products = array("IQOS 2.4 PLUS", "IQOS 3", "IQOS 3 MULTI");
	// A= letter 1= number
	$index = rand(0, sizeof($products) - 1);
    return $products[$index]; 
} 

//TUZB JT4 L5N 58Q4


for($i=0; $i <10; $i++){
	echo getSerial("AAAA AA1 A1A 11A1") . "<br />"; 
}

for($i=0; $i <10; $i++){
	echo getModel() . "<br />"; 
}

for($i=0; $i <10; $i++){
	echo "Registered" . "<br />"; 
}


?>

