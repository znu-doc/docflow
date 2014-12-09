<?php
/* @var $model ControlMark */

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
    float: left;
    font-size: 11pt;
  }
  
</style>
</head>
<body>
  <table class="card">
      <h2 style="text-align: center;"> <strong>Контрольні відмітки</strong></h2>
      <tr style="height: 80px;vertical-align: middle;">
          <td class="common left">
              <div class='slabel'><b>Фонд №</b></div>
              <br/>
              <?php echo $model->Fund; ?>
              <br/>
          </td>
          <td class="common centre">
              <div class='slabel'><b>Опис №</b></div>
              <br/>
              <?php echo $model->Description; ?>            
          </td>
          <td>
              <div class='slabel'><b>Справа №</b></div>
              <br/>
              <?php echo $model->Act; ?>
          </td>
      </tr>

  </table>
</body>
</html>