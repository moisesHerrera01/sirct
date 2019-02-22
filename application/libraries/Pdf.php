<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";

class Pdf extends FPDF
{
    public function __construct() {
            parent::__construct();
        }
/****************************************************************************************************/
var $widths;
var $aligns;
var $cod_entidad;
var $cod_usu;
var $id_val;
var $form,$form2,$form3;
var $titulo1;
var $titulo2;
var $titulo3;
var $titulo4;
var $titulo5;
var $cuadros;

function SetTituloPagina($e,$e2,$e3){
  $this->form=$e;
  $this->form2=$e2;
  $this->form3=$e3;
}
function SetTituloTabla1($e){
  $this->titulo1=$e;
}
function SetTituloTabla2($e){
  $this->titulo2=$e;
}
function SetTituloTabla3($e){
  $this->titulo3=$e;
}
function SetTituloTabla4($e){
  $this->titulo4=$e;
}
function SetTituloTabla5($e){
  $this->titulo5=$e;
}

function Setentidad($e)
{
    //Set the array of column widths
    $this->cod_entidad=$e;
}
function Set_id_val_valuos($e)
{
    //Set the array of column widths
    $this->id_val=$e;
}

function SetUsuario($s)
{
    //Set the array of column widths
    $this->cod_usu=$s;
}
function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}
function SetCuadros($c){
  $this->cuadros=$c;
}
function Header()
{

    $this->SetFont('Arial','',8);
    $this->Image(('application/libraries/logomtps.jpeg'),13,3,30,17);
    $this->Image(('application/libraries/escudo.jpg'),172,3,25,16);
    //$this->Text(145,12,$valueValuo['par_nombre_entidad'],0,'C', 0);
    $this->SetFont('Arial','B',9);
    $this->Text(68,8,utf8_decode($this->form),0,'C', 0);
    $this->Text(75,12,utf8_decode($this->form2),0,'C', 0);
    $this->Text(74,16,utf8_decode($this->form3),0,'C', 0);
    $this->Ln(30);
    if($this->cuadros=="viatico_pendiente_empleado"){
        $this->Cuadros_viatico_pendiente_o_pagado();
    }else if($this->cuadros=="viatico_pagado_empleado"){
      $this->Cuadros_viatico_pendiente_o_pagado();
    }else if($this->cuadros=="monto_viatico_mayor_a_menor"){
      $this->Cuadros_monto_viatico_mayor_a_menor();
    }else if($this->cuadros=="monto_por_periodo"){
      $this->Cuadros_monto_por_periodo();
    }


}
function Cuadros_monto_por_periodo(){
  $this->cuadrogrande(12,30,30,5,0,'D');
  $this->cuadrogrande(42,30,81,5,0,'D');
  $this->cuadrogrande(123,30,22,5,0,'D');
  $this->cuadrogrande(145,30,22,5,0,'D');
  $this->cuadrogrande(167,30,23,5,0,'D');

  $this->Text(23,34,utf8_decode($this->titulo1),0,'C', 0);
  $this->Text(65,34,utf8_decode($this->titulo2),0,'C', 0);
  $this->Text(125,34,utf8_decode($this->titulo3),0,'C', 0);
  $this->Text(148,34,utf8_decode($this->titulo4),0,'C', 0);
  $this->Text(172,34,utf8_decode($this->titulo5),0,'C', 0);

  $this->cuadrogrande(12,30,30,250,0,'D');
  $this->cuadrogrande(42,30,81,250,0,'D');
  $this->cuadrogrande(123,30,22,250,0,'D');
  $this->cuadrogrande(145,30,22,250,0,'D');
  $this->cuadrogrande(167,30,23,250,0,'D');
  $this->SetY(36);
}
function Cuadros_viatico_pendiente_o_pagado(){
  //cabecera de tabla
    $this->cuadrogrande(9,30,21,5,0,'D');
    $this->cuadrogrande(30,30,146,5,0,'D');
    $this->cuadrogrande(176,30,27,5,0,'D');
    $this->Text(11,34,utf8_decode($this->titulo1),0,'C', 0);
    $this->Text(35,34,utf8_decode($this->titulo2),0,'C', 0);
    $this->Text(178,34,utf8_decode($this->titulo3),0,'C', 0);
    //cuerpo de tabla
    $this->cuadrogrande(9,35,21,250,0,'D');
    $this->cuadrogrande(30,35,146,250,0,'D');
    $this->cuadrogrande(176,35,27,250,0,'D');
    $this->SetY(38);
}
function Cuadros_monto_viatico_mayor_a_menor(){
  $this->cuadrogrande(8,30,15,5,0,'D');
  $this->cuadrogrande(23,30,100,5,0,'D');
  $this->cuadrogrande(123,30,22,5,0,'D');
  $this->cuadrogrande(145,30,22,5,0,'D');
  $this->cuadrogrande(167,30,23,5,0,'D');

  $this->Text(11,34,utf8_decode($this->titulo1),0,'C', 0);
  $this->Text(35,34,utf8_decode($this->titulo2),0,'C', 0);
  $this->Text(125,34,utf8_decode($this->titulo3),0,'C', 0);
  $this->Text(148,34,utf8_decode($this->titulo4),0,'C', 0);
  $this->Text(172,34,utf8_decode($this->titulo5),0,'C', 0);

  $this->cuadrogrande(8,30,15,250,0,'D');
  $this->cuadrogrande(23,30,100,250,0,'D');
  $this->cuadrogrande(123,30,22,250,0,'D');
  $this->cuadrogrande(145,30,22,250,0,'D');
  $this->cuadrogrande(167,30,23,250,0,'D');
  $this->SetY(36);
}

function Footer()
{

    $this->SetY(-15);
    $this->SetFont('Arial','B',7);
    $this->SetTextColor(3, 3, 3);
    //$this->cuadrogrande(9,269,60,4,1,D);
    //$this->Text(11,272,"hola", 0);


}
/*
function Row($data,$dibujacelda)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=4*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border

        $this->Rect($x,$y,$w,$h);

        $this->MultiCell($w,4,$data[$i],$dibujacelda[$i],$a,'false');
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}*/
public function Row($data,$dibujacelda,$fuente,$v=true,$color,$fill)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=4*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border

        //$this->Rect($x,$y,$w,$h);
         $this->SetFillColor($fill[$i][0],$fill[$i][1],$fill[$i][2]);
        $this->SetTextColor($color[$i][0],$color[$i][1],$color[$i][2]);
        $this->SetFont($fuente[$i][0],$fuente[$i][1],$fuente[$i][2]);
         $this->MultiCell($w,4,$data[$i],$dibujacelda[$i],$a,$v[$i]);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function RowCuadro($data,$dibujacelda,$fuente,$v=true,$cuadro,$fill=array(array('12','12','0')))
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=4*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row

        $xx=$this->GetX();
        $yy=$this->GetY();
        //Draw the border

        $this->SetXY($xx,$yy);
         $this->RoundedRect($xx+2, $yy-17, $cuadro[0], $cuadro[1], $cuadro[2],$cuadro[3]);
         $this->SetXY($xx+2,$yy-17);

    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$xx;
        $y=$yy;
        //Draw the border



        //$this->Rect($x,$y,$w,$h);
       // $this->SetFillColor($fill[$i][0],$fill[$i][1],$fill[$i][2]);
        $this->SetFont($fuente[$i][0],$fuente[$i][1],$fuente[$i][2]);
        $this->MultiCell($w,4,$data[$i],$dibujacelda[$i],$a,$v);




        //Put the position to the right of the cell
        $this->SetXY($xx+$w,$yy-0.5);
    }
    //Go to the next line
    $this->Ln($h);
}

function RowPeque($data,$dibujacelda,$fuente,$v=true,$color,$fill)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=2.3*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border

        //$this->Rect($x,$y,$w,$h);
       $this->SetFillColor($fill[$i][0],$fill[$i][1],$fill[$i][2]);
        $this->SetTextColor($color[$i][0],$color[$i][1],$color[$i][2]);
        $this->SetFont($fuente[$i][0],$fuente[$i][1],$fuente[$i][2]);
        $this->MultiCell($w,2.3,$data[$i],$dibujacelda[$i],$a,$v[$i]);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
function Row1($data,$dibujacelda,$fuente,$v=true)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5.5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border

        //$this->Rect($x,$y,$w,$h);
        $this->SetFont($fuente[$i][0],$fuente[$i][1],$fuente[$i][2]);
        $this->MultiCell($w,5.5,$data[$i],$dibujacelda[$i],$a,$v);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
function cuadrogrande_salto($ejexx,$ejeyy,$ancho,$alto,$curva,$relleno,$misma=true){
        /*
        datos:
        1er valor: ancho de lado izquierdo
        2do valor: ancho de arriba
        3er valor: ancho del cuadro
        4to valor: alto del cuadro
        5to valor: ancho de las esquinas
        6to valor: D: dibuja borde; FD: dibuja borde y rellena
        */
          $h=2.3*$alto;
    //Issue a page break first if needed
       $this->CheckPageBreak($h);
       $ejex=$this->GetX()+$ejexx;

        if($misma==false){
             $ejey=$this->GetY()-4;
             $this->SetXY($ejex,$ejey);
        }else{
             $ejey=$this->GetY();
         $this->SetXY($ejex,$ejey);
        }
         $this->RoundedRect($ejex, $ejey, $ancho, $alto, $curva, $relleno);

        if($misma==true){
              $this->SetXY($ejex+$ejexx,$ejey);
        }
    }
function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}


/*********************************************************/

function TablaBasica($data,$x,$y)
   {
    $this->Ln();
     for($i=0;$i<count($data);$i++){
      $this->Cell($x[$i],$y[$i],$data[$i],'0');
    }

   }
   function cabecera($header,$x,$y){
     for($i=0;$i<count($header);$i++){

            $this->Cell($x[$i],$y[$i],$header[$i],'0');
        }
   }






/**********************************************************************************************/









    function cabeceraHorizontal($cabecera)
    {
        $this->SetXY(10, 10);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(2,157,116);//Fondo verde de celda
        $this->SetTextColor(240, 255, 240); //Letra color blanco
        $ejeX = 10;
        foreach($cabecera as $fila)
        {
            $this->RoundedRect($ejeX, 10, 30, 7, 2, 'FD');
            $this->CellFitSpace(30,7, utf8_decode($fila),0, 0 , 'C');
            $ejeX = $ejeX + 30;
        }
    }


    function datosHorizontal($datos)
    {
        $this->SetXY(10,17);
        $this->SetFont('Arial','',10);
        $this->SetFillColor(229, 229, 229); //Gris tenue de cada fila
        $this->SetTextColor(3, 3, 3); //Color del texto: Negro
        $bandera = false; //Para alternar el relleno
        $ejeY = 17; //Aquí se encuentra la primer CellFitSpace e irá incrementando
        $letra = 'D'; //'D' Dibuja borde de cada CellFitSpace -- 'FD' Dibuja borde y rellena
        foreach($datos as $fila)
        {
            //Por cada 3 CellFitSpace se crea un RoundedRect encimado
            //El parámetro $letra de RoundedRect cambiará en cada iteración
            //para colocar FD y D, la primera iteración es D
            //Solo la celda de enmedio llevará bordes, izquierda y derecha
            //Las celdas laterales colocarlas sin borde
            $this->RoundedRect(10, $ejeY, 90, 7, 2, $letra);
            $this->CellFitSpace(30,7, utf8_decode($fila['nombre']),0, 0 , 'L' );
            $this->CellFitSpace(30,7, utf8_decode($fila['apellido']),'LR', 0 , 'L' );
            $this->CellFitSpace(30,7, utf8_decode($fila['matricula']),0, 0 , 'L' );

            $this->Ln();
            //Condición ternaria que cambia el valor de $letra
            ($letra == 'D') ? $letra = 'FD' : $letra = 'D';
            //Aumenta la siguiente posición de Y (recordar que X es fijo)
            //Se suma 7 porque cada celda tiene esa altura
            $ejeY = $ejeY + 7;
        }
    }
    function cuadropequeño($ejex,$ejey,$ancho,$alto,$curva,$relleno){
         $this->SetXY($ejex,$ejey);
         $this->RoundedRect($ejex, $ejey, $ancho, $alto, $curva, $relleno);
    }
    function leyendas($x,$y,$font,$estilo,$tamaño,$ancho,$alto,$datos)
    {
        $this->SetXY($x,$y);
        $this->SetFont($font,$estilo,$tamaño);
       // $this->SetFillColor(229, 229, 229); //Gris tenue de cada fila
        //$this->SetTextColor(3, 3, 3); //Color del texto: Negro
        $this->CellFitSpace($ancho,$alto, utf8_decode($datos),0, 0 , 'L' );
        $this->Ln();

    }
    function leyendasColor($x,$y,$font,$estilo,$tamaño,$ancho,$alto,$datos,$color1,$color2,$color3)
    {
        $this->SetXY($x,$y);
        $this->SetFont($font,$estilo,$tamaño);
       // $this->SetFillColor(229, 229, 229); //Gris tenue de cada fila
        $this->SetTextColor($color1, $color2, $color3); //Color del texto: Negro
        $this->CellFitSpace($ancho,$alto, utf8_decode($datos),0, 0 , 'L' );
        $this->Ln();

    }
    function multicelda($x,$y,$multiAncho,$multiAlto,$font,$estilo,$tamaño,$ancho,$alto,$datos,$justi='')
    {
        $this->SetXY($x,$y);
        $this->SetFont($font,$estilo,$tamaño);
      //  $this->SetFillColor(229, 229, 229); //Gris tenue de cada fila
        //$this->SetTextColor(3, 3, 3); //Color del texto: Negro
        //$this->CellFitSpace($ancho,$alto, utf8_decode($datos),0, 0 , 'L' );
        $this->MultiCell($multiAncho,$multiAlto,$datos,0, $justi);
        $this->Ln();

    }
    function cuadrogrande($ejex,$ejey,$ancho,$alto,$curva,$relleno){
        /*
        datos:
        1er valor: ancho de lado izquierdo
        2do valor: ancho de arriba
        3er valor: ancho del cuadro
        4to valor: alto del cuadro
        5to valor: ancho de las esquinas
        6to valor: D: dibuja borde; FD: dibuja borde y rellena
        */
         $this->SetXY($ejex,$ejey);
         $this->RoundedRect($ejex, $ejey, $ancho, $alto, $curva, $relleno);

    }

    function tablaHorizontal($cabeceraHorizontal, $datosHorizontal)
    {
        $this->cabeceraHorizontal($cabeceraHorizontal);
        $this->datosHorizontal($datosHorizontal);
    }

    //**************************************************************************************************************
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }

        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }

    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if($this->CurrentFont['type']=='Type0')
        {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++)
            {
                if (ord($s[$i])<128)
                    $len++;
                else
                {
                    $len++;
                    $i++;
                }
            }
            return $len;
        }
        else
            return strlen($s);
    }
//**********************************************************************************************

 function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m', ($x+$r)*$k, ($hp-$y)*$k ));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-$y)*$k ));
        if (strpos($angle, '2')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$yc)*$k));
        if (strpos($angle, '3')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-($y+$h))*$k));
        if (strpos($angle, '4')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$yc)*$k ));
        if (strpos($angle, '1')===false)
        {
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$y)*$k ));
            $this->_out(sprintf('%.2f %.2f l', ($x+$r)*$k, ($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
} // FIN Class PDF
?>
