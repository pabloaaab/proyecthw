<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroMatriculas extends Model
{
    public $identificacion;
    public $nivel;
    public $docente;
    public $sede;

    public function rules()
    {
        return [

            ['identificacion', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['nivel', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
            ['docente', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['sede', 'match', 'pattern' => '/^[a-z\s]+$/i', 'message' => 'Sólo se aceptan letras'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'Nro identificación:',
            'nivel' => 'Nivel:',
            'docente' => 'N° Docente:',
            'sede' => 'Sede:',
        ];
    }
}
