<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Sede;

/**
 * ContactForm is the model behind the contact form.
 */
class FormSede extends Model
{
    public $consecutivo;
    public $sede;

    public function rules()
    {
        return [

            ['consecutivo', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['sede', 'sede_existe'],
            ['sede', 'required', 'message' => 'Campo requerido'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'consecutivo' => '',
            'sede' => 'Sede:',

        ];
    }

    public function sede_existe($attribute, $params)
    {
        //Buscar la sede en la tabla
        $table = Sede::find()->where("sede=:sede", [":sede" => $this->sede])->andWhere("consecutivo!=:consecutivo", [':consecutivo' => $this->consecutivo]);
        //Si la sede existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "La Sede ya existe ".$this->sede);
        }
    }
}
