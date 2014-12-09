<?php
/* @var $model Docflows */
/* @var $this DocflowsController */
/* @var $mode string */


?>

<?php 
  $search_flow_form = $this->BeginWidget(
  'bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-docflow-form',
    'action' => Yii::app()->CreateUrl('/docflows/index'),
    'enableAjaxValidation' => false,
   )
  );
?>
<div class='row-fluid'>
  <div class="span12 dfbox ">
    <div class='row-fluid'>
      <h1 class='span12 dfmetaheader'>Розширений пошук по розсилкам</h1>
    </div>
    <div class='row-fluid'>
      <div class="span4">
        <?php
          echo CHtml::label("Назва розсилки","Docflows_DocFlowName",array("class"=>"span12"));
          echo $search_flow_form->TextField($model,"DocFlowName",
            array("class"=>"span12"));
          echo CHtml::hiddenField("mode",$mode);
        ?>
      </div>
      <div class="span8">
        <?php
          echo CHtml::label("Особливості розсилки","Docflows_DocFlowDescription",array("class"=>"span12"));
          echo $search_flow_form->TextField($model,"DocFlowDescription",
            array("class"=>"span12"));
        ?>
      </div>
    </div>
    <div class='row-fluid'>
      <div class="span3">
        <?php
          echo CHtml::label("Розпочато (формат: РРРР-ММ-ДД)","Docflows_Created",array("class"=>"span12"));
          echo $search_flow_form->TextField($model,"Created",
            array("class"=>"span12"));
        ?>
      </div>
      <div class="span3">
        <?php
          echo CHtml::label("Діє до (формат: РРРР-ММ-ДД)","Docflows_ExpirationDate",array("class"=>"span12"));
          echo $search_flow_form->TextField($model,"ExpirationDate",
            array("class"=>"span12"));
        ?>
      </div>
      <div class="span6">
        <?php
          echo CHtml::label("Контроль","Docflows_ControlDate",array("class"=>"span12"));
          echo $search_flow_form->TextField($model,"ControlDate",
            array("class"=>"span12"));
        ?>
      </div>
    </div>
    <hr/>
    <div class='row-fluid' style="text-align: center;">
      <?php
      $this->widget(
          "bootstrap.widgets.TbButton", array(
            'buttonType' => 'submit',
            'type' => 'primary',
            "size" => "large",
            'label' => 'Пошук',
          )
      );
      ?>
    </div>
  </div>
</div>
<?php 
  $this->endWidget();
?>

<script type="text/javascript" charset="utf-8">
  $('.datepicker').datepicker({format: "dd.mm.yyyy", weekStart: 1});
</script>