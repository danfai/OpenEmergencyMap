<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    public function registerScripts() {
        $cs = Yii::app()->clientScript;
        $baseUrl = Yii::app()->baseUrl . '/static';
        $cs->registerPackage('jquery');
        $cs->registerPackage('jquery.ui');
        $cs->registerScriptFile($baseUrl . '/js/leaflet.js');
        $cs->registerScriptFile($baseUrl . '/js/leaflet.draw.js');
        $cs->registerScriptFile($baseUrl. '/js/leaflet-hash.js');
//        $cs->registerScriptFile($this->assetsDir . '/js/leaflet.markercluster.js');

        $cs->registerCssFile($baseUrl . "/css/jquery-ui.css");
        $cs->registerCssFile($baseUrl . "/css/leaflet.css");
//        $cs->registerCssFile($baseUrl . "/css/MarkerCluster.css");
//        $cs->registerCssFile($baseUrl . "/css/MarkerCluster.Default.css");
        $cs->registerCssFile($baseUrl . "/css/leaflet.draw.css");
    }

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
}