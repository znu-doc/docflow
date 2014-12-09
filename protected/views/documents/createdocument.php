<?php
//Программист у ЭВМ
//Пишет код бесшумно.
//Дает программу без проблем
//С интерфейсом умным.
//
//
//
//Не отличил бы он, где Си, а где Фортран,
//Когда бы был программер пьян.
/* @var $this DocviewController */
/* @var $model Documents */
/* @var $fmodel Files */
/* @var $cat_list array */
/* @var $type_list array */



?>
<style>
  .input-medium {
    width: 400px !important;
  }
</style>
<div class="row-fluid" >
    <h1 class='span12 dfmetaheader'>Форма створення нового документа</h1>
    <div class='row-fluid'>
      <div class="span12 dfbox">
        <div class="span12 dfheader">Кореспондент</div>
        <div class="span12">
        <?php 
  $attr = 'Correspondent';
  $attr_title = 'Кореспондент';
  $this->widget('editable.EditableField', array(
   'type' => 'typeahead',
   'attribute' => $attr,
   'id' => 'Corr',
   'name' => $attr,
   'model' => $model,
   'url' => Yii::app()->CreateUrl('/documents/updateEditable',array('field' => $attr)),
   'title' => $attr_title,
   'mode' => 'popup',
   'showbuttons' => false,
   'source' => '../documents/AjaxItems?attr=Correspondent',
   'options' => array('onblur' => 'submit'),
   'placement' => 'right',
  ));
        ?>
        </div>
      </div>
    </div>
    
    <div class='row-fluid'>
      <div class="span6 dfbox">
        <div class="span12 dfheader">Дата надходження та індекс документа</div>
        <div class="span12">
        <?php
        if (Yii::app()->user->checkAccess('asOffice')){

    $attr = 'DocumentInputNumber';
    $attr_title = 'Дата надходж. та індекс документа';
    $this->widget('editable.EditableField', array(
     'type' => 'typeahead',
     'attribute' => $attr,
     'name' => $attr,
     'model' => $model,
     'url' => Yii::app()->CreateUrl('/documents/updateEditable',array('field' => $attr)),
     'title' => $attr_title,
      'source' => '../documents/AjaxItems?attr=DocumentInputNumber',
     'mode' => 'popup',
     'showbuttons' => false,
     'options' => array('onblur' => 'submit'),
     'placement' => 'right',
    ));
        }
        ?>
        </div>
      </div>
      <div class="span6 dfbox">
        <div class="span12 dfheader">Дата та індекс документа</div>
        <div class="span12">
        <?php
    $attr = 'DocumentOutputNumber';
    $attr_title = 'Дата та індекс документа';
    $this->widget('editable.EditableField', array(
     'type' => 'typeahead',
     'attribute' => $attr,
     'name' => $attr,
     'model' => $model,
     'url' => Yii::app()->CreateUrl('/documents/updateEditable',array('field' => $attr)),
     'title' => $attr_title,
      'source' => '../documents/AjaxItems?attr=DocumentOutputNumber',
     'mode' => 'popup',
     'showbuttons' => false,
     'options' => array('onblur' => 'submit'),
     'placement' => 'left',
    ));
        ?>
        </div>
      </div>
    </div>
    
    <div class='row-fluid'>
      <div class="span12 dfbox">
        <div class="span12 dfheader">Короткий зміст</div>
        <div class="span12">
        <?php
        $this->widget('editable.EditableField', array(
         'type' => 'textarea',
         'attribute' => 'DocumentDescription',
         'name' => 'DocumentDescription',
         'model' => $model,
         'url' => $this->createUrl('/documents/updateEditable',array('field' => 'DocumentDescription')),
         'title' => 'Короткий зміст',
         'placement' => 'right', 'mode' => 'popup',
         'showbuttons' => false,
         'options' => array('onblur' => 'submit', 'tpl' => '<textarea style="width: 400px;"></textarea>'),
        ));
        ?>
        </div>
      </div>
    </div>
    
    <div class='row-fluid'>
      <div class="span12 dfbox">
        <div class="span12 dfheader">Резолюція або кому надіслано документ</div>
        <div class="span12">
        <?php
    $attr = 'DocumentForWhom';
    $attr_title = 'Резолюція або кому надіслано документ';
    $this->widget('editable.EditableField', array(
     'type' => 'typeahead',
     'attribute' => $attr,
     'name' => $attr,
     'model' => $model,
     'url' => Yii::app()->CreateUrl('/documents/updateEditable',array('field' => $attr)),
     'title' => $attr_title,
      'source' => '../documents/AjaxItems?attr=DocumentForWhom',
     'mode' => 'popup',
     'showbuttons' => false,
     'options' => array('onblur' => 'submit'),
     'placement' => 'right',
    ));
        ?>
        </div>
      </div>
    </div>
    
    <div class="row-fluid">
      <div class="span12 dfbox">
        <div class="span12 dfheader">Відмітка про виконання документа</div>
        <div class="span12">
        <?php
    $attr = 'mark';
    $attr_title = 'Відмітка про виконання документа';
    $this->widget('editable.EditableField', array(
     'type' => 'typeahead',
     'attribute' => $attr,
     'name' => $attr,
     'model' => $model,
     'url' => Yii::app()->CreateUrl('/documents/updateEditable',array('field' => $attr)),
     'title' => $attr_title,
      'source' => '../documents/AjaxItems?attr=mark',
     'mode' => 'popup',
     'showbuttons' => false,
     'options' => array('onblur' => 'submit'),
     'placement' => 'right',
    ));
        ?>
        </div>
      </div>
    </div>
    
    <div class='row-fluid'>
      <div class="span6 dfbox">
        <div class="span12 dfheader">Назва документа</div>
        <div class="span12">
          <?php
    $attr = 'DocumentName';
    $attr_title = 'Назва документа';
    $this->widget('editable.EditableField', array(
     'type' => 'typeahead',
     'attribute' => $attr,
     'name' => $attr,
     'model' => $model,
     'url' => Yii::app()->CreateUrl('/documents/updateEditable',array('field' => $attr)),
     'title' => $attr_title,
      'source' => '../documents/AjaxItems?attr=DocumentName',
     'mode' => 'popup',
     'showbuttons' => false,
     'options' => array('onblur' => 'submit'),
     'placement' => 'right',
      'onShown' => 'js: function() {
          var $tip = $(this).data("editableContainer").tip();
          var txtval = $tip.find("input").val();
          if (txtval === "Натисніть, щоб ввести значення"){
            $tip.find("input").val("");
          }
      }'
    ));
          ?>
        </div>
      </div>
      <div class="span6 dfbox">
        <div class="span12 dfheader">Підписано</div>
        <div class="span12">
        <?php
    $attr = 'signed';
    $attr_title = 'Підписано';
    $this->widget('editable.EditableField', array(
     'type' => 'typeahead',
     'attribute' => $attr,
     'name' => $attr,
     'model' => $model,
     'url' => Yii::app()->CreateUrl('/documents/updateEditable',array('field' => $attr)),
     'title' => $attr_title,
      'source' => '../documents/AjaxItems?attr=signed',
     'mode' => 'popup',
     'showbuttons' => false,
     'options' => array('onblur' => 'submit'),
     'placement' => 'left',
    ));
        ?>
        </div>
      </div>
    </div>
    
    <div class='row-fluid'>
      <div class="span6 dfbox">
        <div class="span12 dfheader">Категорія</div>
        <div class="span12">
        <?php
        $this->widget('editable.EditableField', array(
         'type' => 'select',
         'attribute' => 'DocumentCategoryID',
         'name' => 'DocumentCategoryID',
         'model' => $model,
         'url' => $this->createUrl('/documents/updateEditable',array('field' => 'DocumentCategoryID')),
         'title' => 'Категорія документа',
         'source' => $cat_list,
         'placement' => 'right', 'mode' => 'popup',
         'showbuttons' => false,
       ));
        ?>
        </div>
      </div>
      <div class="span6 dfbox">
        <div class="span12 dfheader">Тип документа</div>
        <div class="row-fluid">
          <div class="span4" id="doctype">
          <?php
          $this->widget('editable.EditableField', array(
           'type' => 'select',
           'attribute' => 'DocumentTypeID',
           'name' => 'DocumentTypeID',
           'model' => $model,
           'url' => $this->createUrl('/documents/updateEditable',array('field' => 'DocumentTypeID')),
           'title' => 'Тип документа',
           'source' => $this->createUrl('/documenttype/DropDown'),
           'placement' => 'left', 'mode' => 'popup',
           'showbuttons' => false,
         ));
          ?>
          </div>
          <div class="span8">
            <div id="ajax_documenttype">
            </div>
            <?php
              if (Yii::app()->user->checkAccess('asOffice')){
              echo CHtml::ajaxLink ("Створити новий тип",
                CController::createUrl('/documenttype/AjaxRenderNewType?DocID='
                        .$model->idDocument), 
                array('update' => '#ajax_documenttype'),
                array('style' => 'color: green; font-size: 8pt; border: 1px solid lightgreen;'));
              }
            ?>
          </div
        </div>
      </div>
    </div>
    
    <div class='row-fluid'>
      <div class="span4 dfbox">
        <div class="span12 dfheader">Файли</div>
        <div class="span11">
        <?php
          $this->widget('bootstrap.widgets.TbFileUpload', array(
                  'url' => $this->createUrl('/files/upload', array('DocumentID' => $model->idDocument)),
                  'imageProcessing' => true,
                  'name' => 'file_itself',
                  'multiple' => false,
                  'model' => $fmodel,
                  'attribute' => 'file_itself', // see the attribute?
                  'multiple' => false,
                  'uploadView' => 'application.views.documents._fileupload',
                  'formView' => 'application.views.documents._fileupload_form',
                  'downloadView' => 'application.views.documents._fileupload_download',
                  'options' => array(
                    'maxFileSize' => 200000000,
                    'acceptFileTypes' => 'js:/(\.|\/)(pdf|rtf|odt|ods|txt|csv|'.
            'jpg|gif|png|tiff|tif|bmp|jpeg|'.
            'doc|docx|xls|xlsx|ppt|pptx|'.
            'html|htm|js|css|zip|rar|7z|tar|gz)$/i',
                    'autoUpload' => true,
              )));
        ?>
        </div>
      </div>
      <div class="span8">
        <div class='row-fluid'>
        <?php
        if (Yii::app()->user->checkAccess('asOffice')){
          $this->widget(
              "bootstrap.widgets.TbButton", array(
                'buttonType' => 'link',
                'url' => Yii::app()->CreateUrl('/documents/autonum',array('id' => $model->idDocument)),
                'type' => 'success',
                "size" => "large",
                'htmlOptions' => array(
                    'class' => 'span12',
                    'style' => 'font-size: 12pt;',
                    'id' => 'auto_button',
                ),
                'label' => 'Автоматично вставити дату надходження та індекс документа і перейти на список документів',
              )
          );
        }
        ?>
        </div>
        <div class='row-fluid'>
          <div class="span8">
          </div>
          <div class="span4">
          <?php
          $this->widget(
              "bootstrap.widgets.TbButton", array(
                'buttonType' => 'link',
                'url' => Yii::app()->CreateUrl('/documents/index',
                  array('id' => $model->idDocument, 'Save' => 1)),
                'type' => 'primary',
                "size" => "mini",
                'htmlOptions' => array(
                    'class' => 'span12',
                    'style' => 'margin-top: 10px; margin-bottom: 10px;'
                ),
                'label' => 'Перейти на список документів',
              )
          );
          ?>
          </div>
        </div>
      </div>
    </div>

</div>