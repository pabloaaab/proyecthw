<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Pagos;

/**
 * ContactForm is the model behind the contact form.
 */
class FormOtrosPagos extends Model
{    
    public $identificacion;
    public $mensualidad;
    public $pago1;    
    public $observaciones;
    public $ttpago;    

    public function rules()
    {
        return [

            [['identificacion', 'mensualidad', 'pago1', 'observaciones','ttpago'], 'required', 'message' => 'Campo requerido'],
            ['identificacion', 'identificacion_no_existe'],                        
            [['mensualidad', 'observaciones','ttpago'], 'string'],
            [['pago1'], 'number'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'N° Identificacion:',
            'mensualidad' => 'Pago:',
            'pago1' => 'Valor Pago:',
            'observaciones' => 'observaciones:',  
            'ttpago' => 'Tipo Pago:',  
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
