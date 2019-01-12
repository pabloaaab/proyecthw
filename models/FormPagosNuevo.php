<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Pagos;

/**
 * ContactForm is the model behind the contact form.
 */
class FormPagosNuevo extends Model
{    
    public $identificacion;
    public $mensualidad;
    public $total;    
    public $observaciones;
    public $ttpago;
    public $bono;

    public function rules()
    {
        return [

            [['identificacion', 'mensualidad', 'total', 'observaciones','ttpago','bono'], 'required', 'message' => 'Campo requerido'],
            ['identificacion', 'identificacion_no_existe'],                        
            [['mensualidad', 'observaciones','ttpago','bono'], 'string'],
            [['total'], 'number'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'N° Identificacion:',
            'mensualidad' => 'Mensualidad:',
            'total' => 'Valor Pago:',
            'observaciones' => 'observaciones:',  
            'ttpago' => 'Tipo Pago:',
            'bono' => 'Bono:',
        ];
    }
    
    public function identificacion_no_existe($attribute, $params)
    {
        //Buscar la cedula/nit en la tabla
        $table = Inscritos::find()->where("identificacion=:identificacion", [":identificacion" => $this->identificacion]);
        //Si la identificacion no existe en inscritos mostrar el error
        if ($table->count() == 0)
        {
            $this->addError($attribute, "El número de identificación No existe en inscritos, por favor realizar la inscripción");
        }
    }

}
