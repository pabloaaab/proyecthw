<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Pagos;

/**
 * ContactForm is the model behind the contact form.
 */
class FormPagosAnula extends Model
{        
    public $fechaanulado;
    public $motivo;          

    public function rules()
    {
        return [

            [['fechaanulado', 'motivo'], 'required', 'message' => 'Campo requerido'],                     
            [['motivo'], 'string'],                        
        ];
    }

    public function attributeLabels()
    {
        return [
            'fechaanulado' => 'Fecha Anulación:',
            'motivo' => 'Motivo Anulación:',              
        ];
    }        
}
