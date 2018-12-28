<?php

namespace app\models;

use yii\db\ActiveRecord;

use Yii;
use yii\base\Model;

class Grupo extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db;
    }

    public static function tableName()
    {
        return 'grupos';
    }

    public function getInscritos()
    {
        return $this->hasMany('Inscritos', ['docente' => 'docente']);
    }
}