<?php
error_reporting(0);

$url = htmlentities($_GET['url']);

$opts = array(
  'http'=>array(
    'method'=>"GET",
    "header" => "Accept: */*\r\n" .
    "User-agent: image-proxy/2.0"
  )
);

$file = 'log.log';
if (str_contains($_SERVER['REMOTE_ADDR'], '10.10.10.') {
	$_SERVER['REMOTE_ADDR'] = $_SERVER['X_FORWARDED_FOR']
}
$current = file_get_contents($file);
$s = $current . "\r\n" . 'Request from: ' . $_SERVER['REMOTE_ADDR'] . ' to URL ' . $_SERVER['REQUEST_URI'] . ' (user agent: ' . $_SERVER['USER-AGENT'] . ')';
file_put_contents($file, $s);

$context = stream_context_create($opts);

if(!$url){
	header('HTTP/1.1 400 Bad Request');
	echo 'No URL!';
	die;
}

function getUrlMimeType($url) {
    $buffer = file_get_contents($url);
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    return $finfo->buffer($buffer);
}


date_default_timezone_set('Europe/Berlin'); // Change this to your own timezone
$current_date = date('H:i:s - d/m/Y');
$mimetypes = ['image/png','image/jpg','image/jpeg','image/gif'];
try {
	$mime = getUrlMimeType($url, false, $context);
} catch (Exception $e) {
	header('HTTP/1.1 403 Forbidden');
	echo 'Bad status code!';
	die;
}

foreach ($mimetypes as $mimetype) {
	if ($mime == $mimetype) {
		header('Content-type: '.$mime.';');
		if(isset($url)) {
			echo file_get_contents($url, false, $context);
			die;
		}
	}
} 

header('HTTP/1.1 502 Bad gateway');
echo 'Bad gateway';
