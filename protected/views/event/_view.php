<?php
/* @var $this EventController */
/* @var $data Event */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_lat')); ?>:</b>
	<?php echo CHtml::encode($data->start_lat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_lng')); ?>:</b>
	<?php echo CHtml::encode($data->start_lng); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_lat')); ?>:</b>
	<?php echo CHtml::encode($data->end_lat); ?>
	<br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('end_lng')); ?>:</b>
    <?php echo CHtml::encode($data->end_lng); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('preset')); ?>:</b>
    <?php echo CHtml::encode(join(', ',CHtml::listData($data->presets,'id','name'))); ?>
    <br />


</div>