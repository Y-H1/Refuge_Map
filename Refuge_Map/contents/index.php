<?php
	require("phpQuery-onefile.php");
	/*
	//$html = file_get_contents("http://www.city.yokohama.lg.jp/ex/kikikanri/weather/.cgi/yokohama/warning.cgi?cache=%27+(new%20Date()).getTime()");
	$html = file_get_contents("https://www.jma.go.jp/bosai/#pattern=default&area_type=class20s&area_code=1410000");
	#echo $html;
	$doc = phpQuery::newDocument($html);
	#$saigai = $doc["#bosaitop-bosai_panel_div"]->text();
	$saigai = $doc["#bosaitop-bosai_panel_div"]->html();
	#echo $doc . "aaa";

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
?>
<!DOCTYPE html>
	<meta charset="UTF-8">
	<head>
		<title>避難マップ</title>
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
			}else {
				echo "<marquee><font size = '5px'>$saigai</font></marquee>";
			}*/
		?>
		<script>
			function getElement(name) {
				var element=document.getElementById("geolocation")
				navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
					function successCallback(position) {    //成功時の処理
						var latitude = position.coords.latitude;
						var longitude = position.coords.longitude;
						if(latitude){   //変数latitudeに値が入ってた時
							getmap = confirm("位置情報の取得を開始します");//取得開始のアラート
							if ( getmap == true ){
								location.href = name + "?latitudecd=" + latitude + "&longitudecd=" + longitude;//取得したらリダイレクト
							}else{
								alert("またのご利用お待ちしています。");//取得開始を許可しない場合のアラート
							}
						}
					}
				function errorCallback(error) { //失敗時の処理
					alert("位置情報が取得できません");
				}
			};
		</script>
		
		<script>
			function keiziban() {
				location.href = "./keiziban.php";
			}
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
								<li><a href="" id="geolocation" onclick="getElement('call_back.php'); return false;">防災拠点・一時滞在施設</a></li>
								<li><a href="" id="geolocation" onclick="getElement('call_back_tunami.php'); return false;">津波避難施設</a></li>
								<li><a href="" id="geolocation" onclick="getElement('call_back_water.php'); return false;">災害時給水所</a></li>
								<li><a href="" id="geolocation" onclick="getElement('call_back_AED.php'); return false;">AED設置場所</a></li>
								<li><a href="" id="geolocation" onclick="getElement('call_back_wifi.php'); return false;">フリーWi-Fi</a></li>
							</ul>
						</li>
					</ul>
				</nav>
			</header><!-- /header -->
			
			<div id="leftmenu">	<p>メニュー</p>
				<ul>
					<li><a href="refuge_knowledge.php">・震災で必要な知識</a></li>
					<li><a href="xxx.html">・管理者用ページ</a></li>
				</ul>
			</div>
		</div>

		<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="js/jquery.meanmenu.js"></script>
		<script>
			jQuery(document).ready(function () {
				jQuery('header nav').meanmenu();
			});
		</script>
	</body>
</html>