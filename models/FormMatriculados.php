<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Matriculados;


class FormMatriculados extends Model
{
    public $consecutivo;
    public $identificacion;
    public $fechamat;
    public $acudiente1;
    public $observaciones;    
    public $nivel;    
    public $valor_matricula;    
    public $valor_mensual;    
    public $docente;    
    public $sede;    
    public $tipo_jornada;    
    public $horario;    
    public $dias;    
    public $motivo_can;
    public $grupo;    
    public $ultimo_periodo_generado;
    public $estado;
    public $estado2;
    public $fecha_cierre;
    public $fecha_ren1;
    public $programa1;
    public $programa2;
    public $fecha_can;
    public $acudiente2;
    
    public function rules()
    {
        return [
            [['identificacion', 'fechamat', 'nivel', 'valor_matricula', 'valor_mensual', 'docente', 'sede','tipo_jornada'], 'required', 'message' => 'Campo requerido'],
            ['identificacion', 'identificacion_no_existe'],
            ['nivel', 'identificacion_nivel_abierto'],
            ['nivel', 'identificacion_nivel_aprobado'],
            [['fechamat', 'fecha_ren1', 'fecha_ren2', 'fecha_can','fecha_cierre'], 'safe'],
            [['motivo_can', 'observaciones','tipo_jornada'], 'string'],
            [['valor_matricula', 'valor_mensual'], 'number'],
            [['grupo', 'programa1', 'acudiente1', 'programa2', 'acudiente2'], 'string', 'max' => 50],
            [['identificacion', 'docente', 'ultimo_periodo_generado'], 'string', 'max' => 20],
            [['estado', 'nivel', 'sede', 'estado2','horario','dias'], 'string', 'max' => 25],
            [['identificacion'], 'exist', 'skipOnError' => true, 'targetClass' => Inscritos::className(), 'targetAttribute' => ['identificacion' => 'identificacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'consecutivo' => '',
            'grupo' => 'Grupo',
            'identificacion' => 'Identificacion',
            'fechamat' => 'Fecha Matricula',
            'fecha_ren1' => 'Fecha Ren1',
            'programa1' => 'Programa1',
            'acudiente1' => 'Acudiente1',
            'fecha_ren2' => 'Fecha Ren2',
            'programa2' => 'Programa2',
            'acudiente2' => 'Acudiente2',
            'fecha_can' => 'Fecha Can',
            'motivo_can' => 'Motivo Can',
            'observaciones' => 'Observaciones',
            'estado' => 'Estado',
            'nivel' => 'Nivel',
            'valor_matricula' => 'Valor Matricula',
            'valor_mensual' => 'Valor Mensual',
            'docente' => 'Docente',
            'sede' => 'Sede',
            'ultimo_periodo_generado' => 'Ultimo Periodo Generado',
            'estado2' => 'Estado2',
            'fecha_cierre' => 'Fecha Cierre',
            'tipo_jornada' => 'Jornada',
            //'horario' => 'Horario',
            //'dias' => 'Días',
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
    
    public function identificacion_nivel_abierto($attribute, $params)
    {
        //Buscar el email en la tabla
        $table = Matriculados::find()->where("nivel=:nivel", [":nivel" => $this->nivel])->andWhere("identificacion=:identificacion", [':identificacion' => $this->identificacion])->andWhere("estado2=:estado2", [':estado2' => 'ABIERTA'])->andWhere("consecutivo=:consecutivo", [':consecutivo' => $this->consecutivo]);
        //Si el email existe mostrar el error
        if ($table->count() > 0)
        {
            $this->addError($attribute, "El número de identificación ya tiene una matricula abierta para el nivel ".$this->nivel);
        }
    }
    
    public function identificacion_nivel_aprobado($attribute, $params)
    {
        //Buscar el email en la tabla
        $table = Matriculados::find()->where("nivel=:nivel", [":nivel" => $this->nivel])->andWhere("identificacion=:identificacion", [':identificacion' => $this->identificacion])->andWhere("estado2=:estado2", [':estado2' => 'APROBADA'])->andWhere("fechamat!=:fechamat", [':fechamat' => $this->fechamat]);
        //Si el email existe mostrar el error
        if ($table->count() > 0)
        {
            $this->addError($attribute, "El número de identificación ya tiene una matricula aprobada para el nivel ".$this->nivel);
        }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntificacion()
    {
        return $this->hasOne(Inscritos::className(), ['identificacion' => 'identificacion']);
    }
        
}
