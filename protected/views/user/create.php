<?php
/* @var $model User */
/* @var $deptmodel Departments */
?>
<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Перелік користувачів','url'=>array('index'),'icon'=>"icon-list-alt"),
	
);
?>

<h1>Створити користувача</h1>
<div class ="well form">
<?php echo $this->renderPartial('_form', array('model'=>$model, 'deptmodel'=>$deptmodel)); ?>
</div>