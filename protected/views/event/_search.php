<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'start_lat'); ?>
		<?php echo $form->textField($model,'start_lat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'start_lng'); ?>
		<?php echo $form->textField($model,'start_lng'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'end_lat'); ?>
		<?php echo $form->textField($model,'end_lat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'end_lng'); ?>
		<?php echo $form->textField($model,'end_lng'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->