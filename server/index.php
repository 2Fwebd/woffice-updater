<?php // Array of versions available 
$data = file_get_contents('http://YOUR-URL.com/woffice-updater/json-data.json');
$data_decoded = json_decode($data, true); 

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<!-- PAGE TITLE -->
		<title>Woffice Updater - Get Woffice version now</title>
		<!-- MAKE IT RESPONSIVE -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- STYLESHEETS -->
		<link href="template/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="template/css/style.css" rel="stylesheet" media="screen">
		<!-- FONTS -->
		<link href='http://fonts.googleapis.com/css?family=Lato:900,300,400,200,800' rel='stylesheet' type='text/css'>
	</head>
	<!-- START BODY -->
	<body>
		<div id="page">
			
			<div id="wrapper">
	  	
				<header id="header">
					
					<a href="http://alka-web.com/woffice-updater/index.php">
						<img src="template/images/logo.png" alt="Woffice Logo">
						<span class="label label-success">Updater</span>
					</a>
					
				</header>
				
				<section id="form">
					
					<form action="theme-updater.php" method="post">
						
						<?php if ($_GET['result'] =='success') { ?>
							<div class="alert alert-success" role="alert">Thanks ! Your download is going to start in the next seconds. </div>
						<?php } elseif ($_GET['result'] =='error') { ?>
							<div class="alert alert-danger" role="alert">Sorry, the Username/Purchase code don't seem to be valid... Try again please.</div>
						<?php } elseif ($_GET['result'] =='empty') { ?>
							<div class="alert alert-danger" role="alert">Sorry, the fields are empty..</div>
						<?php } else {
							
						} ?>
						
						<div class="form-group">
							<label for="username">Envato (Themeforest) Username</label>
							<input type="text" class="form-control" id="username" name="username" placeholder="Your username: JohnDoe123">
	  					</div>
	  							
	  					<div class="form-group">
							<label for="purchase_code">Woffice Purchase Code</label>
							<input type="text" class="form-control" id="purchase_code" name="purchase_code" placeholder="Like : 7fe94dc7-280f-440c-90c3-e19c6b78fde7">
	  					</div>
	  					
	  					<div class="form-group">
							<label for="version">Woffice Version</label>
							<select class="form-control" id="version" name="version">
								<?php // Array of versions available 
								foreach ($data_decoded as $update) {
									echo '<option value="'.$update['new_version'].'">'.$update['new_version'].'</option>';
								}
								?>
							</select>	
	  					</div>	
	  					
	  					<input type="hidden" value="from-form" name="source">
	  					
	  					<div class="form-group text-right">
							<button type="submit" class="btn btn-default btn-success">Download</button>
						</div>	
						
						<p class="bottom_line">If you have any trouble, feel free to open a ticket <a href="https://2f.ticksy.com/">Here</a>.</p>
	  								
					</form>
					
					<div id="tutorial" class="center">
						<a href="https://2f.ticksy.com/article/4136/" class="btn btn-default btn-info">Woffice Update Tutorial</a>
					</div>
					
				</section>
				
			</div>

		</div>
	</body>
    <!-- END BODY -->
</html>