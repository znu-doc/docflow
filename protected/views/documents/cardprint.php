<?php
/* @var $this DocumentsController */
/* @var $model Documents */

?>
<html>

<head>
  <meta charset="utf-8" />
<style>
  TABLE.card{
    border-collapse: collapse;
    margin: 0px auto;
    width: 7.25in;
    border: 3px solid black;
    border-left-width: 0px;
    border-right-width: 0px;
    font-family: 'Times New Roman';
  }
  
  td {
    font-size: 14pt;
  }
  
  TD.common {
    border: 3px solid black;
    border-left-width: 0px;
    border-right-width: 3px;
  }
  TD.half{
    width: 50%;
  }
  TD.last {
    border-right-width: 0px !important;
  }
  
  div.slabel {
    float: right;
    font-size: 11pt;
  }
  
</style>
</head>
<body>
  <table class="card">
    <tr>
      <td colspan="2" class="common last">
        <div style="width: 4.7in; margin: 0px auto; font-size: 10pt; font-weight: bold;">
        <?php
          for ($i = 1; $i <=30; $i++){
            echo $i . ' ';
          }
        ?>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="common last">
        <?php echo $model->Correspondent; ?>
        <br/>
        <div class='slabel'><b>Кореспондент</b></div>
      </td>
    </tr>
    <tr style="height: 80px;vertical-align: middle;">
      <td class="common half">
        <?php echo $model->DocumentInputNumber; ?>
        <br/>
        <div class='slabel'><b>Дата надходження та індекс документа</b></div>
      </td>
      <td class="common last">
        <?php echo $model->DocumentOutputNumber; ?>
        <br/>
        <div class='slabel'><b>Дата та індекс документа</b></div>
      </td>
    </tr>
    <tr>
     <td colspan="2" class="common last" style="height: 120px;vertical-align: bottom;">
        <?php echo $model->DocumentDescription; ?>
        <br/>
        <div class='slabel'><b>Короткий зміст</b></div>
      </td>
    </tr>
    <tr style="height: 80px;vertical-align: bottom;">
     <td colspan="2" class="common last">
        <?php echo $model->DocumentForWhom . ((!empty($model->signed))? ' ( ' .$model->signed . ') ' : ''); ?>
        <br/>
        <div class='slabel'><b>Резолюція або кому надіслано документ</b></div>
      </td>
    </tr>
    <tr style="height: 100px;vertical-align: bottom;">
     <td colspan="2" class="common last">
        <?php 
        
          $controls = array();
          foreach ($model->docflows as $flow){
            $controls[]= $flow->ControlDate;
          }
          $control_dates = implode('; ',$controls);
          $for_echo = trim(implode("; ",array($control_dates,$model->mark)));
          if ($for_echo != ";" && $for_echo != "; ;"){
            echo $for_echo;
          }
        ?>
        <br/>
        <div class='slabel'><b>Відмітка про виконання документа</b></div>
      </td>
    </tr>
  </table>
</body>
</html>