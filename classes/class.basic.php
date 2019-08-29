<?php 
//base class for all database reading classes

abstract class cBasic {
	//seems only to work within the class?
	public function __get($name){
		if (method_exists($this, ($method = 'get'.$this->pascalcasify($name)))){
		  	return $this->$method();
		}
		else return;
	}

	public function __set($name, $value){
		
		if (method_exists($this, ($method = 'set'.$this->pascalcasify($name)))){
	  		$this->$method($value);
		}
	}

	public function __isset($name){
		if (method_exists($this, ($method = 'isset'.$this->pascalcasify($name)))){
		  return $this->$method();
		}
		else return;
	}

	public function __unset($name){
		if (method_exists($this, ($method = 'unset'.$this->pascalcasify($name)))){
	  		$this->$method();
		}
	}

  	public function  __construct ($variables=null) {
		//base constructor - build if values exist
		if(!empty($variables)) $this->Build($variables);
	}

	public function LoadAndBuild($query){
		global $cDB, $cErr;
    	$results = $cDB->Query($query);
		while($row = $cDB->FetchArray($results)) {
			$this->Build($row);
			//var_dump($row);
			return true;
		}
		$cErr->Error("There was an error accessing the entity");
		return false;
	}

	public function pascalcasify($snakecase){
		return str_replace('_', '', ucwords($snakecase, '_'));
	}


	public function Build($variables){

		//build from what's been given
		foreach ($variables as $key => $value) {
			//assumes all keys coming in are the same name as the field in the object...
			if(!is_int($key)) {
				$this->__set($key, $value);
				//$this->$key = $value;
				//var_dump("{$key}: {$this->__get($key)}<br />");
			}
		}
	}

	function makeLabelArray($title, $value){
		return array("title" => $title, "value" => $value);
	}
	//help for debugging...
	// make a header line for a table
	function TableHeaderFromArray($variables){
		$output="";
		foreach ($variables as $key => $value) $output .= "<th>{$key}</th>";
		return "<tr>{$output}</tr>";
	}
	function TableRowFromArray($variables, $cssClass="odd"){
		$output="";
		foreach ($variables as $key => $value) $output .= "<td>{$value}</td>";
		return "<tr class=\"$cssClass\">{$output}</tr>";
	}
	//if just a row or multiple rows
    function TableFromArray($array) {
    	if(!empty($array)) return false;
    	$output ="";
    	if(is_array($array[0])){
    		$output .= $this->TableHeaderFromArray($array[0]);

    		$cssClass="odd";
    		foreach ($array as $row) {
    			$output .= $this->TableRowFromArray($row, $cssClass);
    			//alternate rows
    			$cssClass = ($cssClass=="odd") ? "even" : "odd";
    		}
    	}else{
    		$output .= $this->TableHeaderFromArray($array);
    		$output .= $this->TableRowFromArray($array);
    	}
		return "<table class=\"tabulated layout1\">{$output}</table>";
    }

}
?>