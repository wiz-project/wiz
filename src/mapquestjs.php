<?php

/*
  Description: mapquestjs.php?q=QUERY[&limit=N][&lang=LANG]
               parses the xml response from mapquest and delivers a tab delimited list of serch results

  Parameters:  q=QUERY:    an urldecoded search string,
                           eg "Berlin" or "Bahnhof Starnberg" or "Brasil" or "Hotel London" or "Heinestraße, Würzburg"  
               limit=N:    the maximum number of search results
                           default: 3
               lang=LANG:  a comma separated list of the preferred response language
                           eg "de,en,ru" or "de" or  "fr-FR,de-DE;q=0.5"
                           default: the ACCEPT_LANGUAGE-Parameter sent by the browser
               
                           
  Result:          LON<tab>LAT<tab>Shortname<tab>Longname<CR>
                   LON<tab>LAT<tab>Shortname<tab>Longname<CR>
                   LON<tab>LAT<tab>Shortname<tab>Longname<CR>
             
                   Shortname is something estimated from the type of the search result (estimated means sometimes too short...)
                   Longname  is the "DISPLAY_NAME" delivered by mapquest/nominatim (very verbose and often too long...)
             
  Error response:  None, just no result.
  
  Needs CURL and PHP...
*/

/*
 Read "nominatim-i8n.csv" and get an array 
   $translation['peak']['en']='peak';
   $translation['peak']['de']='Berg'; 
   $translation['peak']['it']='Montagna';

 for file format of nominatim-i8n.csv see comments there
*/

  $translation['dummy']['en']='dummy';
  $fd=fopen("nominatim-i8n.csv","r");
  if($fd){
   $i=0;
   while(!feof($fd)){
    $line=trim(fgets($fd,1000));
    if(($line)&&($line[0]!="#")&&(strlen($line)>2)){
     $i++;
     if($i==1){
      $lang=preg_split("/:/",strtolower($line));
      for($j=0;$j<count($lang);$j++){$lang[$j]=trim($lang[$j]);}
     }else{
      $trans=preg_split("/:/",$line);
      for($j=0;$j<count($trans);$j++){
       $trans[$j]=trim($trans[$j]);
       $translation[$trans[0]][$lang[$j]]=$trans[$j];
      }
     }
    }
   }
   fclose($fd);
  }


/* get parameters, set defaults */             

  $query=urlencode($_GET["q"]);
  $limit=urlencode($_GET["limit"]);
  $lang=urlencode($_GET["lang"]);
  if($limit==0){$limit=3;}
  if($lang==""){$lang=$_SERVER['HTTP_ACCEPT_LANGUAGE'];}
  
/* open mapquestapi.com and get results  */

  $crl = curl_init();
  $url="http://open.mapquestapi.com/nominatim/v1/search?q=".$query."&format=xml&polygon=0&addressdetails=1&limit=".$limit."&accept-language=".$lang;
  $lang=substr(strtolower($lang),0,2);
  curl_setopt ($crl, CURLOPT_URL,$url);
  curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
  $ret = curl_exec($crl);
  curl_close($crl);


/* Parse XML data into an array structure */

  $xml_parser = xml_parser_create();
  xml_parse_into_struct($xml_parser, $ret, $vals, $index);
  xml_parser_free($xml_parser);


/* Loop throug each array

   <Place level=2> opens a record and contains lon,lat,display_name....
   then follow some level=3-Items with county,city,peak,road,hotel...
   </Place level=2> closes this record
   
   <place type> is replaced by translation if available
*/

  foreach($vals as $element){
   if(($element['tag']=="PLACE")&&($element['type']=="open")&&($element['level']=="2")){
    $type=$longname=$shortname="";$lon=$lat=0;
    $lon=$element['attributes']['LON'];
    $lat=$element['attributes']['LAT'];
    $longname=$element['attributes']['DISPLAY_NAME'];
    $class=$element['attributes']['CLASS'];
    $type=strtolower($element['attributes']['TYPE']);
    if($translation[$type][$lang]!="") {$transtype=$translation[$type][$lang];}
    else {$transtype=$type;}
   }


/* Try to interprete level-3-tags to build a shortname */

   if($element['level']=="3"){
    if(($element['tag']==strtoupper($type))||(($element['tag']=='ROAD')&&($class=='highway'))){
     $shortname=$shortname." ".$element['value'];
    }else{
     if(($element['tag']=='VILLAGE')){$shortname=$shortname.", ".$element['value'];}
     if(($element['tag']=='TOWN'))   {$shortname=$shortname.", ".$element['value'];}
     if(($element['tag']=='CITY'))   {$shortname=$shortname.", ".$element['value'];}
     if(($element['tag']=='STATE'))  {$shortname=$shortname.", ".$element['value'];}
     if(($element['tag']=='COUNTRY')){$shortname=$shortname.", ".$element['value'];}
    }
   }

/* end of record: print result */

   if(($element['tag']=="PLACE")&&($element['type']=="close")&&($element['level']=="2")){
    $shortname=preg_replace("/^,/","",$shortname);
    print "$lon\t$lat\t".ucfirst($transtype).": $shortname\t".ucfirst($type).": $longname\n";
    $type=$longname=$shortname="";$lon=$lat=0; 
   }
  }
?>
