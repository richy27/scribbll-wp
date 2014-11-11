<?php
error_reporting(0);
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');
if (!is_user_logged_in() || !wp_verify_nonce($_GET['nonce'], 'ajax-nonce')) { die(); }

$url = esc_url_raw('http' . $_GET['url']);

if ($url != 'http://http') {
	$response = wp_remote_get($url);

	if(is_wp_error($response)) {
	   echo 'error' . $response->get_error_message();
	} else {
		preg_match("/content=\"text\/(.*)>/i", $response['body'], $content_type);

		if (strpos($response['headers']['content-type'], 'text/') !== false && ($response['response']['code'] == '200')) {
			preg_match("/<title>(.*?)<\/title>/is", $response['body'], $title);

			//for multiple languages in title ref: http://php.net/manual/en/function.htmlentities.php
			if (strpos($content_type[0], '8859-1') !== false) {
				$pinclonetitle = '<pinclonetitle>' . htmlentities($title[1], ENT_QUOTES, 'ISO-8859-1') . '</pinclonetitle>';
			} else if (strpos($content_type[0], '8859-5') !== false) {
				$pinclonetitle = '<pinclonetitle>' . htmlentities($title[1], ENT_QUOTES, 'ISO-8859-5') . '</pinclonetitle>';
			} else if (strpos($content_type[0], '8859-15') !== false) {
				$pinclonetitle = '<pinclonetitle>' . htmlentities($title[1], ENT_QUOTES, 'ISO-8859-15') . '</pinclonetitle>';
			} else if (strpos($content_type[0], '866') !== false) {
				$pinclonetitle = '<pinclonetitle>' . htmlentities($title[1], ENT_QUOTES, 'cp866') . '</pinclonetitle>';
			} else if (strpos($content_type[0], '1251') !== false) {
				$pinclonetitle = '<pinclonetitle>' . htmlentities($title[1], ENT_QUOTES, 'cp1251') . '</pinclonetitle>';
			} else if (strpos($content_type[0], '1252') !== false) {
				$pinclonetitle = '<pinclonetitle>' . htmlentities($title[1], ENT_QUOTES, 'cp1252') . '</pinclonetitle>';
			} else if (stripos($content_type[0], 'koi8') !== false) {
				$pinclonetitle = '<pinclonetitle>' . htmlentities($title[1], ENT_QUOTES, 'KOI8-R') . '</pinclonetitle>';
			} else if (stripos($content_type[0], 'hkscs') !== false) {
				$pinclonetitle = '<pinclonetitle>' . mb_convert_encoding($title[1], 'UTF-8', 'BIG5-HKSCS') . '</pinclonetitle>';
			} else if (stripos($content_type[0], 'big5') !== false || strpos($content_type[0], '950') !== false ) {
				$pinclonetitle = '<pinclonetitle>' . mb_convert_encoding($title[1], 'UTF-8', 'BIG5') . '</pinclonetitle>';
			} else if (strpos($content_type[0], '2312') !== false || strpos($content_type[0], '936') !== false ) {
				$pinclonetitle = '<pinclonetitle>' . mb_convert_encoding($title[1], 'UTF-8', 'GB2312') . '</pinclonetitle>';
			} else if (stripos($content_type[0], 'jis') !== false || strpos($content_type[0], '932') !== false ) {
				$pinclonetitle = '<pinclonetitle>' . mb_convert_encoding($title[1], 'UTF-8', 'Shift_JIS') . '</pinclonetitle>';
			} else if (stripos($content_type[0], 'jp') !== false) {
				$pinclonetitle = '<pinclonetitle>' . mb_convert_encoding($title[1], 'UTF-8', 'EUC-JP') . '</pinclonetitle>';
			} else {
				$pinclonetitle = '<pinclonetitle>' . htmlentities($title[1], ENT_QUOTES, 'UTF-8') . '</pinclonetitle>';
			}
								
			$body = '<!DOCTYPE html><html><head><title>&nbsp;</title><meta charset="UTF-8" /></head><body>';
			$body .= $pinclonetitle;

			$dom = new domDocument;
			libxml_use_internal_errors(true);
			$dom->loadHTML($response['body']);
			$dom->preserveWhiteSpace = false;
			$images = $dom->getElementsByTagName('img');

			foreach ($images as $image) {
				$prepend = '';
				if (substr($image->getAttribute('src'), 0, 2) == '//') {
					$prepend = 'http:';
				}
				$body .= '<a href="#"><img src="' . $prepend . $image->getAttribute('src') .'" /></a>';
			}

			$body .= '</body></html>';
			$body = absolute_url($body, parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST));
			
			echo $body;
		} else {
			echo 'error' . __('Invalid content', 'pinclone');
		}
	}
} else {
	echo 'error' . __('Invalid content', 'pinclone');
}

//convert relative to absolute path for images
//ref: http://www.howtoforge.com/forums/showthread.php?t=4
function absolute_url($txt, $base_url){
  $needles = array('src="');
  $new_txt = '';
  if(substr($base_url,-1) != '/') $base_url .= '/';
  $new_base_url = $base_url;
  $base_url_parts = parse_url($base_url);

  foreach($needles as $needle){
    while($pos = strpos($txt, $needle)){
      $pos += strlen($needle);
      if(substr($txt,$pos,7) != 'http://' && substr($txt,$pos,8) != 'https://' && substr($txt,$pos,6) != 'ftp://' && substr($txt,$pos,9) != 'mailto://'){
        if(substr($txt,$pos,1) == '/') $new_base_url = $base_url_parts['scheme'].'://'.$base_url_parts['host'];
        $new_txt .= substr($txt,0,$pos).$new_base_url;
      } else {
        $new_txt .= substr($txt,0,$pos);
      }
      $txt = substr($txt,$pos);
    }
    $txt = $new_txt.$txt;
    $new_txt = '';
  }
  return $txt;
}
?>