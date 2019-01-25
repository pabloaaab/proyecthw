<?php

namespace app\controllers;

use Codeception\Lib\HelperModule;
use yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Matriculados;
use app\models\Pagos;
use yii\helpers\Url;
use app\models\FormFiltroInformesPagos;
use yii\web\UploadedFile;

use yii\helpers\ArrayHelper;


class InformepagosController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroInformesPagos;
            //$nivel = null;
            $identificacion = null;
            $fechapago = null;
            $sede = null;
            $tipopago = null;            
            $anio_mes_dia = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    //$nivel = Html::encode($form->nivel);
                    $identificacion = Html::encode($form->identificacion);
                    $fechapago = Html::encode($form->fechapago);
                    $sede = Html::encode($form->sede);
                    $tipopago = Html::encode($form->tipo_pago);
                    $anio_mes_dia = Html::encode($form->anio_mes_dia);
                    if ($anio_mes_dia == "dia"){
                        $fechapago = $fechapago;
                    }
                    if ($anio_mes_dia == "mes"){
                        $fechapago = date('Y-m', strtotime($fechapago));
                    }
                    if ($anio_mes_dia == "anio"){
                        $fechapago = date('Y', strtotime($fechapago));
                    }
                    $table = Pagos::find()                            
                            //->andFilterWhere(['like', 'nivel', $nivel])
                            ->andFilterWhere(['like', 'identificacion', $identificacion])
                            ->andFilterWhere(['like', 'fecha_registro', $fechapago])
                            ->andFilterWhere(['like', 'tipo_pago', $tipopago])
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->orderBy('nropago desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count()
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                                
                $connection = Yii::$app->getDb();
                if ($identificacion != null){
                    $d1= " and identificacion = '". $identificacion."'";
                }else{
                    $d1= " ";
                }
                if ($fechapago != null){
                    $d2= " and fecha_registro like '%". $fechapago."%'";
                }else{
                    $d2= " ";
                }
                if ($sede != null){
                    $d3= " and sede = '". $sede."'";
                }else{
                    $d3= " ";
                }
                if ($tipopago != null){
                    $d4= " and tipo_pago = '". $tipopago."'";
                }else{
                    $d4= " ";
                }
                $command = $connection->createCommand("
                    SELECT 
                        SUM(IF(tipo_pago = 'otros' and anulado = '',total,0))   AS otrospagos,
                        SUM(IF(tipo_pago = 'otros' and anulado = 'si',total,0))   AS otrospagosanulados,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'medellin' and anulado = '',total,0))   AS pagosmedellin,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'medellin' and anulado = 'si',total,0))   AS pagosmedellinanulado,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'rionegro' and anulado = '',total,0))   AS pagosrionegro,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'rionegro' and anulado = 'si',total,0))   AS pagosrionegroanulado      
                        FROM pagos where nropago <> 0 ".$d1.$d2.$d3.$d4);
                                            

                $result = $command->queryAll();
                $subtotal = $result[0]['otrospagos'] + $result[0]['pagosmedellin'] + $result[0]['pagosrionegro'];
                $totalanulado = $result[0]['otrospagosanulados'] + $result[0]['pagosmedellinanulado'] + $result[0]['pagosrionegroanulado'];
                $grantotal = $subtotal - $totalanulado;
                } else {
                    $form->getErrors();
                }
                
                if (isset($_GET["excel"])) {
                    $this->actionExcel($identificacion);
                }
            } else {
                $table = Pagos::find()                        
                        ->orderBy('nropago desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 20,
                    'totalCount' => $count->count(),
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();                
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("
                    SELECT 
                        SUM(IF(tipo_pago = 'otros' and anulado = '',total,0))   AS otrospagos,
                        SUM(IF(tipo_pago = 'otros' and anulado = 'si',total,0))   AS otrospagosanulados,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'medellin' and anulado = '',total,0))   AS pagosmedellin,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'medellin' and anulado = 'si',total,0))   AS pagosmedellinanulado,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'rionegro' and anulado = '',total,0))   AS pagosrionegro,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'rionegro' and anulado = 'si',total,0))   AS pagosrionegroanulado      
                        FROM pagos
                    ");

                $result = $command->queryAll();
                $subtotal = $result[0]['otrospagos'] + $result[0]['pagosmedellin'] + $result[0]['pagosrionegro'];
                $totalanulado = $result[0]['otrospagosanulados'] + $result[0]['pagosmedellinanulado'] + $result[0]['pagosrionegroanulado'];
                $grantotal = $subtotal - $totalanulado;
            }
            
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,                        
                        'result' => $result,
                        'totalanulado' => $totalanulado,
                        'subtotal' => $subtotal,
                        'grantotal' => $grantotal
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }
    
    public function actionExcel($model) {
        
        \moonland\phpexcel\Excel::export([
   	'models' => Pagos::find()->where(['=','identificacion',1035917181])->all(),
      	'columns' => [
      		'identificacion.name:text:Author Name',
      		[
      				'attribute' => 'content',
      				'header' => 'Content Post',
      				'format' => 'text',
      				'value' => function($model) {
      					return Pagos::removeText('example', $model->content);
      				},
      		],
      		'like_it:text:Reader like this content',
      		'created_at:datetime',
      		[
      				'attribute' => 'updated_at',
      				'format' => 'date',
      		],
      	],
      	'headers' => [
     		'created_at' => 'Date Created Content',
		],
]);    
    }

}
