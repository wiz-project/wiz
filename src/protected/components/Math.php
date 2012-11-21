<?php 
 
class Math extends CApplicationComponent
{

	public static function safe_eval($code) { //status 0=failed,1=all clear 
    	if ($code) {
    		try	{
		    	//Signs 
		        //Can't assign stuff 
		    	$bl_signs = array("="); 
		
				//Language constructs 
				$bl_constructs = array("print","echo","require","include","if","else","while","for","switch","exit","break");
		
				//Functions 
				$funcs = get_defined_functions(); 
				$funcs = array_merge($funcs['internal'],$funcs['user']); 
				
				//Functions allowed        
		        //Math cant be evil, can it? 
		    	$whitelist = array("pow","exp","abs","sin","cos","tan"); 
		
				//Remove whitelist elements 
				foreach($whitelist as $f) { 
					unset($funcs[array_search($f,$funcs)]);    
				} 
				//Append '(' to prevent confusion (e.g. array() and array_fill()) 
				foreach($funcs as $key => $val) { 
					$funcs[$key] = $val."("; 
				} 
				$blacklist = array_merge($bl_signs,$bl_constructs,$funcs); 
			
				//Check 
				$status=1; 
				foreach($blacklist as $nono) { 
					if(strpos($code,$nono) !== false) { 
						$status = 0; 
						return 0; 
					} 
				}
				
				$mathString = trim($code);     // trim white spaces
		    	//$mathString = ereg_replace ('[^0-9\+-\*\/\(\) ]', '', $mathString);    // remove any non-numbers chars; exception for math operators
		 
		    	$compute = create_function("", "return (" . $mathString . ");" );
		    	
		    	return 0 + $compute();
	    	}
			catch (Exceptio $e) {
				//LOG Error
				return 0;
			} 
    	}
		return 0;
		
		/*
		Yii::log('CODE: '.$code , CLogger::LEVEL_INFO, 'Math');  // DEBUG
		//Eval
		$e_code = escapeshellarg($code);
		return eval($e_code);
		*/
	}
	
	//TODO: da rimuovere!!!
	public static function round($value){
		return round($value, Yii::app()->params['decimals']);
	}
	
	public static function pe_round($value){
		return round($value, Yii::app()->params['pe_precision']);
	}

	public static function wd_round($value){
		return round($value, Yii::app()->params['wd_precision']);
	}
	
	public static function wd_percentage_round($value){
		return round($value, Yii::app()->params['wd_percentage_precision']);
	}
	
	public static function margin_round($value){
		return round($value, Yii::app()->params['margin_precision']);
	}
	

} 