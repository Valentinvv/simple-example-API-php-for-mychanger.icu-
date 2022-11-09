<?php
//The url you wish to send the POST request to
$ch = '';
global $ch;
if (function_exists('curl_init')) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5); 
//	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	global $_cfg;
	if ($_cfg['Sec_ProxyHost'])
	{
		curl_setopt($ch, CURLOPT_PROXY, $_cfg['Sec_ProxyHost']);
		if ($_cfg['Sec_ProxyAuth'])
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $_cfg['Sec_ProxyAuth']);
	}
} else
	$ch = -1;

if ($_POST['action'] == 'GET_BALANCE'){
	$data = get_data_auth();
	$r = array(
		'action' => 'GET_BALANCE',
		'm_shop' => $data[2]
		);
	$o1 = implode(':', $r + array($data[1]));       
	$sc = md5($o1);		
	$r['m_sign'] = md5($o1);
	$data_send = json_encode($r);
	$res = json_decode(inet_request($data[0],$data_send)); 
	echo '<pre>'; 
	print_r($res);
	echo '</pre>';	
}

if ($_POST['action'] == 'check_oper'){
	$data = get_data_auth();
	$r = array(
		'action' => 'check_oper',
		'm_shop' => $data[2],
		'm_order_id' => $a['m_order_id']
		);
	$o1 = implode(':', $r + array($data[1]));       
	$sc = md5($o1);		
	$r['m_sign'] = md5($o1);
	$data_send = json_encode($r);
	$res = json_decode(inet_request($data[0],$data_send)); 
	echo '<pre>'; 
	print_r($res);
	echo '</pre>';
}

if ($_POST['action'] == 'GET_STATUS'){
	$data = get_data_auth();
	$r = array(
		'action' => 'GET_STATUS',
		'm_shop' => $data[2],
		'm_order_id' => $a['m_order_id']
		);
	$o1 = implode(':', $r + array($data[1]));       
	$sc = md5($o1);		
	$r['m_sign'] = md5($o1);
	$data_send = json_encode($r);
	$res = json_decode(inet_request($data[0],$data_send)); 
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 

}

if ($_POST['action'] == 'GET_BALANCE'){
	$data = get_data_auth();
	$r = array(
		'action' => 'GET_BALANCE',
		'm_shop' => $data[2]
		);
	$o1 = implode(':', $r + array($data[1]));       
	$sc = md5($o1);		
	$r['m_sign'] = md5($o1);
	$data_send = json_encode($r);	
	$res = json_decode(inet_request($data[0],$data_send)); 
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 
	
}

if ($_POST['action'] == 'Withdraw'){
	$data = get_data_auth();
	$r = array(
		'action' => 'Withdraw',
		'm_shop' => $data[2],
		'm_order_id' => $_POST['order_id'],
		'm_amount' => $_POST['sum'],
		'm_curr' => 'SBR',	
		'cart' => $_POST['cart'],
		'm_desc' => $_POST['desc']
		);
	$o1 = implode(':', $r + array($data[1]));       
	$sc = md5($o1);		
	$r['m_sign'] = md5($o1);
	$data_send = json_encode($r);
	$res = json_decode(inet_request($data[0],$data_send)); 
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 
}

if ($_POST['action'] == 'prepareSCI'){
	$data = get_data_auth();
	if (!$_POST['desc']) $_POST['desc']='SUPER';
	$r = array(
		'action' => 'prepareSCI',
		'm_shop' => $data[2],
		'm_order_id' => $_POST['order_id'],
		'm_amount' => $_POST['sum'],
		'm_curr' => 'SBR',		
		'm_desc' => $_POST['desc']
		);
	$o1 = implode(':', $r + array($data[1]));       
	$sc = md5($o1);		
	$r['m_sign'] = md5($o1);
	$data_send = json_encode($r);	
	$res = json_decode(inet_request($data[0],$data_send)); 
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 	
}


//url-ify the data for the POST
function send_data($url, $data_send){
	//open connection
	if (strlen($data_send) <1) return 0;
	$ch = curl_init();
	$fields_string = http_build_query($data_send);

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	//So that curl_exec returns the contents of the cURL; rather than echoing it
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

	//execute post
	$result = curl_exec($ch);
}

function get_data_auth(){
	$api_key = '049048-Das8mXtvkbOlMdLpfzyJ12GeYSrN94ATRujwChKIWZ6UQVinF573E0xBgHqc-niX';	
	$id_shop = 10;
	$url = 'https://mychanger.icu/api_interface';
	return array($url, $api_key, $id_shop);
}





function inet_request($url, $par = array(), $cookiefl = '', $agent = '', $onlyheader = false) {
	global $ch;
	try {
		if ($ch < 0) xAbort();
		curl_setopt($ch, CURLOPT_URL, trim($url));
		curl_setopt($ch, CURLOPT_HEADER, $onlyheader);
		curl_setopt($ch, CURLOPT_USERAGENT, ($agent ? $agent : 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13'));
		if ($onlyheader) 
			curl_setopt($ch, CURLOPT_NOBODY, true);
		elseif (empty($par)) 
			curl_setopt($ch, CURLOPT_HTTPGET, true);
		else {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $par);
		}
		@curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefl);
		@curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefl);
		$answ = curl_exec($ch);
		if (curl_errno($ch) != 0) $answ = false;
	} catch (Exception $e) {
		$answ = false;
	}
	return $answ;
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Оплата заказа</title>
	<link rel="stylesheet" type="text/css" href="css/form.css">
	<style>
		pre {text-align: initial;}
	</style>
</head>
<body>
	<a href='https://a1.mychanger.icu/index2.php'><button>Очистить форму</button></a>
	<div class="container">
		<form method='post'>
		<input type='hidden' name='action' value='check_oper'>
			<h1>Проверить баланс</h1>
			<h3>Введите Ваш уникальный номер операции для проверки<br/><span><input type='text' name='m_order_id'></span></h3>
			<div class="button">
				<button type="submit" class="btn">Выполнить </button>
			
			</div>
		</form>
	</div>
	<div class="container">
		<form method='post'>
		<input type='hidden' name='action' value='GET_BALANCE'>
			<h1>Проверить баланс</h1>
			<div class="button">
				<button type="submit" class="btn">Выполнить </button>
			
			</div>
		</form>
	</div>
	<div class="container">
		<form method='post'>
		<input type='hidden' name='action' value='Withdraw'>
			<h1>Заявка на вывод</h1>			
			<h3>Введите сумму для вывода <br/><span><input type='text' name='sum'></span></h3>
			<hr>
			<h3>Введите Ваш уникальный номер операции <br/><span><input type='text' name='order_id'></span></h3>
			<hr>
			<h3>Введите адрес кошелька получателя <br/><span><input type='text' name='cart'></span></h3>
			<hr>
			<h3>Введите описание  дискрипшин<br/><span><input type='text' name='desc'></span></h3>
			
			<hr>
			<div class="button">
				<button type="submit" class="btn">Выполнить </button>
			
			</div>
		</form>
	</div>
	<div class="container">
		<form method='post'>
		<input type='hidden' name='action' value='Withdraw'>
			<h1>Заявка на вывод</h1>
			<a href='https://a1.mychanger.icu/index2.php'><button>Очистить форму</button></a>
			<h3>Введите сумму для вывода <br/><span><input type='text' name='sum'></span></h3>
			<hr>
			<h3>Введите Ваш уникальный номер операции <br/><span><input type='text' name='order_id'></span></h3>
			<hr>
			<h3>Введите адрес кошелька получателя <br/><span><input type='text' name='cart'></span></h3>
			<hr>
			<h3>Введите описание  дискрипшин<br/><span><input type='text' name='desc'></span></h3>
			
			<hr>
			<div class="button">
				<button type="submit" class="btn">Выполнить </button>
			
			</div>
		</form>
	</div>
	
	<div class="container">
		<form method='post'>
		<input type='hidden' name='action' value='prepareSCI'>
			<h1>Заявка на пополнение</h1>
			
			<div class="card_box">

			</div>
			<h3>Введите сумму пополнения <br/><span><input type='text' name='sum'></span></h3>
			<hr>
			<h3>Введите описание  дискрипшин<br/><span><input type='text' name='desc'></span></h3>
			
			<hr>
			<div class="button">
				<button type="submit" class="btn">Выполнить </button>
			
			</div>
		</form>
	</div>
</body>
</html>