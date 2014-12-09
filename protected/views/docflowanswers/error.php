<?php
/* @var $returl string */
/* @var $message string */
  
?>
<style>
  .error_header{
    color: red;
    text-align: center;
    font-size: 14pt;
  }
</style>
<div class="row-fluid dfbox error_header">
  Виникла помилка
</div>
<div class="row-fluid dfbox">
  <?php echo $message; ?>
  <hr/>
  <?php echo CHtml::link('Перейти назад',$returl); ?>
</div>