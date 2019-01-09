
<?php
$this->title = 'Imprimir Otros Pagos';
$msg = "Pago de ".$model2->mensualidad."";
$saldo = 0;
$msg1 = "";
$saldo1 = 0;
$msg3 =0;
$anulado= "<font color='red' size='30'>ANULADA</font>";


include "letras.php";
if ($model2->anulado == '')
{
	?>
<body onload='window.print()'>
<table width="950" border="1" align="center" bordercolor="#000000">
  <tr>
    <td width="161" rowspan="6" align="center"><img src="images/logo.png" width="139" height="145" /></td>
    <td height="10" align="center">&nbsp;</td>
    <td height="10" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="550" height="25" align="center"><span class="Estilo25"><strong>FACTURA DE VENTA  </strong></span></td>
    <td width="217" height="25" align="center"><span class="Estilo25"><em><strong>Nit 811.041.215-4 </strong></em></span></td>
  </tr>
  <tr>
    <td height="25" align="center" valign="middle"><span class="Estilo25"><strong>ESCUELA DE IDIOMAS HEADWAY </strong></span></td>
    <td height="25" align="center" valign="middle"><span class="Estilo25"><em><strong>Carrera 51 # 50 - 21 Piso 21</strong></em></span></td>
  </tr>
  <tr>
    <td height="30" align="center"><span class="Estilo25"></span></td>
    <td height="30" align="center"><span class="Estilo25"><em><strong>Tel&eacute;fono: 444 23 22 </strong></em></span></td>
  </tr>
  <tr>
    <td height="10" colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" colspan="2">&nbsp;</td>
  </tr>
</table>

<table width="950" border="1" align="center" bordercolor="#000000">
  <tr>
    <td height="30" colspan="2"><span class="Estilo26">Facturado a: <?php echo $model->nombre1. ' ' .$model->nombre2. ' ' .$model->apellido1. ' '. $model->apellido2 ." - "." Identificaci&oacute;n: ".$model->identificacion;?></span></td>
    <td colspan="2" align="center"><span class="Estilo26">Factura N&deg;: <?php echo $model2->nropago;?></span></td>
  </tr>
  <tr>
    <td width="615" height="30" align="center"><span class="Estilo26">Producto</span></td>
    <td width="85" align="center"><span class="Estilo26">Iva</span></td>
    <td colspan="2" align="center"><span class="Estilo26">Valor</span></td>
  </tr>
  <tr>
    <td height="15" align="center"><span class="Estilo26"><?php echo $model2->mensualidad;?></span></td>
    <td height="15" align="center"><span class="Estilo25"><strong>0%</strong></span></td>
    <td height="15" colspan="2" align="center"><span class="Estilo25"></span></td>
  </tr>
  <tr>
  <?php
  $l= $model2->total; 
  $V=new EnLetras(); 
  $con_letra=strtoupper($V->ValorEnLetras($l,'pesos'));
  ?>
    <td colspan="2" rowspan="2"><span class="Estilo28">Total Letras: <?php echo $con_letra;?></span> </td>
    <td width="136" height="30" align="right"><span class="Estilo25">Subtotal:</span></td>
    <td width="86" align="right"><span class="Estilo25">$ <?php echo number_format($model2->total);?></span></td>
  </tr>
  <tr>
    <td height="30" align="right"><span class="Estilo25">Iva:</span></td>
    <td align="right"><span class="Estilo25">$ 0</span></td>
  </tr>
  <tr>
    <td height="30" colspan="2"><span class="Estilo25"><strong>Observaciones: <?php echo $msg." - (".$model2->observaciones.")";?></strong></span></td>
    <td align="right"><span class="Estilo28">Total:</span></td>
    <td align="right"><span class="Estilo28">$ <?php echo number_format($model2->total);?></span></td>
  </tr>
  <tr>
    <td height="30" align="center"><span class="Estilo28">Pagos Pendientes </span></td>
    <td height="30" align="center"><span class="Estilo28">Saldo</span></td>
    <td align="right"><span class="Estilo25">Efectivo:</span></td>
    <td align="right"><span class="Estilo25">$ <?php echo number_format($model2->total);?></span></td>
  </tr>
  <tr>
    <td height="30" align="center"><span class="Estilo25"></span></td>
    <td height="30" align="center"><span class="Estilo25">$ <?php echo number_format($saldo1);?></span></td>
    <td height="30" align="center"><span class="Estilo28">TOTAL PAGADO</span></td>
    <td align="right"><span class="Estilo28">$ <?php echo number_format($model2->total);?></span></td>
  </tr>
  <tr>
    <td height="50" colspan="4"><span class="Estilo25"><strong>Cajero(a): <?php echo $model2->usuarioregistra;?> - Fecha: </strong><?php echo $model2->fecha_registro;?></span></td>
  </tr>
  <tr>
    <td height="30" colspan="4" align="center"><span class="Estilo25"><?php echo utf8_decode($model2->resolucion);?> </span></td>
  </tr>
</table>
<?php
}
else
{
?>
<body onload='window.print()'>
<table width="950" border="1" align="center" bordercolor="#000000">
  <tr>
    <td width="161" rowspan="6" align="center"><img src="images/logo.png" width="139" height="145" /></td>
    <td height="10" align="center">&nbsp;</td>
    <td height="10" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="550" height="25" align="center"><span class="Estilo25"><strong>FACTURA DE VENTA  </strong></span></td>
    <td width="217" height="25" align="center"><span class="Estilo25"><em><strong>Nit 811.041.215-4 </strong></em></span></td>
  </tr>
  <tr>
    <td height="25" align="center" valign="middle"><span class="Estilo25"><strong>ESCUELA DE IDIOMAS HEADWAY </strong></span></td>
    <td height="25" align="center" valign="middle"><span class="Estilo25"><em><strong>Trasnversal 5ta A # 45-189 </strong></em></span></td>
  </tr>
  <tr>
    <td height="30" align="center"><span class="Estilo25"></span></td>
    <td height="30" align="center"><span class="Estilo25"><em><strong>Tel&eacute;fono: 3125210 </strong></em></span></td>
  </tr>
  <tr>
    <td height="10" colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" colspan="2">&nbsp;</td>
  </tr>
</table>

<table width="950" border="1" align="center" bordercolor="#000000">
  <tr>
    <td height="30" colspan="2"><span class="Estilo26">Facturado a: <?php echo $model->nombre1. ' ' .$model->nombre2. ' ' .$model->apellido1. ' '. $model->apellido2 ." - "." Identificaci&oacute;n: ".$model->identificacion;?></span></td>
    <td colspan="2" align="center"><span class="Estilo26">Factura N&deg;: <?php echo $model2->nropago;?></span></td>
  </tr>
  <tr>
    <td width="615" height="30" align="center"><span class="Estilo26">Producto</span></td>
    <td width="85" align="center"><span class="Estilo26">Iva</span></td>
    <td colspan="2" align="center"><span class="Estilo26">Valor</span></td>
  </tr>
  <tr>
    <td height="15" align="center"><span class="Estilo26"><?php echo $model2->mensualidad;?></span></td>
    <td height="15" align="center"><span class="Estilo25"><strong>0%</strong></span></td>
    <td height="15" colspan="2" align="center"><span class="Estilo25"></span></td>
  </tr>
  <tr>
  <?php
  $l= $model2->total; 
  $V=new EnLetras(); 
  $con_letra=strtoupper($V->ValorEnLetras($l,'pesos'));
  ?>
    <td colspan="2" rowspan="2"><span class="Estilo28">Total Letras: <?php echo $con_letra." ".$anulado;?></span> </td>
    <td width="136" height="30" align="right"><span class="Estilo25">Subtotal:</span></td>
    <td width="86" align="right"><span class="Estilo25">$ <?php echo number_format($model2->total);?></span></td>
  </tr>
  <tr>
    <td height="30" align="right"><span class="Estilo25">Iva:</span></td>
    <td align="right"><span class="Estilo25">$ 0</span></td>
  </tr>
  <tr>
    <td height="30" colspan="2"><span class="Estilo25"><strong>Observaciones: <?php echo $msg;?></strong></span></td>
    <td align="right"><span class="Estilo28">Total:</span></td>
    <td align="right"><span class="Estilo28">$ <?php echo number_format($model2->total);?></span></td>
  </tr>
  <tr>
    <td height="30" align="center"><span class="Estilo28">Pagos Pendientes </span></td>
    <td height="30" align="center"><span class="Estilo28">Saldo</span></td>
    <td align="right"><span class="Estilo25">Efectivo:</span></td>
    <td align="right"><span class="Estilo25">$ <?php echo number_format($model2->total);?></span></td>
  </tr>
  <tr>
    <td height="30" align="center"><span class="Estilo25"><?php echo $msg1;?></span></td>
    <td height="30" align="center"><span class="Estilo25">$ <?php echo number_format($saldo1);?></span></td>
    <td height="30" align="center"><span class="Estilo28">TOTAL PAGADO</span></td>
    <td align="right"><span class="Estilo28">$ <?php echo number_format($model2->total);?></span></td>
  </tr>
  <tr>
    <td height="50" colspan="4"><span class="Estilo25"><strong>Cajero(a): <?php echo $model2->usuarioregistra;?> - Fecha: </strong><?php echo $model2->fecha_registro;?></span></td>
  </tr>
  <tr>
    <td height="30" colspan="4" align="center"><span class="Estilo25"><?php echo $model2->resolucion;?> </span></td>
  </tr>
</table>
<?php
}
?>
</body>
</html>