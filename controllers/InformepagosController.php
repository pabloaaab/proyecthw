<?php

namespace app\controllers;

use app\models\FormFiltroInformesPagos;
use app\models\Matriculados;
use app\models\Pagos;
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
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use moonland\phpexcel\Excel;
use app\models\UsuarioDetalle;
use PHPExcel;



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
                    $this->actionExcel($model);
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Cliente')
                    ->setCellValue('B1', 'Orden Producción')
                    ->setCellValue('C1', 'Cantidad por Hora')
                    ->setCellValue('D1', 'Cantidad Diaria')
                    ->setCellValue('E1', 'Tiempo Entrega Días')
                    ->setCellValue('F1', 'Nro Horas')
                    ->setCellValue('G1', 'Días Entrega')
                    ->setCellValue('H1', 'Costo Muestra Operaría')
                    ->setCellValue('I1', 'Costo por Hora');

        $i = 2;
        
        foreach ($model as $val) {
            
            
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->nropago)
                    ->setCellValue('B' . $i, $val->nropago)
                    ->setCellValue('C' . $i, $val->nropago)
                    ->setCellValue('D' . $i, $val->nropago)
                    ->setCellValue('E' . $i, $val->nropago)
                    ->setCellValue('F' . $i, $val->nropago)
                    ->setCellValue('G' . $i, $val->nropago)
                    ->setCellValue('H' . $i, $val->nropago)
                    ->setCellValue('I' . $i, $val->nropago);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('informe');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="informe.xlsx"');
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
        exit;	    
    }

}
