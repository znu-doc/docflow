<?php
/* @var $this PropositionbookController */
/* @var $model Propositionbook */

$this->breadcrumbs=array(
	'Proposition Books'=>array('index'),
	'Довідник ',
);

$this->menu=array(
array('label'=>'Додати запис', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('proposition-book-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Розділ скарг і пропозицій</h1>


<?php echo CHtml::link('Розширений пошук','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView', array(
'id'=>'proposition-book-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'Proposition',
		array(
        'name'=>'UserID',
        'value'=>'\'<p title=\'.$data->UserID.\'>\'.User::model()->findByPk($data->UserID)->username.\'</p>\'',
        'type'=>'raw'
    ),
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
