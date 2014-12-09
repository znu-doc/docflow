<?php
/* @var $models Documents[] */
/* @var $doc Documents */

error_reporting(E_ERROR);

$file_name = 'Report_'.date('d.m.Y_h-i-s') . '.xls';
$workbook = new Spreadsheet_Excel_Writer();
$workbook->send($file_name);

// Creating a worksheet
$worksheet =& $workbook->addWorksheet('Report');
$worksheet->setInputEncoding('windows-1251');
$worksheet->setLandscape();

$doc = new Documents;

// put text at the top and center it horizontally
$format_top_center =& $workbook->addFormat();
$format_top_center->setAlign('top');
$format_top_center->setAlign('center');
$format_top_center->setBold();
$format_top_center->setTextWrap();
$format_top_center->setBorder(1);

$format_wordwrap =& $workbook->addFormat();
$format_wordwrap->setTextWrap();
$format_wordwrap->setAlign('top');
$format_wordwrap->setBorder(1);


// The actual data
$worksheet->setColumn(0, 0, 16);
$worksheet->setColumn(1, 1, 16);
$worksheet->setColumn(2, 2, 18);
$worksheet->setColumn(3, 3, 24);
$worksheet->setColumn(4, 4, 17);
$worksheet->setColumn(5, 5, 11);
$worksheet->setColumn(6, 6, 21);

$worksheet->write(0, 0, iconv("utf-8", "windows-1251",$doc->getAttributeLabel('DocumentInputNumber')),
        $format_top_center);
$worksheet->write(0, 1, iconv("utf-8", "windows-1251",$doc->getAttributeLabel('DocumentOutputNumber')),
        $format_top_center);
$worksheet->write(0, 2, iconv("utf-8", "windows-1251",$doc->getAttributeLabel('Correspondent')),
        $format_top_center);
$worksheet->write(0, 3, iconv("utf-8", "windows-1251",$doc->getAttributeLabel('DocumentDescription')),
        $format_top_center);
$worksheet->write(0, 4, iconv("utf-8", "windows-1251",$doc->getAttributeLabel('DocumentForWhom')),
        $format_top_center);
$worksheet->write(0, 5, iconv("utf-8", "windows-1251",$doc->getAttributeLabel('DocumentTypeID')),
        $format_top_center);
$worksheet->write(0, 6, iconv("utf-8", "windows-1251",$doc->getAttributeLabel('mark')),
        $format_top_center);
$i = 1;

    foreach ($models as $model){
      foreach ($model->docflows as $flow){
        $controls[]= $flow->ControlDate;
      }
      $worksheet->write($i, 0, iconv("utf-8", "windows-1251",$model->DocumentInputNumber),
              $format_wordwrap);
      $worksheet->write($i, 1, iconv("utf-8", "windows-1251",$model->DocumentOutputNumber),
              $format_wordwrap);
      $worksheet->write($i, 2, iconv("utf-8", "windows-1251",$model->Correspondent),
              $format_wordwrap);
      $worksheet->write($i, 3, iconv("utf-8", "windows-1251",$model->DocumentDescription),
              $format_wordwrap);
      $for_whom = (mb_strlen($model->DocumentForWhom,'utf-8')>4) ? 
              $model->DocumentForWhom . '; ' : "";
      if (trim($model->signed)){
        $for_whom .= 'підписано: '.$model->signed;
      }
      $worksheet->write($i, 4, iconv("utf-8", "windows-1251",$for_whom),
              $format_wordwrap);
      $worksheet->write($i, 5, iconv("utf-8", "windows-1251",$model->type->DocumentTypeName),
              $format_wordwrap);
      $mark = $model->mark;
      $controls = array();
      foreach ($model->docflows as $flow){
        if (trim($flow->ControlDate) != ""){
          $controls [] = $flow->ControlDate;
        }
      }
      $imploded_control = implode(' ; ',$controls);
      $str_control = "";
      for ($j = 0; $j < strlen($imploded_control); $j++){
        if ($imploded_control[$j] != ';' && $imploded_control[$j] != ' '){
          $str_control = (($mark != "") ? '; ' : '') . $imploded_control;
          break;
        }
      }
      $mark .= $str_control;
      $worksheet->write($i, 6, iconv("utf-8", "windows-1251",$mark),
              $format_wordwrap);
      $i++;
    }

// Let's send the file
$workbook->close();
?>


