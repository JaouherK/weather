<!DOCTYPE html>
<html>
	<head>
		<title>Weather Forecast - Ridoan Saleh Nasution</title>
		<style type="text/css">
			body{ text-align: center; }
			#city{ width: 200px; height: 30px; font-size: 16px; }
			#btn{ width: 80px; height: 30px; font-size: 16px; }
			#weather{ font-size: 20px; }
		</style> 
	</head>
	<body>
		<div>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<select id="city" name="kota">
				<option value="Jakarta">Jakarta</option>
				<option value="Surabaya">Surabaya</option>
				<option value="Medan">Medan</option>
				<option value="Makassar">Makassar</option>
				<option value="Jayapura">Jayapura</option>
			</select>
			<input id="btn" type="submit" value="Go!"/>
		</form>
		</div>
		<?php
			class w_forecast{
				public $result;
				function weather($city){
					$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
					$yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$city.'") and u="c"';
					$yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
					
					// Make call with cURL
					$session = curl_init($yql_query_url);
					curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
					$json = curl_exec($session);
					
					// Convert JSON to PHP object
					$phpObj =  json_decode($json);
					
					$weatherd='<br/><div id="weather">The weather of <b>'.$city.'</b> at';
					$fcast = $phpObj->query->results->channel->item->forecast;
					
					$a=1;
					foreach($fcast as $witem){
						if($a==1){
							$fdate=DateTime::createFromFormat('j M Y', $witem->date);
							$weatherd.= '&nbsp;'.$fdate->format('d M Y').'&nbsp; is ';
							$weatherd.= '<u>'.$witem->text.'</u></div>';
						}
						$a++;
					};
					$this->result = $weatherd;
				}
			}

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$kota = $_POST["kota"];
				$h = new w_forecast;
				$h->weather($kota);
				echo $h->result;
			}

		?>
	</body>
</html>