<?php
/* @var $this PresetController */
/* @var $model Preset */

$this->breadcrumbs=array(
	'Presets'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Preset', 'url'=>array('index')),
	array('label'=>'Create Preset', 'url'=>array('create')),
	array('label'=>'View Preset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Preset', 'url'=>array('admin')),
);
?>

<h1>Update Preset <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>