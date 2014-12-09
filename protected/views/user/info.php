<?php 
/* @var $model User */
/* @var $my_dept_names array */
/* @var $ans_count array */
/* @var $flow_count array */
/* @var $doc_count array */
/* @var $file_count array */
/* @var $quota string */

?>
<style>
.height70{
  height: 70px;
  overflow: auto;
}
.height200{
  height: 200px;
  overflow: auto;
}
.simple_t tr td {
  padding: 0;
  padding-left: 10px;
}
</style>

<div class="row-fluid">
  <div class="span12">
    <div style="text-align:center;">
      <h1>Налаштування та інформація користувача</h1>
    </div>
    
  </div>
</div>
<hr/>
<div class="row-fluid">
      <div class="span3 dfbox height70">
        <span class="dfheader">Контактні дані</span><br/>
        <?php
        $this->widget('editable.EditableField', array(
         'type' => 'text',
         'attribute' => 'email',
         'name' => 'email',
         'model' => $model,
         'url' => $this->createUrl('/user/updateEditable',array('field' => 'email')),
         'title' => 'Контактні дані',
         'placement' => 'left',
       ));
        ?>
      </div>
      <div class="span4 dfbox height70">
        <span class="dfheader">Прізвище, ім'я та по-батькові, посада</span><br/>
        <?php
        $this->widget('editable.EditableField', array(
         'type' => 'textarea',
         'attribute' => 'info',
         'name' => 'info',
         'model' => $model,
         'url' => $this->createUrl('/user/updateEditable',array('field' => 'info')),
         'title' => "Прізвище, ім`я та по-батькові, посада",
         'placement' => 'right',
       ));
        ?>
      </div>
      <div class="span5 dfbox height70">
        <span class="dfheader">Підрозділи</span><br/>
        <?php
        foreach ($my_dept_names as $my_dept_name){
          $this->widget('bootstrap.widgets.TbLabel', array(
              'type' => 'info',
              'label' => $my_dept_name,
              'htmlOptions' => array(
                'style' => 'margin-right: 5px; margin-top: 5px;'
              ),
          )); 
        }
        ?>
      </div>
</div>



<div class="row-fluid">
  <div class="span3 dfbox height200">
    <span class="dfheader">Логін</span><br/>
    <?php
    echo $model->username;
    ?>
  </div>
  <div class="span4 dfbox height200">
    <span class="dfheader">Зміна пароля</span><br/>
    <div class="row-fluid">
      <?php
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
          'id'=>'user-form',
          'enableAjaxValidation'=>false,
          'action' => '',
          'method' => 'post'
        )); ?>
        <div class="span6">
        <?php
        echo CHtml::textField('password','',array('placeholder' => 'Будьте уважні'));
        ?>
        </div>
        <div class="span6" style="text-align: center;">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
          'buttonType'=>'submit',
          'type'=>'primary',
          'label'=>'Змінити',
        )); 
        $this->endWidget(); 
        ?>
        </div>
    </div>
  </div>
  <div class="span5 dfbox height200">
    <div class="span12 dfheader">Статистика</div>
    <table border=0 class="simple_t">
      <tr>
        <td>Кількість відповідей на розсилки</td>
        <td> <?php
          $this->widget('bootstrap.widgets.TbLabel', array(
              'type' => 'success',
              'label' => $ans_count,
          ));
         ?>
        </td>
      </tr>
      <tr>
        <td>Кількість ініційованих розсилок</td>
        <td> <?php
          $this->widget('bootstrap.widgets.TbLabel', array(
              'type' => 'info',
              'label' => $flow_count,
          )); 
          ?>
        </td>
      </tr>
      <tr>
        <td>Кількість доданих документів</td>
        <td> <?php
          $this->widget('bootstrap.widgets.TbLabel', array(
              'type' => 'warning',
              'label' => $doc_count,
          )); 
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php
            echo CHtml::link('Кількість вивантажених файлів',
              Yii::app()->CreateUrl('files')
            );
          ?>
        </td>
        <td> <?php
          $this->widget('bootstrap.widgets.TbLabel', array(
              'type' => 'inverse',
              'label' => $file_count,
          )); 
          ?>
        </td>
      </tr>
      <tr>
        <td>Квота вільного місця</td>
        <td> <?php
          $this->widget('bootstrap.widgets.TbLabel', array(
              'type' => 'important',
              'label' => $quota,
          ));
          ?>
        </td>
      </tr>
    </table>
  </div>
</div>    

<div class="row-fluid">
  <?php
    $smodel = new StatAverage();
    $smodel->unsetAttributes();
    $smodel->OwnerID = Yii::app()->user->id;
    
  ?>
  <div class="span12 dfbox">
    <h2 style="text-align: center;" class="span12">Статистика вихідних розсилок</h2> 
  <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'docflow-watch-grid--',
        'dataProvider' => $smodel->search(),
        'emptyText' => 'Статистика вихідних розсилок відсутня',
        'columns' => array(
            array(
                'header' => 'Респондент',
                'filter' => false,
                'value' => '$data->RespDeptName',
                'type' => 'raw',
            ),
            array(
                'header' => 'К-сть відповідей',
                'filter' => false,
                'value' => '$data->AnswerCnt',
                'type' => 'raw',
            ),
            array(
                'value' => '$data->AveAnswerDelay',
                'header' => 'Середня затримка відповіді',
                'type' => 'raw'
            ),
            array(
                'value' => '$data->NoReplyCnt',
                'header' => 'К-сть розсилок без відповіді',
                'type' => 'raw'
            ),
            array(
                'header' => 'Всього розсилок респонденту',
                'value' => '$data->AllFlowsCnt',
                'type' => 'raw',
            ),
        ),
        'htmlOptions' => array('style' => 'font-size: 8pt;'),
    ));
  ?>
  </div>
</div>

