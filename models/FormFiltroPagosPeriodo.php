<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroPagosPeriodo extends Model
{
    public $identificacion;
    //public $nivel;
    public $mensualidad;
    public $sede;
    public $anulado;
    public $pagado;

    public function rules()
    {
        return [

            ['identificacion', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            //['nivel', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
            ['mensualidad', 'match', 'pattern' => '/^[a-z0-9-\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
            ['sede', 'match', 'pattern' => '/^[a-z\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['anulado', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['pagado', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'Nro identificación:',
            //'nivel' => 'Nivel:',
            'mensualidad' => 'Mensualidad:',
            'sede' => 'Sede:',
            'anulado' => 'Anulado:',
            'pagado' => 'Pagado:',
        ];
    }
}