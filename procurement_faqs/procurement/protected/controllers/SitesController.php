<?php

class SitesController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	public function actionPs1_a()
	{
		$model = new PS1_A;
		$display = FALSE;
		$res		= FALSE;
		
		if(isset($_POST['PS1_A']))
		{
			$model->attributes	= $_POST['PS1_A'];

			if($model->validate())
			{
				$query	= $model->buildQuery();
				$raw	= $model->runQuery($query);

				$model->prepareDisplay($raw);
			}
		}

		$locOptions = $model->getAvailableLocationList();
		$this->render('ps1_a', array('model' => $model, 'locOptions' => $locOptions, 'display' => $display, 'res' => $res));
		

// 		$query = $model->buildQuery();
// echo $query;
// 		$run_one = Yii::app()->curl->run($query);
// 		$result = json_decode($run_one->getData());
// 		if(!$run_one->hasErrors()) {
// 			$this->render('ps1', array('locOptions' => $locOptions,'result'=>$result));
		
// 		} else {
// 		    echo '<pre>';
// 		    var_dump($run_one->getErrors());
// 		    echo '</pre>';
// 		}
	}
}