<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sede;

$this->title = 'Aprobar Matricula';
?>

<h1>Aprobar registro con Consecutivo <?= Html::encode($_GET["consecutivo"]) ?></h1>
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

<div class="row" id="matricula">
    <div class="col-lg-3">                       
        <?= $form->field($model, 'observaciones')->textArea(['maxlength' => true]) ?>      
        <?= $form->field($model, 'fecha_cierre')->input("text") ?>                
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"]) ?>
        <a href="<?= Url::toRoute("matriculas/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
