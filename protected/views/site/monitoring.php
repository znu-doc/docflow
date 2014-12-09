<?php
  /* @var $model Monitoringanswers */
?>

<h2>Моніторинг відповідей на анкету</h2>
<?php

  $columns = array();
  for ($i = 0; $i < 13; $i++){
    $n = $i + 1;
    if ($i == 9) {
      array_push($columns,
              array(
                  'name' => 'q' . $n,
                  'headerHtmlOptions' => array('title' => $model->getQuestion($i)),
              )
      );
      continue;
    }
    array_push($columns,
            array(
                'name' => 'q' . $n,
                'filter' => $model->QDropDown($i),
                'headerHtmlOptions' => array('title' => $model->getQuestion($i)),
                'value' => '"<p style=\"font-size: 7pt;\">".$data->QDropDown('.$i.',(integer)$data->q'.$n.')."</p>"',
                'type' => 'raw'
            )
    );
  }
  
  
  array_push($columns,
          array(
              'name' => 'UserID',
              'filter' => $model->UserDropDown($i),
              'value' => '$data->UserID." : ".User::model()->findByPk($data->UserID)->info'
          )
  );
  array_push($columns,
          array(
              'name' => 'Created',
          )
  );
  
  $this->widget('bootstrap.widgets.TbGridView', array(
      'id'=>'monitoring-grid',
      'dataProvider'=>$model->search(),
      'filter'=>$model,
      'columns' => $columns,
      'htmlOptions' => array(
          'style'=>'font-size: 8pt;' 
      )
    )
  );

?>
Питання: <br/><ol>
<?php
  for ($i = 0; $i < 13; $i++){
    echo '<li><div class="well" style="width:800px;">' . $model->getQuestion($i) . "</div></li>"; 
  }
?>
</ol>