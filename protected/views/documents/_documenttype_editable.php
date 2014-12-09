<?php
  /* @var $DocID integer */
  $dtmodel = new Documenttype();
  $dtmodel->DocumentTypeName = "назва нового типу документа";
  $dtmodel->save();
  ?>
<span>Новий тип (редагування): </span>
<?php
  $this->widget('editable.EditableField', array(
   'type' => 'text',
   'attribute' => 'DocumentTypeName',
   'name' => 'DocumentTypeName',
   'model' => $dtmodel,
   'url' => $this->createUrl('/documenttype/UpdateEditable',
      array('DocID' => $DocID)),
   'title' => 'Тип документа (новий)',
   'mode' => 'popup',
    'onSave' => 'js: function(e, params) {
        $.ajax({
            type: "GET",
            url: "../documenttype/AjaxRenderType?DocID='.$DocID.'"
        }).done(function(data) {
          $("#doctype").html(data);
        });
    }'
  ));