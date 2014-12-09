<?php
/* @var $this Sitecontroller */
$this->pageTitle=Yii::app()->name;
?>

<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
'heading'=>'Вітаємо в системі "'.CHtml::encode(Yii::app()->name).'"',
'htmlOptions' => array(
  'class' => 'dfbox',
  'style' => 'font-family: Arial;'
)
)); ?>

<p>Запорізький національний університет</p>
<br/>

    <div class="row-fluid">
      <div class="span4">
      <?php $this->widget('bootstrap.widgets.TbButton', array(
      'label'=>'Авторизуватися',
      'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
      'size'=>'large', // null, 'large', 'small' or 'mini'
      'url'=>Yii::app()->createUrl("site/login"),
      'htmlOptions'=>array('class'=>'span12'),
      )); ?>
      </div>
    </div>
    <hr/>
    <div class="row-fluid">
      <div class="span4" style="">
      <?php $this->widget('bootstrap.widgets.TbButton', array(
      'label'=>'Інформація про документообіг',
      'type'=>'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
      'size'=>'large', // null, 'large', 'small' or 'mini'
      'url'=>Yii::app()->createUrl("docflowinfo"),
      'htmlOptions'=>array('class'=>'span12'),
      )); ?>
      </div>
    </div>

<hr/>
<?php $this->endWidget(); ?>
