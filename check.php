<?php

require('simple_html_dom.php');
require('config.php');

$cache = dirname(__FILE__).'/katataxi.txt';

$form = 'http://www.stratologia.gr/proepiskophsh_stoixoivn';
$data = array(
	'mode' => 'katataxi',
	'asg' => $user['asg'],
	'asm' => $user['asm'],
	'klash' => $user['klash'],
	'lastname' => $user['lastname'],
	'firstname' => $user['firstname'],
	'yearofbirth' => $user['yearofbirth'],
	'pskForm' => true,
);

$ch = curl_init($form);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

$response['data'] = curl_exec($ch);
$response['info'] = curl_getinfo($ch);

$html = str_get_html($response['data']);

$katataxi = $html->find('div#enhmKatataxisDiv', 0);

$katataxiText = trim(preg_replace('/\s+/', ' ', $katataxi->plaintext));

$fileData = file_get_contents($cache);

if($fileData !== $katataxiText) {
	
	$to      = $mail['to'];
	$subject = 'ARMY STATUS';
	$message = $katataxiText;
	$headers = 'From: ' . $mail['from'] . "\r\n" . 'Reply-To: ' . $mail['from'] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);

	echo 'Status changed: '.$katataxiText;
	
	file_put_contents($cache, $katataxiText);
} else {
	echo 'Status is the same: '.$katataxiText;
}

