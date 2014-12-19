<?php
/* @var $data CActiveDataProvider */
/* @var $model documents */
/* @var $this documentsController */
/* @var $ndeleted integer */
    ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/documentsview.css" />
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){

});
</script>

<div class="row-fluid" style="text-align: center;">
  <div class="btn-toolbar" >
  <?php
    /*$this->widget('bootstrap.widgets.TbButton', array(
      'type'=>'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
      'label'=>'*',
      'icon' => 'folder-open white',
      'url' => Yii::app()->CreateUrl('documents/index',array('hidden'=>'1')),
      'htmlOptions' => array(
        'title' => 'Не збережені',
      ),
    ));*/
    $this->widget('bootstrap.widgets.TbButton', array(
      'type'=>'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
      'label'=>'Журнал',
      'htmlOptions' => array(
        'onclick' => '$("#doclisttoxls-holder").slideToggle();return false;',
      ),
    ));

    $items = array();
    foreach (Documentcategory::DropDownFull() as $id => $val){
      $items[] = array('label'=>$val, 'url'=>'?DocumentCategoryID='.$id,
            'itemOptions' => array(
              'style' => 'text-align: left;'
            )
      );
    }
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
      'type'=>'', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
      'buttons'=>array(
          array('label'=>'Категорії', 'items' => $items,
          ),
      ),
    ));
    if ($current_cat){
      $this->widget('bootstrap.widgets.TbButton', array(
        'type'=>'info', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'label'=>'Обрана категорія: '.$current_cat,
      ));
    }
  ?>
  </div>
</div>
<div id="doclisttoxls-holder" style="display:none;">
  <?php
  $form = $this->beginWidget(
          'bootstrap.widgets.TbActiveForm', array(
     'id' => 'doclisttoxls-form',
     'action' => Yii::app()->createUrl("documents/doclisttoxls"),
     'enableAjaxValidation' => false,
     'type' => 'GET',
          )
  );
  ?>
  <div class="row-fluid">
    <div class="span4"></div>
    <div class="span4" id="doclisttoxls-block">
      <div class="row-fluid">
        <?php
        $category_list = Documentcategory::DropDown();
        $category_list[0] = 'Усі категорії';
        ?>
        <div class="span12 dfheader">Категорія</div>
        <?php echo CHtml::dropDownList('category', 0, $category_list, array(
          'class' => 'span12',
        )); ?>
        <div class="row-fluid">
          <div class="span6">
            <?php echo CHtml::label('Від дати', 'doclisttoxls-datefrom',array(
              'class' => 'span12 dfheader',
            )); ?>
            <?php
            echo CHtml::textField('datefrom', str_replace('-', '.', date('d-m-Y')),
              array('id' => 'doclisttoxls-datefrom',
               'class' => 'span12 datepicker'));
            ?>
          </div>
          <div class="span6">
            <?php echo CHtml::label('До дати', 'doclisttoxls-dateto',array(
              'class' => 'span12 dfheader',
            )); ?>
            <?php
            echo CHtml::textField('dateto', str_replace('-', '.', date('d-m-Y')),
              array('id' => 'doclisttoxls-dateto',
               'class' => 'span12 datepicker'));
            ?>
          </div>
        </div>
      </div>
      <div class="row-fluid">
        <div class="span5">
          <?php echo CHtml::label('Максимум',
            'doclisttoxls-limit', array(
            'class' => 'span7 dfheader',
          )); ?>
          <?php echo CHtml::textField('limit', '1000', array(
            'id' => 'doclisttoxls-limit',
            'class' => 'span5',
          ));
          ?>
        </div>
        <div class="span7">
        <?php
        $this->widget("bootstrap.widgets.TbButton", array(
           'buttonType' => 'submit',
           'type' => 'primary',
           "size" => "small",
           'htmlOptions' => array(
              'id' => 'doclisttoxls-submit',
              'class' => 'span12',
           ),
           'label' => 'Завантажити таблицю Excel',
        ));
        ?>
        </div>
      </div>
    </div>
    <div class="span4"></div>
  </div>

  <?php
  $this->endWidget();
  ?>
</div>

<?php
$controller = $this;
$this->widget('bootstrap.widgets.TbGridView', array(
   'id' => 'documents-list-grid',
   'type' => 'striped bordered condensed',
   'template'=>"<div class='row-fluid'><div class='span9'>{pager}</div><div class='span3'>{summary}</div></div>"
   . "<div class='row-fluid'>{items}</div><div class='row-fluid'>{pager}</div>",
   'pager' => array(
      'maxButtonCount' => 15,
      'header' => ''
   ),
   'dataProvider' => $data,
   'ajaxUpdate' =>false,
   'filter' => $model,
   'columns' => array(
      array(
         'header' => 'Дії',
         'filter' => 
         '<div class="row-fluid">'
           .CHtml::link("<span class='btn'><i class='icon-bookmark'></i></span>",
           Yii::app()->CreateUrl("/documents/index?own=1"),
           array('title' => 'Показати лише власні'))
           .'</div>',
         'value' => function ($data, $row) use ($controller){
            echo '<span id="doc-'.$data->idDocument.'"></span>';
            $last_ver_id = $data->LastDocVersion($data->idDocument);
            if ($last_ver_id && Files::model()->findByPk($last_ver_id)->FileExists()){
              $controller->widget(
                  "bootstrap.widgets.TbButton", array(
                    'buttonType' => 'link',
                    'type' => 'button',
                    "size" => "normal",
                    'url' => Yii::app()->CreateUrl(
                      "/files/DownloadFile/", array(
                  "id" => $last_ver_id)),
                    'icon' => 'hdd',
                    'htmlOptions' => array(
                      'title' => 'Завантажити останню версію',
                      'class' => 'ctrl_button'
                    ),
                  )
              );
            }
            $controller->widget(
                "bootstrap.widgets.TbButton", array(
                  'buttonType' => 'link',
                  'type' => 'success',
                  "size" => "normal",
                  'url' => Yii::app()->CreateUrl(
                    "/docflows/createflow", array(
                "DocumentID" => $data->idDocument)),
                  'icon' => 'envelope',
                  'htmlOptions' => array(
                    'title' => 'Розіслати',
                    'target' => '_blank',
                    'class' => 'ctrl_button'
                  ),
                )
            );
            $controller->widget(
                "bootstrap.widgets.TbButton", array(
                  'buttonType' => 'link',
                  'type' => 'info',
                  "size" => "normal",
                  'url' => Yii::app()->CreateUrl(
                    "/documents/cardprint", array(
                "id" => $data->idDocument)),
                  'icon' => 'print',
                  'htmlOptions' => array(
                    'title' => 'Картка',
                    'target' => '_blank',
                    'class' => 'ctrl_button'
                  ),
                )
            );
            $controller->widget(
                "bootstrap.widgets.TbButton", array(
                  'buttonType' => 'link',
                  'type' => 'primary',
                  "size" => "normal",
                  'url' => Yii::app()->CreateUrl(
                    "/documents/cardprintback", array(
                "id" => $data->idDocument)),
                  'icon' => 'print white',
                  'htmlOptions' => array(
                    'title' => 'Картка (зворотня сторона)',
                    'target' => '_blank',
                    'class' => 'ctrl_button'
                  ),
                )
            );
//             if (Yii::app()->user->id == $data->UserID){
//               $form_delete = $controller->beginWidget(
//                   'bootstrap.widgets.TbActiveForm', array(
//                   'id' => 'deletedoc-form'-$data->idDocument,
//                   'method' => 'POST',
//                   'action' => Yii::app()->createUrl("documents/deletedocument"),
//                   'enableAjaxValidation' => false,
//                       )
//               );
//               /* @var $form_delete TbActiveForm */
//               echo CHtml::hiddenField('id', $data->idDocument);
//               echo CHtml::hiddenField('returl', Yii::app()->request->url);
//               $controller->widget(
//                   "bootstrap.widgets.TbButton", array(
//                     'buttonType' => 'submit',
//                     'type' => 'danger',
//                     "size" => "normal",
//                     'icon' => 'trash',
//                     'htmlOptions' => array(
//                       'title' => 'Видалити',
//                       'onclick' => 'if(!confirm("Остаточно видалити?"))return false;',
//                       'class' => 'ctrl_del_button'
//                     ),
//                   )
//               );
//               $controller->endWidget();
//             }
         },
         'htmlOptions' => array('class' => 'span1'),
         'type' => 'raw'
      ),//кінець стовпчика дій (меню)
      array('header' => 'Документи',
        'name' => 'searchField',
        'filter' => '<div class="row-fluid">'
           .'<div class="span2">'
           .'<span>'
           .CHtml::link("[розширений]",Yii::app()->CreateUrl("/documents/search"))
           .' </span>'
           .'Пошук</div>'
           .'<div class="span5">'
           .CHtml::activeTextField($model, 'searchField',array(
            'placeholder' => 'Пошук по всім полям'
           ))
           .'</div>'
           .'<div class="span3">'
           .CHtml::activeTextField($model, 'ControlDateField',array(
            'placeholder' => 'Пошук по даті контролю'
            ))
           .'</div>'
           .'<div class="span2">'
           .CHtml::activeTextField($model, 'DocYear',array(
            'placeholder' => 'Рік'
            ))
           .'</div>'
           .'</div>',
        'htmlOptions' => array('class' => 'span11'),
        'value' => function ($data, $row) use ($controller) {
          $controller->renderPartial("/documents/_document_item",array(
            'data' => $data,
            'controller' => $controller,
          ));
        }, //end function value
      ),
    ),
));
?>
<script type="text/javascript" charset="utf-8">
  $('.datepicker').datepicker({format: "dd.mm.yyyy", weekStart: 1});
</script>