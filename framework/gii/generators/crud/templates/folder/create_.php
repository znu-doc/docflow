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
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Створення запису довідника ',
);\n";
?>

$this->menu=array(
	/*array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),*/
	array('label'=>'Переглянути записи', 'url'=>array('admin')),
);
?>

<h1>Створити запис довідника <?php echo TranslateModelName::getTranstalion($this->modelClass); ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
