<?php
/* @var $data array */

error_reporting(E_ERROR);

$file_name = 'Dovidka_'.date('Y') . '.xls';
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


// The actual data
$worksheet->setColumn(0, 0, 35);
$worksheet->setColumn(1, 1, 10);
$worksheet->setColumn(2, 2, 10);
$worksheet->setColumn(3, 3, 12);
$worksheet->setColumn(4, 4, 12);
$worksheet->mergeCells(0,0,0,4);


$worksheet->write(0, 0, iconv("utf-8", "windows-1251","Довідка про обсяг документообігу в загальному відділі за ".date('Y')." рік"),
        $format_top_center);
$worksheet->write(1, 0, iconv("utf-8", "windows-1251","Документи за номенклатурою"),
        $format_top_center);
$worksheet->write(1, 1, iconv("utf-8", "windows-1251","Вхідні"),
        $format_top_center);
$worksheet->write(1, 2, iconv("utf-8", "windows-1251","Вихідні"),
        $format_top_center);
$worksheet->write(1, 3, iconv("utf-8", "windows-1251","Виконано"),
        $format_top_center);
$worksheet->write(1, 4, iconv("utf-8", "windows-1251","Невиконано"),
        $format_top_center);

$i = 2;
$at_all = 0;
$control_mark = 0;
$done_mark = 0;
$undone_mark = 0;

foreach ($data as $item){
  $worksheet->write($i, 0, iconv("utf-8", "windows-1251",$item['cat']),
	  $format_wordwrap);
  $worksheet->write($i, 1, iconv("utf-8", "windows-1251",$item['at_all']),
	  $format_wordwrap);
  $worksheet->write($i, 2, iconv("utf-8", "windows-1251",""),
	  $format_wordwrap);
  $worksheet->write($i, 3, iconv("utf-8", "windows-1251",$item['done_mark']),
	  $format_wordwrap);
  $worksheet->write($i, 4, iconv("utf-8", "windows-1251",($item['control_mark']-$item['done_mark'])),
	  $format_wordwrap);
  $at_all += $item['at_all'];
  $done_mark += $item['done_mark'];
  $undone_mark += ($item['control_mark']-$item['done_mark']);
  $i++;
}
$worksheet->write($i, 0, iconv("utf-8", "windows-1251","Разом"),
	$format_wordwrap);
$worksheet->write($i, 1, iconv("utf-8", "windows-1251",$at_all),
	$format_wordwrap);
$worksheet->write($i, 2, iconv("utf-8", "windows-1251",""),
	$format_wordwrap);
$worksheet->write($i, 3, iconv("utf-8", "windows-1251",$done_mark),
	$format_wordwrap);
$worksheet->write($i, 4, iconv("utf-8", "windows-1251",$undone_mark),
	$format_wordwrap);
	
$worksheet->write($i+1, 0, iconv("utf-8", "windows-1251","В. о. начальника загального відділу      В. В. Буличова"),
	$format_top_center);
$worksheet->mergeCells($i+1,0,$i+1,4);
// Let's send the file
$workbook->close();
?>


