<?php

namespace app\models;

use yii\db\ActiveRecord;

use Yii;
use yii\base\Model;

class Inscritos extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db;
    }

    public static function tableName()
    {
        return 'inscritos';
    }
    
    public function getNombreDocente()
    {
        return "{$this->identificacion} - {$this->nombre1}  {$this->nombre2}  {$this->apellido1}  {$this->apellido2}";
    }
    
    public function getNombreEstudiante()
    {
        return "{$this->identificacion} - {$this->nombre1}  {$this->nombre2}  {$this->apellido1}  {$this->apellido2}";
    }
    
    public function getNombreEstudiante2()
    {
        return "{$this->nombre1}  {$this->nombre2}  {$this->apellido1}  {$this->apellido2}";
    }

}