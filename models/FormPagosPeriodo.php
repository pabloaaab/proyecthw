<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Pagos;

/**
 * ContactForm is the model behind the contact form.
 */
class FormPagosPeriodo extends Model
{    
    public $nropago;
    public $sede;
    public $mensualidad;
    public $identificacion;    
    public $cuota;    
    public $valorpagado;
    public $pagado;    

    public function rules()
    {
        return [

            [['identificacion', 'sede','mensualidad','cuota','valorpagado','pagado'], 'required', 'message' => 'Campo requerido'],
            ['identificacion', 'identificacion_no_existe'],                        
            [['mensualidad', 'sede','pagado'], 'string'],
            [['cuota','valorpagado'], 'number'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'N° Identificacion:',
            'mensualidad' => 'Mensualidad:',
            'sede' => 'Sede:',
            'cuota' => 'Cuota a Pagar:',  
            'valorpagado' => 'Valor Pagado:',
            'pagado' => 'Pagado:',            
            'nropago' => 'N° Pago:',
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
