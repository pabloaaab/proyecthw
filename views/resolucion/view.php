<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Resolucion */

$this->title = $model->codigo_resolucion_pk;
$this->params['breadcrumbs'][] = ['label' => 'Resolucions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resolucion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->codigo_resolucion_pk], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->codigo_resolucion_pk], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'codigo_resolucion_pk',
            'resolucion:ntext',
        ],
    ]) ?>

</div>
