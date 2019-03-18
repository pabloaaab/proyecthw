<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sede;
use app\models\Horario;
use kartik\date\DatePicker;

$this->title = 'Nueva Matricula';
?>

<h1>Nueva matricula</h1>
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

<?php
$sede = ArrayHelper::map(\app\models\Sede::find()->where(['=', 'estado', 1])->all(), 'sede', 'sede');
$nivel = ArrayHelper::map(\app\models\Nivel::find()->all(), 'nivel', 'nivel');
$docentes = app\models\Inscritos::find()->Where(['=', 'tipo_personal', 'Docente'])->all();
$docentes = ArrayHelper::map($docentes, "identificacion", "nombredocente");
?>

<div class="row" id="matricula">
    <div class="col-lg-3">
        <?= $form->field($model, 'consecutivo')->input("hidden") ?>
        <?= $form->field($model, 'identificacion')->input("text") ?>        
        <?=
        $form->field($model, 'fechamat')->widget(DatePicker::className(), ['name' => 'check_issue_date',
            'value' => date('d-M-Y', strtotime('+2 days')),
            'options' => ['placeholder' => 'Seleccione una fecha ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true]])
        ?>
        <?= $form->field($model, 'nivel')->dropDownList($nivel, ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'valor_matricula')->input("text") ?>
        <?= $form->field($model, 'valor_mensual')->input("text") ?>        
        <?= $form->field($model, 'docente')->dropDownList($docentes, ['prompt' => 'Seleccione...']) ?>        
        <?= $form->field($model, 'sede')->dropDownList($sede, ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'tipo_jornada')->dropdownList(['Semana' => 'Semana', 'Sabado' => 'Sabado', 'Domingo' => 'Domingo'], ['prompt' => 'Seleccione...']) ?>
        
        <?php $rows = Horario::find()->all();
        echo "<b>De</b> <select id='de' name='de' class='form-control'>";
        echo "<option required>Seleccione...</option>";
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                echo "<option value='$row->horario' required>$row->horario</option>";
            }
        }
        echo "</select><br>";
        echo "<b>Hasta</b> <select id='hasta' name='hasta' class='form-control'>";
        echo "<option required>Seleccione...</option>";
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                echo "<option value='$row->horario' required>$row->horario</option>";
            }
        }
        echo "</select><br>";
        ?>
        <input type="checkbox" id="lunes" name="lunes" value="L" > Lunes <br>
        <input type="checkbox" id="martes" name="martes" value="M" > Martes<br>
        <input type="checkbox" id="miercoles" name="miercoles" value="W" > Miercoles <br>
        <input type="checkbox" id="jueves" name="jueves" value="J" > Jueves <br>
        <input type="checkbox" id="viernes" name="viernes" value="V" > Viernes <br>
        <input type="checkbox" id="sabado" name="sabado" value="S" > Sabado <br>
        <input type="checkbox" id="domingo" name="domingo" value="D" > Domingo <br>
        <?= $form->field($model, 'seguro')->input("text") ?>
        <br>
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"]) ?>
        <a href="<?= Url::toRoute("matriculas/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
