<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Procurement Budget';

?>
		<style type="text/css">
		.highcharts-title {display: none;}
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {

    Highcharts.data({
        csv: document.getElementById('tsv').innerHTML,
        itemDelimiter: '\t',
        parsed: function (columns) {

            var brands = {},
                brandsData = [],
                versions = {},
                drilldownSeries = [];

            // Parse percentage strings
            columns[1] = $.map(columns[1], function (value) {
                if (value == value.length - 1) {
                    value = parseFloat(value);
                }
                return value;
            });

            $.each(columns[0], function (i, name) {
                var brand,
                    version;

                if (i > 0) {

                    // Remove special edition notes
                    name = name.split(' -')[0];

                    // Split into brand and version
                    version = name.match(/([0-9]+[\.,0-9x]*)/);
                    if (version) {
                        version = version[0];
                    }
                    brand = name.replace(version, '');

                    // Create the main data
                    if (!brands[brand]) {
                        brands[brand] = columns[1][i];
                    } else {
                        brands[brand] += columns[1][i];
                    }

                    // Create the version data
                    if (version !== null) {
                        if (!versions[brand]) {
                            versions[brand] = [];
                        }
                        versions[brand].push(['v' + version, columns[1][i]]);
                    }
                }

            });

            $.each(brands, function (name, y) {
                brandsData.push({
                    name: name,
                    y: y,
                    drilldown: versions[name] ? name : null
                });
            });
            $.each(versions, function (key, value) {
                drilldownSeries.push({
                    name: key,
                    id: key,
                    data: value
                });
            });

            // Create the chart
            $('#container').highcharts({
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Browser market shares. November, 2013.'
                },
                subtitle: {
                    text: 'Click the slices to view the details.'
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.y:.1f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
                },

                series: [{
                    name: 'Classification',
                    colorByPoint: true,
                    data: brandsData
                }],
                drilldown: {
                    series: drilldownSeries
                }
            });
        }
    });
});


		</script>
<div class="w100p ha p10 bc1 mt10 mb10">
	<h3 class="m0 col-md-5 ">What location do you want to see:</h3>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'ps1-ps1-form',
		'method'=>'post',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation'=>false,
	)); ?>
		<div class="btn-group col-md-2">
			<?php
				echo '<select class="form-control" name="PS1_A[location]">';
					echo '<option value="0" '.($model->location == 0 ? 'selected' : '').'>Nationwide</option>';
					foreach($locOptions as $index => $value)
						echo '<option value="'.$value.'"'.($model->location == $value ? 'selected' : '').'>'.$value.'</option>';
				echo '</select>';
			?>
		</div>
		<div class="btn-group col-md-2">
			<?php
				echo '<select class="form-control" name="PS1_A[year]">';
					echo '<option value="0" '.($model->year == 0 ? 'selected' : '').'>---</option>';
					for($x = 2009;$x<=date('Y');$x++)
						echo '<option value="'.$x.'" '.($model->year == $x ? 'selected' : '').'>'.$x.'</option>';
				echo '</select>';
			?>	
		</div>
		<div class="btn-group col-md-2">
			<?php
				echo '<select class="form-control" name="PS1_A[month]">';
					echo '<option value="0" '.($model->month == 0 ? 'selected' : '').'>---</option>';
					for($x = 1;$x<=12;$x++)
						echo '<option value="'.$x.'" '.($model->month == $x ? 'selected' : '').'>'.date('F', strtotime(date('2014-'.$x.'-13'))).'</option>';
				echo '</select>';
			?>
		</div>
		<div class="btn-group col-md-1">
			<?php echo CHtml::submitButton('Search', array('class' => 'btn')); ?>
		</div>
	<?php $this->endWidget(); ?>
	<div class="clearfix"></div>
</div>		

<?php if($model->displayResult) : ?>
<div class="jumbotron">
    <div class="container">
        <div class="col-md-5">
            <h1 class="mt10 mb10"><?php echo $model->displayResult['location']; ?></h1>
            <span class="fsi">Location</span>
            <h2 class="pb0 mb10"><?php echo $model->displayResult['totalBudget']; ?></h2>
            <span class="fsi">Total Approve Budget</span>
            <h2 class="pb0 mb10"><?php echo $model->displayResult['totalSpent']; ?></h2>
            <span class="fsi">Total Spent</span>
            <h2 class="pb0 mb10"><?php echo $model->displayResult['totalUnused']; ?></h2>
            <span class="fsi">Total Unused Funds</span>
        </div>
    
      <div class="col-md-7">

<div id="container" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div></div></div></div>

<!-- Data from www.netmarketshare.com. Select Browsers => Desktop share by version. Download as tsv. -->
<?php if($model->displayResult['pieChart']) { ?>
<pre id="tsv" style="display:none">Classification	Budget Share
<?php echo $model->displayResult['pieChart']; ?>
</pre>
<?php } ?>
<h2>Detailed Breakdown</h2>
<?php
	if($model->displayResult['resultData'])
	{
		echo '<table class="table table-condensed">';
			echo '<tbody>';
				echo '<tr>';
					echo '<th class="w55p">Description</th>';
					echo '<th class="w10p">Approved Budget</th>';
					echo '<th class="w10p">Contracted Amount</th>';
					echo '<th class="w10p">Status</th>';
				echo '</tr>';

				foreach($model->displayResult['resultData'] as $classification => $details)
				{
					echo '<tr class="clickable" data-toggle="collapse" data-target="#id_'.str_replace(" ","_",$classification).'">';
						echo '<td class="fsb w15p"><span class="glyphicon glyphicon-chevron-down"></span>&nbsp;'.$classification.'</td>';
						echo '<td class="w10p"></td>';
						echo '<td class="w10p"></td>';
						echo '<td class="w10p"></td>';
					echo '</tr>';

					echo '<tr><!--Hiddent Row-->';
						echo '<td colspan="5" class="hiddenrow">';
							echo '<div class="accordian-body collapse" id="id_'.str_replace(" ","_",$classification).'">';
								echo '<table class="table table-condensed">';
									echo '<tbody>';

									if($details['breakdown'])
									{
										foreach($details['breakdown'] as $index => $value)
										{

											echo '<tr>';
												echo '<td class="w55p">';
													if($value['award_title'] || $value['item_name'] || $value['item_description'])
													{
														echo $value['award_title'];
														echo ($value['item_name']) ? '<br/>' . $value['item_name'] : '';
														echo ($value['item_description']) ? '<br/>' . $value['item_description'] : '';
													}
													else
													{
														echo 'No description available';
													}
												echo '</td>';
												echo '<td class="w10p">'.PS1_A::formatAmount($value['approved_budget']).'</td>';
												echo '<td class="w10p">'.PS1_A::formatAmount($value['contract_amt']).'</td>';
												echo '<td class="w10p">'.$value['tender_status'].'</td>';
											echo '</tr>';

										}
									}
									else
									{
										echo '<tr>';
												echo '<td class="fsb w15p" colspan="4">No breakdown details available</td>';
										echo '</tr>';
									}

									echo '</tbody>';
								echo '</table>';
							echo '</div>';
						echo '</td>';
					echo '</tr>';
				}


			echo '</tbody>';
		echo '</table>';
	}
	else
	{
		echo '<table class="table table-condensed">';
			echo '<tbody>';
				echo '<tr>';
					echo '<th class="w15p">No breakdown details available</th>';
				echo '</tr>';
			echo '</tbody>';
		echo '</table>';
	}

?>
<div class="clearfix"></div>
<?php endif; ?>