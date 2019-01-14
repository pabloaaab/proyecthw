<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sede;

$this->title = 'Nuevo Pago Mensualidad';
?>

<h1>Nuevo Pago Mensualidad</h1>
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

<div class="row" id="matricula">
    <div class="col-lg-3">        
        <?= $form->field($model, 'identificacion')->input("text",['readonly' => true]) ?>
        <?= $form->field($model, 'mensualidad')->input("text",['readonly' => true]) ?>
        <?= $form->field($model, 'ttpago')->dropdownList(['Efectivo' => 'Efectivo', 'Consignacion' => 'Consignacion'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'bono')->dropdownList(['si' => 'SI']) ?>
        <?= $form->field($model, 'total')->input("text",['readonly' => true]) ?>
        <?= $form->field($model, 'observaciones')->textArea(['maxlength' => true, 'value' => 'Pago mensualidad de '.$model->mensualidad]) ?>
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("pagos/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
