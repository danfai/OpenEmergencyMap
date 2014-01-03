<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form CActiveForm */
$baseUrl = Yii::app()->baseUrl . '/static/';
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'event-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'preset'); ?>
        <?php echo $form->checkBoxList($model,'preset',CHtml::listData(Preset::model()->findAll(),'id','name')); ?>
        <?php echo $form->error($model,'preset'); ?>
    </div>

    <div id="map" style="width:400px; height:300px" class="row"></div>


    <?php echo $form->hiddenField($model,'start_lat'); ?>
    <?php echo $form->hiddenField($model,'start_lng'); ?>
    <?php echo $form->hiddenField($model,'end_lat'); ?>
    <?php echo $form->hiddenField($model,'end_lng'); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
<script>
    L.Icon.Default.imagePath = "<?php echo $baseUrl ?>images";
    var tile = L.tileLayer('/tiles/{z}/{x}/{y}.png');
    var drawnItems = L.featureGroup();
    var map = L.map('map',{layers:[tile,drawnItems]});
    var hash = L.hash(map);
    if(!hash.lastHash){
        map.setView([49.97,8.5],11);
    }

    var box;
    if($('#Event_start_lat').val()!=''){
        box = [
            [$('#Event_start_lat').val(),
        $('#Event_start_lng').val()],
        [$('#Event_end_lat').val(),
        $('#Event_end_lng').val()]];
        map.setView([
            box[0][0]-(box[0][0]-box[1][0])/2,
            box[0][1]-(box[0][1]-box[1][1])/2
        ]);
    } else {
        var bounds = map.getBounds();
        var center = bounds.getCenter();
        box = [
            [bounds.getNorth() - (bounds.getNorth() - center.lat)/2,
                bounds.getWest() - (bounds.getWest() - center.lng)/2],
            [bounds.getSouth() - (bounds.getSouth() - center.lat)/2,
                bounds.getEast() - (bounds.getEast() - center.lng)/2]
        ];
    }
    drawnItems.addLayer(L.rectangle(box));

    var edit = (new L.EditToolbar.Edit(map,{
        featureGroup:drawnItems,
        selectedPathOptions:L.EditToolbar.prototype.options.edit.selectedPathOptions
    }));
    edit.enable();

    $("form").submit(function(e){
        var box = drawnItems.getLayers()[0].getBounds();
        $('#Event_start_lat').val(box.getNorth());
        $('#Event_start_lng').val(box.getWest());
        $('#Event_end_lat').val(box.getSouth());
        $('#Event_end_lng').val(box.getEast());
    });
</script>
<?php $this->endWidget(); ?>

</div><!-- form -->