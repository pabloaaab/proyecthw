<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Grupos Abiertos';
?>

    <h1>Grupos Abiertos</h1>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("grupos/grupo_matricula"),
    "enableClientValidation" => true,
]);

$total = "";
?>

<div class="form-group">
    <?= $f->field($form, "q")->input("search") ?>
</div>

<div class="row" >
    <div class="col-lg-4">
        <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
        <a align="right" href="<?= Url::toRoute("grupos/grupo_matricula") ?>" class="btn btn-primary">Actualizar</a>
    </div>
</div>
<?php $f->end() ?>

<h3><?= $search ?></h3>


    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Grupo</th>
                <th scope="col">Nivel</th>
                <th scope="col">Sede</th>
                <th scope="col">Tipo Horario</th>
                <th scope="col">Días</th>
                <th scope="col">Jornada</th>
                <th scope="col">Horario</th>
                <th scope="col">Docente</th>
                <th scope="col">N° Estudiantes</th>
                <th scope="col">Periodo Generado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <?php $lunes = "";
            $martes = "";
            $miercoles = "";
            $jueves = "";
            $viernes = "";
            ?>
            <?php if ($val['lunes'] == "1"){$lunes = "L";} ?>
            <?php if ($val['martes'] == "1"){$martes = "M";} ?>
            <?php if ($val['miercoles'] == "1"){$miercoles = "W";} ?>
            <?php if ($val['jueves'] == "1"){$jueves = "J";} ?>
            <?php if ($val['viernes'] == "1"){$viernes = "V";} ?>
            <tr>
                <th scope="row"><?= $val['consecutivo'] ?></th>
                <td><?= $val['nivel'] ?></td>
                <td><?= $val['sede'] ?></td>
                <td><?= $val['tipo_horario'] ?></td>
                <td><?= $lunes." - ".$martes." - ".$miercoles." - ".$jueves." - ".$viernes ?></td>
                <td><?= $val['jornada'] ?></td>
                <td><?= $val['de']." - ".$val['a'] ?></td>
                <td><?= $val['nombre1'].' '.$val['nombre2'].' '.$val['apellido1'].' '.$val['apellido2'] ?></td>
                <?php foreach ($conteo as $val2): ?>
                    <?php if ($val2['grupo'] == $val['consecutivo']){$total = $val2['total'];} ?>
                <?php endforeach; ?>
                <td><?= $total ?></td>
                <td><?= $val['ultimo_periodo_generado'] ?></td>
                <td><a href="<?= Url::toRoute(["grupos/editar", "consecutivo" => $val['consecutivo']]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>


    </div>

