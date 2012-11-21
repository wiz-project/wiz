<?php 
 
class Geometry extends CApplicationComponent
{
	
	//TODO: togliere sistema di riferimento
	
	/**
	 * Wrapper for ST_GeomFromText(wkt, srid). Default SRID = 900913
	 * @param string $geometry
	 * @param integer $srid
	 */
	public static function ST_GeomFromText($coordinates, $srid=900913)
	{
		return "ST_GeomFromText('".$coordinates."',".$srid.")";
	}
	
	/** 
	 * Per usi futuri, quando (e se) faremo query dirette per caricare i modelli da editare
	 * @param unknown_type $geometry
	 */
	public static function ST_AsEWKT($geometry)
	{
		return "ST_AsEWKT('".$geometry."')";
	}
	/**
	 * Wrapper for Tranform(geometry, srid). Default SRID = 4326
	 * @param string $geometry
	 * @param integer $srid
	 */
	public static function Transform($geometry, $srid=4326)
	{
		/*TODO: adesso geometry non ha gli apici a circondarlo perchè la stringa è una funzione (ST_GeomFromText())
		 * 		ma se viene passato il valore direttamente ci vogliono.
		 */
		return "ST_Transform(".$geometry.",".$srid.")";
	}
	
	/**
	 * Calcola il comune in cui cade la geometria data
	 * @param integer $id Id della geometria
	 * @return array the result set (each array element represents a row of data). An empty array will be returned if the result contains no row. NULL is returned if ID is null.
	 */
	public static function Get_City_State_ByID_all($id){
		
		if(gettype($id)!="integer")
			return;
		
		$connection=Yii::app()->db;
		$sql = '
		
 SELECT intersezioni.nome, st_area(intersezioni.clipped_geom) / st_area(lotto.lotto) * 100::double precision AS percentuale
   FROM ( 
   		SELECT toccano.gid, toccano.id, toccano.nome, toccano.the_geom,
   			st_intersection(toccano.the_geom, ST_Transform(( SELECT water_request_geometries.geom
                   											FROM water_request_geometries
                  											WHERE water_request_geometries.id = '.$id.'),
                  										 '.Yii::app()->params['geoserver']['citystate_layer_srid'].')
                  			) AS clipped_geom
           FROM ( 
           		SELECT confini_comunali.gid, confini_comunali.id, confini_comunali.nome, confini_comunali.the_geom
                  FROM confini_comunali
                 WHERE st_intersects(
                 			confini_comunali.the_geom,
                 			ST_Transform(( SELECT water_request_geometries.geom
                           				FROM water_request_geometries
                          				WHERE water_request_geometries.id = '.$id.'),
                          			 '.Yii::app()->params['geoserver']['citystate_layer_srid'].')
                       )
                ) toccano
          ) intersezioni, ST_Transform(( SELECT water_request_geometries.geom
           								FROM water_request_geometries
          								WHERE water_request_geometries.id = '.$id.'),
          							'.Yii::app()->params['geoserver']['citystate_layer_srid'].') lotto(lotto) ORDER BY percentuale DESC;
		';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		// retrieving all rows at once in a single array
		$rows=$dataReader->readAll();
		//Yii::log(print_r($rows) , CLogger::LEVEL_INFO, 'Query.DB');  // DEBUG
		return $rows;
		
	}
	/**
	 * Calcola il comune in cui cade la geometria data
	 * @param string $wkt Geometria in WellKnownText format
	 * @return array the result set (each array element represents a row of data). An empty array will be returned if the result contains no row. NULL is returned if ID is null.
	 */
	public static function Get_City_State_ByWKT_all($wkt){
		
		$wkt_geom = Geometry::Transform(Geometry::ST_GeomFromText($wkt),Yii::app()->params['geoserver']['citystate_layer_srid']);
		//Yii::log(print_r($wkt_geom, true) , CLogger::LEVEL_INFO, 'Get_City_State_ByWKT');  // DEBUG
		//return;
		
		$connection=Yii::app()->db;
		$sql = '
		
 SELECT intersezioni.nome, st_area(intersezioni.clipped_geom) / st_area(lotto.lotto) * 100::double precision AS percentuale
   FROM ( 
   		SELECT toccano.gid, toccano.id, toccano.nome, toccano.the_geom,
   			st_intersection(toccano.the_geom, '.$wkt_geom.'
                  			) AS clipped_geom
           FROM ( 
           		SELECT confini_comunali.gid, confini_comunali.id, confini_comunali.nome, confini_comunali.the_geom
                  FROM confini_comunali
                 WHERE st_intersects(
                 			confini_comunali.the_geom,
                 			'.$wkt_geom.'
                       )
                ) toccano
          ) intersezioni, '.$wkt_geom.' lotto(lotto) ORDER BY percentuale DESC;
		';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		// retrieving all rows at once in a single array
		$rows=$dataReader->readAll();
		//Yii::log(print_r($rows) , CLogger::LEVEL_INFO, 'Query.DB');  // DEBUG
		return $rows;
		
	}
	
	/**
	 * Calcola il comune in cui cade la geometria data
	 * @param integer $id Id della geometria
	 * @return array the result set (each array element represents a row of data). An empty array will be returned if the result contains no row. NULL is returned if ID is null.
	 */
	public static function Get_City_State_ByID($id){
		
		$connection=Yii::app()->db;
		$sql = '
			SELECT confini_comunali.gid, confini_comunali.id, confini_comunali.nome, confini_comunali.the_geom
            FROM confini_comunali
            WHERE st_intersects(
            			confini_comunali.the_geom,
              			ST_Transform(( SELECT centroids.centroid
                       				FROM centroids
                       				WHERE centroids.id = '.$id.'),
                       			 '.Yii::app()->params['geoserver']['citystate_layer_srid'].')
                   		);';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		// calling read() repeatedly until it returns false
		//while(($row=$dataReader->read())!==false) { 		}
		// using foreach to traverse through every row of data
		//foreach($dataReader as $row) { }
		// retrieving all rows at once in a single array
		$rows=$dataReader->readAll();
		//Yii::log(print_r($rows) , CLogger::LEVEL_INFO, 'Query.DB');  // DEBUG
		if(count($rows)>1)
			return Geometry::Get_City_State_ByID_all($id);  // Caso MOLTO particolare
		else
			return $rows;
		
	}
	/**
	 * Calcola il comune in cui cade la geometria data
	 * @param string $wkt Geometria in WellKnownText format
	 * @return array the result set (each array element represents a row of data). An empty array will be returned if the result contains no row. NULL is returned if ID is null.
	 */
	public static function Get_City_State_ByWKT($wkt){
		
		$wkt_geom = Geometry::Transform(Geometry::ST_GeomFromText($wkt),Yii::app()->params['geoserver']['citystate_layer_srid']);
		//Yii::log(print_r($wkt_geom, true) , CLogger::LEVEL_INFO, 'Get_City_State_ByWKT');  // DEBUG
		//return;
		
		$connection=Yii::app()->db;
		$sql = '
			SELECT confini_comunali.gid, confini_comunali.id, confini_comunali.nome, confini_comunali.the_geom
            FROM confini_comunali
            WHERE st_intersects(
            			confini_comunali.the_geom,
              			ST_Centroid('.$wkt_geom.')
                   		);';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		// calling read() repeatedly until it returns false
		//while(($row=$dataReader->read())!==false) { 		}
		// using foreach to traverse through every row of data
		//foreach($dataReader as $row) { }
		// retrieving all rows at once in a single array
		$rows=$dataReader->readAll();
		//Yii::log(print_r($rows) , CLogger::LEVEL_INFO, 'Query.DB');  // DEBUG
		if(count($rows)>1)
			return Geometry::Get_City_State_ByWKT_all($wkt);  // Caso MOLTO particolare
		else
			return $rows;
				
	}

	/**
	 * Calcola il comune in cui cade la geometria passata come id o wkt.
	 * @param object $id_or_wkt ID as integer or WKT as string 
	 * @return string The name of the CityState, NULL if none is found.
	 */
	public static function Get_City_State($id_or_wkt){
		
		if(!is_numeric($id_or_wkt)){
			$comuni = Geometry::Get_City_State_ByWKT($id_or_wkt);
		}else{
			$comuni = Geometry::Get_City_State_ByID($id_or_wkt);
		}
		
		if(count($comuni))
			return $comuni[0]['nome'];
		else  
 			return null;
	}

	/**
	 * Calculate service area holding the geometry
	 * @param integer $id Id della geometria
	 * @return array the result set (each array element represents a row of data). An empty array will be returned if the result contains no row. NULL is returned if ID is null.
	 */
	public static function Get_Service_Area_ByID($id){
	
		$connection=Yii::app()->db;
		$sql = '
		SELECT service_areas.gid, service_areas.area, service_areas.desc_area, service_areas.the_geom
		FROM service_areas
		WHERE st_intersects(
			service_areas.the_geom,
			ST_Transform(( SELECT centroids.centroid
							FROM centroids
							WHERE centroids.id = '.$id.'),
							'.Yii::app()->params['geoserver']['service_areas_layer_srid'].')
			);';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		/*
		if(count($rows)>1)
			return Geometry::Get_Service_Area_ByID_all($id);  // Caso MOLTO particolare
		else
		*/
			return $rows;
	
	}
	/**
	 * Calculate service area holding the geometry
	 * @param string $wkt Geometria in WellKnownText format
	 * @return array the result set (each array element represents a row of data). An empty array will be returned if the result contains no row. NULL is returned if ID is null.
	 */
	public static function Get_Service_Area_ByWKT($wkt){
	
		$wkt_geom = Geometry::Transform(Geometry::ST_GeomFromText($wkt),Yii::app()->params['geoserver']['service_areas_layer_srid']);
		//Yii::log(print_r($wkt_geom, true) , CLogger::LEVEL_INFO, 'Get_City_State_ByWKT');  // DEBUG
		//return;
	
		$connection=Yii::app()->db;
		$sql = '
		SELECT service_areas.gid, service_areas.area, service_areas.desc_area, service_areas.the_geom
		FROM service_areas
		WHERE st_intersects(
			service_areas.the_geom,
			ST_Centroid('.$wkt_geom.')
			);';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		/*
		if(count($rows)>1)
			return Geometry::Get_Service_Area_ByWKT_all($wkt);  // Caso MOLTO particolare
		else
		*/
			return $rows;
	
	}
	

	/**
	 * Calculate the service area holding the geometry passed as id or wkt.
	 * @param object $id_or_wkt ID as integer or WKT as string 
	 * @return string The service area id, NULL if none is found.
	 */
	public static function Get_Service_Area_Detailed($id_or_wkt){
		
		if(!is_numeric($id_or_wkt)){
			$sas = Geometry::Get_Service_Area_ByWKT($id_or_wkt);
		}else{
			$sas = Geometry::Get_Service_Area_ByID($id_or_wkt);
		}
		
		if(count($sas))
			return $sas[0];
		else  
 			return null;
	}
	
	/**
	 * Return the description of the service area holding the geometry passed as id or wkt.
	 * @param object $id_or_wkt ID as integer or WKT as string 
	 * @return string The service area description, NULL if none is found.
	 */
	public static function Get_Service_Area($id_or_wkt){
		
		$sa = Geometry::Get_Service_Area_Detailed($id_or_wkt);
		if ($sa)
			if (array_key_exists('desc_area',$sa))
				return $sa['desc_area'];
		return '';
		
	}
	
	/**
	 * Calcola il BoundingBox di un punto con un buffer
	 * @param array $centroid Geometria in WellKnownText format
	 * @return array BoundingBox nella forma {minx, miny, maxx, maxy}
	 */
	public static function Get_Buffered_BBox_ByWKT($centroid){
		// Per wgs84 con 6 cifre decimali arriviamo ad una precisione sotto il metro.
		// Per WMS Version 1.1.1 l'ordine è lon/lat (x/y)
		// Per WMS Version 1.3.0 l'ordine è lat/lon (y/x)
		return array('xmin'=>$centroid->X-0.000001,
					 'ymin'=>$centroid->Y-0.000001,
					 'xmax'=>$centroid->X+0.000001,
					 'ymax'=>$centroid->Y+0.000001,
		);		
	}
	
	/**
	 * Verifica che il sistema di riferimento esista nel DB
	 * @param string $srid SRID
	 * @return boolean, true if srid exists, false otherwise.
	 */
	public static function Srid_Exists($srid){
		//Yii::log(print_r("testing: ".$srid, true) , CLogger::LEVEL_INFO, 'Srid_Exists');  // DEBUG
		if(!is_numeric($srid))
			return false;
		$connection=Yii::app()->db;
		$sql = 'SELECT * FROM spatial_ref_sys WHERE srid=\''.$srid.'\'; ';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$nrows=$dataReader->getRowCount();
		//Yii::log(print_r("nrows: ".$nrows, true) , CLogger::LEVEL_INFO, 'Srid_Exists');  // DEBUG
		if($nrows>0)
			return true;  
		else
			return false;
	
	}
	
	
}