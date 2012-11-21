<?php 
 
class EPANET extends CApplicationComponent
{
	
	const SECTION_MARKER_START = '[';
	const SECTION_MARKER_END = ']';
	const SECTION_REGULAR_EXPRESSION = '/^\[[a-zA-Z0-9]{1,}\]/';
	const COMMENT_MARKER = ';';
	
	const SECTION_JUNCTIONS = 'JUNCTIONS';
	const SECTION_COORDINATES = 'COORDINATES';
	const SECTION_TAGS = 'TAGS';
	const SECTION_EMITTERS = 'EMITTERS';
	const SECTION_QUALITY = 'QUALITY';
	const SECTION_SOURCES = 'SOURCES';
	const TAG_NODE_JUNCTION = 'NODE';
	const SOURCES_TYPE_JUNCTION = 'CONCEN';
	
	private $_tab_char = "\t";
	private $_eol;
	
	private $_in_file;
	private $_out_file;
	private $_file_content;
	
	private $_section_marker_start;
	private $_section_marker_end;
	private $_section_regular_expression;
	private $_comment_marker;
	
	private $_section_junctions;
	private $_section_coordinates;
	private $_section_tags;
	private $_section_emitters;
	private $_section_quality;
	private $_section_sources;
	
	private $_tag_node_junction;
	private $_source_type_junction;
		
	//\t -> metterlo in una costante
	
	
	function __construct($filename) {
			
		$this->_eol = null;
		
		$this->_section_marker_start = isset(Yii::app()->params['EPANET']['section_marker_start'])?Yii::app()->params['EPANET']['section_marker_start']: SECTION_MARKER_START;
		$this->_section_marker_end = isset(Yii::app()->params['EPANET']['section_marker_end'])?Yii::app()->params['EPANET']['section_marker_end']: SECTION_MARKER_END;
		$this->_section_regular_expression = isset(Yii::app()->params['EPANET']['section_regular_expression'])?Yii::app()->params['EPANET']['section_regular_expression']: SECTION_REGULAR_EXPRESSION;
		$this->_comment_marker = isset(Yii::app()->params['EPANET']['comment_marker'])?Yii::app()->params['EPANET']['comment_marker']: COMMENT_MARKER;
		
		$this->_section_junctions = isset(Yii::app()->params['EPANET']['section_junctions'])?Yii::app()->params['EPANET']['section_junctions']: SECTION_JUNCTIONS;
		$this->_section_coordinates = isset(Yii::app()->params['EPANET']['section_coordinates'])?Yii::app()->params['EPANET']['section_coordinates']: SECTION_COORDINATES;
		$this->_section_tags = isset(Yii::app()->params['EPANET']['section_tags'])?Yii::app()->params['EPANET']['section_tags']: SECTION_TAGS;
		$this->_section_emitters = isset(Yii::app()->params['EPANET']['section_emitters'])?Yii::app()->params['EPANET']['section_emitters']: SECTION_EMITTERS;
		$this->_section_quality = isset(Yii::app()->params['EPANET']['section_quality'])?Yii::app()->params['EPANET']['section_quality']: SECTION_QUALITY;
		$this->_section_sources = isset(Yii::app()->params['EPANET']['section_sources'])?Yii::app()->params['EPANET']['section_sources']: SECTION_SOURCES;
		
		$this->_tag_node_junction = isset(Yii::app()->params['EPANET']['tag_node_junction'])?Yii::app()->params['EPANET']['tag_node_junction']: TAG_NODE_JUNCTION;
		$this->_source_type_junction = isset(Yii::app()->params['EPANET']['source_type_junction'])?Yii::app()->params['EPANET']['source_type_junction']: SOURCES_TYPE_JUNCTION;
		
		$this->openEPANETFile($filename);	
	}
		
	private function openEPANETFile($filename)
	{
		$this->_in_file = fopen ($filename, "r");
		$this->readFromFile();
		fclose($this->_in_file);
	}

	public function finalize($filename)
	{
		$this->_out_file = fopen ($filename, "w");
		$this->writeToFile();
		fclose($this->_out_file);
	}
	
	public function addJunction($junction_id, $x_coord, $y_coord, $description, $tag, $elevation, $base_demand, $demand_pattern, $demand_categories, $emitter_coeff, $initial_quality, $source_quality)	
	{
		//JUNCTIONS
		$this->addInSection($this->_section_junctions,array($junction_id, $elevation, $base_demand, $demand_pattern, $this->_comment_marker.$description));
		//COORDINATES
		$this->addInSection($this->_section_coordinates,array($junction_id, $x_coord,$y_coord));
		if ($tag!='') //TAGS
			$this->addInSection($this->_section_tags,array($this->_tag_node_junction,$junction_id,$tag));
		if ($emitter_coeff!='') //EMITTERS
			$this->addInSection($this->_section_emitters,array($junction_id,$emitter_coeff));
		if ($initial_quality!='') //QUALITY
			$this->addInSection($this->_section_quality,array($junction_id,$initial_quality));
		if ($source_quality!='') //SOURCES
			$this->addInSection($this->_section_sources,array($junction_id,$this->_source_type_junction,$source_quality));	
	}
	
	public function readFromFile()
	{
		
		$this->_file_content = array();
		
		$i=-1;
		while ($line=fgets($this->_in_file)) {
			if ($line===FALSE) {
				//ERROR
				echo 'error';
				return;
			}
			$i++;
			if (preg_match($this->_section_regular_expression,$line,$matches)) {
				//new section
				$section = str_replace(array($this->_section_marker_start,$this->_section_marker_end), array('',''),$matches[0]);
				$i=0;
				$this->_file_content[$section]= array();
				$this->_file_content[$section][$i]=$line;
				continue;
			}
			$this->_file_content[$section][$i]=$line;
			
			//EOL
			if (!isset($this->_eol)) {
				if ((strlen($line)-1 >= 0) && ($line[strlen($line)-1] == "\n")) {
					if ((strlen($line)-2 >= 0) && ($line[strlen($line)-2] == "\r"))
						$this->_eol = "\r\n";
					else
						$this->_eol = "\n";
				}
				else if ((strlen($line)-1 >= 0) && ($line[strlen($line)-1] == "\r"))
						$this->_eol = "\r";
				else
					$this->_eol = PHP_EOL;
			}//if
			
		}//while
	}
	
	public function addInSection($section, $values)
	{
		if (isset($this->_file_content[$section])){
			
			//to maintain the same layout, count the spaces 
			//between two values (in the file)
			$len = count($this->_file_content[$section]);
			$k=0;
			while($k < $len) {
				$values_spaces = array();
				$t=0;
				$spaces=0;
				$first_letter=true;
				$line = $this->_file_content[$section][$k];
				for ($i = 0, $j = strlen($this->_file_content[$section][$k]); $i < $j ; $i++) {
					//if (($line[$i]=="\t") || (preg_match('/^[a-zA-Z0-9]/',$line[$i],$m)>0)){
					if (($line[$i]==$this->_tab_char) || ((preg_match('/^[a-zA-Z0-9]/',$line[$i])>0) && ($first_letter))){
						$values_spaces[$t] = $spaces;
						$t++;
						$spaces=0;
						if ($line[$i]!=$this->_tab_char) {
							$first_letter = false;
							$spaces++;
						}
						
					}
					else
						$spaces++;
				}//for
				
				if (count($values_spaces) === count($values))
					break;
				$k++;
				
			}//while

			//extract the last value
			$tmp = array_pop($this->_file_content[$section]);
			$to_write='';
			if (count($values_spaces) === count($values)) {
				//space-padding values
				$to_write = str_pad('',$values_spaces[0]);
				for ($i=0; $i < count($values)-1; $i++ )
					$to_write.=str_pad($values[$i],$values_spaces[$i+1]).$this->_tab_char;
				$to_write.=$values[count($values)-1];
				$to_write.=$this->_eol;
			}
			else {
				for ($i=0; $i < count($values); $i++ )
					$to_write.=' '.$values[$i].$this->_tab_char;
				$to_write.=$this->_eol;
				
			}
			//insert values
			array_push($this->_file_content[$section], $to_write);
			//re-insert the last value
			array_push($this->_file_content[$section], $tmp);
		}
		
	}

	public function writeToFile()
	{
		foreach($this->_file_content as $key=>$value){
			for ($i = 0; $i < count($value); $i++)
				fwrite($this->_out_file, $value[$i]);
		}
	}
}