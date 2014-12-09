<?php
/* @var $this DocflowperiodController */
/* @var $model Docflowperiod */

$this->breadcrumbs=array(
	'Docflowperiods'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Управління', 'url'=>array('admin')),
);
?>

<h1>Додавання нового запису періодичності документообігу</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>