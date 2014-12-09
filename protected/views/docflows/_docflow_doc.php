<?php
/* @var $doc string */
$items = explode('||',$doc);

?>
<div class="dfdocrow row-fluid">
  <div class="row-fluid">
    <div class="span6">
      <div class="row-fluid">
        <span class="dfminicontenthead">Внутрішній номер і дата: </span>
        <span class="dfminicontent">
        <?php 
        if (!isset($items[2])){
          var_dump(explode('$$$',$data->doc_field) );
        } else{
          echo (($items[1])? 
          $items[1]:'відсутні'); 
        } 
        ?>
        </span>
      </div>
      <div class="row-fluid">
        <span class="dfminicontenthead">Вихідний номер і дата: </span>
        <span class="dfminicontent">
        <?php 
        if (!isset($items[2])){
          var_dump($items);
        } else{
        echo (($items[2])? 
        $items[2]:'відсутні'); 
        }
        ?>
        </span>
      </div>
    </div>
    <div class="span3">
    <?php
      $i=1;
      if (isset($items[5])){
        foreach (explode(';',$items[5]) as $docfile_id){
          $docfile = Files::model()->findByPk($docfile_id);
          if (!$docfile){ continue;}
          if ($docfile->FileExists()){
            $ext = pathinfo(Yii::app()->getBasePath().'/../docs/'.$docfile->FileLocation, PATHINFO_EXTENSION);
            if (!strcasecmp ($ext,'pdf')){
            $this->beginWidget('ext.prettyPhoto.PrettyPhoto', array(
                    'id'=>'pretty_photo_'.$docfile_id,
                    // prettyPhoto options
                    'options'=>array(
                      'opacity'=>0.70,
                      'modal'=>true,
                      'theme' => 'facebook', 
                      'default_width' => '100%',
                      'default_height' => '90',
                      'allow_resize' => true,
                    ),
                  ));
            
            echo CHtml::link(
            "<i class=\"icon-eye-open\" "
            . "title=\"Версія документа ".date('d.m.Y H:i:s',strtotime($docfile->FileTimeStamp)).", "
                . "додав користувач : ".$docfile->user->info."\"></i>Файл_".($i++) . ' (PDF)', 
            Yii::app()->CreateUrl(
                "files/DownloadFile/", array(
            "id" => $docfile->idFile,
            "iframe" => 'true',
            //"width" => "1024",
            //"height" => "700"
            )), array(
            //"target" => "_blank",
            'rel' => "prettyPhoto",
            'title' => 'Перегляд PDF',
            )).'<br/>';
            $this->endWidget('ext.prettyPhoto.PrettyPhoto'); 
            } else {
              echo CHtml::link(
              "<i class=\"icon-download\" "
              . "title=\"Версія документа ".date('d.m.Y H:i:s',strtotime($docfile->FileTimeStamp)).", "
                  . "додав користувач : ".$docfile->user->info."\"></i>Файл_".($i++), 
              Yii::app()->CreateUrl(
                  "files/DownloadFile/", array(
              "id" => $docfile->idFile)), array(
              //"target" => "_blank",
              )).'<br/>';
            }
          }
        }
      }
    ?>
    </div>
    <div class="span3">
      <?php echo CHtml::link('<i class="icon-share"></i>документ',
          Yii::app()->CreateUrl('/documents/index',array('id' => $items[0])), array(
            'title'=>'Перейти на перегляд/редагування документа',
            'target' => '_blank',
            )
          ); ?>
    </div>
  </div>
  <div class="row-fluid dfdescr">
      <?php 
      if (isset($items[3])){
        echo ($items[3])? $items[3] :
         '<span class=\'absent_value\'>Немає короткого змісту документа</span>'; 
      }
      ?>
  </div>
</div>