<?php
class PS1_A extends CFormModel
{
	const URL_SELECT				= 'http://philgeps.cloudapp.net:5000/api/action/datastore_search_sql?sql=';

	const TBL_AWARD					= '539525df-fc9a-4adf-b33d-04747e95f120';
	const TBL_BIDDERS_LIST			= '539525df-fc9a-4adf-b33d-04747e95f120';
	const TBL_ORGANIZATION			= 'ec10e1c4-4eb3-4f29-97fe-f09ea950cdf1';
	const TBL_BID_LINE_ITEM			= 'daa80cd8-da5d-4b9d-bb6d-217a360ff7c1';
	const TBL_BID_INFORMATION		= 'baccd784-45a2-4c0c-82a6-61694cd68c9d';
	const TBL_PROJECT_LOCATION		= '116b0812-23b4-4a92-afcc-1030a0433108';
	const TBL_ORGANIZATION_CATEGORY	= '58ea40bf-15e9-4c38-adef-fd93455d8c7e';

	// const SCENARIO_NO_FILTER		= 0;
	// const SCENARIO_FILTER_LOCATION	= 1;
	// const SCENARIO_FILTER_DATE		= 2;
	// const SCENARIO_FILTER_BOTH		= 3;

	public $location;
	public $year;
	public $month;
	public $displayResult;

	public function rules(){
        return array(
        	array('location, year, month', 'required'),
        	// array('year', 'numerical'),
        	// array('month', 'numerical'),
            //array('date, english_level, sid, student_attendance, time, comment_for_student', 'required'),
            // array('student_attendance', 'required'),
            // array('student_attendance', 'validateInput'),
            // array('comment_for_student, date, english_level, material_recommendation, material_used, new_word, other_comments, purpose, recommendation, sid, skype, skype_assistance_no, skype_comment, specific_material_id, student_attendance, time, material_lesson_comment', 'safe'),
        );
    }

    // public function setOwnScenario()
    // {
    // 	if(is_null($this->location) || $this->location === '0')
    // 		$this->scenario = self::SCENARIO_NO_FILTER;
    // 	elseif($this->location !== '0')
    // 		$this->scenario = self::SCENARIO_FILTER_LOCATION;
    // }



	public function getAvailableLocationList()
	{
		$query = Constants::URL_SELECT;
		$query .= 'SELECT DISTINCT(location) FROM "'.Constants::TBL_PROJECT_LOCATION.'" ORDER BY location';
		$query = str_replace(' ', '%20', $query);

		$raw = $this->runQuery($query);

		$res = array();

		if($raw)
		{
			foreach($raw as $key => $value)
				$res[] = $value['location'];
		}

		return ($res ? $res : FALSE);
	}

	public static function cleanResult($var)
	{

		
	}

	public function runQuery($query)
	{
		$raw	= Yii::app()->curl->run($query);
		$res	= json_decode($raw->getData(), TRUE);

		return $res['result']['records'];
	}

	public function buildQuery()
	{
		$query = Constants::URL_SELECT;

		$query .= 'SELECT ';
			$query .= 'b.classification, ';
			$query .= 'sum(b.approved_budget) as total_approved_budget, ';
			$query .= 'SUM(c.contract_amt) as total_contract_amount ';

		if($this->location !== '0')
		{
			$query .= 'FROM "'.Constants::TBL_PROJECT_LOCATION.'" as a ';
		
			$query .= 'LEFT JOIN "'.Constants::TBL_BID_INFORMATION.'" as b ';
				$query .= 'ON a.refid = b.ref_id ';
			
			$query .= 'LEFT JOIN "'.Constants::TBL_AWARD.'" as c ';
				$query .= 'ON a.refid = c.ref_id ';
		}
		else
		{		
			$query .= 'FROM "'.Constants::TBL_BID_INFORMATION.'" as b ';
			
			$query .= 'LEFT JOIN "'.Constants::TBL_AWARD.'" as c ';
				$query .= 'ON b.ref_id = c.ref_id ';
		}

		$query .= 'WHERE 1 = 1 ';

		if($this->location !== '0')
			$query .= ' AND a.location = \''. mysql_real_escape_string($this->location) .'\' ';
				
		if($this->year !== '0')
			$query .= ' AND EXTRACT(YEAR FROM b.publish_date) = \''. mysql_real_escape_string($this->year) .'\' ';
		
		if($this->month !== '0')
			$query .= ' AND EXTRACT(MONTH FROM b.publish_date) = \''. mysql_real_escape_string($this->month).'\'';

		$query .= 'GROUP BY b.classification';

		$query .= ($this->location !== '0') ? ', a.location' : '';
		
		
		return str_replace(' ', '%20', $query);
	}

	public function getDetailedBreakdown($classification)
	{
		$query = Constants::URL_SELECT;

		$query .= 'SELECT ';
			$query .= 'a.location, ';
			$query .= 'b.classification, ';
			$query .= 'b.publish_date, ';
			$query .= 'b.approved_budget, ';
			$query .= 'b.tender_status, ';
			$query .= 'c.award_title, ';
			$query .= 'c.awardee_id, ';
			$query .= 'c.item_name, ';
			$query .= 'c.item_description, ';
			$query .= 'c.contract_amt ';

		if($this->location !== '0')
		{
			$query .= 'FROM "'.Constants::TBL_PROJECT_LOCATION.'" as a ';
		
			$query .= 'LEFT JOIN "'.Constants::TBL_BID_INFORMATION.'" as b ';
				$query .= 'ON a.refid = b.ref_id ';
			
			$query .= 'LEFT JOIN "'.Constants::TBL_AWARD.'" as c ';
				$query .= 'ON a.refid = c.ref_id ';
		}
		else
		{		
			$query .= 'FROM "'.Constants::TBL_BID_INFORMATION.'" as b ';
			
			$query .= 'LEFT JOIN "'.Constants::TBL_AWARD.'" as c ';
				$query .= 'ON b.ref_id = c.ref_id ';

			$query .= 'LEFT JOIN "'.Constants::TBL_PROJECT_LOCATION.'" as a ';
				$query .= 'ON a.refid = b.ref_id ';
		}

		$query .= 'WHERE ';
			$query .= 'b.classification = \''.mysql_real_escape_string($classification).'\' ';

		if($this->location !== '0')
			$query .= ' AND a.location = \''. mysql_real_escape_string($this->location) .'\' ';
				
		if($this->year !== '0')
			$query .= ' AND EXTRACT(YEAR FROM b.publish_date) = \''. mysql_real_escape_string($this->year) .'\' ';
		
		if($this->month !== '0')
			$query .= ' AND EXTRACT(MONTH FROM b.publish_date) = \''. mysql_real_escape_string($this->month).'\'';

		$query = str_replace(' ', '%20', $query);

		$raw = $this->runQuery($query);
		
		return $raw ;
	}

	public function prepareDisplay($rawResult)
	{
		//-prepare array
		$res = array();

		foreach($rawResult as $key => $value)
			$res[$value['classification']] = array(
				'totalApprovedBudget' => $value['total_approved_budget'], 
				'totalContractAmount' => $value['total_contract_amount'],
				'breakdown'				=> $this->getDetailedBreakdown($value['classification']),
			);

		//get location
		$location		= $this->getDisplayLocation();
		//get total approved budget
		$totalBudget	= $this->getDisplayTotalApprovedBudget($res);
		$totalBudgetString	= self::formatAmount($totalBudget);
		//get total spent
		$totalSpent		= $this->getDisplayTotalContractAmount($res);
		$totalSpentString	= self::formatAmount($totalSpent);
		//get total unused
		$totalUnused	= ($totalBudget && $totalSpent) ? ($totalBudget - $totalSpent) : FALSE ;
		$totalUnusedString	= self::formatAmount($totalUnused);
		//get piechart
		$pieChart		= $this->getPieChart($res);

		$breakDownString	= '';
		//detailed breakdown
		
		$this->displayResult	= array(
			'location'		=> $location,
			'totalBudget'	=> $totalBudgetString,
			'totalSpent'	=> $totalSpentString,
			'totalUnused'	=> $totalUnusedString,
			'pieChart'		=> $pieChart,
			'resultData'	=> (($res) ? $res : FALSE),
		);
	}


	public function getDisplayLocation()
	{
		if($this->location === '0')
			return 'Philippines';
		else
			return htmlspecialchars($this->location);
	}

	public function getDisplayTotalApprovedBudget($data)
	{
		if($data)
		{
			$total = 0;

			foreach($data as $classification => $values)
				$total = $total + $values['totalApprovedBudget'];

			return $total;
		}
		else
		{
			return FALSE;
		}
	}

	public function getDisplayTotalContractAmount($data)
	{
		if($data)
		{
			$total = 0;

			foreach($data as $classification => $values)
				$total = $total + $values['totalContractAmount'];

			return $total;
		}
		else
		{
			return FALSE;
		}
	}

	public function getPieChart($data)
	{
		$pieChart = "";

		if($data)
		{
			foreach($data as $classification => $value)
				$pieChart .=  $classification . "\t" . $value['totalApprovedBudget'] . "\r\n";
		}
		else
		{
			$pieChart = FALSE;
		}

		return $pieChart;
	}

	public static function formatAmount($amount)
	{
		if($amount && is_numeric($amount))
			return '&#8369; ' . number_format($amount,2);
		else
			return '-';
	}

}