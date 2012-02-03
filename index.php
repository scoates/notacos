<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="wf-loading" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>No Tacos!</title>
	</head>
	<body>
<?php
if (isset($_POST['tacoed'])) {
	echo '<div><p>Un tacoed!</p><p>' . htmlentities(preg_replace_callback(
		'!http://t\.co/[a-z0-9]+!i',
		function ($tacoUrl) {
			$opts = array(
				'http' => array(
					'timeout' => 14,
					'method' => 'HEAD',
					'content' => null, 
					'user_agent' => 'NoTaco!/0.1',
					'ignore_errors' => true,
				),
			);
			$context = stream_context_create($opts);
			$stream = @fopen($tacoUrl[0], 'r', false, $context);
			if ($stream) {
				$meta = stream_get_meta_data($stream);
				foreach ($meta['wrapper_data'] as $header) {
					if (strpos($header, ': ') === false) {
						continue;
					}
					list($name, $val) = explode(': ', $header);
					if ('Location' === $name) {
						return $val;
					}
				}
			}
			return $tacoUrl[0];
		},
		$_POST['tacoed']
	), ENT_QUOTES, 'UTF-8') . "</p></div>\n";
}
?>
<div>
 <p>Text to untaco:</p>
 <form method="POST" action="<?php echo htmlentities($_SERVER['SCRIPT_NAME'], ENT_QUOTES, 'UTF-8');?>">
  <textarea name="tacoed" cols="80" rows="2"></textarea>
  <input type="submit" value="De-taco!"/>
 </form>
</div>
(see <a href="https://github.com/scoates/notacos">notacos on github</a>)
</body></html>

