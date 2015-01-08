<?php
  /* @var $data Documents */
  /* @var $controller DocumentsController */
?>

<div class="row-fluid dfmetaheader">
<?php
$data->MyDeptIDs = implode(',',$controller->my_dept_ids);
if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
  $controller->widget('editable.EditableField', array(
 'type' => 'textarea',
 'attribute' => 'DocumentName',
 'model' => $data,
 'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'DocumentName')),
 'title' => 'Назва розсилки',
 'placement' => 'right'
 ));
} else {
  echo $data->DocumentName;
}

$cnt = $data->MyDeptDocflowsCount();
echo '<br/>'
  .'<span class="dfheader">Тип: <i class="header_value">';
  if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
    $controller->widget('editable.EditableField', array(
     'type' => 'select',
     'attribute' => 'DocumentTypeID',
     'name' => 'DocumentTypeID',
     'model' => $data,
     'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'DocumentTypeID')),
     'title' => 'Тип документа',
     'source' => Documenttype::DropDown(),
     'placement' => 'right', 'mode' => 'popup',
     'showbuttons' => false,
    ));
  } else {
    echo $data->type->DocumentTypeName;
  }
  echo '</i></span> '
  .'<span class="dfheader">Категорія: <i class="header_value">';
  if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
    $controller->widget('editable.EditableField', array(
     'type' => 'select',
     'attribute' => 'DocumentCategoryID',
     'name' => 'DocumentCategoryID',
     'model' => $data,
     'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'DocumentCategoryID')),
     'title' => 'Тип документа',
     'source' => Documentcategory::DropDown(),
     'placement' => 'right', 'mode' => 'popup',
     'showbuttons' => false,
    ));
  } else {
    echo $data->category->DocumentCategoryName;
  }
  echo '</i></span> '
  .($cnt? '<span class="dfheader"><a target="_blank" href="'
      . Yii::app()->CreateUrl('/docflows/index',array('DocumentID' => $data->idDocument)).'">'
  . 'Розсилки: '
  .  $cnt.'</a></span><br/>' : '<br/>')
  .'<span class="dfheader">Створив користувач: <i>'.$data->user->info.' '
  .  date('d.m.Y H:i:s',strtotime($data->Created)).'</i></span> ';
?>
</div>

  <div class="row-fluid">
    <div class="dfbox dfbox120 span3">
      <span class="dfheader">Дата надходження та індекс</span><br/>
      <?php
      if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
        $controller->widget('editable.EditableField', array(
        'type' => 'textarea',
        'model' => $data,
        'attribute' => 'DocumentInputNumber',
        'pk' => $data->idDocument,
        'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'DocumentInputNumber')),
        'placement' => 'right',
        'title' => 'Дата надходження та індекс документа',
        ));
        echo " ";
          $controller->widget('editable.EditableField', array(
          'type' => 'date',
          'model' => $data,
          'attribute' => 'SubmissionDate',
          'pk' => $data->idDocument,
          'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'SubmissionDate')),
          'placement' => 'right',
          'format' => 'yyyy-mm-dd', //format in which date is expected from model and submitted to server
          'viewformat' => 'dd.mm.yyyy', //format in which date is display
          'title' => 'Вкажіть дату надходження',
           'options' => array('onblur' => 'submit'),
          ));
      } else {
        echo ($data->DocumentInputNumber) ? $data->DocumentInputNumber :
          '<span class=\'absent_value\'>Не вказано</span>';
        echo ($data->SubmissionDate) ? $data->SubmissionDate :
          '';
      }
      ?> <br/>
      <span class="dfheader">Дата та індекс</span><br/>
      <?php
      if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
        $controller->widget('editable.EditableField', array(
        'type' => 'textarea',
        'model' => $data,
        'attribute' => 'DocumentOutputNumber',
        'pk' => $data->idDocument,
        'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'DocumentOutputNumber')),
        'placement' => 'right',
        'title' => 'Дата та індекс документа',
        ));
      } else {
        echo ($data->DocumentOutputNumber) ? $data->DocumentOutputNumber :
          '<span class=\'absent_value\'>Не вказано</span>';
      }
      ?>
    </div>
    <div class="dfbox dfbox120 span3">
      <span class="dfheader">Кореспондент</span><br/>
      <?php
      if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
        $controller->widget('editable.EditableField', array(
        'type' => 'textarea',
        'model' => $data,
        'attribute' => 'Correspondent',
        'pk' => $data->idDocument,
        'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'Correspondent')),
        'placement' => 'right',
        'title' => 'Кореспондент',
        ));
      } else {
        echo ($data->Correspondent) ? $data->Correspondent :
          '<span class=\'absent_value\'>Не вказано</span>';
      }
      ?>
    </div>
    <div class="dfbox dfbox120 span3">
      <span class="dfheader">Підписано</span><br/>
      <?php
      if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
        $controller->widget('editable.EditableField', array(
        'type' => 'textarea',
        'model' => $data,
        'attribute' => 'signed',
        'pk' => $data->idDocument,
        'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'signed')),
        'placement' => 'right',
        'title' => 'Підписано',
        ));
      } else {
        echo ($data->signed) ? $data->signed :
          '<span class=\'absent_value\'>Не вказано</span>';
      }
      ?>
    </div>
    <div class="dfbox dfbox120 span3">
      <span class="dfheader">На кого розписано</span><br/>
      <?php
      if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
        $controller->widget('editable.EditableField', array(
        'type' => 'textarea',
        'model' => $data,
        'attribute' => 'DocumentForWhom',
        'pk' => $data->idDocument,
        'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'DocumentForWhom')),
        'placement' => 'right',
        'title' => 'На кого розписано',
        ));
      } else {
        echo ($data->DocumentForWhom) ? $data->DocumentForWhom :
          '<span class=\'absent_value\'>Не вказано</span>';
      }
      ?>
    </div>
  </div>

  <div class="row-fluid">
    <div class="dfbox dfbox120 docnode_line2 span6">
      <span class='dfheader'>Короткий зміст документа</span><br/>
      <?php
      if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
        $controller->widget('editable.EditableField', array(
        'type' => 'textarea',
        'model' => $data,
        'attribute' => 'DocumentDescription',
        'pk' => $data->idDocument,
        'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'DocumentDescription')),
        'placement' => 'right',
        'title' => 'Короткий зміст документа',
        ));
      } else {
        echo ($data->DocumentDescription) ? $data->DocumentDescription :
          '<span class=\'absent_value\'>Не вказано</span>';
      }
      ?>
    </div>
    <div class="dfbox dfbox120 docnode_line2 span3">
      <span class='dfheader'>Контроль</span><br/>
        <div class="row-fluid">
          <div class="span12 <?php echo (!$data->mark && strlen(trim($data->ControlField)))? 'controlItem':''; ?>">
      <?php
      if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
        $controller->widget('editable.EditableField', array(
        'type' => 'textarea',
        'model' => $data,
        'attribute' => 'ControlField',
        'pk' => $data->idDocument,
        'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'ControlField')),
        'placement' => 'right',
        'title' => 'Контроль виконання',
        ));
      } else {
        echo ($data->ControlField) ? $data->ControlField :
          '<span class=\'absent_value\'>Не вказано</span>';
      }
      ?>
          </div>
        </div>
    </div>
    <div class="dfbox dfbox120 dfresolution span3" id="mark-<?php echo $data->idDocument; ?>"
    style="background-color: <?php echo ((strlen(trim($data->ControlField)) && empty($data->mark))? 'rgba(255,64,64,0.6)'
    : ((!empty($data->mark)) ? 'rgba(64,255,64,0.6)' : 'rgba(220, 220, 255,0.7)') ); ?>;">
      <span class="dfheader">Відмітка про виконання</span><br/>
      <?php
      if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
        $controller->widget('editable.EditableField', array(
        'type' => 'textarea',
        'model' => $data,
        'attribute' => 'mark',
        'pk' => $data->idDocument,
        'url' => $controller->createUrl('/documents/updateEditable',array('field' => 'mark')),
        'placement' => 'right',
        'title' => 'Відмітка про виконання',
        'success' => 'js: function(response, newValue) {
            if(newValue.length) {
              $("#mark-'.$data->idDocument.'").css("background-color","rgba(64,255,64,0.6)");
            } else {
              $("#mark-'.$data->idDocument.'").css("background-color","'.
              ((!empty($data->ControlField)) ? 'rgba(255,64,64,0.6)' : 'rgba(220, 220, 255,0.7)')
              .'");
            }
        }'
        ));
      } else {
        echo ($data->mark) ? $data->mark :
          '<span class=\'absent_value\'>Не вказано</span>';
      }
      ?>
    </div>
  </div>

  <div class="row-fluid" id="detail-<?php echo $data->idDocument; ?>">
    <div class="docnode_line3 span8">
      <span class='dfheader'>
      <?php
        echo CHtml::ajaxLink ("Респонденти, що повідомили про ознайомлення (із розсилок)",
          CController::createUrl('/documents/AjaxDocAnswers?DocID='
                  .$data->idDocument), 
          array (
          'update' => '#docanswers_'.$data->idDocument,
          'beforeSend' => 'function(){
            $("#docanswers_'.$data->idDocument.'").text("Завантажується...");}',
          ),
          array('style' => 'color: green; font-size: 8pt; border: 1px solid lightgreen;'));
      ?>
      
      </span><br/>
      <div id="docanswers_<?php echo $data->idDocument; ?>">
      </div>
    </div>
    <div class="docnode_line3 span4">
      <div class="span12">
        <span class='dfheader'>Файли</span><br/>
        <?php
          if ($controller->CheckDeptAccess($data->UserID,false) || Yii::app()->user->CheckAccess('CanWatchAllDocs')){
          $add_v_url = Yii::app()->CreateUrl('/documents/addversion',array('id' => $data->idDocument, 
            'returl' => Yii::app()->request->requestUri));
            if (!$controller->showAddversionBlock){
              $controller->widget('bootstrap.widgets.TbButton', array(
                'type'=>'button', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size' => 'mini',
                'type' => 'primary',
                'buttonType' => 'link',
                'url' => Yii::app()->CreateUrl('/documents/index',array('id' => $data->idDocument, 
                    'returl' => Yii::app()->request->url)),
                'label'=>'Додати файл' ,
              ));
            } else {
              $returl = Yii::app()->request->getParam('returl',
                      Yii::app()->CreateUrl('/documents/index#doc-'.$data->idDocument)) 
                      . '#doc-' . $data->idDocument;
              if ($controller->CheckDeptAccess($data->UserID,false) || 
              Yii::app()->user->CheckAccess('CanWatchAllDocs')) { ?>
                <div class="row-fluid">
                  <div class="span11 dfversions">
                    <?php
                      $fmodel = new Files();
                      $controller->widget('bootstrap.widgets.TbFileUpload', array(
                        'id' => '123431',
                        'url' => Yii::app()->CreateUrl('/files/upload', array('DocumentID' => $data->idDocument)),
                        'imageProcessing' => true,
                        'name' => 'file_itself',
                        'multiple' => false,
                        'model' => $fmodel,
                        'attribute' => 'file_itself',
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
                    <div class="row-fluid">
                        <?php
                          $controller->widget('bootstrap.widgets.TbButton',array(
                             'type' => '',
                             'size' => 'mini',
                             'buttonType' => 'link',
                             'url' => $returl,
                             'label' => 'Оновити перегляд',
                          ));
                        ?>
                    </div>
                  </div>
                </div>
              <?php 
              } 
            }
            
          }
          $i = 1;
          foreach ($data->documentfiles as $dfile){
            if (!$dfile){
              continue;
            }
            $items = array();
            echo '<div class="btn-toolbar" >';
            $dept = ' (';
            $dept_names = array();
            foreach ($dfile->file->user->departments as $_dept){
              $dept_names[] = $_dept->DepartmentName;
            }
            $dept .= implode(',',$dept_names);
            $dept .= ') ';
            $controller->widget('bootstrap.widgets.TbButton', array(
              'type'=>(!$dfile->file->FileExists())? 'warning':'button',
              'size' => 'mini',
              'buttonType' => 'link',
              'url'=>(!$dfile->file->FileExists())? 'javascript:alert("Файл не знайдено");'
               :Yii::app()->CreateUrl('/files/DownloadFile/'.$dfile->FileID),
              'label'=>'Файл #'.($i++) ,
              'id'=>$data->idDocument . ' ' . $dfile->FileID,
              'htmlOptions' => array(
                'title' => ' додав користувач: '
                 . $dfile->file->user->info . $dept . date('d.m.Y H:i:s',strtotime($dfile->file->FileTimeStamp))
                 . ((!$dfile->file->FileExists())? ' - файл не знайдено':''),
              ),
            ));
            if ($dfile->file->UserID == Yii::app()->user->id){
              $controller->widget('bootstrap.widgets.TbButton', array(
                'type'=>'danger',
                'size' => 'mini',
                'buttonType' => 'link',
                'url' => Yii::app()->CreateUrl('/documents/deleteversion/'.$dfile->idDocumentFile,
                      array('returl' => Yii::app()->request->requestUri)),
                'icon' => 'trash',
                'label' => 'Видалити',
                'htmlOptions' => array(
                  'onclick' => 'if(!confirm("Остаточно?")) return false;'
                )
              ));
            }
            echo '</div>';
          }
        ?>
      </div>
    </div>
  </div>
