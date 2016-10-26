<!doctype>
<html>
<head>
<title>Transcoder</title>
</head>
<body>
<h1>Transcoder</h1>
<h2>Files</h2>
<ul>
    <?php foreach ($files as $file): if (in_array($file, ['.','..','.gitkeep'])) continue; ?>
    <li><a target="_blank" href="export/<?php echo $file ?>"><?php echo $file ?></a></li>
    <?php endforeach ?>
</ul>
<br>
<h2>Paste a link of file to transcode (dropbox, mediafire) pls make sure it's a direct link.</h2>
<p><?php echo $message ?></p>
<form action="" method="POST">
	<input type="hidden" name="submitted" value="1">
	<input type="text" name="url" style="width:100%">
	<br>
	<input type="submit">
</form>
<script>
    
</script>
</body>
</html>