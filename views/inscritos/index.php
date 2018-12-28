<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Inscritos   ';
?>

    <h1>Inscritos</h1>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("inscritos/index"),
    "enableClientValidation" => true,
]);
?>

<div class="form-group">
    <?= $f->field($form, "q")->input("search") ?>
</div>

<div class="row" >
    <div class="col-lg-4">
        <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
        <a align="right" href="<?= Url::toRoute("inscritos/index") ?>" class="btn btn-primary">Actualizar</a>
    </div>
</div>
<?php $f->end() ?>

<h3><?= $search ?></h3>

    <div class = "form-group" align="right">
        <a align="right" href="<?= Url::toRoute("inscritos/nuevo") ?>" class="btn btn-primary">Nuevo Inscrito</a>
    </div>

    <div class="container-fluid">
        <div class="col-lg-2">

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Tipo Doc</th>
                <th scope="col">Identificación</th>
                <th scope="col">Nombre_1</th>
                <th scope="col">Nombre_2</th>
                <th scope="col">Apellido_1</th>
                <th scope="col">Apellido_2</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Municipio</th>
                <th scope="col">Email</th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>
                <th scope="row"><?= $val->consecutivo ?></th>
                <td><?= $val->tipo_doc ?></td>
                <td><?= $val->identificacion ?></td>
                <td><?= $val->nombre1 ?></td>
                <td><?= $val->nombre2 ?></td>
                <td><?= $val->apellido1 ?></td>
                <td><?= $val->apellido2 ?></td>
                <td><?= $val->telefono ?></td>
                <td><?= $val->municipio ?></td>
                <td><?= $val->email ?></td>
                <td><a href="<?= Url::toRoute(["inscritos/editar", "consecutivo" => $val->consecutivo]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>
                <td><a href="<?= Url::toRoute(["inscritos/firma_estudiante", "consecutivo" => $val->consecutivo]) ?>" ><img src="svg/si-glyph-pencil.svg" align="center" width="20px" height="20px" title="Firma Estudiante"></a></td>
                <td><a href="<?= Url::toRoute(["inscritos/firma_acudiente", "consecutivo" => $val->consecutivo]) ?>" ><img src="svg/si-glyph-pencil.svg" align="center" width="20px" height="20px" title="Firma Acudiente"></a></td>
                <td><a href="<?= Url::toRoute(["inscritos/imprimir", "consecutivo" => $val->consecutivo]) ?>" ><img src="svg/si-glyph-print.svg" align="center" width="20px" height="20px" title="Imprimir"></a></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>

        <div class = "form-group" align="right">
            <a href="<?= Url::toRoute("inscritos/nuevo") ?>" class="btn btn-primary">Nuevo Inscrito</a>
        </div>
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>

