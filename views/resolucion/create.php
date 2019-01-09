<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Resolucion */

$this->title = 'Create Resolucion';
$this->params['breadcrumbs'][] = ['label' => 'Resolucions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resolucion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
