<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sede;
use kartik\date\DatePicker;
use app\models\Horario;

$this->title = 'Editar Matricula';
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
$sede = ArrayHelper::map(\app\models\Sede::find()->all(), 'sede','sede');
$nivel = ArrayHelper::map(\app\models\Nivel::find()->all(), 'nivel','nivel');
$docentes = app\models\Inscritos::find()->Where(['=', 'tipo_personal', 'Docente'])->all();
$docentes = ArrayHelper::map($docentes, "identificacion", "nombredocente");
?>

<div class="row" id="matricula">
    <div class="col-lg-3">
        <?= $form->field($model, 'consecutivo')->input("hidden") ?>
        <?= $form->field($model, 'identificacion')->input("text") ?>
        <?= $form->field($model,'fechamat')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>        
        <?= $form->field($model, 'nivel')->dropDownList($nivel,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'valor_matricula')->input("text") ?>        
        <?= $form->field($model, 'valor_mensual')->input("text") ?>
        <?= $form->field($model, 'docente')->dropDownList($docentes,['prompt' => 'Seleccione...' ]) ?>        
        <?= $form->field($model, 'sede')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'tipo_jornada')->dropdownList(['Semana' => 'Semana', 'Sabado' => 'Sabado', 'Domingo' => 'Domingo'], ['prompt' => 'Seleccione...']) ?>
        <?php $rows = Horario::find()->all();
        if ($model->horario){
            $horario = explode("-", $model->horario);
            $de = $horario[0];
            $hasta = $horario[1];
        }else{
            $de = '';
            $hasta = '';
        }
                
        echo "<b>De</b> <select id='de' name='de' class='form-control'>";
        echo "<option value='$de' required>$de</option>";
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                echo "<option value='$row->horario' required>$row->horario</option>";
            }
        }
        echo "</select><br>";
        echo "<b>Hasta</b> <select id='hasta' name='hasta' class='form-control'>";
        echo "<option value='$hasta' required>$hasta</option>";
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                echo "<option value='$row->horario' required>$row->horario</option>";
            }
        }
        echo "</select><br>";
        ?>
        <?php 
            $cadena= $model->dias;
            $lunes = '';
            $martes = '';
            $miercoles = '';
            $jueves = '';
            $viernes = '';
            $sabado = '';
            $domingo = ''; 
            $checklunes = '';
            $checkmartes = '';
            $checkmiercoles = '';
            $checkjueves = '';
            $checkviernes = '';
            $checksabado = '';
            $checkdomingo = '';
            //$total = count($cadena);
            for($i=0;$i<strlen($cadena);$i++){
                $d = $cadena[$i];
                if ($cadena[$i] == 'l' or $cadena[$i] == 'L'){
                    $lunes = 'L';
                    $checklunes = "checked='true'";
                }
                if ($cadena[$i] == 'm' or $cadena[$i] == 'M'){
                    $martes = 'M';
                    $checkmartes = "checked='true'";
                }
                if ($cadena[$i] == 'W' or $cadena[$i] == 'w'){
                    $miercoles = 'W';
                    $checkmiercoles = "checked='true'";
                }
                if ($cadena[$i] == 'j' or $cadena[$i] == 'J'){
                    $jueves = 'J';
                    $checkjueves = "checked='true'";
                }
                if ($cadena[$i] == 'v' or $cadena[$i] == 'V'){
                    $viernes = 'V';
                    $checkviernes = "checked='true'";
                }
                if ($cadena[$i] == 'S' or $cadena[$i] == 's'){
                    $sabado = 'S';
                    $checksabado = "checked='true'";
                }
                if ($cadena[$i] == 'd' or $cadena[$i] == 'D'){
                    $domingo = 'D';
                    $checkdomingo = "checked='true'";
                }
            } 
        ?>
        <input type="checkbox" id="lunes" name="lunes" value="L" <?php echo $checklunes; ?>> Lunes <br>
        <input type="checkbox" id="martes" name="martes" value="M" <?php echo $checkmartes; ?>> Martes<br>
        <input type="checkbox" id="miercoles" name="miercoles" value="W" <?php echo $checkmiercoles; ?>> Miercoles <br>
        <input type="checkbox" id="jueves" name="jueves" value="J" <?php echo $checkjueves; ?>> Jueves <br>
        <input type="checkbox" id="viernes" name="viernes" value="V" <?php echo $checkviernes; ?>> Viernes <br>
        <input type="checkbox" id="sabado" name="sabado" value="S" <?php echo $checksabado; ?>> Sabado <br>
        <input type="checkbox" id="domingo" name="domingo" value="D" <?php echo $checkdomingo; ?>> Domingo <br>
        <?= $form->field($model, 'seguro')->input("text") ?>
        <br>
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Actualizar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("matriculas/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
