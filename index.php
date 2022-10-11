<?php
$file = 'log.log';
// Open the file to get existing content
$current = file_get_contents($file);
$s = $current . "\r\n" . 'Req: ' . $_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REQUEST_URI'];
file_put_contents($file, $s);

if(!$url){
	header('HTTP/1.1 400 Bad Request');
	die;
}

$d = hex2bin($url);
function getUrlMimeType($url) {
    $buffer = file_get_contents($url);
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    return $finfo->buffer($buffer);
}

$opts = array(
  'http'=>array(
    'method'=>"GET",
    "header" => "Accept: */*\r\n" .
    "User-agent: image-proxy/2.0"
  )
);

$context = stream_context_create($opts);

date_default_timezone_set('Europe/Berlin'); // Change this to your own timezone
$current_date = date('H:i:s - d/m/Y');
$mimetypes = ['image/png','image/jpg','image/jpeg','image/gif'];

$url = htmlentities($_GET['url']);
$mime = getUrlMimeType($url);

foreach($mimetypes as $mimetype) {
	if ($mime == $mimetype){
		header('Content-type: '.$mime.';');
		if(isset($url)){
			echo file_get_contents($url, false, $context);
			die;
		}
	}
} 

header('HTTP/1.1 502 Bad Gateway');
