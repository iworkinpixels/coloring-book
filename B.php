<?
	$convert_path = '/usr/local/bin/convert';
	$page = "B/";
	$imgs = '/Users/tony/Desktop/coloring_book/images/';
	$img_path = '';
	
	$components = array('Background', 'Letter Inside', 'Letter Outside', 'Breadboard', 'Battery', 'Battery Stripe', 'Skin', 'Hair', 'Lips', 'Eyes', 'Glasses', 'Shirt');
	$components_lower = array();

	foreach($components as $c){
		$components_lower[] = strtolower(str_replace(' ','_',$c));
	}

	if ($_POST['form_btn']) {
		// Change to the main img dir
		chdir($imgs);
		// Make a dir for the new images
		$path = date('Y-m-d-H-i-s');
		exec('mkdir ./'.$path);
		exec('chmod 777 '.$path);

		foreach ($components_lower as $cl) {
			$command = "{$convert_path} {$page}{$cl}.png -background \"{$_POST[$cl]}\" -alpha Shape {$path}/{$cl}.png";
			exec($command);
		}
		chdir($imgs.$path);
		$command = "{$convert_path} ";
		foreach ($components_lower as $cl) {
			$command .= $cl.'.png ';
		}
		$command .= " ../{$page}lines.png -layers flatten ../completed/{$path}.png";
		exec($command);
		// Clean up
		chdir($imgs);
		exec("rm -rf ./{$path}");
		$img_path = '/images/completed/'.$path.'.png';
	}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Lady Ada's Coloring Book</title>
	<link rel="stylesheet" href="/css/screen.css"/>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script src="/js/main.js"></script>
	<script>
		var components = new Array();
		$(document).ready(function(){
			<? for($i=0; $i<count($components); $i++): ?>
				components[<?=$i?>]='<?=$components[$i]?>';
			<? endfor ?>
			currentComponent = components[0];
			$('div#msg-box').html('You have 10 seconds to choose a '+components[0]+' color!');
			setTimeout(nextComponent,10000);
		});

	</script>
</head>
<body onload="setup();">
	<? include('header.php'); ?>
	<div id="main">
		<? if (empty($img_path)): ?>
			<form method="post">
				<? for ($i = 0; $i < count($components); $i++): ?>
					<label for="<?=$components_lower[$i]?>"><?=$components[$i]?>:</label><input type="text" id="<?=$components_lower[$i]?>" name="<?=$components_lower[$i]?>"/><br/>
				<? endfor ?>
				<input type="submit" name="form_btn" id="form_btn" value="Draw picture!"/>
			</form>
			<div id="container">
				<img id="preview-img" src="/images/<?=$page?>/lines.png"/>
				<div id="right-side">
					<div id="msg-box">MESSAGE GOES HERE!</div>
					<div id="preview-box">#FF0000</div>
				</div>
			</div>
			<object type="application/Seriality" id="seriality" width="0" height="0"></object>
		<? else: ?>
			<script type="text/javascript">
				window.location = '<?=$img_path?>';
			</script>
		<? endif ?>
	</div>
</body>
</html>
