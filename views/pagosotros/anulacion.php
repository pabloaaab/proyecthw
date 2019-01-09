<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sede;

$this->title = 'Anular Pago';
?>

<h1>Anular registro con nropago <?= Html::encode($_GET["nropago"]) ?></h1>
<?php if ($tipomsg == "danger") { ?>
    <h3 class="alert-danger"><?= $msg ?></h3>
<?php } else { ?>
    <h3 class="alert-success"><?= $msg ?></h3>
<?php } ?>

<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
        ]);
?>

<div class="row" id="Pagosotros">
    <div class="col-lg-3">                               
        <?= $form->field($model, 'fechaanulado')->input("text") ?>                
        <?= $form->field($model, 'motivo')->textArea(['maxlength' => true]) ?>      
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"]) ?>
        <a href="<?= Url::toRoute("pagosotros/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
