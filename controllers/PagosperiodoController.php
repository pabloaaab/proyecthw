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
use app\models\PagosPeriodo;
use yii\helpers\Url;
use app\models\FormFiltroPagosPeriodo;
use app\models\FormPagosPeriodoCerrar;
use app\models\FormPagosPeriodo;
use yii\web\UploadedFile;

class PagosperiodoController extends Controller {
    
    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroPagosPeriodo;
            //$nivel = null;
            $identificacion = null;            
            $sede = null;
            $anulado = null;
            $pagado = null;
            $mensualidad = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    //$nivel = Html::encode($form->nivel);
                    $identificacion = Html::encode($form->identificacion);                    
                    $sede = Html::encode($form->sede);
                    $anulado = Html::encode($form->anulado);
                    $pagado = Html::encode($form->pagado);
                    $mensualidad = Html::encode($form->mensualidad);
                    $table = PagosPeriodo::find()                            
                            ->Where(['=', 'cerro_grupo', '0'])                            
                            ->andFilterWhere(['like', 'anulado', $anulado])
                            ->andFilterWhere(['like', 'afecta_pago', $pagado])
                            ->andFilterWhere(['like', 'mensualidad', $mensualidad])
                            ->andFilterWhere(['like', 'identificacion', $identificacion])                            
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->orderBy('consecutivo desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 50,
                        'totalCount' => $count->count()
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    //deuda generada
                    $connection = Yii::$app->getDb();
                if ($anulado != null){
                    $d1= " and anulado = '". $anulado."'";
                }else{
                    $d1= " ";
                }
                if ($pagado != null){
                    $d2= " and fecha_registro = '". $pagado."'";
                }else{
                    $d2= " ";
                }
                if ($mensualidad != null){
                    $d3= " and mensualidad = '". $mensualidad."'";
                }else{
                    $d3= " ";
                }
                if ($identificacion != null){
                    $d4= " and identificacion = '". $identificacion."'";
                }else{
                    $d4= " ";
                }
                if ($sede != null){
                    $d5= " and sede = '". $sede."'";
                }else{
                    $d5= " ";
                }
                $command = $connection->createCommand("
                    SELECT 
                        SUM(IF(afecta_pago = '0' and cerro_grupo = '0' and anulado = '0',total,0))   AS totaldeudagenerada       
                        FROM pagos_periodo where cerro_grupo = '0' ".$d1.$d2.$d3.$d4.$d5);                                            
                $result = $command->queryAll();
                $totaldeudagenerada = $result[0]['totaldeudagenerada'];
                    //total deuda
                    $connection2 = Yii::$app->getDb();
                    $command2 = $connection2->createCommand("
                    SELECT 
                        SUM(IF(afecta_pago = '0' and cerro_grupo = '0' and anulado = '0',total,0))   AS totaldeuda                              
                        FROM pagos_periodo
                    ");
                    $result2 = $command2->queryAll();
                    $totaldeuda = $result2[0]['totaldeuda'];
                } else {
                    $form->getErrors();
                }
            } else {
                if(Yii::$app->user->identity->role == 2){
                    $table = PagosPeriodo::find()
                        ->Where(['=', 'cerro_grupo', '0'])
                        ->orderBy('consecutivo desc'); 
                }else{
                    $table = PagosPeriodo::find()
                        ->Where(['=', 'cerro_grupo', '0'])
                        ->andWhere(['=', 'sede', Yii::$app->user->identity->sede])    
                        ->orderBy('consecutivo desc');
                }                
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 30,
                    'totalCount' => $count->count(),
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("
                    SELECT 
                        SUM(IF(afecta_pago = '0' and cerro_grupo = '0' and anulado = '0',total,0))   AS totaldeuda                              
                        FROM pagos_periodo
                    ");

                $result = $command->queryAll();
                $totaldeuda = $result[0]['totaldeuda'];
                $totaldeudagenerada = $result[0]['totaldeuda'];                
            }
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
                        'totaldeudagenerada' => $totaldeudagenerada,  
                        'totaldeuda' => $totaldeuda,  
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }    

    public function actionEditar($consecutivo) {
        $model = new FormPagosPeriodo;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = PagosPeriodo::find()->where(['consecutivo' => $consecutivo])->one();
                if ($table) {
                    $table->identificacion = $model->identificacion;
                    $table->sede = $model->sede;
                    $table->mensualidad = $model->mensualidad;
                    $table->nropago = $model->nropago;
                    $table->total = $model->cuota;
                    $table->pago1 = $model->valorpagado;
                    $table->afecta_pago = $model->pagado;                    
                    if ($table->update()) {
                        $msg = "El registro ha sido actualizado correctamente";
                        return $this->redirect(["pagosperiodo/index"]);
                    } else {
                        $msg = "El registro no sufrio ningun cambio";
                        $tipomsg = "danger";
                    }
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                    $tipomsg = "danger";
                }
            } else {
                $model->getErrors();
            }
        }


        if (Yii::$app->request->get("consecutivo")) {
            $consecutivo = Html::encode($_GET["consecutivo"]);
            $table = PagosPeriodo::find()->where(['consecutivo' => $consecutivo])->one();
            if ($table) {
                $model->identificacion = $table->identificacion;
                $model->nropago = $table->nropago;
                $model->sede = $table->sede;
                $model->mensualidad = $table->mensualidad;
                $model->cuota = $table->total;
                $model->valorpagado = $table->pago1;
                $model->pagado = $table->afecta_pago;                               
            } else {
                return $this->redirect(["pagosperiodo/index"]);
            }
        } else {
            return $this->redirect(["pagosperiodo/index"]);
        }
        return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }

    public function actionCerrar($consecutivo) {
        $model = new FormPagosPeriodoCerrar;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = PagosPeriodo::find()->where(['consecutivo' => $consecutivo])->one();
                if ($table) {                    
                    if ($table->anulado == 0){
                        $table->cerro_grupo = 1;
                        $table->fecha_cerro_grupo = $model->fecha_cerro_grupo;                        
                        if ($table->update()) {
                            $msg = "El registro ha sido actualizado correctamente";
                            return $this->redirect(["pagosperiodo/index"]);
                        } else {
                            $msg = "El registro no sufrio ningun cambio";
                            $tipomsg = "danger";                        
                        }
                    }else{
                        $msg = "El registro se encuentra anulado";
                        $tipomsg = "danger";
                    }
                    
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                    $tipomsg = "danger";
                }
            } else {
                $model->getErrors();
            }
        }        
        
        return $this->render("cerrar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }        

}
