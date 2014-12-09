<?php
/* @var $model Docflows */
/* @var $this DocflowsController */
/* @var $dept_ids [] integer */
/* @var $resp_ids [] integer */

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/createflow.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/addflow.js"></script>
<?php 
  $createflow_form = $this->BeginWidget(
  'bootstrap.widgets.TbActiveForm', array(
    'id' => 'createflow-form',
    'action' => '',
    'enableAjaxValidation' => false,
   )
  );
?>
<div class='row-fluid'>
  <div class="span12 dfbox">
    <div class='row-fluid'>
      <h1 class='span12 dfmetaheader'>Форма створення нової розсилки</h1>
    </div>
<!--    <div class='row-fluid'>
      <span class="dfheader">Оберіть підрозділи</span>
      <input type="text" id="RespondentField" 
      name="RespIDs"
      class="span12" style="margin-left: 0px;" />
      
    </div>-->
    <div class="row-fluid">
      <div class="row-fluid">
        <div class='span11'>
          <div class="span6">
                <h4>Група розсилки</h4>
                <i class="icon-search"></i>
                <?php echo CHtml::textField('filterDept', 
                        '', 
                        array('class'=>'input-medium span9',
                            'placeholder' => 'Починайте вводити підрозділ',
                            'id'=>'filterDept',
                            "title"=>"Фільтр для пошуку підрозділу по частині його назви")); 
                ?>
                <br/>
                <input type='checkbox' value='' id="check_all_dept"/>
                  Вибрати/зняти усі
            </div> 
            <div class="span6">
              <?php
                Yii::app()->user->setFlash('info', 'Зірочка (*) - показ усіх підрозділів. '
                        . 'Краще вводити лише маленькі букви (нижній регістр).');
                $this->widget('bootstrap.widgets.TbAlert', array(
                  'fade'=>true, // use transitions?
                  'block'=>true,
                  'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
                  'alerts'=>array( // configurations per alert type
                      'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), 
                      // success, info, warning, error or danger
                  ),
                  'htmlOptions' => array(
                    'class' => 'span11 alert_info',
                  )
                )); 
              ?>
              Обрані підрозділи для розсилки
            </div>
        </div>
      </div>
      <div class='span11'>
        <!-- Блок для оперування групою розсилки -->
        <div class="span6 block_departments">
          <!-- Блок вибору підрозділів встановленням прапорців з фільтром 
               (пошуком по частині назви) -->

            <?php
              foreach ($dept_list as $id => $dept){
            ?>
              <div class="row-fluid check_row" id="check_row_<?php echo $id; ?>" style="display:none;">
              <div class='dept_input_check span1'>
                <input type='checkbox' 
                       value='<?php echo $id; ?>' 
                       title="<?php echo $dept['name'] . 
                               (($dept['desc'])? ' --- ' . $dept['desc'] : 
                         ''); ?>" 
                       class="dept_check" 
                       id="group_<?php echo $id; ?>_check"
                       name="input_check_dept[]"
                       />
              </div>
              <div id="group_<?php echo $id; ?>" 
                   class="dept_item span11"
                   >
                <span 
                  class='dept_text' 
                  id="span_group_<?php echo $id; ?>" > 
                  <?php echo $dept['name']; ?> 
                </span>
                <span class="dept_text_hidden">
                  <?php echo mb_strtolower($dept['name'] . ' * ' . $dept['desc'],'utf8'); ?> 
                </span>
              </div>
              </div>

            <?php
              }
            ?>
        </div>

        <div class="span6 block_departments" id="dept_chosen">
          <!-- Блок обраних підрозділів для розсилки -->
          <div class="span11" id="dept_chosen">
          <!-- Блок обраних підрозділів для розсилки -->
            <?php
              foreach ($dept_list as $id => $dept){
                ?>
                <div id='dept_label_<?php echo $id; ?>' 
                  class='row-fluid chosen_depts'>
                <?php
                $this->widget('bootstrap.widgets.TbLabel', array(
                    'type'=>'info', // 'success', 'warning', 'important', 'info' or 'inverse'
                    'label'=>((mb_strlen($dept['name'],'utf-8')>50)? 
                        mb_substr($dept['name'],0,50,'utf-8') . '...' : 
                        $dept['name']),
                    'htmlOptions' => array(
                        'title' => $dept['name'].'::'. ((empty($dept['desc']))? $id : $dept['desc']) 
                          . ' ('. implode(';',Departments::model()->findByPk($id)->getUserNames()) . ')',
                        'class' => 'dept_label_item'
                    )
                ));
                ?>
                <span class="icon-remove" 
                  onclick="$('#group_<?php echo $id; ?>_check').click();"
                  style="cursor: pointer;">
                </span>
                </div>
                <?php
              }
            ?>
          </div>
        </div>

        <div class='span12'><hr/></div>

        <div class="span12">
          <!-- Блок для оперування групами розсилки: 
               збереження або використання існуючої -->
          <div class="span6" id="Bsave_group_as">
            <!-- Блок для збереження групи з введеною назвою -->
            <input type="checkbox" 
                   name="save_group_as" 
                   value="1" 
                   id="check_save_group"/>
            <?php echo CHtml::label('Зберегти групу розсилки з назвою', 'check_save_group', 
                    array('style'=>'font-family: "Verdana"; font-size: 8pt;')); ?>
            <?php 
              echo CHtml::textField('DocFlowGroupName', '', array('style'=>'display:none;',
                  'id' => 'DocFlowGroupName_text')); 
            ?>
          </div>
          <div class='span6' class='select_existing_group'>
            <!-- Блок для вибору існуючої групи розсилки -->
            <input type="checkbox" 
                   name="is_selected_group" 
                   value="1" 
                   id="select_group_checkbox" />
            <?php echo CHtml::label('Вибрати існуючу групу для розсилки', 
                    'select_group_checkbox', 
                    array('style'=>'font-family: "Verdana"; font-size: 8pt;')); ?>
            <?php
              $group_list = Docflowgroups::model()->DropDown();
              if (!empty($group_list)){
                echo CHtml::dropDownList('idDocFlowGroupSelected', '', 
                        $group_list,
                        array('class'=>'span10', 'style' => 'display: none;'));
              } else {
                echo CHtml::dropDownList('idDocFlowGroupSelected', '0', 
                        array('0'=>'Групи відсутні'),
                        array('class'=>'span10', 'readonly'=>true));
              }
            ?>
          </div>

        </div>
      </div>
    </div>
     <hr /> 
    <div class='row-fluid'>
      <div class="span6">
      <?php 
        echo $createflow_form->labelEx($model, 'DocFlowName', array(
          'class' => 'span6 dfheader',
        ));
        echo $createflow_form->textField($model,'DocFlowName',array(
          'class' => 'span12', 'readonly' => true
        ));
        
      ?>
      </div>
      <div class="span3">
      <?php
        echo $createflow_form->labelEx($model, 'DocFlowPeriodID', array(
          'class' => 'span12 dfheader',
        ));
        echo $createflow_form->dropDownList($model,'DocFlowPeriodID',Docflowperiod::DropDown(),array(
          'class' => 'span12'
        ));
      ?>
      </div>
      <div class="span3">
      <?php
        echo $createflow_form->labelEx($model, 'ExpirationDate', array(
          'class' => 'span12 dfheader',
        ));
        echo $createflow_form->textField($model,'ExpirationDate',array(
          'class' => 'span12 datepicker'
        ));
      ?>
      </div>
    </div>
    <div class='row-fluid'>
      <div class="span6">
      <?php 
        echo $createflow_form->labelEx($model, 'DocFlowDescription', array(
          'class' => 'span6 dfheader',
        ));
        echo $createflow_form->textArea($model,'DocFlowDescription',array(
          'class' => 'span12'
        ));
      ?>
      </div>
      <div class="span6">
      <?php 
        echo $createflow_form->labelEx($model, 'ControlDate', array(
          'class' => 'span6 dfheader',
        ));
        echo $createflow_form->textArea($model,'ControlDate',array(
          'class' => 'span12'
        ));
      ?>
      </div>
    </div>
    <hr/>
    <div class='row-fluid'>
    <?php if (!$docs){ ?>
      <span class="dfheader">Оберіть документи</span>
      <input type="text" id="DocumentsField" 
      name="DocIDs"
      class="span12" style="margin-left: 0px;" />
    <?php } else {
      $doc_ids = array();
      ?>
      <span class="dfheader">Документи вже обрані</span>
      <ul>
      <?php
      foreach ($docs as $id => $doc){
        echo '<li>'.$doc.'</li>';
        $doc_ids [] = $id;
      }
      ?>
      </ul>
      <?php
      $ids = implode(',',$doc_ids);
      echo CHtml::hiddenField('DocIDs',$ids);
    } ?>
    </div>
    <hr/>
    <div class='row-fluid' style="text-align: center;">
      <?php
      $this->widget(
          "bootstrap.widgets.TbButton", array(
            'buttonType' => 'submit',
            'type' => 'primary',
            "size" => "large",
            'loadingText'=>'Зачекайте...',
            'htmlOptions' => array(
                'id' => 'add_docflow_button',
            ),
            'label' => 'Ініціювати',
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