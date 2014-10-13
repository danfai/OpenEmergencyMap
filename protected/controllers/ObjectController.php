<?php

class ObjectController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
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
        echo CJSON::encode(Object::model()->with('coordinates','attributes')->findAllByBBox($coord[0], $coord[2], $coord[1], $coord[3]));
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
        ObjectCoordinates::model()->deleteAllByAttributes(array('object_id'=>$_POST['id']));
        ObjectAttributes::model()->deleteAllByAttributes(array('object_id'=>$_POST['id']));
        echo CJSON::encode(Object::model()->deleteByPk($_POST['id']));
        Yii::app()->end();
    }


    public function actionCreate(){
        if(!(isset($_POST['type']) &&
            isset($_POST['name']) &&
            isset($_POST['description']) &&
            isset($_POST['coordinates']))){
            throw new CHttpException(400, "You have to send the correct parameters.");
        }

        $object = new Object('insert');
        $object->type = $_POST['type'];
        $object->name = $_POST['name'];
        $object->description = $_POST['description'];

        $trans = Yii::app()->db->beginTransaction();
        $object->insert();

        foreach($_POST['coordinates'] AS $index => $coord) {
            $oc = new ObjectCoordinates('insert');
            $oc->lat = $coord['lat'];
            $oc->lng = $coord['lng'];
            $oc->index = $index;
            $oc->object_id = $object->id;
            $oc->insert();
        }

        if(isset($_POST['attributes'])) {
            foreach($_POST['attributes'] AS $key => $value) {
                $oa = new ObjectAttributes('insert');
                $oa->setIsNewRecord(true);
                $oa->key = $key;
                $oa->value = $value;
                $oa->object_id = $object->id;
                $oa->insert();
            }
        }

        $trans->commit();

        echo CJSON::encode($object);
    }

    public function actionEdit(){
        if(!isset($_POST['id']) || !isset($_POST['coordinates'])) {
            throw new CHttpException(400, "You have to send the correct parameters.");
        }

        $object = Object::model()->findByPk((int) $_POST['id']);
        if(isset($_POST['type']))
            $object->type = $_POST['type'];
        if(isset($_POST['name']))
            $object->name = $_POST['name'];
        if(isset($_POST['description']))
            $object->description = $_POST['description'];

        $trans = Yii::app()->db->beginTransaction();
        $object->save();

        ObjectCoordinates::model()->deleteAllByAttributes(array('object_id' => $object->id));

        foreach($_POST['coordinates'] AS $index => $coord) {
            $oc = new ObjectCoordinates('insert');
            $oc->setIsNewRecord(true);
            $oc->lat = $coord['lat'];
            $oc->lng = $coord['lng'];
            $oc->index = $index;
            $oc->object_id = $object->id;
            $oc->insert();
        }

        if(isset($_POST['attributes'])) {
            ObjectAttributes::model()->deleteAllByAttributes(array('object_id' => $object->id));

            foreach($_POST['attributes'] AS $key => $value) {
                $oa = new ObjectAttributes('insert');
                $oa->setIsNewRecord(true);
                $oa->key = $key;
                $oa->value = $value;
                $oa->object_id = $object->id;
                $oa->insert();
            }
        }

        $trans->commit();

        echo CJSON::encode(Object::model()->with('coordinates')->findByPk($object->id));
    }

}
