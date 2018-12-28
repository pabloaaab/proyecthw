<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\menu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);



    if (!Yii::$app->user->isGuest )
    {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-center'],
            'items' => [
                ['label' => 'Inicio', 'url' => ['/site/index']],
                [
                  'label' => 'Administración',
                  'items' => [
                      ['label' => 'Inscritos', 'url' => ['/inscritos/index']],
                      ['label' => 'Sedes', 'url' => ['/sedes/index']],
                      ['label' => 'Grupos', 'url' => ['/grupos/index']],
                  ]
                ],
                [
                'label' => 'Procesos',
                'items' => [
                    ['label' => 'Matriculas', 'url' => ['/grupos/grupo_matricula']],
                    ['label' => 'Periodos Generados', 'url' => ['/site/index']],
                    ['label' => 'Facturas Faltantes', 'url' => ['/site/index']],
                    ]
                ],
                [
                'label' => 'Utilidades',
                'items' => [
                    ['label' => 'Informes Pagos', 'url' => ['/site/index']],
                    ['label' => 'Consulta Niveles', 'url' => ['/site/index']],
                    ]
                ],
                [
                    'label' => 'Movimientos',
                    'items' => [
                        ['label' => 'Pagos Mensualidad', 'url' => ['/site/index']],
                        ['label' => 'Pagos Periodo', 'url' => ['/site/index']],
                        ['label' => 'Otros Pagos', 'url' => ['/site/index']],
                        ['label' => 'HabeasData', 'url' => ['/site/index']],
                        ['label' => 'Notas', 'url' => ['/site/index']],
                    ]
                ]
            ],
        ]);
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [


            Yii::$app->user->isGuest ? (
                ['label' => 'Iniciar Sesión', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Cerrar(' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container" style="width:1208px">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
