<?php
/* @var $model Documents */
/* @var $this DocumentsController */


?>

<?php 
  $search_doc_form = $this->BeginWidget(
  'bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-doc-form',
    'action' => Yii::app()->CreateUrl('/documents/index'),
    'enableAjaxValidation' => false,
   )
  );
?>
<div class='row-fluid'>
  <div class="span12 dfbox ">
    <div class='row-fluid'>
      <h1 class='span12 dfmetaheader'>Розширений пошук по документам</h1>
    </div>
    <div class='row-fluid'>
      <div class="span4">
        <?php
          echo CHtml::label("Назва документа","Documents_DocumentName",array("class"=>"span12"));
          echo $search_doc_form->TextField($model,"DocumentName",
            array("class"=>"span12"));
        ?>
      </div>
      <div class="span8">
        <?php
          echo CHtml::label("Короткий зміст","Documents_DocumentDescription",array("class"=>"span12"));
          echo $search_doc_form->TextField($model,"DocumentDescription",
            array("class"=>"span12"));
        ?>
      </div>
    </div>
    <div class='row-fluid'>
      <div class="span3">
        <?php
          echo CHtml::label("Дата та індекс док.","Documents_DocumentOutputNumber",array("class"=>"span12"));
          echo $search_doc_form->TextField($model,"DocumentOutputNumber",
            array("class"=>"span12"));
        ?>
      </div>
      <div class="span3">
        <?php
          echo CHtml::label("Дата надходж. та індекс док.","Documents_DocumentInputNumber",array("class"=>"span12"));
          echo $search_doc_form->TextField($model,"DocumentInputNumber",
            array("class"=>"span12"));
        ?>
      </div>
      <div class="span6">
        <?php
          echo CHtml::label("Резолюція або кому надісл. док.","Docflows_DocumentForWhom",array("class"=>"span12"));
          echo $search_doc_form->TextField($model,"DocumentForWhom",
            array("class"=>"span12"));
        ?>
      </div>
    </div>
    <div class='row-fluid'>
      <div class="span3">
        <?php
          echo CHtml::label("Кореспондент","Documents_Correspondent",array("class"=>"span12"));
          echo $search_doc_form->TextField($model,"Correspondent",
            array("class"=>"span12"));
        ?>
      </div>
      <div class="span6">
        <?php
          echo CHtml::label("Відмітки про виконання","Documents_mark",array("class"=>"span12"));
          echo $search_doc_form->TextField($model,"mark",
            array("class"=>"span12"));
        ?>
      </div>
      <div class="span3">
        <?php
          echo CHtml::label("Створено (формат: РРРР-ММ-ДД)","Docflows_Created",array("class"=>"span12"));
          echo $search_doc_form->TextField($model,"Created",
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