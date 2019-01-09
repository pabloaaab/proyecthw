<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Resolucion */

$this->title = 'Update Resolucion: ' . $model->codigo_resolucion_pk;
$this->params['breadcrumbs'][] = ['label' => 'Resolucions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo_resolucion_pk, 'url' => ['view', 'id' => $model->codigo_resolucion_pk]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="resolucion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
