<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Довідник ',
);\n";
?>

$this->menu=array(
array('label'=>'Додати запис', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Довідник <?php
$labname = $this->pluralize($this->class2name($this->modelClass));
echo $labname;
/*echo TranslateModelName::getTranstalion($labname)*/
?></h1>

<p>
    Можна додати оператор порівняння (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) перед значенням пошуку
</p>

<?php echo "<?php echo CHtml::link('Розширений пошук','#',array('class'=>'search-button')); ?>"; ?>

<div class="search-form" style="display:none">
    <?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->

<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbGridView', array(
'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
<?php
$count = 0;
foreach ($this->tableSchema->columns as $column) {
    if (++$count == 7)
        echo "\t\t/*\n";
    if ($column->name == 'Visible') {
        echo "\t\t" ."array('name'=>'Visible',
                    'header'=>'Відображати при виборі',
                    'filter'=>array('1'=>'так','0'=>'ні'),
                    'value'=>'(\$data->Visible=='1')?('так'):('ні')')".",\n";
    } else {
        
        echo "\t\t'" . $column->name . "',\n";
    }
}
if ($count >= 7)
    echo "\t\t*/\n";
?>
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
