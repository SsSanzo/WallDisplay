<?php
	include 'NoaaWeather.php';
				
	$weather = new NOAAWeather();
	$noaaQuery = array("datasetid=" . $weather->datasetGHCND, "locatinid=FIPS:17", "limit=1000", "startdate=2018-06-07", "enddate=2018-06-09", "datatype=AWND");
	$weather->init(
		$noaaQuery, 
		null, 
		"https://www.ncdc.noaa.gov/cdo-web/api/v2/data" 
	);
	// echo $weather->callNoaa();
	$res = json_decode($weather->callNoaa());
	$datacount = $res["metadata"]["results"]["count"];
	for($i=0;$i<$datacount; $i+=1000){
		$myNoaaQuery = $noaaQuery;
		$weather->init(
			$myNoaaQuery->append("offset=" . $i), 
			null, 
			"https://www.ncdc.noaa.gov/cdo-web/api/v2/data" 
		);
		$res = json_decode($weather->callNoaa());
		$myDatacount = $res["metadata"]["resultset"]["count"];
		$jmax = min(1000, $myDatacount);
		For($j=0; $j< $jmax; $j++){
			echo $res["results"][$j]["value"];
		}
	}
?>