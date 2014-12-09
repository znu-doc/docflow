<?php
/* @var $this FilesController */
/* @var $data CActiveDataProvider */
/* @var $model Files */

?>
<div class="row-fluid">
  <div class="span12 dfbox">
    <h1>Перегляд файлів</h1>
  </div>
</div>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
  'dataProvider'=>$data,
  'filter' => $model,
  'columns' => array(
    array(
      'header' => '#',
      'name' => 'idFile',
      'value' => function ($data){
        if (file_exists(Yii::app()->getBasePath().'/../docs/'.$data->FileLocation)){
          echo '<span style="color:green;">'.$data->idFile.'</span>';
        } else {
          echo '<span style="color:red;">'.$data->idFile.': не має</span>';
        }
      },
      'htmlOptions' => array(
        'class' => 'span1',
      )
    ),
    array(
      'header' => 'Пов`язаний документ',
      'name' => 'linkedDocumentName'
    ),
    array(
      'header' => 'Завантажено',
      'name' => 'FileTimeStamp',
      'htmlOptions' => array(
        'class' => 'span3',
      )
    ),
    array(
      'header' => 'Користувач',
      'name' => 'user.info',
      'htmlOptions' => array(
        'class' => 'span3',
      )
    ),
    array(
      'class'=>'bootstrap.widgets.TbButtonColumn',
      'template'=>'{download}&nbsp;&nbsp;{delete}',
      'buttons'=>array
      (
          'download' => array(
              'label'=>'Завантажити',
              'icon'=>'download',
              'url'=>'Yii::app()->controller->createUrl("/files/DownloadFile/",array("id"=>$data->primaryKey))',
          ),
      )
    )
  )
)); ?>
