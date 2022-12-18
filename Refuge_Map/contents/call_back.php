<?php
	class Hoge {

		public function result($x,$s) {
			require('../db.php');
			$db = new PDO($dsn, '', '');
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

			$latitudecd = $_GET['latitudecd'];
			$longitudecd = $_GET['longitudecd'];


			global $lat_array,$lng_array,$name_array,$sel,$mode_count;


			$sel = isset($_POST['saigai_type']) ? $_POST['saigai_type'] : "tunami";

			$count = 0;
			$not_in = "";

			while($x[count($x) - 1] == "") {
				if($s == "name") {
					$query = "select DISTINCT substr(name,1,instr(name, '　')-1) as name,((lat - '".$latitudecd."') * (lat - '".$latitudecd."') + (lng - '".$longitudecd."') * (lng - '".$longitudecd."')) as distance from kanagawa where substr(name,1,instr(name, '　')-1) <> '' and ".$sel." = 1 union all select DISTINCT name as name,((lat - '".$latitudecd."') * (lat - '".$latitudecd."') + (lng - '".$longitudecd."') * (lng - '".$longitudecd."')) as distance from kanagawa where substr(name,1,instr(name, '　')-1) == '' and ".$sel." = 1 order by distance limit '".$count."',1";
					//$query = "select DISTINCT substr(name,1,instr(name, '　')-1) as name,((lat - '".$latitudecd."') * (lat - '".$latitudecd."') + (lng - '".$longitudecd."') * (lng - '".$longitudecd."')) as distance from kanagawa where ".$sel." = 1 and EXISTS (select substr(name,1,instr(name, '　')-1) from kanagawa where substr(name,1,instr(name, '　')-1) <> '' and ".$sel." = 1 union select name from kanagawa where substr(name,1,instr(name, '　')-1) == '' and ".$sel." = 1) order by distance asc limit '".$count."',1";
				}else {
					$query = "select DISTINCT ".$s.",((lat - '".$latitudecd."') * (lat - '".$latitudecd."') + (lng - '".$longitudecd."') * (lng - '".$longitudecd."')) as distance from kanagawa where substr(name,1,instr(name, '　')-1) <> '' and ".$sel." = 1 union all select DISTINCT ".$s.",((lat - '".$latitudecd."') * (lat - '".$latitudecd."') + (lng - '".$longitudecd."') * (lng - '".$longitudecd."')) as distance from kanagawa where substr(name,1,instr(name, '　')-1) == '' and ".$sel." = 1 order by distance limit '".$count."',1";
					//$query = "select DISTINCT ".$s.",substr(name,1,instr(name, '　')-1) as name,((lat - '".$latitudecd."') * (lat - '".$latitudecd."') + (lng - '".$longitudecd."') * (lng - '".$longitudecd."')) as distance from kanagawa where ".$sel." = 1 and EXISTS (select substr(name,1,instr(name, '　')-1) from kanagawa where substr(name,1,instr(name, '　')-1) <> '' and ".$sel." = 1 union select name from kanagawa where substr(name,1,instr(name, '　')-1) == '' and ".$sel." = 1) order by distance asc limit '".$count."',1";
				}

				//echo $query;
				if(!empty($_POST["radio"])) {
					$query = "select * ,((lat - '".$latitudecd."') * (lat - '".$latitudecd."') + (lng - '".$longitudecd."') * (lng - '".$longitudecd."')) as distance from hinanzyo2 where type = '地域防災拠点' order by distance asc limit '".$count."',1";
				}

				$sql = $db->query($query);
				while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
					$x[$count] = $row[$s];

					$not_in .= " and ".$s." not in ('%".$row[$s]."%')";
				}
				$count++;
			}

			if($s == "lat") {
				$lat_array = array();
				$lat_array = array_pad($lat_array, count($x), null);
				for($i = 0; $i < count($x); $i++) {
					$lat_array[$i] = $x[$i];
				}
			}else if($s == "lng") {
				$lng_array = array();
				$lng_array = array_pad($lng_array, count($x), null);
				for($i = 0; $i < count($x); $i++) {
					$lng_array[$i] = $x[$i];
				}
			}else if($s == "name") {
				$name_array = array();
				$name_array = array_pad($name_array, count($x), null);
				for($i = 0; $i < count($x); $i++) {
					$name_array[$i] = $x[$i];
				}
			}else{
				for($i = 0; $i < count($x); $i++) {
					if($i == count($x) - 1) {
						echo $x[$i];
					}else {
						echo $x[$i]."<br>";
					}
				}
			}
			$x = null;
			$sql = null;
			$db = null;
		}

		public function select(){
			header("Content-type: text/html; charset=utf-8");
			require('../db.php');

			$db = new PDO($dsn,'','');
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

			global $address_search,$lat_search,$lng_search,$serch,$errormessage,$serch_check;

			if (!$db) {
				print('<p>データベースへの接続に失敗しました。</p>');
				exit();
			}else{
				$serch = $_POST['name'];
				$db->quote($serch);
				$query = "SELECT count(*) as count FROM kanagawa where name like '%" . $serch . "%'";
				$result = $db->query($query);
				if (!$result) {
					print('クエリーが失敗しました。' . $db->error);
					$db = null;
					exit();
				}else{
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
						$check = $row['count'];
					}

					if($check == 0) {
						$errormessage = "＊”".$serch."”は見つかりませんでした";
					}else{
						$query = "SELECT * FROM kanagawa where name like '%" . $serch . "%'";
						$result = $db->query($query);
						if (!$result) {
							print('クエリーが失敗しました。' . $db->error);
							$db = null;
							exit();
						}else{
							$serch_check = true;

							while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
								$address_search = $row['address'];
								$lat_search = $row['lat'];
								$lng_search = $row['lng'];
							}
						}
					}
				}
			}
		}
	}

	$check = "";
	$errormessage = "";
	$serch = "";
	$sel = "";
	$name_search = "";
	$count = 0;
	$serch_check = false;
	$mode_array = array("DRIVING","WALKING","BICYCLING","TRANSIT");
	$select_array = array("","","","");
	$mode = "WALKING";
	$mode_count = 5;
	$map_print = "";
	$map_print2 = "";
	$j_print = "";
	$row_cnt = 0;

	if (isset($_POST["count"])) {
		$mode_count = $_POST["count"];
	}

	if (isset($_POST["mode"])) {
		$mode = $_POST["mode"];
		for($i = 0; $i < count($mode_array); $i++) {
			if($mode_array[$i] == $mode) {
				$select_array[$i] = " selected";
			}
		}
	}

	require('../db.php');

	$db = new PDO($dsn,'','');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	$query = "select count(*) as count from kanagawa";
	$result = $db->query($query);
	if (!$result) {
		print('クエリーが失敗しました。');
		exit();
	}else{
		while ($row = $result->fetch(PDO::FETCH_ASSOC)){
			$row_cnt = $row['count'];
		}

		//$array = array();
		$array2 = array();

		//$array = array_pad($array, $row_cnt, "");
		$array2 = array_pad($array2, $row_cnt, "");


		for($i = 0; $i < count($array2); $i++) {

			$query = "select DISTINCT case when substr(name,1,instr(name, '　')-1) <> '' then substr(name,1,instr(name, '　')-1) when substr(name,1,instr(name, '　')-1) == '' then name end as name2 from kanagawa limit ".$i.",1";
			$result = $db->query($query);

			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				if(count($array2)-1 == $i) {
					//$array[$i] = "'".$row['address']."'";
					$array2[$i] = "'".$row['name2']."'";
				}else {
					//$array[$i] = "'".$row['address']."',";
					$array2[$i] = "'".$row['name2']."',";
				}
			}
		}
	}


	if(!empty($_POST["radio"])) {
		$check = "checked";
	}else{
		$check = "";
	}

	$result_1 = array();

	$result_1 = array_pad($result_1, $mode_count, null);


	$sample = new Hoge();
	$sample->result($result_1,"name");
	$sample->result($result_1,"lat");
	$sample->result($result_1,"lng");

	/*
	for($i = 0; $i < count($result_1); $i++) {
		echo "<br>".$lat_array[$i]."<br>";
		echo $lng_array[$i]."<br>";
	}*/

	for($i = 0; $i < count($result_1); $i++) {
		$map_print .= "var destination".($i + 1)." = {lat:".$lat_array[$i].", lng:".$lng_array[$i]."};";
		$j_print .= "array[".$i."] = '".$name_array[$i]."';";
		if($i == count($result_1) - 1) {
			$map_print2 .= "destination".($i + 1);
		}else {
			$map_print2 .= "destination".($i + 1).",";
		}
	}

	if(isset($_POST["route"]) || isset($_POST["sousin"]) || isset($_POST["button_p"])) {
		if(!empty($_POST["name"])) {
			$serch = $_POST["name"];
			if(isset($_POST["route"]) || isset($_POST["sousin"])) {
				$sample = new Hoge();
				$sample->select();
				$db = null;
			}
		}else {
			if(isset($_POST["route"]) || isset($_POST["sousin"])) {
				$errormessage = "<font color='red'>＊文字が入力されていません</font>";
			}
		}
	}

	require("phpQuery-onefile.php");
	/*$html = file_get_contents("http://www.city.yokohama.lg.jp/ex/kikikanri/weather/.cgi/yokohama/warning.cgi?cache=%27+(new%20Date()).getTime()");
	$doc = phpQuery::newDocument($html);
	$saigai = $doc["marquee"]->text();

	$saigai_array = preg_split("//u", $saigai);
	$start = -1;
	$end = -1;
	$start_string = "";
	$half_string = "";
	$end_string = "";
	$saigai_check = false;


	for($i = 0; $i < count($saigai_array); $i++) {
		if($saigai_array[$i] === "[" && $start == -1) {
			$start = $i;
		}else if($saigai_array[$i] === "]") {
			if($saigai_array[$i - 1] === "報") {
				$end = $i;
				$saigai_check = true;
			}
		}
	}

	for($i = 0; $i < count($saigai_array); $i++) {
		if($i < $start) {
			$start_string .= $saigai_array[$i];
		}else if($i >= $start && $i <= $end) {
			$half_string .= $saigai_array[$i];
		}else {
			$end_string .= $saigai_array[$i];
		}
	}*/

	$db = null;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<meta charset="UTF-8">
	<head>
		<title>Disaster Map</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- CSS　読み込み -->
		<link rel="stylesheet"href="meanmenu.css" media="(min-width: 0px) and (max-width: 559px)">
		<link rel="stylesheet"href="system2.css" media="all">
	</head>
	<body>

		<?php  
			/*if($saigai_check == true) {
				echo "<marquee><font size = '5px'>$start_string</font>"."<font color = 'red' size = '5px'>$half_string</font>"."<font size = '5px'>$end_string</font></marquee>";
				//echo "<font color = 'red' size = '5px'>$half_string</font>";
				//echo "<font size = '5px'>$end_string</font>";
			}else {
				echo "<marquee><font size = '5px'>$saigai</font></marquee>";
			}*/
		?>
		<script>
			function getElement(name) {
				var element=document.getElementById("test")
				navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
					function successCallback(position) {    //成功時の処理
						var latitude = position.coords.latitude;
						var longitude = position.coords.longitude;
						if(latitude){   //変数latitudeに値が入ってた時
							getmap = confirm("位置情報の取得を開始します");//取得開始のアラート
							if ( getmap == true ){
								location.href = name + "?latitudecd=" + latitude + "&longitudecd=" + longitude;//取得したらリダイレクト
							}
						}
					}
				function errorCallback(error) { //失敗時の処理
					alert("位置情報が取得できません");
				}
			};
		</script>


		<script>
			function Link(link,type) {
				if(type == 0) {
					location.href = "./" + link;
				}else {
					location.href = "./" + link + "?type=" + type;
				}
			}
		</script>


		<h1>避難マップ</h1>
		<div class="wrap">
			<span class="decor"></span>
			<header>
				<nav>
					<ul class="primary">
						<!-- メニュータイトル　地域防災拠点 -->
						<li>
							<a href="">地域防災拠点</a>
							<ul class="sub">
								<li><a href="" onclick="Link('select.php',1); return false;">青葉区</a></li>
								<li><a href="" onclick="Link('select.php',2); return false;">旭区</a></li>
								<li><a href="" onclick="Link('select.php',3); return false;">泉区</a></li>
								<li><a href="" onclick="Link('select.php',4); return false;">磯子区</a></li>
								<li><a href="" onclick="Link('select.php',5); return false;">神奈川区</a></li>
								<li><a href="" onclick="Link('select.php',6); return false;">金沢区</a></li>
								<li><a href="" onclick="Link('select.php',7); return false;">港南区</a></li>
								<li><a href="" onclick="Link('select.php',8); return false;">港北区</a></li>
								<li><a href="" onclick="Link('select.php',9); return false;">栄区</a></li>
								<li><a href="" onclick="Link('select.php',10); return false;">瀬谷区</a></li>
								<li><a href="" onclick="Link('select.php',11); return false;">都筑区</a></li>
								<li><a href="" onclick="Link('select.php',12); return false;">鶴見区</a></li>
								<li><a href="" onclick="Link('select.php',13); return false;">戸塚区</a></li>
								<li><a href="" onclick="Link('select.php',14); return false;">中区</a></li>
								<li><a href="" onclick="Link('select.php',15); return false;">西区</a></li>
								<li><a href="" onclick="Link('select.php',16); return false;">保土ケ谷区</a></li>
								<li><a href="" onclick="Link('select.php',17); return false;">緑区</a></li>
								<li><a href="" onclick="Link('select.php',18); return false;">南区</a></li>
							</ul>
						</li>
						
						<li>
							<a href="">一時滞在施設</a>
							<ul class="sub">
								<li><a href="" onclick="Link('select.php',19); return false;">青葉区</a></li>
								<li><a href="" onclick="Link('select.php',20); return false;">旭区</a></li>
								<li><a href="" onclick="Link('select.php',21); return false;">泉区</a></li>
								<li><a href="" onclick="Link('select.php',22); return false;">磯子区</a></li>
								<li><a href="" onclick="Link('select.php',23); return false;">神奈川区</a></li>
								<li><a href="" onclick="Link('select.php',24); return false;">金沢区</a></li>
								<li><a href="" onclick="Link('select.php',25); return false;">港南区</a></li>
								<li><a href="" onclick="Link('select.php',26); return false;">港北区</a></li>
								<li><a href="" onclick="Link('select.php',27); return false;">栄区</a></li>
								<li><a href="" onclick="Link('select.php',28); return false;">瀬谷区</a></li>
								<li><a href="" onclick="Link('select.php',29); return false;">都筑区</a></li>
								<li><a href="" onclick="Link('select.php',30); return false;">鶴見区</a></li>
								<li><a href="" onclick="Link('select.php',31); return false;">戸塚区</a></li>
								<li><a href="" onclick="Link('select.php',32); return false;">中区</a></li>
								<li><a href="" onclick="Link('select.php',33); return false;">西区</a></li>
								<li><a href="" onclick="Link('select.php',34); return false;">保土ケ谷区</a></li>
								<li><a href="" onclick="Link('select.php',35); return false;">緑区</a></li>
								<li><a href="" onclick="Link('select.php',36); return false;">南区</a></li>
							</ul>
						</li>
						
						<li>
							<a href="">津波避難施設</a>
							<ul class="sub">
								<li><a href="" onclick="Link('select.php',40); return false;">磯子区</a></li>
								<li><a href="" onclick="Link('select.php',41); return false;">神奈川区</a></li>
								<li><a href="" onclick="Link('select.php',42); return false;">金沢区</a></li>
								<li><a href="" onclick="Link('select.php',48); return false;">鶴見区</a></li>
								<li><a href="" onclick="Link('select.php',50); return false;">中区</a></li>
								<li><a href="" onclick="Link('select.php',51); return false;">西区</a></li>
								<li><a href="" onclick="Link('select.php',52); return false;">保土ケ谷区</a></li>
								<li><a href="" onclick="Link('select.php',54); return false;">南区</a></li>
							</ul>
						</li>
						
						<li>
							<a href="">位置情報・検索</a>
							<ul class="sub">
								<li><a href="" id="test" onclick="getElement('call_back.php'); return false;">防災拠点・一時滞在施設</a></li>
								<li><a href="" id="test" onclick="getElement('call_back_tunami.php'); return false;">津波避難施設</a></li>
								<li><a href="" id="test" onclick="getElement('call_back_water.php'); return false;">災害時給水所</a></li>
								<li><a href="" id="test" onclick="getElement('call_back_AED.php'); return false;">AED設置場所</a></li>
								<li><a href="" id="test" onclick="getElement('call_back_wifi.php'); return false;">フリーWi-Fi</a></li>
							</ul>
						</li>
					</ul>
				</nav>
			</header><!-- /header -->
			
			<div id="leftmenu">	<p>メニュー</p>
				<ul>
					<li><a href="./refuge_knowledge.php">・震災で必要な知識</a></li>
					<li><a href="xxx.html">・管理者用ページ</a></li>
				</ul>
			</div>


			<form id="Form" name="Form" action="" method="POST">
				<div class="d_h1"><h1>避難所</h1>
				
					<input type="text" id="automplete2" name="name" autocomplete="on" placeholder="   検索" value="<?php echo $serch;?>">
					<input type="submit" id="search" name="sousin" value="検索">
					<input type="button" name="route" value="ルート案内" onclick="kakunin(0);"> <?php echo "<font color='red' size='3px'>".$errormessage."</font>";?>
				</div>
				<p class="option2">オプション</p>
				<!--<div id="floating-panel3"></div>-->


				<div id="floating-panel">
					<b>移動手段 : </b>
					<select id="mode" name="mode">
						<option value="WALKING"<?php echo $select_array[1];?>>徒歩</option>
						<option value="DRIVING"<?php echo $select_array[0];?>>車</option>
					</select>
				</div>

				<!--<div id="space">
							<label>	 <input type="checkbox" name="radio" value="radio" onclick="check()" <?php //echo $check?>>一時滞在施設を除外する</label>
								<input type="submit" id="sousin" name="sousin" value="送信">
				</div>-->

				<div id="map"></div>


				<div id="floating-panel2">
					<b>災害タイプ : </b>
					<select id="mode" name="saigai_type">
						<option value="kouzui"<?= $sel === 'kouzui' ? ' selected' : ''; ?>>洪水</option>
						<option value="gake_dosya_zisuberi"<?= $sel === 'gake_dosya_zisuberi' ? ' selected' : ''; ?>>土砂崩れ</option>
						<option value="takasio"<?= $sel === 'takasio' ? ' selected' : ''; ?>>高潮</option>
						<option value="zisin"<?= $sel === 'zisin' ? ' selected' : ''; ?>>地震</option>
						<option value="tunami"<?= $sel === 'tunami' ? ' selected' : ''; ?>>津波</option>
						<option value="kazi"<?= $sel === 'kazi' ? ' selected' : ''; ?>>火事</option>
						<!--<option value="naisuihanran"<?= $sel === 'naisuihanran' ? ' selected' : ''; ?>>氾濫</option>
						<option value="kazan"<?= $sel === 'kazan' ? ' selected' : ''; ?>>火山</option>-->
					</select>
				</div>

				<div id="floating-panel4">
					<b>出力数 : </b>
					<select id="mode" name="count">
						<option value="5"<?= $mode_count == 5 ? ' selected' : ''; ?>>5</option>
						<option value="10"<?= $mode_count == 10 ? ' selected' : ''; ?>>10</option>
					</select>
				</div>

				<input type="submit" value="設定を送信" id="button_p">
			</form>

			<table border="1" class="table1" align="center">
				<tr>
					<th scope="col">施設名</th>
					<th scope="col">距離</th>
					<th scope="col">時間</th>
					<th scope="col">ルート案内</th>
				</tr>

				<td class="gyokan">
					<?php
						for($i = 0; $i < count($result_1); $i++) {
							echo $name_array[$i];
							echo "<br>";
						}
					?>
				</td>

				<td class="gyokan">
					<div id="output"></div>
				</td>

				<td class="gyokan">
					<div id="output2"></div>
				</td>

				<td id="sample">
					<?php
						for($i = 0; $i < count($result_1); $i++) {
							echo "<input type='button' value='案内を表示' style='WIDTH: 200px; HEIGHT: 30px' onclick='kakunin(".($i + 1).");'><br/>";
						}
					?>
				</td>
			</table>
		</div>

		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>

		<script src="js/jquery.meanmenu.js"></script>
		<script>
			jQuery(document).ready(function () {
				jQuery('header nav').meanmenu();
			});
		</script>

		<script>
			$(function() {
				var brands = [
					<?php 
						for($i = 0; $i < count($array2); $i++) {
							echo $array2[$i];
						}
					?>
				];
				$( "#automplete2" ).autocomplete({
					source: brands
				});
			});
		</script>

		<script>
			var map;
			var marker;
			var geocoder;
			function initMap3() {
				geocoder = new google.maps.Geocoder();
				geocoder.geocode({
					'address': '<?php echo $address_search;?>' // TAM 東京
				}, function(results, status) { // 結果
					if (status === google.maps.GeocoderStatus.OK) { // ステータスがOKの場合
						map = new google.maps.Map(document.getElementById('map'), {
							center: results[0].geometry.location, // 地図の中心を指定
							zoom: 19 // 地図のズームを指定
						});
						marker = new google.maps.Marker({
							position: results[0].geometry.location, // マーカーを立てる位置を指定
							map: map // マーカーを立てる地図を指定
						});
					} else { // 失敗した場合
						alert(status);
					}
				});
			}
		</script>

		<script>
			function initMap() {
				var bounds = new google.maps.LatLngBounds;
				var markersArray = [];

				var origin1 = {lat: <?php echo $_GET['latitudecd'];?>, lng: <?php echo $_GET['longitudecd'];?>};
				<?php echo $map_print;?>

				var destinationIcon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
				var originIcon = 'https://chart.googleapis.com/chart?' +
					'chst=d_map_pin_letter&chld=O|FFFF00|000000';

				var map = new google.maps.Map(document.getElementById('map'), {
					center: {lat: <?php echo $_GET['latitudecd'];?>, lng: <?php echo $_GET['longitudecd'];?>},
					zoom: 13
				});

				var geocoder = new google.maps.Geocoder;

				var service = new google.maps.DistanceMatrixService;

				var selectedMode = document.getElementById('mode').value;
				service.getDistanceMatrix({
					origins: [origin1],//ここ
					destinations: [<?php echo $map_print2;?>],//ここ
					travelMode: '<?php echo $mode;?>',
					unitSystem: google.maps.UnitSystem.METRIC,
					avoidHighways: false,
					avoidTolls: false
				}, function(response, status) {
					if (status !== 'OK') {
						alert('Error was: ' + status);
					} else {
						var originList = response.originAddresses;
						var destinationList = response.destinationAddresses;
						var outputDiv = document.getElementById('output');
						var outputDiv2 = document.getElementById('output2');
						outputDiv.innerHTML = '';
						deleteMarkers(markersArray);

						var showGeocodedAddressOnMap = function(asDestination) {
							var icon = asDestination ? destinationIcon : originIcon;
							return function(results, status) {
								if (status === 'OK') {
									map.fitBounds(bounds.extend(results[0].geometry.location));
									markersArray.push(new google.maps.Marker({
										map: map,
										position: results[0].geometry.location,
										icon: icon
									}));
								} /*else {
									alert('Geocode was not successful due to: ' + status);
								}*/
							};
						};

						for (var i = 0; i < originList.length; i++) {
							var results = response.rows[i].elements;
							geocoder.geocode({'address': originList[i]},
								showGeocodedAddressOnMap(false));
							for (var j = 0; j < results.length; j++) {
								geocoder.geocode({'address': destinationList[j]},
									showGeocodedAddressOnMap(true));
								outputDiv.innerHTML += '<b>' + results[j].distance.text + '</b><br>';
								outputDiv2.innerHTML += '<b>' + results[j].duration.text + '</b><br>';
							}
						}
					}
				});
			}

			function deleteMarkers(markersArray) {
				for (var i = 0; i < markersArray.length; i++) {
					markersArray[i].setMap(null);
				}
				markersArray = [];
			}
		</script>

		<script>
			function kakunin(btnNo){
				//alert(btnNo);
				var link;
				var array = new Array(<?php echo count($result_1);?>);

				<?php echo $j_print;?>

				if(btnNo == 0) {
					link = document.getElementById("automplete2").value;
				}else {
					link = array[btnNo - 1];
				}

				var ret = confirm("GoogleMAPへ飛びます。宜しいですか？ : " + link);
				if (ret == true){
					window.open('http://maps.google.co.jp/maps?f=q&hl=ja&geocode=&q=' + link + '&ie=UTF8', '_blank');
					//location.href = 'http://maps.google.co.jp/maps?f=q&hl=ja&geocode=&q=' + link + '&ie=UTF8';
				}
			}
		</script>


		<script async defer
			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRwF0eEILxYC9Im1Cx9H-EWbfZc2GRluc&callback=<?php if(isset($_POST["sousin"]) && $_POST["name"] != "" && $serch_check == true){echo "initMap3";}else{ echo "initMap";}?>">
		</script>
	</body>
</html>