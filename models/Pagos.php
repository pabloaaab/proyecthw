<?php

namespace app\models;

use yii\db\ActiveRecord;

use Yii;
use yii\base\Model;

class Pagos extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db;
    }

    public static function tableName()
    {
        return 'pagos';
    }  
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntificacion()
    {
        return $this->hasOne(Inscritos::className(), ['identificacion' => 'identificacion']);
    }

}
