<?php
/* @var $data array */
/* @var $year integer */

error_reporting(E_ERROR);

$file_name = 'Zvedennya_'.$year . '.xls';
$workbook = new Spreadsheet_Excel_Writer();
$workbook->send($file_name);

// Creating a worksheet
$worksheet =& $workbook->addWorksheet('Report');
$worksheet->setInputEncoding('windows-1251');
//$worksheet->setLandscape();

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

$format_top_center_nb =& $workbook->addFormat();
$format_top_center_nb->setAlign('top');
$format_top_center_nb->setAlign('center');
$format_top_center_nb->setBold();
$format_top_center_nb->setTextWrap();
$format_top_center_nb->setBorder(0);


// The actual data
$worksheet->setColumn(0, 0, 12);
$worksheet->setColumn(1, 1, 9);
$worksheet->setColumn(2, 2, 15);
$worksheet->setColumn(3, 3, 10);
$worksheet->setColumn(4, 4, 14);
$worksheet->setColumn(5, 5, 15);
$worksheet->setColumn(6, 6, 10);

$worksheet->mergeCells(0,0,0,6);
$worksheet->mergeCells(1,0,1,6);
$worksheet->mergeCells(2,1,2,6);
$worksheet->mergeCells(3,3,3,6);


$worksheet->write(0, 0, iconv("utf-8", "windows-1251","Зведення "),
        $format_top_center_nb);
$worksheet->write(1, 0, iconv("utf-8", "windows-1251","про виконання документів, 
  що підлягають індивідуальному контролю станом на ".date('Y')." р."),
        $format_top_center_nb);
        
$worksheet->write(2, 1, iconv("utf-8", "windows-1251","Документи на контролі"),
        $format_top_center);
$worksheet->write(3, 3, iconv("utf-8", "windows-1251","з них"),
        $format_top_center);
        
$worksheet->write(2, 0, iconv("utf-8", "windows-1251","Назви та індекси структурних підрозділів"),
        $format_top_center);
$worksheet->mergeCells(2,0,4,0);
$worksheet->write(3, 1, iconv("utf-8", "windows-1251","загалом"),
        $format_top_center);
$worksheet->mergeCells(3,1,4,1);
$worksheet->write(3, 2, iconv("utf-8", "windows-1251","надійшло за рік"),
        $format_top_center);
$worksheet->mergeCells(3,2,4,2);
$worksheet->write(4, 3, iconv("utf-8", "windows-1251","виконані"),
        $format_top_center);
$worksheet->write(4, 4, iconv("utf-8", "windows-1251","виконуються у визначений строк"),
        $format_top_center);      
$worksheet->write(4, 5, iconv("utf-8", "windows-1251","з продовженням строку виконання"),
        $format_top_center);
$worksheet->write(4, 6, iconv("utf-8", "windows-1251","прострочені"),
        $format_top_center);
        
$worksheet->write(5, 0, iconv("utf-8", "windows-1251","1"),
        $format_wordwrap);
$worksheet->write(5, 1, iconv("utf-8", "windows-1251","2"),
        $format_wordwrap);
$worksheet->write(5, 2, iconv("utf-8", "windows-1251","3"),
        $format_wordwrap);
$worksheet->write(5, 3, iconv("utf-8", "windows-1251","4"),
        $format_wordwrap);
$worksheet->write(5, 4, iconv("utf-8", "windows-1251","5"),
        $format_wordwrap);      
$worksheet->write(5, 5, iconv("utf-8", "windows-1251","6"),
        $format_wordwrap);
$worksheet->write(5, 6, iconv("utf-8", "windows-1251","7"),
        $format_wordwrap);

$i = 5;


foreach ($data as $item){
  $i++;
}

	
$worksheet->write($i+1, 0, iconv("utf-8", "windows-1251","В. о. начальника загального відділу      В. В. Буличова"),
	$format_top_center);
$worksheet->mergeCells($i+1,0,$i+1,6);
// Let's send the file
$worksheet->setMarginLeft(1.14);
$worksheet->setMarginRight(0.01);

$workbook->close();
?>


