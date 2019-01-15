<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroInformesPagos extends Model
{
    public $identificacion;
    //public $nivel;
    public $fechapago;
    public $sede;
    public $tipo_pago;
    public $anio_mes_dia;    

    public function rules()
    {
        return [

            ['identificacion', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            //['nivel', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
            ['fechapago', 'safe'],            
            ['sede', 'match', 'pattern' => '/^[a-z\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['tipo_pago', 'match', 'pattern' => '/^[a-z\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['anio_mes_dia', 'match', 'pattern' => '/^[a-z\s]+$/i', 'message' => 'Sólo se aceptan letras'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'Nro identificación:',
            //'nivel' => 'Nivel:',
            'fechapago' => 'Fecha Dia:',
            'sede' => 'Sede:',
            'tipo_pago' => 'Tipo de Pago:',
            'anio_mes_dia' => 'Fecha Año-Mes-Día:',
        ];
    }
}
