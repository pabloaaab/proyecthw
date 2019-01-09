<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Matriculas';
?>

    <h1>Matriculas</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("matriculas/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>

<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtromatriculas">
        <div class="row" >
            <?= $formulario->field($form, "identificacion")->input("search") ?>
            <?= $formulario->field($form, "nivel")->input("search") ?>
            <?= $formulario->field($form, "sede")->input("search") ?>
            <?= $formulario->field($form, "docente")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("matriculas/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>    
    

<?php $formulario->end() ?>
    
    <div class="container-fluid">
        <div class="col-lg-2">

        </div>
    </div>
    <div class="table-condensed">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Código</th>                                
                <th scope="col">Estudiante</th>
                <th scope="col">Nivel</th>
                <th scope="col">Fecha Matricula</th>
                <th scope="col">Docente</th>
                <th scope="col">Sede</th>
                <th scope="col">Estado</th>                
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>
                <th scope="row"><?= $val->consecutivo ?></th>                
                <td><?= $val->entificacion->nombreestudiante ?></td>                                
                <td><?= $val->nivel ?></td>
                <td><?= $val->fechamat ?></td>
                <td><?= $val->entificacion->nombredocente ?></td>
                <td><?= $val->sede ?></td>
                <td><?= $val->estado2 ?></td>
                <td><a href="<?= Url::toRoute(["matriculas/editar", "consecutivo" => $val->consecutivo]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>                
                <td><a href="<?= Url::toRoute(["matriculas/imprimir", "consecutivo" => $val->consecutivo]) ?>" target="_blank"><img src="svg/si-glyph-print.svg" align="center" width="20px" height="20px" title="Imprimir"></a></td>
                <td><a href="<?= Url::toRoute(["matriculas/aprobar", "consecutivo" => $val->consecutivo]) ?>"><img src="svg/si-glyph-checked.svg" align="center" width="20px" height="20px" title="Aprobar"></a></td>
                <td><a href="<?= Url::toRoute(["matriculas/cancelar", "consecutivo" => $val->consecutivo]) ?>"><img src="svg/si-glyph-delete.svg" align="center" width="20px" height="20px" title="Cancelar"></a></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>

        <div class = "form-group" align="right">
            <a href="<?= Url::toRoute("matriculas/nuevo") ?>" class="btn btn-primary">Nuevo Matricula</a>
        </div>
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>
