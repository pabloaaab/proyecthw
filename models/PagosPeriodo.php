<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pagos_periodo".
 *
 * @property int $consecutivo
 * @property int $nropago
 * @property string $grupo
 * @property string $identificacion
 * @property string $mensualidad
 * @property int $pago1
 * @property int $pago2
 * @property int $pago3
 * @property int $total
 * @property string $fecha_registro
 * @property string $usuarioregistra
 * @property string $observaciones
 * @property string $anulado
 * @property string $fechaanulado
 * @property string $usuarioanula
 * @property string $motivo
 * @property string $bono
 * @property int $ttpago
 * @property int $factura
 * @property string $resolucion
 * @property int $afecta_pago
 * @property string $sede
 * @property int $cerro_grupo
 * @property string $fecha_cerro_grupo
 * @property string $nivel
 * @property int $matricula
 */
class PagosPeriodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pagos_periodo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nropago', 'identificacion', 'pago1', 'pago2', 'pago3', 'total', 'ttpago', 'factura', 'afecta_pago', 'cerro_grupo', 'matricula'], 'integer'],
            [['identificacion', 'mensualidad', 'pago1'], 'required'],
            [['fecha_registro', 'fechaanulado', 'fecha_cerro_grupo'], 'safe'],
            [['observaciones', 'resolucion'], 'string'],
            [['grupo', 'usuarioanula'], 'string', 'max' => 450],
            [['mensualidad'], 'string', 'max' => 180],
            [['usuarioregistra'], 'string', 'max' => 630],
            [['anulado'], 'string', 'max' => 90],
            [['motivo'], 'string', 'max' => 1350],
            [['bono'], 'string', 'max' => 27],
            [['sede'], 'string', 'max' => 150],
            [['nivel'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'consecutivo' => 'Consecutivo',
            'nropago' => 'Nropago',
            'grupo' => 'Grupo',
            'identificacion' => 'Identificacion',
            'mensualidad' => 'Mensualidad',
            'pago1' => 'Pago1',
            'pago2' => 'Pago2',
            'pago3' => 'Pago3',
            'total' => 'Total',
            'fecha_registro' => 'Fecha Registro',
            'usuarioregistra' => 'Usuarioregistra',
            'observaciones' => 'Observaciones',
            'anulado' => 'Anulado',
            'fechaanulado' => 'Fechaanulado',
            'usuarioanula' => 'Usuarioanula',
            'motivo' => 'Motivo',
            'bono' => 'Bono',
            'ttpago' => 'Ttpago',
            'factura' => 'Factura',
            'resolucion' => 'Resolucion',
            'afecta_pago' => 'Afecta Pago',
            'sede' => 'Sede',
            'cerro_grupo' => 'Cerro Grupo',
            'fecha_cerro_grupo' => 'Fecha Cerro Grupo',
            'nivel' => 'Nivel',
            'matricula' => 'Matricula',
        ];
    }
    
    public function getNombres()
    {
        $estudiante = Inscritos::find()->where(['=','identificacion',$this->identificacion])->one();
        return $estudiante->nombre1.' '.$estudiante->nombre2.' '.$estudiante->apellido1.' '.$estudiante->apellido2;
    }
}
