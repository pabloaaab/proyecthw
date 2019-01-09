<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Matriculados;

/**
 * ContactForm is the model behind the contact form.
 */
class FormAprobarMatricula extends Model
{    
    public $observaciones;
    public $fecha_cierre;
    
    public function rules()
    {
        return [
            [['fecha_cierre','observaciones'], 'required', 'message' => 'Campo requerido'],                       
            [['fecha_cierre'], 'safe'],
            ['observaciones', 'default'],

        ];
    }

    public function attributeLabels()
    {
        return [            
            'fecha_cierre' => 'Fecha Cierre',
            'observaciones' => 'Observaciones',            
        ];
    }

}
