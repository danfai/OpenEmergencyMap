<?php
/* @var $this PresetController */
/* @var $model Preset */

$this->breadcrumbs=array(
	'Presets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Preset', 'url'=>array('index')),
	array('label'=>'Manage Preset', 'url'=>array('admin')),
);
?>

<h1>Create Preset</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>