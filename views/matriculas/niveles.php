<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

$this->title = 'Niveles';
?>

    <h1>Niveles</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("matriculas/niveles"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],
]);
?>

<?php

$sede = ArrayHelper::map(\app\models\Sede::find()->where(['=','estado',1])->all(), 'sede','sede');
$nivel = ArrayHelper::map(\app\models\Nivel::find()->all(), 'nivel','nivel');

?>    
    
<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtromatriculas">
        <div class="row" >            
            <?= $formulario->field($form, 'nivel')->dropDownList($nivel,['prompt' => 'Seleccione...' ]) ?>            
            <?= $formulario->field($form, 'sede')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>            
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("matriculas/niveles") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>   
    
<?php $formulario->end() ?>

<div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>
    
    <div class="table-responsive table-bordered">
        <table class="table table-responsive">            
            <tr>
                <th scope="col">Total A1: <?= $A1 ?></th>                                
                <th scope="col">Total A2: <?= $A2 ?></th>
                <th scope="col">Total B1: <?= $B1 ?></th>
                <th scope="col">Total B2: <?= $B2 ?></th>
                <th scope="col">Total C1: <?= $C1 ?></th>
                <th scope="col">Total C2: <?= $C2 ?></th>                
                <th scope="col">Total Pedagogia: <?= $pedagogia ?></th>
                <th scope="col">Total S.E.B: <?= $seb ?>
            </tr>            
        </table>
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
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>
                <?php if($val->docente){
                    $docente = \app\models\Inscritos::find()->where(['=','identificacion',$val->docente])->one();
                    $dato = $docente->nombredocente;
                } else {
                    $dato = "Sin definir";
                }
                ?>                
                <th scope="row"><?= $val->consecutivo ?></th>                
                <td><?= $val->entificacion->nombreestudiante ?></td>                                
                <td><?= $val->nivel ?></td>
                <td><?= $val->fechamat ?></td>
                <td><?= $dato ?></td>
                <td><?= $val->sede ?></td>                
                <td><?= $val->estado2 ?></td>                
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>        
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>

