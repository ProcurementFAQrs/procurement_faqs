<?php
class BiddersForm extends CFormModel
{
	public $query;
	public $classification;
	public $success;
	public $savings;
	public $suppliers;
	public $offset;

	public function rules(){
		return array(
				array('query, classification', 'safe'),
			);
	}

	public function getAgencies(){
		$sql = 'SELECT * FROM "'.Constants::TBL_ORGANIZATION.'" WHERE member_type=\'Supplier\'';
		$query = Constants::URL_SELECT.str_replace(' ', '%20', $sql);

		$run = Yii::app()->curl->run($query);

		if(!$run->hasErrors()) {
			return json_decode($run->getData(), true);	    	
		} else {
			return FALSE;
		}
	}

	public function getBidderTypeArray(){
		$sql = 'SELECT supplier_organization_type, COUNT(*) FROM "ec10e1c4-4eb3-4f29-97fe-f09ea950cdf1" WHERE member_type=\'Supplier\' GROUP BY supplier_organization_type';

		$query = Constants::URL_SELECT.str_replace(' ', '%20', $sql);
		$run = Yii::app()->curl->run($query);

		if(!$run->hasErrors()) {
			$data = json_decode($run->getData(), true);
			return CHtml::listData($data['result']['records'], 'supplier_organization_type', 'supplier_organization_type');    	
		} else {
			return array();
		}
	}

	public function searchAgencies($term){
		$sql = 'SELECT * FROM "'.Constants::TBL_ORGANIZATION.'" WHERE member_type=\'Supplier\' AND org_name ILIKE \'%'.$term.'%\'';
		$query = Constants::URL_SELECT.str_replace(' ', '%20', $sql);
		//echo $query; exit;

		$run = Yii::app()->curl->run($query);

		if(!$run->hasErrors()) {
			return json_decode($run->getData(), true);	    	
		} else {
			return FALSE;
		}
	}

	public function getSavings(){
		$class_sql = isset($this->classification)? (' AND o.supplier_organization_type=\''. $this->classification).'\'' : '';
		$query_sql = isset($this->query)? (' AND o.org_name ILIKE \'%'. $this->query .'%\' ') : '';
		$offset_sql = ' OFFSET 0 ';
		if(isset($this->offset)){
			$offset_sql = ' OFFSET '.(10*$this->offset).' ';
		}


		//var_dump($this->query); exit;

		$sql = 'SELECT o.*, (a.budget - a.contract)/a.budget savings, a.budget, a.contract, success_bid FROM "'.Constants::TBL_ORGANIZATION.'" o LEFT JOIN (SELECT a1.awardee_id, SUM(a1.budget) budget, SUM(a1.contract_amt) contract, COUNT(*) success_bid FROM "'.Constants::TBL_AWARD.'" a1 LEFT JOIN "'.Constants::TBL_BID_INFORMATION.'" bi ON a1.ref_id=bi.ref_id WHERE bi.tender_status=\'Awarded\' AND a1.ref_id IS NOT NULL GROUP BY awardee_id) a ON o.org_id=a.awardee_id WHERE a.budget IS NOT NULL AND a.budget!=0 '.$class_sql.$query_sql.' ORDER BY ((a.budget-a.contract)/a.budget)  DESC LIMIT 10 '.$offset_sql;
		$query = Constants::URL_SELECT.str_replace(' ', '%20', $sql);
		//echo $query; exit;

		$run = Yii::app()->curl->run($query);

		if(!$run->hasErrors()) {
			return json_decode($run->getData(), true);	    	
		} else {
			return FALSE;
		}

	}

	public function getDetails($id){
		$id = (int) $int;
		$sql = 'SELECT * FROM "'.Constants::TBL_BID_INFORMATION.'" bi LEFT JOIN "'.Constants::TBL_AWARD.'" a ON bi.ref_id=a_ref_id WHERE a.awardee_id='.$id;
		$query = Constants::URL_SELECT.str_replace(' ', '%20', $sql);
		//echo $query; exit;

		$run = Yii::app()->curl->run($query);

		if(!$run->hasErrors()) {
			return json_decode($run->getData(), true);	    	
		} else {
			return FALSE;
		}
	}

	public function getOrgDetails($id){
		$sql = 'SELECT * FROM "'.Constants::TBL_BID_INFORMATION.'" bi LEFT JOIN "'.Constants::TBL_AWARD.'" a ON bi.ref_id=a.ref_id LEFT JOIN "'.Constants::TBL_ORGANIZATION.'" o ON bi.org_id=o.org_id WHERE a.awardee_id='.$id;
		$query = Constants::URL_SELECT.str_replace(' ', '%20', $sql);
		//echo $query; exit;

		$run = Yii::app()->curl->run($query);

		if(!$run->hasErrors()) {
			return json_decode($run->getData(), true);	    	
		} else {
			return FALSE;
		}
	}

}