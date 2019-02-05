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
use app\models\Grupo;
use app\models\Inscritos;
use app\models\Matriculados;
use app\models\FormGrupo;
use yii\helpers\Url;
use app\models\FormFiltroGrupo;
use app\models\FormFiltroGrupoMatricula;


    class GruposController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $form = new FormFiltroGrupo;
                $search = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $search = Html::encode($form->q);
                        $table = Grupo::find()
                            ->where(['like', 'consecutivo', $search])
                            ->orWhere(['like', 'grupo', $search])
                            ->orWhere(['like', 'consecutivo', $search])
                            ->orWhere(['like', 'nivel', $search])
                            ->orderBy('consecutivo desc');
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
                    $table = Grupo::find()
                        ->orderBy('consecutivo desc');
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
                    'search' => $search,
                    'pagination' => $pages,

                ]);
            }else{
                return $this->redirect(["site/login"]);
            }

        }

        public function actionNuevo()
        {
            $model = new FormGrupo;
            $msg = null;
            $tipomsg = null;
            $codigo = null;
            $registros = Grupo::find()->orderBy('consecutivo desc')->one();
            $codigo = $registros->consecutivo + 1;
            $codigoz = mb_strlen($codigo,'UTF-8');
            if ($codigoz == '1'){
                $codigo = '000'.$codigo;
            }
            if ($codigoz == '2'){
                $codigo = '00'.$codigo;
            }
            if ($codigoz == '3'){
                $codigo = '0'.$codigo;
            }
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = new Grupo;
                    $table->consecutivo = $codigo;
                    $table->grupo = $codigo;
                    $table->nivel = $model->nivel;
                    $table->jornada = $model->jornada;
                    $table->sede = $model->sede;
                    $table->tipo_horario = $model->tipo_horario;
                    $table->de = $model->de;
                    $table->a = $model->a;
                    $table->docente = $model->docente;
                    $table->cuota_mensual = $model->cuota_mensual;
                    $table->fechaInicio = $model->fechaInicio;
                    $table->lunes = $model->lunes;
                    $table->martes = $model->martes;
                    $table->miercoles = $model->miercoles;
                    $table->jueves = $model->jueves;
                    $table->viernes = $model->viernes;
                    $table->estado = "";

                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        $model->grupo = null;
                        $model->nivel = null;
                        $model->jornada = null;
                        $model->sede = null;
                        $model->tipo_horario = null;
                        $model->de = null;
                        $model->a = null;
                        $model->docente = null;
                        $model->cuota_mensual = null;
                        $model->fechaInicio = null;
                        $model->lunes = null;
                        $model->martes = null;
                        $model->miercoles = null;
                        $model->jueves = null;
                        $model->viernes = null;

                    } else {
                        $msg = "error";
                    }
                } else {
                    $model->getErrors();
                }
            }

            return $this->render('nuevo', ['model' => $model, 'msg' => $msg, 'tipomsg' => $tipomsg]);
        }

        public function actionEditar()
        {
            $model = new FormGrupo;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = Grupo::find()->where(['consecutivo' => $model->consecutivo])->one();
                    if ($table) {

                        $table->nivel = $model->nivel;
                        $table->jornada = $model->jornada;
                        $table->sede = $model->sede;
                        $table->tipo_horario = $model->tipo_horario;
                        $table->de = $model->de;
                        $table->a = $model->a;
                        $table->docente = $model->docente;
                        $table->cuota_mensual = $model->cuota_mensual;
                        $table->fechaInicio = $model->fechaInicio;
                        $table->lunes = $model->lunes;
                        $table->martes = $model->martes;
                        $table->miercoles = $model->miercoles;
                        $table->jueves = $model->jueves;
                        $table->viernes = $model->viernes;

                        if ($table->update()) {
                            $msg = "El registro ha sido actualizado correctamente";
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
                $table = Grupo::find()->where(['consecutivo' => $consecutivo])->one();
                if ($table) {
                    $model->consecutivo = $table->consecutivo;
                    $model->nivel = $table->nivel;
                    $model->jornada = $table->jornada;
                    $model->sede = $table->sede;
                    $model->tipo_horario = $table->tipo_horario;
                    $model->de = $table->de;
                    $model->a = $table->a;
                    $model->docente = $table->docente;
                    $model->cuota_mensual = $table->cuota_mensual;
                    $model->fechaInicio = $table->fechaInicio;
                    $model->lunes = $table->lunes;
                    $model->martes = $table->martes;
                    $model->miercoles = $table->miercoles;
                    $model->jueves = $table->jueves;
                    $model->viernes = $table->viernes;
                } else {
                    return $this->redirect(["grupos/index"]);
                }
            } else {
                return $this->redirect(["grupos/index"]);
            }
            return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
        }

        public function actionGrupo_matricula()
        {
            if (!Yii::$app->user->isGuest) {
                $form = new FormFiltroGrupoMatricula;
                $search = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $search = Html::encode($form->q);
                        $table = Grupo::find()
                            ->where(['like', 'consecutivo', $search])
                            ->orWhere(['like', 'grupo', $search])
                            ->orWhere(['like', 'consecutivo', $search])
                            ->orWhere(['like', 'nivel', $search])
                            ->andWhere(['=', 'estado', ''])
                            ->orderBy('consecutivo desc');
                        $count = clone $table;
                        $pages = new Pagination([
                            'pageSize' => 400,
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
                    $query = new \yii\db\Query;
                    $command = $query->innerJoin(
                        'inscritos',
                        'grupos.docente = inscritos.identificacion')
                        ->select('grupos.consecutivo,nivel,docente,ultimo_periodo_generado,inscritos.nombre1,inscritos.nombre2,inscritos.apellido1,inscritos.apellido2,lunes,martes,miercoles,jueves,viernes,sede,tipo_horario,jornada,de,a')
                        ->from('grupos')
                        ->andWhere(['=', 'estado', ''])
                        ->orderBy('grupos.consecutivo desc')
                        ->createCommand();
                    $table = $command->query();
                    $model = $table;
                    //cantidad de matriculados por grupo
                    $query = new \yii\db\Query;
                    $command2 = $query->select('grupo,COUNT(identificacion) as total')->from('matriculados')->where(['=','estado',''])->groupBy('grupo')->createCommand();
                    $table2 = $command2->queryAll();
                    $model2 = $table2;

                }
                return $this->render('grupomatricula', [
                    'model' => $model,
                    'form' => $form,
                    'search' => $search,
                    'conteo' => $model2,

                ]);
            }else{
                return $this->redirect(["site/login"]);
            }

        }
                
}