<?php
/*
copyright @ medantechno.com
Modified @ Farzain - zFz
2017

*/

require_once('./line_class.php');
require_once('./unirest-php-master/src/Unirest.php');

$channelAccessToken = 'MNt6FGRmt+ESXa5UGmjKss/wccOI2ckP/I2Y3EXeRC8joAxfcyhIs/hPACvNNhAi6D9sPg8K74OnslPFAQo71QNQZiO2U9InYmsovD02eNpxuwVAicKZhY7InCbx/2sJvCkYLWIzsFRKHG0mB1GduQdB04t89/1O/w1cDnyilFU='; //sesuaikan 
$channelSecret = '51d48d6d030cea18cc66f016a65bb2aa';//sesuaikan

$client = new LINEBotTiny($channelAccessToken, $channelSecret);

$userId 	= $client->parseEvents()[0]['source']['userId'];
$groupId 	= $client->parseEvents()[0]['source']['groupId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp	= $client->parseEvents()[0]['timestamp'];
$type 		= $client->parseEvents()[0]['type'];

$message 	= $client->parseEvents()[0]['message'];
$messageid 	= $client->parseEvents()[0]['message']['id'];

$profil = $client->profil($userId);

$pesan_datang = explode(" ", $message['text']);

$command = $pesan_datang[0];
$options = $pesan_datang[1];
if (count($pesan_datang) > 2) {
    for ($i = 2; $i < count($pesan_datang); $i++) {
        $options .= '+';
        $options .= $pesan_datang[$i];
    }
}

#-------------------------[Function]-------------------------#
function shalat($keyword) {
    $uri = "https://time.siswadi.com/pray/" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Jadwal Shalat Sekitar ";
	$result .= $json['location']['address'];
	$result .= "\nTanggal : ";
	$result .= $json['time']['date'];
	$result .= "\n\nShubuh : ";
	$result .= $json['data']['Fajr'];
	$result .= "\nDzuhur : ";
	$result .= $json['data']['Dhuhr'];
	$result .= "\nAshar : ";
	$result .= $json['data']['Asr'];
	$result .= "\nMaghrib : ";
	$result .= $json['data']['Maghrib'];
	$result .= "\nIsya : ";
	$result .= $json['data']['Isha'];
    return $result;
}
#-------------------------[Function]-------------------------#

# require_once('./src/function/search-1.php');
# require_once('./src/function/download.php');
# require_once('./src/function/random.php');
# require_once('./src/function/search-2.php');
# require_once('./src/function/hard.php');

//show menu, saat join dan command /menu
if ($type == 'join' || $command == '/menu') {
    $text = "Assalamualaikum Kakak Kakak Member Nipam, aku adalah bot Arsip Nipam yang bisa memunculkan Apa saja tentang data-data Member & Alumni Nipam, Ketik saja HELP untuk bantuan & Kata Kunci untuk Keyword bot ini. Have Fun :) ";
    $balas = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}

//pesan bergambar
if($message['type']=='text') {
	    if ($command == '/shalat') {

        $result = shalat($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }

}else if($message['type']=='sticker')
{	
	$balas = array(
							'replyToken' => $replyToken,														
							'messages' => array(
								array(
										'type' => 'Sticker',									
										'text' => 'Jangan pake itu stiker, dapet hadiah Kuis di Konkow jg Lo :v '										
									
									)
							)
						);
						
}
if (isset($balas)) {
    $result = json_encode($balas);
//$result = ob_get_clean();

    file_put_contents('./balasan.json', $result);


    $client->replyMessage($balas);
}
?>
