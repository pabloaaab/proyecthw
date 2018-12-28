<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Grupo;

/**
 * ContactForm is the model behind the contact form.
 */
class FormGrupo extends Model
{
    public $consecutivo;
    public $grupo;
    public $nivel;
    public $fechaInicio;
    public $jornada;
    public $sede;
    public $tipo_horario;
    public $docente;
    public $de;
    public $a;
    public $cuota_mensual;
    public $estado;
    public $ultimo_periodo_generado;
    public $lunes;
    public $martes;
    public $miercoles;
    public $jueves;
    public $viernes;


    public function rules()
    {
        return [

            ['consecutivo', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['nivel', 'required', 'message' => 'Campo requerido'],
            ['fechaInicio', 'required', 'message' => 'Campo requerido'],
            ['jornada', 'required', 'message' => 'Campo requerido'],
            ['sede', 'required', 'message' => 'Campo requerido'],
            ['tipo_horario', 'required', 'message' => 'Campo requerido'],
            ['docente', 'required', 'message' => 'Campo requerido'],
            ['de', 'required', 'message' => 'Campo requerido'],
            ['a', 'required', 'message' => 'Campo requerido'],
            ['cuota_mensual', 'required', 'message' => 'Campo requerido'],
            ['cuota_mensual', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['lunes', 'default'],
            ['martes', 'default'],
            ['miercoles', 'default'],
            ['jueves', 'default'],
            ['viernes', 'default'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'consecutivo' => '',
            'nivel' => 'Nivel:',
            'fechaInicio' => 'Fecha Inicio:',
            'jornada' => 'Jornada:',
            'sede' => 'Sede:',
            'tipo_jornada' => 'Tipo Jornada:',
            'docente' => 'Docente:',
            'de' => 'Desde:',
            'a' => 'Hasta:',
            'cuota_mensual' => 'Mensualidad:',
            'lunes' => 'Lunes:',
            'martes' => 'Martes:',
            'miercoles' => 'Miercoles:',
            'jueves' => 'Jueves:',
            'viernes' => 'Viernes:',
        ];
    }

}
