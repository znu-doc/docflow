<?php
/* @var $model User  */
/* @var $funmodel Departments  */
/* @var $error string  */
?>

<h3>Реєстрація нового користувача системи</h3>

<?php
    $burl = Yii::app()->baseUrl;
    Yii::app()->clientScript->registerScriptFile($burl."/js/doc.js");
    Yii::app()->clientScript->registerCssFile($burl."/css/styles.css",'');

  $form = $this->beginWidget(
      'bootstrap.widgets.TbActiveForm',
      array(
          'id' => 'register-user-form',
          'action'=>Yii::app()->createUrl("site/register"),
          'enableAjaxValidation' => false,
      )
  );
?>

<div class="well well-small">
 <div class="row-fluid">
  <div class="span4">
    <?php echo $form->textFieldRow($model,'username',array('class'=>'span12','maxlength'=>255)); ?>
  
    <?php echo $form->passwordFieldRow(
            $model,
            'password',
            array('class'=>'span12','maxlength'=>255)
          ); ?>
  
    <?php echo $form->textFieldRow($model,'email',
                     array(
                     'maxlength'=>255,
                     'class'=>'span12',
                     )
                  ); ?>
  
  </div>
  <div class="span4">
    <?php echo $form->textAreaRow($model,'info',
            array('rows' => 4,'cols'=>50, 'class'=>'span12')); ?>
    <?php               
    echo $form->dropDownListRow($funmodel,'idDepartment',Departments::model()->DropDown(),
              array('class'=>'span12'));
    ?>

  </div>
   
 </div>
  <hr/>
    <center>
      <div class="row-red">Будьте уважні при заповненні полів і не розголошуйте свій пароль!</div>
    <?php
    $this->widget("bootstrap.widgets.TbButton", array(
          'buttonType'=>'submit',
          'type'=>'primary',
          "size"=>"large",
          'htmlOptions' => array(
              'onclick' => '',
          ),
          'label'=>'Зареєструватися',
          )
    );     
    ?>
    </center>
</div>

<?php
  $this->endWidget();
    if (isset($error)){
      echo '<script>alert(\''.$error.'\');</script>';
    }
?>