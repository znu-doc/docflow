<?php
  /* @var $controller DocflowsController  */
  /* @var $data Docflows  */

?>
<div class="row-fluid dfmetaheader">
<?php 
if (Yii::app()->user->id == $data->docFlowGroup->OwnerID){
  ?>
  <h3 class="flowname">
  <?php
  $controller->widget('editable.EditableField', array(
 'type' => 'text',
 'attribute' => 'DocFlowName',
 'model' => $data,
 'url' => $controller->createUrl('/docflows/updateEditable',array('field' => 'DocFlowName')),
 'title' => 'Назва розсилки',
 'placement' => 'right'
 ));
  ?>
  </h3>
  <div class="flowdescr">
   <?php
   $controller->widget('editable.EditableField', array(
    'type' => 'textarea',
    'attribute' => 'DocFlowDescription',
    'model' => $data,
    'url' => $controller->createUrl('/docflows/updateEditable',array('field' => 'DocFlowDescription')),
    'title' => 'Особливості розсилки (коментар)',
    'placement' => 'right',
    'htmlOptions' => array(
      'class' => 'flowdescr'
   )));
   ?>
 </div>
 <?php
} else {
  ?>
  <h3 class="flowname">
    <?php
    echo ($data->DocFlowName)? $data->DocFlowName : 'Розсилка #'.$data->idDocFlow; 
    ?> 
  </h3>
  <?php
  echo ($data->DocFlowDescription)? 
  '<p class=\'flowdescr\'>'.$data->DocFlowDescription.'</p>': '<p class="flowdescr absent_value">Деталі розсилки відсутні</p>';
}
echo '<span class="dfheader">Розпочато <i>'.date('d.m.Y H:i:s',strtotime($data->Created)).'</i></span>';
?>
</div>

<?php
 $data->MyDeptIDs = implode(',',$controller->my_dept_ids);
 $answer_model = $data->getAnswer();
 $already_answered = ($controller->mode && $answer_model);
 if ($controller->mode){

 ?>
<div class='row-fluid'>
    <?php if (!$already_answered && !Yii::app()->user->checkAccess('showProperties')){
      $controller->renderPartial('_answer_form', array(
          'data' => $data,
          'controller' => $controller,
      ));
    } 
    if ($already_answered && !Yii::app()->user->checkAccess('showProperties')){
    ?>
      <span class="dfheader already_answered">
      <?php /* ?>
        <span>
        <a href="<?php echo Yii::app()->CreateUrl('docflowanswers/deleteanswers/'.$data->idDocFlow); ?>"><i class='icon-trash' title='Видалити'></i></a>
        </span>
       <?php */ ?>
        Інформовано про надходження 
        <i><?php echo date('d.m.Y H:i:s',strtotime($answer_model->AnswerTimestamp)); ?></i>
      </span>
      <!--span class="dfheader"> ; Коментар <i>
       <?php 
          $controller->widget('editable.EditableField', array(
           'type' => 'textarea',
           'attribute' => 'DocFlowAnswerText',
           'name' => 'DocFlowAnswerText',
           'model' => $answer_model,
           'url' => $controller->createUrl('/documents/updateEditable',
              array('field' => 'DocumentName')),
           'title' => 'Коментар до відповіді на розсилку',
           'mode' => 'popup'
          ));
       ?>
       </i></span-->
       <a href="javascript:DOC.show_hide('docflow_node<?php echo $data->idDocFlow; ?>');">[зміст розсилки]</a>
    <?php } 
    ?>
</div>
<?php } ?>

<div style="display: <?php echo($already_answered)? "none":"block"?>" id="docflow_node<?php echo $data->idDocFlow; ?>">
<div class="row-fluid">
  <div class="dfbox dfbox120 span4">
    <span class="dfheader">Ініціатор</span><br/>
    <?php 
    echo $data->docFlowGroup->owner->info;
    echo ' (';
    $dept_names = array();
    foreach ($data->docFlowGroup->owner->departments as $dept){
      $dept_names[] = $dept->DepartmentName;
    }
    echo implode(',',$dept_names);
    echo ')';
    ?>
  </div>
  <div class="dfbox dfbox120 span2">
    <span class="dfheader">Діє до</span><br/>
    <?php 
    $time_to = strtotime($data->ExpirationDate);
    if (Yii::app()->user->id == $data->docFlowGroup->OwnerID){
      $controller->widget('editable.EditableField', array(
      'type' => 'date',
      'model' => $data,
      'attribute' => 'ExpirationDate',
      'pk' => $data->idDocFlow,
      'url' => $controller->createUrl('/docflows/updateEditable',array('field' => 'ExpirationDate')),
      'placement' => 'right',
      'format' => 'yyyy-mm-dd', //format in which date is expected from model and submitted to server
      'viewformat' => 'dd.mm.yyyy', //format in which date is display
      'title' => 'Вкажіть дату закінчення розсилки',
      ));
    } else {
      echo ($time_to) ? date('d.m.Y',strtotime($data->ExpirationDate)) :
        '<span class=\'absent_value\'>Не вказано</span>';
    }
    ?>
  </div>
  <div class="dfbox dfbox120 span2">
    <i class="icon-refresh" 
       id="<?php echo $data->idDocFlow; ?>_docflowstatus"
       title="Оновити статус розсилки"
       style="cursor: pointer;"></i>
    <span class="dfheader">Статус</span><br/>
    <span id="docflowstatus-<?php echo $data->idDocFlow; ?>" class="docflow_status">
      <?php echo $data->docFlowStatus->DocFlowStatusName; ?>
    </span>
  </div>
  <div class="dfbox dfbox120 <?php echo ($data->ControlDate)? 'dfcontrol': ''; ?> span4">
    <span class="dfheader">Контроль</span><br/>
    <?php
    if (Yii::app()->user->id == $data->docFlowGroup->OwnerID){
      $controller->widget('editable.EditableField', array(
     'type' => 'textarea',
     'attribute' => 'ControlDate',
     'model' => $data,
     'url' => $controller->createUrl('/docflows/updateEditable',array('field' => 'ControlDate')),
     'title' => 'Контроль',
     'placement' => 'right'
     ));
    } else {
      echo ($data->ControlDate)? $data->ControlDate : '<span class=\'absent_value\'>Відсутній</span>';
    }
    ?>
  </div>
</div>
<div class="row-fluid">
  <div class="dfbox dfdocs span8">
    <span class='dfheader'><?php 
    
    echo (empty($data->documents) && empty($data->docflowevents))? 'Документів немає':
    ($data->CanDownloadAllDocs()? CHtml::link(
        "<i class=\"icon-download\"></i>[Завантажити]", Yii::app()->CreateUrl(
          "files/DownloadAll/", array(
            "id" => $data->idDocFlow)), array(
        "target" => "_blank",
        "style" => ""
    )) : "") ." Документи "/*."або заходи"*/;
    ?></span><br/>
    <?php
      foreach (explode('$$$',$data->doc_field) as $dfdoc){
        if ($dfdoc){
          $controller->renderPartial('_docflow_doc',array('doc' => $dfdoc, 'data' => $data));
        }
      } //end foreach $data->documents 
      foreach ($data->docflowevents as $dfevent){
        $controller->renderPartial('_docflow_event',
            array('event' => $dfevent->event));
      } //end foreach $data->docflowevents 
    ?>
  </div>
  <div class="dfbox dfrespondents span4">
    <i class="icon-refresh" 
       id="<?php echo $data->idDocFlow; ?>_dfrespondents"
       title="Оновити інформацію про ознайомлення респондентів"
       style="cursor: pointer;"></i>&nbsp;
    <span>
      <?php 
      if (Yii::app()->user->id == $data->docFlowGroup->OwnerID){
        echo CHtml::link('<i class="icon-eye-open"></i>',
          Yii::app()->CreateUrl('/docflows/docflowwatch',
              array('id' => $data->idDocFlow)),
          array(
            'target' => '_blank',
            'title' => 'Перегляд деталізації ознайомлення і відповідей респондентів'
          )
          ); 
      } ?>
    </span>
    <span class="dfheader">Респонденти</span>
    <br/>
    <ul class="dfrespondents_list" id="dfrespondents-<?php echo $data->idDocFlow; ?>">
    <?php
       foreach (explode('$$',$data->dept_field) as $dept){
         echo '<li >'.$dept. '</li>';
       }
    ?>
    </ul></div>
</div>
</div>