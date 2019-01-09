<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Matriculados;

/**
 * ContactForm is the model behind the contact form.
 */
class FormCancelarMatricula extends Model
{    
    public $fecha_can;
    public $motivo_can;
    
    public function rules()
    {
        return [
            [['fecha_can','motivo_can'], 'required', 'message' => 'Campo requerido'],                       
            [['fecha_can'], 'safe'],
            ['motivo_can', 'default'],

        ];
    }

    public function attributeLabels()
    {
        return [            
            'fecha_can' => 'Fecha Cancelación',
            'motivo_can' => 'Motivo Cancelar o Retiro',            
        ];
    }

}
