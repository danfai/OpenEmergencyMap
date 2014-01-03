<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <?php $this->registerScripts(); ?>

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>

        <div id="mainmenu">
            <?php $this->widget('zii.widgets.CMenu',array(
                'items'=>array(
                    array('label'=>'Home', 'url'=>array('/site/index')),
//				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
                    array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                    array('label'=>'Registration', 'url'=>array('/site/register'), 'visible'=>Yii::app()->user->isGuest),

                    array('label'=>'Preset', 'url'=>array('/preset/index'), 'visible'=>!Yii::app()->user->isGuest),
                    array('label'=>'Event', 'url'=>array('/event/index'), 'visible'=>!Yii::app()->user->isGuest),
                    array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                ),
            )); ?>
        </div><!-- mainmenu -->
	</div><!-- header -->

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<!--<div id="footer">
		<?php //echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

<script type="text/javascript">
    function notifyUser(message,classes){
        if(classes.constructor == Array){
            classes = classes.join(" ");
        }
        console.log(message,classes);
        var div = $("<div></div>");
        div.text(message);
        div.addClass(classes);
        $("#message").append(div);
        div.show(400);
        window.setTimeout(function(){
            div.hide(400).delay(405).remove();
        },3800);
    }

    $(function(){
        $(document).ajaxSuccess(function(e, xhr, settings){
            //Überprüfung, ob das Empfangene im JSON-Format ist
            if(settings.dataType == "json"){
                var data = $.parseJSON(xhr.responseText);
                if(data.message && data.message.length > 0){
                    notifyUser(data.message,(data.success ? "notification" : "error"));
                }
            }
            $("#loading").hide();
        }).ajaxError(function(e, xhr, settings, error){
                $("#loading").hide();
            }).ajaxStart(function(){
                $("#loading").show();
            }).ajaxStop(function(){
                $("#loading").hide();
            }).ajaxError(function(){
                $("#loading").hide();
            });

        $("#message div").click(function(){
            $(this).hide();
            $(this).remove();
        });
    });
</script>
</body>
</html>
