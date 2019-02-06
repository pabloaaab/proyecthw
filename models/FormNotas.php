<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormNotas extends Model
{
    public $identificacion;
    public $docente;
    public $nivel;    
    public $tipo_jornada;
    public $horario;    
    public $dias;

    public function rules()
    {
        return [                        
            ['identificacion', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['docente', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['nivel', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan números y letras'],
            ['tipo_jornada', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan números y letras'],
            ['horario', 'match', 'pattern' => '/^[-a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
            ['dias', 'match', 'pattern' => '/^[-a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'N° Identificación:',
            'docente' => 'Docente:',            
            'nivel' => 'Nivel:',
            'tipo_jornada' => 'Jornada:',
            'horario' => 'Horario:',
            'dias' => 'Días:',
        ];
    }
}
