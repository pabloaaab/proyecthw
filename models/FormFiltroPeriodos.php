<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroPeriodos extends Model
{
    public $mes;

    public function rules()
    {
        return [

            ['mes', 'match', 'pattern' => '/^[a-z0-9-\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mes' => 'Periodo:',
        ];
    }
}
