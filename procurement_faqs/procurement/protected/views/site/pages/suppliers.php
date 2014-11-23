<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Suppliers FAQs';

?>

<div class="wrapper mt20">
    <h3>Search By:</h3>
<div class=" col-lg-3 col-md-3 col-sm-12 col-xs-12">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'bidders-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
    )); ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <?php echo $form->dropDownList($model, 'classification', $model->getBidderTypeArray(), array('class'=>'form-control'));  ?>
        
    </div>
    <div class="form-group">
        <?php echo $form->textField($model, 'query', array('class'=>'form-control')); ?>
    </div>
    <div class="form-group">
        <input type="submit" value="Search" class="btn-success pull-left ml0">
    </div>
    <?php $this->endWidget(); ?>
</div>
<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
    <h3>Result</h3>
    <table class="table table-condensed">
    <tbody>
        <tr>
            <th class="w60p">Supplier Name</th>
            <th class="w10p text-center">Successfull Bids</th>
            <th class="w10p text-center">Savings</th>
            <th class="w10p text-center">View Details</th>

        </tr>
        <?php if(isset($model->suppliers)){ 
        $suppliers = $model->suppliers['result']['records'];    
        foreach ($suppliers as $key => $value) {
            ?>
        <tr class="clickable" data-toggle="collapse" data-orgid="<?php echo $value['org_id']; ?>" data-target="#works<?php echo $key; ?>">
            <td class="fsb w60p"><?php echo $value['org_name'].' - '.$value['address1'].' '.$value['city'].' '.$value['province']; ?></td>
            <td class="w10p text-center"><?php echo $value['success_bid'] ?></td>
            <td class="w10p text-center"><?php printf("%.2f%%", $value['savings'] * 100); ?></td>
            <td class="w10p text-center"><a class="btn text-center">Details</a></td>

        </tr>
        <tr><!--Hiddent Row-->
            <td colspan="4" class="hiddenrow">
                <div class="accordian-body collapse" id="works<?php echo $key; ?>">
                    <table class="table table-condensed">
                    <tbody>
                        <tr>
                           <th class="w25p">Goverment Agency</th>
                           <th class="w45p">Bid Description</th>
                           <th class="w20p">Business Category</th>
                           <th class="w10p">Procurement Mode</th>
                           
                       </tr>
                       <tr>
                           <td class="w25p">Department of Health</td>
                           <td class="w45p">Printing of Grade 9 Learner’s Materials (LMs) & Teacher’s Guides (TGs) and Delivery to DepEd Central Office, DepEd Regional & Division Offices, and High Schools Nationwide - 1 - Printing and Delivery</td>
                           <td class="w10p">Printing Services</td>
                           <td class="w10p">Public Bidding</td>
                           
                       </tr>
                    </tbody>
                </table></div>
            </td>
        </tr>
        <?php }} ?>
        </tbody>
        </table>   
        <div class="pagenav">    

        <ul class="pagination pagination-lg">

            <li><a href="#">&laquo;</a></li>

            <li><a href="<?php echo Yii::app()->createUrl('/site/suppliers/offset/1'); ?>">1</a></li>

            <li><a href="<?php echo Yii::app()->createUrl('/site/suppliers/offset/2'); ?>">2</a></li>

            <li><a href="<?php echo Yii::app()->createUrl('/site/suppliers/offset/3'); ?>">3</a></li>

            <li><a href="<?php echo Yii::app()->createUrl('/site/suppliers/offset/4'); ?>">4</a></li>

            <li><a href="<?php echo Yii::app()->createUrl('/site/suppliers/offset/5'); ?>">5</a></li>

            <li><a href="#">&raquo;</a></li>

        </ul>

</div>  
</div>		
   <div class="clearfix"></div>