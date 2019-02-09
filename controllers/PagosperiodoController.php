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
                    $d2= " and afecta_pago = '". $pagado."'";
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
                        SUM(IF(afecta_pago = '0' and cerro_grupo = '0' and anulado = '0',total,0))   AS totaldeudagenerada,
                        SUM(IF(afecta_pago = '1' and cerro_grupo = '0' and anulado = '0',total,0))   AS totaldeudapagada
                        FROM pagos_periodo where cerro_grupo = '0' ".$d1.$d2.$d3.$d4.$d5);                                            
                $result = $command->queryAll();
                $totaldeudagenerada = $result[0]['totaldeudagenerada'];
                $totaldeudapagada = $result[0]['totaldeudapagada'];
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
                if(isset($_POST['excel'])){
                    $table = PagosPeriodo::find()                            
                            ->Where(['=', 'cerro_grupo', '0'])                            
                            ->andFilterWhere(['like', 'anulado', $anulado])
                            ->andFilterWhere(['like', 'afecta_pago', $pagado])
                            ->andFilterWhere(['like', 'mensualidad', $mensualidad])
                            ->andFilterWhere(['like', 'identificacion', $identificacion])                            
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->orderBy('consecutivo desc');
                    
                    $model = $table->all();
                    $this->actionExcel($model);                    
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
                        SUM(IF(afecta_pago = '0' and cerro_grupo = '0' and anulado = '0',total,0))   AS totaldeuda,                              
                        FROM pagos_periodo
                    ");

                $result = $command->queryAll();
                $totaldeuda = $result[0]['totaldeuda'];
                $totaldeudagenerada = $result[0]['totaldeuda'];
                if(isset($_POST['excel'])){
                    //$this->actionExcel($model);                    
                }                
            }
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
                        'totaldeudagenerada' => $totaldeudagenerada,
                        'totaldeudapagada' => $totaldeudapagada,
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
    
    public function actionExcel($model) {
        //$costoproducciondiario = CostoProduccionDiaria::find()->all();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Código')
                    ->setCellValue('B1', 'Nro Pago')
                    ->setCellValue('C1', 'Mensualidad')
                    ->setCellValue('D1', 'Estudiante')                                        
                    ->setCellValue('E1', 'Valor a Pagar')
                    ->setCellValue('F1', 'Valor Pagado')
                    ->setCellValue('G1', 'Pagado')
                    ->setCellValue('H1', 'Anulado')
                    ->setCellValue('I1', 'Sede')
                    ->setCellValue('J1', 'Nivel');

        $i = 2;
        
        foreach ($model as $val) {
            if ($val->anulado == 0) { $anulado = "NO"; } else { $anulado = "SI"; }
            if ($val->afecta_pago == 1) { $pagado = "SI"; }else { $pagado = "NO"; }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->consecutivo)
                    ->setCellValue('B' . $i, $val->nropago)
                    ->setCellValue('C' . $i, $val->mensualidad)
                    ->setCellValue('D' . $i, $val->identificacion.' - '.$val->nombres)                    
                    ->setCellValue('E' . $i, $val->total)
                    ->setCellValue('F' . $i, $val->pago1)
                    ->setCellValue('G' . $i, $pagado)
                    ->setCellValue('H' . $i, $anulado)                    
                    ->setCellValue('I' . $i, $val->sede)
                    ->setCellValue('J' . $i, $val->nivel);                    
                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('pagosperiodo');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pagosperiodo.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        //return $model;
        exit;
        
    }

}
