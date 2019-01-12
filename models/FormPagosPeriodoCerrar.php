<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\PagosPeriodo;

/**
 * ContactForm is the model behind the contact form.
 */
class FormPagosPeriodoCerrar extends Model
{    
    public $fecha_cerro_grupo;
    public $cerro_grupo;
    
    public function rules()
    {
        return [
            [['fecha_cerro_grupo','cerro_grupo'], 'required', 'message' => 'Campo requerido'],                       
            [['fecha_cerro_grupo'], 'safe'],
            ['cerro_grupo', 'default'],

        ];
    }

    public function attributeLabels()
    {
        return [            
            'fecha_cerro_grupo' => 'Fecha Cierre',
            'cerro_grupo' => 'Cerrar grupo?',            
        ];
    }

}
