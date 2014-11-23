<form action="<?php echo Yii::app()->createUrl('/site/query'); ?>" method="POST">
<input type="text" name="str" />
<button>Query</button>
</form>

<?php 
if(isset($result) && !$result->hasErrors()) {	 
		    echo '<pre>';
		        print_r(json_decode($result->getData()));
		    echo '</pre>';
		    var_dump($result->getInfo());
}		    
?>