<?php
/* @var $DocID integer */
$model = Documents::model()->findByPk($DocID);
          $this->widget('editable.EditableField', array(
           'type' => 'select',
           'attribute' => 'DocumentTypeID',
           'name' => 'DocumentTypeID',
           'model' => $model,
           'url' => $this->createUrl('/documents/updateEditable',array('field' => 'DocumentTypeID')),
           'title' => 'Тип документа',
           'source' => $this->createUrl('/documenttype/DropDown'),
           'placement' => 'right', 'mode' => 'popup'
         ));

?>