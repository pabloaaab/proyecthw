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
use yii\helpers\Url;

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

if (!Yii::$app->user->isGuest) {
    if (Yii::$app->user->identity->role == 2){ // usuario administrador
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-center'],
        'items' => [
            ['label' => 'Inicio', 'url' => ['/site/index']],
            [
                'label' => 'Administración',
                'items' => [
                    ['label' => 'Inscritos', 'url' => ['/inscritos/index']],
                    ['label' => 'Sedes', 'url' => ['/sedes/index']],
                    ['label' => 'Otros', 'url' => ['/site/index']],
                ]
            ],
            [
                'label' => 'Procesos',
                'items' => [
                    ['label' => 'Matriculas', 'url' => ['/matriculas/index']],
                    ['label' => 'Periodos Generados', 'url' => ['/periodos/index']],
                    ['label' => 'Otros', 'url' => ['/site/index']],
                ]
            ],
            [
                'label' => 'Utilidades',
                'items' => [
                    ['label' => 'Informe Pagos', 'url' => ['/informepagos/index']],
                    ['label' => 'Consulta Niveles', 'url' => ['/matriculas/niveles']],
                    ['label' => 'Generar Acceso Estudiantes', 'url' => ['/site/generaracceso']],
                ]
            ],
            [
                'label' => 'Movimientos',
                'items' => [
                    ['label' => 'Otros Pagos', 'url' => ['/pagosotros/index']],
                    ['label' => 'Pagos Mensualidad', 'url' => ['/pagos/index']],
                    ['label' => 'Pagos Periodo', 'url' => ['/pagosperiodo/index']],
                    ['label' => 'HabeasData', 'url' => ['/habeasdata/index']],
                    ['label' => 'Notas', 'url' => ['/notas/notas']],
                ]
            ],            
            [                
                'label' => 'Configuración',
                'items' => [
                    ['label' => 'Usuarios', 'url' => ['/site/usuarios']],
                    ['label' => 'Otros', 'url' => ['/site/index']],
                ],                
            ]
        ],
    ]);
    }
    if (Yii::$app->user->identity->role == 1){ //usuario administrativo
        echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-center'],
        'items' => [
        ['label' => 'Inicio', 'url' => ['/site/index']],
        [
            'label' => 'Administración',
            'items' => [
                ['label' => 'Inscritos', 'url' => ['/inscritos/index']],
                ['label' => 'Sedes', 'url' => ['/sedes/index']],
                ['label' => 'Otros', 'url' => ['/site/index']],
            ]
        ],
        [
            'label' => 'Procesos',
            'items' => [
                ['label' => 'Matriculas', 'url' => ['/matriculas/index']],                    
                ['label' => 'Otros', 'url' => ['/site/index']],
            ]
        ],
        [
            'label' => 'Utilidades',
            'items' => [                    
                ['label' => 'Consulta Niveles', 'url' => ['/site/index']],
            ]
        ],
        [
            'label' => 'Movimientos',
            'items' => [
                ['label' => 'Otros Pagos', 'url' => ['/pagosotros/index']],
                ['label' => 'Pagos Mensualidad', 'url' => ['/pagos/index']],
                ['label' => 'Pagos Periodo', 'url' => ['/pagosperiodo/index']],
                ['label' => 'HabeasData', 'url' => ['/habeasdata/index']],
                ['label' => 'Notas', 'url' => ['/notas/notas']],
            ]
        ],                        
        ],
        ]);
    }
    if (Yii::$app->user->identity->role == 3){ //usuario docente
        echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-center'],
        'items' => [
        ['label' => 'Inicio', 'url' => ['/site/index']],        
        [
            'label' => 'Movimientos',
            'items' => [                
                ['label' => 'Notas', 'url' => ['/notas/notas']],
            ]
        ],                        
        ],
        ]);
    }
    if (Yii::$app->user->identity->role == 4){ //usuario estudiante
        echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-center'],
        'items' => [
        ['label' => 'Inicio', 'url' => ['/site/index']],        
        [
            'label' => 'Movimientos',
            'items' => [                
                ['label' => 'Notas', 'url' => ['/notas/notas']],
            ]
        ],                        
        ],
        ]);
    }
    

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
                        'Cerrar (' . Yii::$app->user->identity->perfil . ' - ' . Yii::$app->user->identity->nombrecompleto . ')', ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
                )
    ],
]);
NavBar::end();
?>

            <div class="container" style="width:1208px">
            <?=
            Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ])
            ?>
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
