<?php 
	$playlist = isset($_REQUEST["playlist"])?$_REQUEST["playlist"]:NULL;
	$shuffle = isset($_REQUEST["shuffle"])?$_REQUEST["shuffle"]:NULL;
	$bysize = isset($_REQUEST["bysize"])?$_REQUEST["bysize"]:NULL;

	function sizefile($size){
		if($size>=0 && $size <=1023){
			return $size." b";
		}elseif ($size>=1024 && $size<=1048575) {
			return round($size/1024 , 2)." kb";
		}elseif ($size>=1048576) {
			return round($size/1048576 , 2)." mb";
		}
	}

	function merge_sort($my_array){
	if(count($my_array) == 1 ) {
		return $my_array;
	}
	$mid = count($my_array) / 2;
    $left = array_slice($my_array, 0, $mid);
    $right = array_slice($my_array, $mid);
	$left = merge_sort($left);
	$right = merge_sort($right);
	return merge($left, $right);
	}
	function merge($left, $right){
	$res = array();
	while (count($left) > 0 && count($right) > 0){
		if(filesize($left[0]) > filesize($right[0])){
			$res[] = $right[0];
			$right = array_slice($right , 1);
		}else{
			$res[] = $left[0];
			$left = array_slice($left, 1);
		}
	}
	while (count($left) > 0){
		$res[] = $left[0];
		$left = array_slice($left, 1);
	}
	while (count($right) > 0){
		$res[] = $right[0];
		$right = array_slice($right, 1);
	}
	return $res;
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Music Viewer</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="viewer.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div id="header">

			<h1>190M Music Playlist Viewer</h1>
			<h2>Search Through Your Playlists and Music</h2>
			<p><a href="music.php">Back to Home</a></p>
		</div>


		<div id="listarea">
			<ul id="musiclist">
				<?php 
					if(isset($playlist)){
						$folder=file("songs/".$playlist,FILE_IGNORE_NEW_LINES);
					}else {
						if($shuffle=="on"){
							$folder = glob("songs/*");
							shuffle($folder);
						}elseif ($bysize=="on") {
							$folder = glob("songs/*");
							$folder = merge_sort($folder);
						}else{
							$folder = glob("songs/*");
						}
					}
					foreach($folder as $filename) {
						if (strstr($filename, ".mp3")) {
							$text = basename($filename);
							?>
								<li class="mp3item"><a href="<?= "songs/".$text ?>">
								<?= $text ?></a>(<?= sizefile(filesize("songs/".$text)) ?>)</li>
							<?php 
						} 
						?>
					<?php
					}
					foreach ($folder as $filename) {
						if (strstr($filename, ".m3u")) {
							$text = basename($filename);
						?> 
							<li class="playlistitem"><a href="<?= "music.php?playlist=".$text ?>">
								<?= $text ?></a></li>
						<?php 
						}
					}
				
				?>
			</ul>
		</div>
	</body>
</html>