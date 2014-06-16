<?php
// show all warning and errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// the maximum execution time, in seconds. If set to zero, no time limit is imposed.
set_time_limit(0);

// module
if(isset($_GET['route'])) {
	$module = $_GET['route'];
} else {
	$module = 'main';
}

// route
$route = $module.'/index.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="css/main.css" />
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<title>Twitter Digger</title>
</head>

<body>
	<div class="container">
		<nav class="navbar navbar-inverse" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="./">Twitter Digger</a>
				</div>
			</div><!-- /.container-fluid -->
		</nav>

		<div class="row">
			<div class="col-md-3">
				<div class="well well-sm">
					<ul class="nav nav-pills nav-stacked" id="yw1">
						<li<?php echo ($module == 'main')?' class="active"':'';?>><a href="index.php"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
						<li<?php echo ($module == 'collect-tweets')?' class="active"':'';?>><a href="index.php?route=collect-tweets"><span class="glyphicon glyphicon-floppy-disk"></span> Collect Tweets</a></li>
						<li<?php echo ($module == 'term-frequency')?' class="active"':'';?>><a href="index.php?route=term-frequency"><span class="glyphicon glyphicon-signal"></span> Term Frequency</a></li>
						<li<?php echo ($module == 'burstiness')?' class="active"':'';?>><a href="index.php?route=burstiness"><span class="glyphicon glyphicon-fire"></span> Burstiness</a></li>
						<li<?php echo ($module == 'named-entities')?' class="active"':'';?>><a href="index.php?route=named-entities"><span class="glyphicon glyphicon-th-list"></span> Named Entities</a></li>
						<li<?php echo ($module == 'content-quality')?' class="active"':'';?>><a href="index.php?route=content-quality"><span class="glyphicon glyphicon-ok"></span> Content Quality</a></li>
						</ul>
				</div>
			</div>
			
			<div class="col-md-9">
				<?php include($route);?>
			</div>
		</div>

		<div id="footer">
			Copyright &copy; 2014
		</div><!-- footer -->
		
	</div><!-- page -->
	
</body>
</html>