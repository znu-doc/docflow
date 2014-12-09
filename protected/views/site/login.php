<?php
/* @var $this Sitecontroller */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
  'Login',
);
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'login-form',
  'enableClientValidation'=>false,
  'clientOptions'=>array(
    'validateOnSubmit'=>true,
  ),
  'htmlOptions'=>array(
      'class'=>"form-signin dfbox",
  ),
)); ?>

  
<h2>Авторизація</h2>
  <div class="row">
    <?php echo $form->labelEx($model,'username'); ?>
    <?php echo $form->textField($model,'username'); ?>
    <?php echo $form->error($model,'username'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'password'); ?>
    <?php echo $form->passwordField($model,'password'); ?>
    <?php echo $form->error($model,'password'); ?>
    <p class="hint">
      Наприклад:  <kbd>admin</kbd>/<kbd>admin</kbd>.
    </p>
  </div>

  <div class="row rememberMe">
    <?php echo $form->checkBox($model,'rememberMe'); ?>
    <?php echo $form->label($model,'rememberMe'); ?>
    <?php echo $form->error($model,'rememberMe'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Увійти', array("class"=>"btn btn-large btn-primary")); ?>
  </div>

<?php $this->endWidget(); ?>

<?php /*  ?>

</div><!-- form -->
<div class="well well-small" style="width: 600px; margin: 0 auto; overflow: auto; max-height: 300px;">
<?php
  $users = User::model()->findAll();
  foreach ($users as $user){
    $depts = '';
    foreach ($user->departments as $dept){
      $depts .= '|'.$dept->DepartmentName . '|';
    }
    $this->widget('bootstrap.widgets.TbLabel', array(
        'type'=>'inverse', // 'success', 'warning', 'important', 'info' or 'inverse'
        'label'=>$user->username . ' -- ' . $depts,
        'htmlOptions' => array(
            'title' => $user->info . ' ( ' .$user->email . ' )',
            'style'=>'margin-top: 3px;width: 100%;'
        )
    )); 
  }
?>
</div>

<?php */ ?>