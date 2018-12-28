<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroInscritos extends Model
{
    public $q;

    public function rules()
    {
        return [

            ['q', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'q' => 'Buscar:',
        ];
    }
}
