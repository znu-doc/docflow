<?php
/* @var $model Docflows */
/* @var $this Controller */

$grid_id = 'docflows-grid-' . 'GENERAL';


//Посилання на відображення респондентів і статистику надання відповіді
  $respondents_label = '"<div class=\'well well-small\'>".CHtml::link(
            "Відповіді респондентів",
            Yii::app()->CreateUrl("docview/docflowwatch",array("id"=>$data->idDocFlow)),
            array(
              "style" => "color: blue;font-size: 7pt;",
              "target" => "_blank",
            )
           ) . "</div>"';

//Посилання на завантаження документа чи пакета документів
  $download_label = '"<div class=\'well well-small\'>".((trim($data->DocFlowDescription))? 
            $data->DocFlowName . " [<i> " . $data->DocFlowDescription . " </i>]<hr/>" : 
            "").CHtml::link(
            "Завантажити",
            Yii::app()->createUrl("files/'
               . 'DownloadAll/",array("id"=>$data->idDocFlow)),
            array(
              "target" => "_blank",
              "style" => "color: blue;font-size: 7pt;",
            )
           )."</div>"';


  $labels_column = $download_label . '.' . $respondents_label;
  
  $columns = array(
    //меню управління
    array('name' => null,
       'value' => $labels_column,
       'filter' => null,
       'type' => 'raw',
       'htmlOptions' => array( 'class' => 'flowlist-column1')
    ),
    //Документи і їх версії
    array(//'name'=>'DocFlowName', 
       'htmlOptions' => array('class' => 'flowlist-column2'),
       'header' => 'Документи і їх версії',
       'type' => 'raw',
       'value' => function ($data){
        /* @var $data Docflows */
          foreach ($data->documents as $doc){
            echo '<div class="well well-small" style="height:200px;overflow-y:auto;'
            . 'font-family: Tahoma; font-size: 7pt !important;">';
            echo $doc->DocumentInputNumber .
                    '<br/><i>' . $doc->category->DocumentCategoryName . ';<br/>' .
                    $doc->type->DocumentTypeName . '</i>' .
                    '<ol>';
            foreach ($doc->documentfiles as $docfile){
              $tstmp = $docfile->file->FileTimeStamp;
              $readable_tstmp = date('d.m.Y h:i:s',strtotime($tstmp));
              echo '<li>'.CHtml::link('Версія '.$readable_tstmp,
                      Yii::app()->CreateUrl('files/DownloadFile',array('id'=>$docfile->FileID)),
                      array('title' => 'Додано користувачем: '.$docfile->file->user->info . 
                          ' ('.$docfile->file->UserID.')')) .'</li>';
            }
            echo '</ol></div>';
          }
       }
    ),     
    //Короткий зміст документів
    array(
      'name' => 'documents.DocumentDescription',
      'header' => 'Короткий зміст документів',
      'filter' => CHtml::activeTextField($model->searchDocument, 'DocumentDescription'),
       'type' => 'raw',
       'htmlOptions' => array('class' => 'flowlist-column3'),
       'value' => function ($data){
          /* @var $data Docflows */
          if ($data->documents) { 
            foreach ($data->documents as $doc) {
              /* @var $doc Documents */
              echo '<div class=\'well well-small\' style=\'height: 200px; width: 140px; overflow-y: auto; '
              . 'font-family: Tahoma; font-size: 7pt !important;\' '.
                      'title=\''.$doc->DocumentName.'(номер: '.$doc->DocumentInputNumber.')\'>'.
                      ((trim($doc->DocumentDescription)) ? $doc->DocumentDescription : "не вказано") .
                    '</div>';              
            }            
          }
       }
    ),
    //Дата закінчення розсилки
    array('name' => 'ExpirationDate', 
       'htmlOptions' => array('class' => 'flowlist-column4'),
       ),
    //Дата і час початку розсилки
    array('name' => 'Created', 
         'htmlOptions' => array('class' => 'flowlist-column5'),
       ),
  );
    

    //для власноруч створених розсилок
    array_push($columns,
      //респонденти - список з наявністю відповіді
      array(
        'name' => 'docFlowGroup.departments.DepartmentName',
        'header' => 'Респонденти',
        'filter' => CHtml::activeTextField($model->searchDept, 'DepartmentName'),
         'htmlOptions' => array('class' => 'flowlist-column6'),
         'type' => 'raw',
         'value' => function ($data) {
            echo "<div style='height:200px;overflow-y:auto;' "
              . "id='df_".$data->idDocFlow."'>";
              foreach ($data->docFlowGroup->departments as $dept){
                $res=Documents::isDeptAnswer($data->docflowAnswers,$dept);
                echo '<div class=\'label label-'.(($res)? 'success':'info').'\' '
                        . 'style=\' '
                        . 'font-size: 9pt; font-weight: normal; font-family: Opengosttypeb; margin-top: 3px; width: 120px !important;'
                . '\' title="'
                . $dept->idDepartment . '::' .$dept->DepartmentName  . 
                        (($res)? ' ; Відповідь надано ' .date('d.m.Y h:i:s',strtotime($res->AnswerTimestamp)) . 
                        ' - '. $res->user->username :'')
                . '">';
                echo mb_substr($dept->DepartmentName,0,21,'utf8') . ' </div>';
              }
           echo '</div>';
         }
      )
    ); 


    //для вхідних розсилок
    array_push($columns, 
      //виведення того, хто почав розсилку
      array(
        'name' => 'docFlowGroup.OwnerID',
        'header' => 'Ініціатор',
        'filter' => CHtml::activeDropDownList($model->searchDocflowGroup, 'OwnerID', User::HasGroupDropDown()),
         'type' => 'raw',
        'htmlOptions' => array('class' => 'flowlist-column7'),
         'value' => function ($data) {
            /* @var $data Docflows */
            echo $data->docFlowGroup->owner->info . ' [' . $data->docFlowGroup->owner->email . ']';
         }
      )
    );


    //для неактуальних
    array_push($columns,
      //вивести статус
      array('name'=>'DocFlowStatusID', 
        'htmlOptions' => array('class' => 'flowlist-column8'),
        'value' => '"<div class=\'font-size8 status".'
         . 'Controller::UpdateDocflowStatus($data->idDocFlow).'
         //. '$data->DocFlowStatusID.'
         . '"\'>" . $data->docFlowStatus->DocFlowStatusName . "</div>"',
        'type' => 'raw',
        'filter' => Docflowstatus::DropDown(),
      )
    );
    array_push($columns,
      //дата і час закінчення
      array('name' => 'Finished', 
         'htmlOptions' => array('class' => 'flowlist-column9'),
      )
    );


  array_push($columns,
    //контроль (опціонально для загального відділу)
    array('name'=>'ControlDate', 
      'htmlOptions' => array('class' => "flowlist-column10"),
    )
  );
    

    array_push($columns,
      //для власних - можливість видалити
      array(
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'deleteButtonUrl'=>
         'Yii::app()->createUrl("/docflows/deleteflow", array("id" => $data->idDocFlow))',
        'template' => '{delete}',
        'htmlOptions' => array('class' => 'flowlist-column11')
      ) 
    );


  $this->widget('bootstrap.widgets.TbGridView', array(
	  'id'=>$grid_id,
	  'dataProvider'=>$model->search_rel(null,null),
	  'filter'=>$model,
	  'columns'=>$columns,
    'htmlOptions' => array(
       'style' => 'width:100%;font-family: Tahoma; font-size: 7pt !important;',
    )
  )); ?>