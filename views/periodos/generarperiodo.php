<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\models\Matriculados;
use app\models\Inscritos;
use app\models\PagosPeriodo;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Session;
use yii\data\Pagination;
use yii\db\ActiveQuery;

?>

<?php $form = ActiveForm::begin([

    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
    ],
]); ?>

<?php
if ($mensaje != ""){
    ?> <div class="alert alert-danger"><?= $mensaje ?></div> <?php
}
?>
<div class="table table-responsive">
    <div class="panel panel-primary ">
        <div class="panel-heading">
            Periodo
        </div>
        <div class="panel-body">
            <table class="table table-condensed">
                <thead>
                <tr>
                    <td><input type="text" name="periodo" value="<?= date("Y-m-d") ?>"></td>
                </tr>
                </thead>                
            </table>
        </div>        
    </div>
</div>
<div class="table table-responsive">
    <div class="panel panel-primary ">
        <div class="panel-heading">
            Registros
        </div>
        <div class="panel-body">
            <table class="table table-condensed">
                <thead>
                <tr>
                    <th scope="col">Matricula</th>
                    <th scope="col">Estudiante</th>
                    <th scope="col">Docente</th>
                    <th scope="col">Nivel</th>
                    <th scope="col">Sede</th>
                    <th scope="col">Valor Mensualidad</th>                    
                    <th scope="col"><input type="checkbox" onclick="marcar(this);" checked="true" readonly="true"/></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($matriculasabiertas as $val): ?>
                <tr>
                    <td><?= $val->consecutivo ?></td>
                    <td><?= $val->entificacion->nombreestudiante ?></td>
                    <td><?= $val->entificacion->nombredocente ?></td>
                    <td><?= $val->nivel ?></td>
                    <td><?= $val->sede ?></td>
                    <td><input type="text" name="valor_mensual[]" value="<?= $val->valor_mensual ?>"></td>                    
                    <td><input type="checkbox" name="consecutivo[]" value="<?= $val->consecutivo ?>" checked="true" readonly="true"></td>
                </tr>
                </tbody>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="panel-footer text-right">
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['periodos/index'], ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton("Guardar", ["class" => "btn btn-primary",]) ?>
        </div>

    </div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>