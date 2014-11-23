<?php
	Yii::app()->clientscript
	
	->registerCoreScript( 'jquery.ui' )
	->registerScriptFile( Yii::app()->theme->baseUrl . '/js/highcharts.js', CClientScript::POS_END )
	->registerScriptFile( Yii::app()->theme->baseUrl . '/js/data.js', CClientScript::POS_END )
	->registerScriptFile( Yii::app()->theme->baseUrl . '/js/drilldown.js', CClientScript::POS_END )
	->registerScriptFile( Yii::app()->theme->baseUrl . '/js/bootstrap.js', CClientScript::POS_END )
	->registerCssFile( Yii::app()->baseUrl . '/assets/e31c144b/jui/css/base/jquery-ui.css' )
	->registerCssFile( Yii::app()->baseUrl . '/assets/e31c144b/jui/css/base/jquery.ui.accordion.css' )
		/*
		->registerScriptfile( Yii::app()->theme->
		*/
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo CHtml::encode('Procurement FAQs'); ?></title>
<meta name="language" content="en" />
<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- Le styles -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
<!-- Le fav and touch icons -->
</head>

<body>
<!-- Navigation -->
    <nav class="navbar-inverse navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#page-top">Procurement FAQs</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse">
               <div class="nav-collapse">
					<?php $this->widget('zii.widgets.CMenu',array(
						'htmlOptions' => array( 'class' => 'nav navbar-nav navbar-right' ),
						'activeCssClass'	=> 'active',
						'items'=>array(
							array('label'=>'Home', 'url'=>array('/site/index')),
							array('label'=>'Procurement Budget', 'url'=>array('/site/page', 'view'=>'budget')),
							array('label'=>'Supplier FAQs', 'url'=>array('/site/suppliers')),
							array('label'=>'Contact', 'url'=>array('/site/contact')),
							array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
							array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
						),
					)); ?>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

	
	<div class="cont">
	<div class="container-fluid">

	
	<?php echo $content ?>
	
	
	</div><!--/.fluid-container-->
	</div>
	
	<div class="extra">
	  <div class="container">
		<div class="row">
			
			<div class="col-md-3 col-md-offset-9 ">
				<h4>About the Team</h4>
				<p>Group of concerned citizens helping the country understand how the goverment procures items, procedures and budget.</p>
				
				</div> <!-- /span3 -->
			</div> <!-- /row -->
		</div> <!-- /container -->
	</div>
	
	<div class="footer">
	  <div class="container">
		<div class="row">
			<div id="footer-copyright" class="col-md-6">
				
			</div> <!-- /span6 -->
			<div id="footer-terms" class="col-md-6">
				© 2014 Team FAQrs.
			</div> <!-- /.span6 -->
		 </div> <!-- /row -->
	  </div> <!-- /container -->
	</div>
</body>
</html>
