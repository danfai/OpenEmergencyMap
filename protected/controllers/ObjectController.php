<?php

class ObjectController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
		);
	}

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        throw new CHttpException(400, "Bad Request");
    }

    public function actionReceive()
    {
        if(!isset($_POST['bbox'])) {
            throw new CHttpException(400, "Please set Bounding Box Parameter.");
        }
        $coord = preg_split("/,/",$_POST['bbox']);
        if(sizeof($coord) != 4){
            throw new CHttpException(400, "Please set Bounding Box correctly.");
        }
        $coord[0] -= 0.01;
        $coord[1] -= 0.01;
        $coord[2] += 0.01;
        $coord[3] += 0.01;
        echo CJSON::encode(Object::model()->with('coordinates')->findAllByBBox($coord[0], $coord[2], $coord[1], $coord[3]));
        Yii::app()->end();
    }

    public function actionDetails()
    {
        if(!isset($_POST['id'])) {
            throw new CHttpException(400, "You have to send the correct parameters.");
        }
        echo CJSON::encode(Object::model()->with('attributes','coordinates')->findByPk($_POST['id']));
        Yii::app()->end();
    }

    public function actionDelete()
    {
        if(!isset($_POST['id'])) {
            throw new CHttpException(400, "You have to send the correct parameters.");
        }
        echo CJSON::encode(Object::model()->findByPk($_POST['id'])->delete());
        Yii::app()->end();
    }


    public function actionCreate(){
        if(!(isset($_POST['type']) &&
            isset($_POST['name']) &&
            isset($_POST['description']) &&
            isset($_POST['coordinates']))){
            Debug::log("Not enough parameter",false);
        }

        $object = Object::model();
        $object->setIsNewRecord(true);
        $object->type = $_POST['type'];
        $object->name = $_POST['name'];
        $object->description = $_POST['description'];
        $object->insert();

        foreach($_POST['coordinates'] AS $index => $coord) {
            $oc = ObjectCoordinates::model();
            $oc->setIsNewRecord(true);
            $oc->lat = $coord['lat'];
            $oc->lng = $coord['lng'];
            $oc->index = $index;
            $oc->object_id = $object->id;
            $oc->insert();
        }

        echo CJSON::encode($object);
    }

}