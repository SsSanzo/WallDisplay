<?php
	//https://www.ncdc.noaa.gov/cdo-web/webservices/v2#data
	class NOAAWeather {
		public $token = "nzubtstLaVVpUAdHAknAXHYibUzRQdDp";
		public $urlDatasets = "https://www.ncdc.noaa.gov/cdo-web/api/v2/datasets";
		public $datasetNORMAL_HLY = "NORMAL_HLY";
		public $datasetPRECIP_HLY = "PRECIP_HLY";
		public $datasetGHCND = "GHCND";
		public $curl;

		public function init(array $params = null, string $datasetId = null, string $curl = null){
			$curl = $curl ?? $this->urlDatasets;
			$urlsuffix = null;
			if(isset($datasetId)){
				$urlsuffix = "/" . $datasetId;
			}
			if(isset($params)){
				$urlsuffix = $urlsuffix . "?" . implode("&", $params);
			}
			$this->curl = curl_init($curl . $urlsuffix ?? "");
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("token:" . $this->token));
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER , true);
		}

		public function callNoaa() {
			return curl_exec($this->curl);
		}
	}
?>