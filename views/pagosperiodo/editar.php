<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sede;
use app\models\PagosPeriodo;
use kartik\date\DatePicker;

$this->title = 'Editar Pago Periodo';
?>

<h1>Editar registro con Consecutivo <?= Html::encode($_GET["consecutivo"]) ?></h1>
<?php if ($tipomsg == "danger") { ?>
    <h3 class="alert-danger"><?= $msg ?></h3>
<?php } else{ ?>
    <h3 class="alert-success"><?= $msg ?></h3>
<?php } ?>

<?php $form = ActiveForm::begin([
    "method" => "post",
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
]);
?>

<?php
$sede = ArrayHelper::map(Sede::find()->all(), 'sede','sede');
$periodos = PagosPeriodo::find()
            ->groupBy('mensualidad')
            ->orderBy('nropago desc')
            ->all();
$periodos = ArrayHelper::map($periodos, "mensualidad", "mensualidad");
?>

<div class="row" id="matricula">
    <div class="col-lg-3">
        <?= $form->field($model, 'nropago')->input("text") ?>
        <?= $form->field($model, 'sede')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'mensualidad')->dropDownList($periodos, ['prompt' => 'Seleccione...']) ?>        
        <?= $form->field($model, 'identificacion')->input("text") ?>                       
        <?= $form->field($model, 'cuota')->input("text") ?>
        <?= $form->field($model, 'valorpagado')->input("text") ?>        
        <?= $form->field($model, 'pagado')->dropDownList(['1' => 'SI', '0' => 'NO'],['prompt' => 'Seleccione...' ]) ?>        
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Actualizar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("pagosperiodo/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
