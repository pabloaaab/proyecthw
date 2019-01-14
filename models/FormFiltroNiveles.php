<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroNiveles extends Model
{    
    public $nivel;    
    public $sede;

    public function rules()
    {
        return [            
            ['nivel', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],            
            ['sede', 'match', 'pattern' => '/^[a-z\s]+$/i', 'message' => 'Sólo se aceptan letras'],
        ];
    }

    public function attributeLabels()
    {
        return [            
            'nivel' => 'Nivel:',            
            'sede' => 'Sede:',
        ];
    }
}
