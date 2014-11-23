<?php

class SiteController extends Controller
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

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionQuery(){
		//$query = 'http://philgeps.cloudapp.net:5000/api/action/datastore_search_sql?sql=SELECT%20*%20FROM%20"baccd784-45a2-4c0c-82a6-61694cd68c9d"%20LIMIT%205';
		if(isset($_POST['str'])){
			//$query = 'http://api.data.gov.ph/api/action/datastore_search_sql?sql='. str_replace(' ', '%20', $_POST['str']);
			$query = 'http://philgeps.cloudapp.net:5000/api/action/datastore_search_sql?sql='. str_replace(' ', '%20', $_POST['str']);
			var_dump($query);

			$run_one = Yii::app()->curl->run($query);
			if(!$run_one->hasErrors()) {
		    	$this->render('query_form', array('result'=>$run_one));
		 
			} else {
			    echo '<pre>';
			    var_dump($run_one->getErrors());
			    echo '</pre>';
			}
		}
		else{
			$this->render('query_form');
		}

	}

	public function actionTestmodel(){
		$model = new BiddersForm;
		var_dump($model->getSavings());
	}

	public function actionTestsearch(){
		$model = new BiddersForm;
		$term = $_GET['q'];
		echo '<pre>';
		print_r($model->searchAgencies($term));
		echo '</pre>';
	}

	public function actionSuppliers(){
		$model = new BiddersForm;
		//var_dump($model->getBidderTypeArray()); exit;

		if(isset($_POST['BiddersForm'])){
			$model->attributes = $_POST['BiddersForm'];
			Yii::app()->session['query'] = $model->query;
			Yii::app()->session['classification'] = $model->classification;
			//print_r($_POST['BiddersForm']);
			//exit;
		}

		if(isset($_GET['offset'])){
			$model->offset = (int) $_GET['offset'];
			//var_dump($model->offset); exit;
		}

		$model->query = isset(Yii::app()->session['query'])? Yii::app()->session['query'] : '';
		$model->classification = isset(Yii::app()->session['classification'])? Yii::app()->session['classification'] : '';
		//var_dump($model->classification);exit;


		$model->suppliers = $model->getSavings();
		$this->render('pages/suppliers', array('model'=>$model));
	}

	public function actionGetsupplierdetail(){
		$id = $_POST['id'];
		$model = new BiddersForm;
		$data = $model->getOrgDetails($id);
		echo json_encode($data['result']['records']);
	}

}