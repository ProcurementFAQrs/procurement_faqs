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
                if (value.indexOf('%') === value.length - 1) {
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
                    version = name.match(/([0-9]+[\.0-9x]*)/);
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
                            format: '{point.name}: {point.y:.1f}%'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
                },

                series: [{
                    name: 'Clasification',
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
	<div class="btn-group col-md-2">
		<select class="form-control">
			<option selected disabled>Select a Location</option>
			<option>All</option>
			<option>Manila</option>
		</select>
	</div>
	<div class="btn-group col-md-2">
		<select class="form-control">
			<option selected disabled>Month</option>
			<option>All</option>
			<option>Manila</option>
		</select>
	</div>
	<div class="btn-group col-md-2">
		<select class="form-control">
			<option selected disabled>Year</option>
			<option>All</option>
			<option>Manila</option>
		</select>
	</div>
	<div class="btn-group col-md-1">
		<button type="submit" class="btn">Search</button>
	</div>
	<div class="clearfix"></div>
</div>		
<div class="jumbotron">
    <div class="container">
        <div class="col-md-5">
            <h1 class="mt10 mb10">Zambuanga del Norte</h1>
            <span class="fsi">Location</span>
            <h2 class="pb0 mb10">&#8369;1,000,000.00</h2>
            <span class="fsi">Total Approve Budget</span>
            <h2 class="pb0 mb10">&#8369;500,000</h2>
            <span class="fsi">Total Spent</span>
            <h2 class="pb0 mb10">&#8369;500,000</h2>
            <span class="fsi">Total Unused Funds</span>
        </div>
    
      <div class="col-md-7">

<div id="container" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div></div></div></div>

<!-- Data from www.netmarketshare.com. Select Browsers => Desktop share by version. Download as tsv. -->
<pre id="tsv" style="display:none">Clasification	Budget Share
Civil Works 8.0	26.61%
Civil Works 9.0	16.96%
Firefox 12	6.72%
Civil Works 6.0	6.40%
Firefox 11	4.72%
Civil Works 7.0	3.55%
Firefox 13	2.16%
Firefox 3.6	1.87%
Firefox 10	0.90%
Firefox 9.0	0.65%
Firefox 8.0	0.55%
Firefox 4.0	0.50%
Firefox 3.0	0.36%
Firefox 3.5	0.36%
Firefox 6.0	0.32%
Firefox 5.0	0.31%
Firefox 7.0	0.29%
Firefox 14	0.10%
Firefox 2.0	0.09%
Civil Works 8.0 - Tencent Traveler Edition	0.09%</pre>

<h2>Detailed Breakdown</h2>
<table class="table table-condensed">
    <tbody>
        <tr>
            <th class="w15p">Classification</th>
            <th class="w55p">Description</th>
            <th class="w10p">Approved Budget</th>
            <th class="w10p">Contracted Amount</th>
            <th class="w10p">Status</th>
        </tr>
        <tr class="clickable" data-toggle="collapse" data-target="#works">
            <td class="fsb w15p"><span class="glyphicon glyphicon-chevron-down"></span>&nbsp;Civil Works</td>
            <td class="w55p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
        </tr>
        <tr><!--Hiddent Row-->
        	<td colspan="5" class="hiddenrow">
        		<div class="accordian-body collapse" id="works">
        			<table class="table table-condensed">
        			<tbody>
        				<tr>
        					<td class="fsb w15p">Civil Works other</td>
				            <td class="w55p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
        				</tr>
        				<tr>
        					<td class="fsb w15p">Civil Works other</td>
				            <td class="w55p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
        				</tr>
	        		</tbody>
	        	</table></div>
        	</td>
        </tr>
        <tr class="clickable" data-toggle="collapse" data-target="#goods">
            <td class="fsb w15p" ><span class="glyphicon glyphicon-chevron-down"></span>&nbsp;Goods Total</td>
            <td class="w55p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
        </tr>
        <tr><!--Hiddent Row-->
        	<td colspan="5" class="hiddenrow">
        		<div class="accordian-body collapse" id="goods">
        			<table class="table table-condensed">
        			<tbody>
        				<tr>
        					<td class="fsb w15p">Goods other</td>
				            <td class="w55p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
        				</tr>
        				<tr>
        					<td class="fsb w15p">Goods other</td>
				            <td class="w55p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
        				</tr>
	        		</tbody>
	        	</table></div>
        	</td>
        </tr>
        <tr class="clickable" data-toggle="collapse" data-target="#consult">
            <td class="fsb w15p"><span class="glyphicon glyphicon-chevron-down"></span>&nbsp;Consulting Services</td>
            <td class="w55p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
        </tr>
        <tr><!--Hiddent Row-->
        	<td colspan="5" class="hiddenrow">
        		<div class="accordian-body collapse" id="consult">
        			<table class="table table-condensed">
        			<tbody>
        				<tr>
        					<td class="fsb w15p">Consulting Services other</td>
				            <td class="w55p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
        				</tr>
        				<tr>
        					<td class="fsb w15p">Consulting Services other</td>
				            <td class="w55p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
        				</tr>
	        		</tbody>
	        	</table></div>
        	</td>
        </tr>
        <tr class="clickable" data-toggle="collapse" data-target="#support">
            <td class="fsb w15p"><span class="glyphicon glyphicon-chevron-down"></span>&nbsp;Goods - General Support Services</td>
            <td class="w55p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
        </tr>
        <tr><!--Hiddent Row-->
        	<td colspan="5" class="hiddenrow">
        		<div class="accordian-body collapse" id="support">
        			<table class="table table-condensed">
        			<tbody>
        				<tr>
        					<td class="fsb w15p">Goods - General Support Services other</td>
				            <td class="w55p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
        				</tr>
        				<tr>
        					<td class="fsb w15p">Goods - General Support Services other</td>
				            <td class="w55p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
				            <td class="w10p"></td>
        				</tr>
	        		</tbody>
	        	</table></div>
        	</td>
        </tr>
        <tr class="tbl-grey fc-white">
            <td class="fsb w15p">TOTAL</td>
            <td class="w55p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
            <td class="w10p"></td>
        </tr>
    </tbody>
</table>
<div class="clearfix"></div>