<?php
/* @var $this PresetController */
/* @var $model Preset */

$this->breadcrumbs=array(
	'Presets'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Preset', 'url'=>array('index')),
	array('label'=>'Create Preset', 'url'=>array('create')),
	array('label'=>'Update Preset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Preset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Preset', 'url'=>array('admin')),
);
?>

<h1>View Preset #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
            'name'=>'type',
            'value'=>join(", ",$model->type)
        ),
		'name',
	),
)); ?>
