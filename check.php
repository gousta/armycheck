<?php require('simple_html_dom.php');

$cache = dirname(__FILE__).'/katataxi.txt';

$form = 'http://www.stratologia.gr/proepiskophsh_stoixoivn';
$data = array(
	'mode' => 'katataxi',
	'asg' => '154',
	'asm' => '002801',
	'klash' => '2011',
	'lastname' => 'ΓΙΟΥΛΔΑΣΗΣ',
	'firstname' => 'ΕΥΣΤΡΑΤΙΟΣ',
	'yearofbirth' => '1990',
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

/*
$asg = $html->find('input[name=asg]', 0);
$asm = $html->find('input[name=asm]', 0);
$klash = $html->find('input[name=klash]', 0);
$lastname = $html->find('input[name=lastname]', 0);
$firstname = $html->find('input[name=firstname]', 0);
$yearofbirth = $html->find('input[name=yearofbirth]', 0);

$nomarxia = $html->find('input[name=nomarxiaMaLektiko]', 0);
$ota = $html->find('input[name=otaMaLektiko]', 0);
$diamerisma = $html->find('input[name=diamMaLektiko]', 0);
*/

$katataxi = $html->find('div#enhmKatataxisDiv', 0);

/*
echo 'Στρατιωτικός Αριθμός (ΣΑ): '.$asg->value.'/'.$asm->value.'/'.$klash->value;
echo '<br/>';
echo 'Νομαρχία: '.$nomarxia->value;
echo '<br/>';
echo 'Δήμος: '.$ota->value;
echo '<br/>';
echo 'Τοπικό Διαμέρισμα: '.$diamerisma->value;
echo '<hr/>';
echo $katataxi->plaintext;
*/

$katataxiText = trim(preg_replace('/\s+/', ' ', $katataxi->plaintext));

$fileData = file_get_contents($cache);

if($fileData !== $katataxiText) {
	
	$to      = 'giouldasis.stratos@gmail.com';
	$subject = 'ARMY STATUS';
	$message = $katataxiText;
	$headers = 'From: mail@giouldasis-stratos.com' . "\r\n" .
	'Reply-To: mail@giouldasis-stratos.com' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);

	echo 'Status changed: '.$katataxiText;
	
	file_put_contents($cache, $katataxiText);
} else {
	echo 'Status is the same: '.$katataxiText;
}

