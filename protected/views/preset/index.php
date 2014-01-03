<?php
/* @var $this PresetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Presets',
);

$this->menu=array(
	array('label'=>'Create Preset', 'url'=>array('create')),
	array('label'=>'Manage Preset', 'url'=>array('admin')),
);
?>

<h1>Presets</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
)); ?>