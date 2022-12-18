<?php
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


	$mode = null;
	if($_GET){
		$mode = $_GET["mode"];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<meta charset="UTF-8">
	<head>
		<title>避難マップ</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- CSS　読み込み -->
		<!--//前の<link rel="stylesheet"href="meanmenu.css" media="(min-width: 0px) and (max-width: 559px)">-->
		<link rel="stylesheet"href="meanmenu.css" media="(min-width: 0px) and (max-width: 480px)">
		<link rel="stylesheet"href="system2.css" media="all">

		<style>
			#square_btn{
				display:block;
				width: 100px;
				height:50px;
				line-height: 50px;
				color: #FFF;
				text-decoration: none;
				text-align: center;
				border-radius: 100px; /*角丸*/
				-webkit-transition: all 0.5s;
				transition: all 0.5s;
				margin-right:10px;
			}
			#square_btn:hover {
				background-color: #f9c500; /*ボタン色*/
			}
		</style>
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
				var ua = navigator.userAgent;
				if (ua.indexOf('iPhone') > 0 || (ua.indexOf('Android') > 0) && (ua.indexOf('Mobile') > 0)) {
					//alert("aaaa");
					if( navigator.geolocation ) {
						// 現在位置を取得できる場合の処理
						//alert( "あなたの端末では、現在位置を取得することができます。" );

						// 現在地を取得
						navigator.geolocation.getCurrentPosition(

							// [第1引数] 取得に成功した場合の関数
							function( position ){
								// 取得したデータの整理
								var data = position.coords ;

								// データの整理
								var lat = data.latitude ;
								var lng = data.longitude ;
								var alt = data.altitude ;
								var accLatlng = data.accuracy ;
								var accAlt = data.altitudeAccuracy ;
								var heading = data.heading ;			//0=北,90=東,180=南,270=西
								var speed = data.speed ;

								location.href = name + "?latitudecd=" + lat + "&longitudecd=" + lng;
								// アラート表示
								//alert( "あなたの現在位置は、\n[" + lat + "," + lng + "]\nです。" ) ;
								//alert( "あなたの現在位置は、\n[" + lat + "," + lng + "]\nです。" );

								// HTMLへの書き出し
								///document.getElementById( 'result' ).innerHTML = '<dl><dt>緯度</dt><dd>' + lat + '</dd><dt>経度</dt><dd>' + lng + '</dd><dt>高度</dt><dd>' + alt + '</dd><dt>緯度、経度の精度</dt><dd>' + accLatlng + '</dd><dt>高度の精度</dt><dd>' + accAlt + '</dd><dt>方角</dt><dd>' + heading + '</dd><dt>速度</dt><dd>' + speed + '</dd></dl>' ;
							},

							// [第2引数] 取得に失敗した場合の関数
							function( error ){
								// エラーコード(error.code)の番号
								// 0:UNKNOWN_ERROR				原因不明のエラー
								// 1:PERMISSION_DENIED			利用者が位置情報の取得を許可しなかった
								// 2:POSITION_UNAVAILABLE		電波状況などで位置情報が取得できなかった
								// 3:TIMEOUT					位置情報の取得に時間がかかり過ぎた…

								// エラー番号に対応したメッセージ
								var errorInfo = [
									"原因不明のエラーが発生しました…。" ,
									"位置情報の取得が許可されませんでした…。" ,
									"電波状況などで位置情報が取得できませんでした…。" ,
									"位置情報の取得に時間がかかり過ぎてタイムアウトしました…。"
								] ;

								// エラー番号
								var errorNo = error.code ;

								// エラーメッセージ
								var errorMessage = "[エラー番号: " + errorNo + "]\n" + errorInfo[ errorNo ] ;

								// アラート表示
								alert( errorMessage ) ;

								// HTMLに書き出し
								///document.getElementById("result").innerHTML = errorMessage;
							} ,

							// [第3引数] オプション
							{
								"enableHighAccuracy": true,
								"timeout": 8000,
								"maximumAge": 2000,
							}

						);
					}else {
						// 現在位置を取得できない場合の処理
						alert( "あなたの端末では、現在位置を取得できません。" );
					}
				}else {
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

		<h1>避難マップくん</h1>
		<div class="wrap">
			<span class="decor"></span>
			<header>
				<nav>
					<ul class="primary">
						<!-- メニュータイトル　地域防災拠点 -->
						<li>
							<a href="" class="no-menu">地域防災拠点</a>
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
							<a href="" class="no-menu">一時滞在施設</a>
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
							<a href="" class="no-menu">津波避難施設</a>
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
							<a href="" class="no-menu">位置情報・検索</a>
							<ul class="sub">
								<li><a href="" id="test" onclick="getElement('call_back.php'); return false;">防災拠点・一時滞在施設</a></li>
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

			<?php
				$page = "防災チュートリアル";
			?>
			
			<main>
				<div class="box">
					<h2>防災対策</h2>
					<p>知りたい対策情報を選んでください</p>
				
					<ul>
						<li>
							<a id="square_btn" href="./refuge_knowledge.php?mode=1" style="background-color:#008000; margin-left:30px;">暴風</a>
						</li>
					
						<li>
							<a id="square_btn" href="./refuge_knowledge.php?mode=2" style="background-color: #4682b4 /*ボタン色*/">豪雨</a>
						</li>
				
						<li>
							<a id="square_btn" href="./refuge_knowledge.php?mode=3"style="background-color: #6495ed /*ボタン色*/">豪雪</a>
						</li>
				
						<li>
							<a id="square_btn" href="./refuge_knowledge.php?mode=4"style="background-color: #5f9ea0 /*ボタン色*/">洪水</a>
						</li>
				
						<li>
							<a id="square_btn" href="./refuge_knowledge.php?mode=5"style="background-color: #8b4513 /*ボタン色*/">地震</a>
						</li>
				
						<li>
							<a id="square_btn" href="./refuge_knowledge.php?mode=6"style="background-color: #800080/*ボタン色*/">津波</a>
						</li>
				
						<li>
							<a id="square_btn" href="./refuge_knowledge.php?mode=7"style="background-color: #ff4500 /*ボタン色*/">火災</a>
						</li>
					</ul>
				
					<div id="info">
						<?php
							if($mode != null){
								switch ($mode) {
									case '1':
										echo "<h2><span style='border-bottom: double 6px #008000;'>暴風のとき</span></h2>";
										echo "<div style='padding: 10px; margin: 20px; border: 5px dashed #008000;border-radius: 20px;'><h2>説明</h2><p>外出は避けましょう、外出中の場合は近くの建物に避難しましょう。
										<br>さらに、停電や断水に備えて懐中電灯の準備やお風呂への水張りをしましょう。</p></div>";
										break;
									case '2':
										echo "<h2><span style='border-bottom: double 6px #4682b4;'>豪雨のとき</span></h2>";
										echo "<div style='padding: 10px; margin: 20px; border: 5px dashed #4682b4;border-radius: 20px;'><h2>説明</h2><p>道路が冠水していたら建物の上階に避難しましょう。
										<br>また、地下街やアンダーパス(地下立体交差点)などを通るのは避けましょう。</p></div>";
										break;
									case '3':
										echo "<h2><span style='border-bottom: double 6px #6495ed;'>豪雪のとき</span></h2>";
										echo "<div style='padding: 10px; margin: 20px; border: 5px dashed #6495ed;border-radius: 20px;'><h2>説明</h2><p>外出は避けましょう
										<br>さらに、停電や断水に備えて懐中電灯の準備やお風呂への水張りをしましょう。</p></div>";
										break;
									case '4':
										echo "<h2><span style='border-bottom: double 6px #5f9ea0;'>洪水のとき</span></h2>";
										echo "<div style='padding: 10px; margin: 20px; border: 5px dashed #5f9ea0;border-radius: 20px;'><h2>説明</h2><p>河川からすぐに離れましょう。
										<br>また、浸水している場所は、傘や棒などで地面を探りながら移動しましょう。</p></div>";
										break;
									case '5':
										echo "<h2><span style='border-bottom: double 6px #8b4513;'>地震のとき</span></h2>";
										echo "<div style='padding: 10px; margin: 20px; border: 5px dashed #8b4513;border-radius: 20px;'><h2>説明</h2><p>1. 揺れが収まってから、火を消す。
										<br>2. むやみに外へ飛び出さない
										<br>3. 机やテーブルの下に入る
										<br>4. 揺れが収まるまで、じっとしている
										<br>5. 大きな災害の可能性がある場合は、正確な情報や指示があるまでむやみに避難行動に移らない
										<br>6. 揺れが収まり余裕があったら、ガスの元栓、ブレーカーなどを止めて避難する
										<br>7. 運転中は、緊急車両の邪魔にならないよう道路の端に停車し、クルマにキーをつけたまま避難する
										<br>8. 街中ではビルや高速道路など倒壊する可能性のある構造物から離れる
										<br>9. 野外では、崖崩れや川の増水に注意して指定された安全な場所へ避難する
										<br>10. 避難警報などが解除されるまで移動しない</p></div>";
										break;
									case '6':
										echo "<h2><span style='border-bottom: double 6px #800080;'>津波のとき</span></h2>";
										echo "<div style='padding: 10px; margin: 20px; border: 5px dashed #800080;border-radius: 20px;'><h2>説明</h2><p>高台や山へ避難しましょう。</p>";
										break;
									case '7';
										echo "<h2><span style='border-bottom: double 6px #ff4500;'>火災のとき</span></h2>";
										echo "<div style='padding: 10px; margin: 20px; border: 5px dashed  #ff4500;border-radius: 20px;'><h2>説明</h2><p>1. まず、119番通報をしましょう。
										<br>2. 次に、消火器で消火を行いましょう。
										<br>3. 火が天井まで昇っていたら消化を諦め、すぐに避難しましょう。</p></div>";
										break;
								}
							}
						?>
					</div>
				</div>
			</main>
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