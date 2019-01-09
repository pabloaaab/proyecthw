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
use app\models\Pagos;
use app\models\Notas;
use yii\helpers\Url;
use app\models\FormFiltroPagos;
use app\models\FormPagos;
use app\models\FormPagosAnula;
use app\models\Resolucion;
use app\models\Inscritos;
use yii\web\UploadedFile;

class PagosController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroPagos;            
            $identificación = null;            
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $identificación = Html::encode($form->identificacion);                    
                    $table = Pagos::find()
                            ->where(['=', 'tipo_pago', 'mensualidad'])
                            ->andFilterWhere(['like', 'identificacion', $identificación])                            
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
                } else {
                    $form->getErrors();
                }
            } else {
                $table = Pagos::find()
                        ->where(['=', 'tipo_pago', 'mensualidad'])                                                    
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
            }
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
            ]);
        } else {
            return $this->redirect(["pagos/index"]);
        }
    }    

    public function actionAnulacion($nropago) {
        $model = new FormPagosAnula;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = Pagos::find()->where(['nropago' => $nropago])->one();
                if ($table) {                                        
                    $table->anulado = 'SI';
                    $table->fechaanulado = $model->fechaanulado.' '.date("H:i:s");
                    $table->usuarioanula = Yii::$app->user->identity->username;
                    $table->motivo = $model->motivo;                    
                    if ($table->save(false)) {
                        $msg = "El registro ha sido actualizado correctamente";
                        return $this->redirect(["pagos/index"]);   
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
        return $this->render("anulacion", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }        

    public function actionImprimir($nropago) {
        $model2 = Pagos::find()->where(['nropago' => $nropago])->one();
        $model = Inscritos::find()->where(['identificacion' => $model2->identificacion])->one();
        return $this->render("generarimprimir", ["model" => $model, "model2" => $model2]);
    }

}
