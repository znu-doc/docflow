<?php
/* @var $data CActiveDataProvider */
/* @var $model Docflows */
/* @var $this docflowsController */
    ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/docflowsview.css" />
<script type="text/javascript" charset="utf-8">
//Миготіння повідомлення про необхідність зворотного інформування.
DOC.blinking("blxx");

$(document).ready(function(){
  $('.icon-refresh').click(function(){
    var id_ = $(this).attr('id');
    var id = parseInt(id_);
    if (id_.indexOf('_docflowstatus') >= 0){
      $('#docflowstatus-'+id).text('');
      var url = '<?php echo Yii::app()->CreateUrl('/docflows/ajaxupdatestatus'); ?>';
      var full_url = url + '/' + id;
      $.ajax({
            url: full_url
          }).done(function(data){
        parsed_data = JSON.parse(data);
        obj = $('#docflowstatus-'+parsed_data.idDocFlow);
        if (obj.attr('class') === 'docflow_status'){
          obj.text(parsed_data.DocFlowStatusName);
        }
      });
    }
    if (id_.indexOf('_dfrespondents') >= 0){
      $('#dfrespondents-'+id).html('');
      var url = '<?php echo Yii::app()->CreateUrl('/docflows/ajaxdocflowrespondents'); ?>';
      var full_url = url + '/' + id;
      $.ajax({
            url: full_url
          }).done(function(data){
        parsed_data = JSON.parse(data);
        obj = $('#dfrespondents-'+parsed_data.idDocFlow);
        obj.html(parsed_data.dept_html_list);
      });
    }
  });
  $('#deleteflow-form').submit(function(){
    if (!confirm('Ви впевненні?')){
      return false;
    }
  });
  $('.span12.createanswer_button').click(function() {
      var btn = $(this);
      btn.button('loading'); // call the loading function
      setTimeout(function() {
          btn.button('reset'); // call the reset function
      }, 40000);
  });
  $(".AnswerDocField.span12").select2({
      placeholder: "Пошук документів",
      multiple: false,
      quietMillis: 200,
      ajax: {
          url: "<?php echo Yii::app()->createUrl("/documents/select2"); ?>",
          dataType: 'json',
          data: function(term, page) {
              return {
                  q: term // search term
                  //page_limit: 10,
                  //page: page
              };
          },
          results: function(data, page) {
              return {results: data};
          }
      }
  });
});
</script>

<?php
$controller = $this;
$this->widget('bootstrap.widgets.TbGridView', array(
   'id' => 'dfg-list-grid',
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
           .(($model->mode != 'new' && $model->mode != 'from')? 
            CHtml::link("<span class='btn'><i class='icon-bookmark'></i></span>",
              Yii::app()->CreateUrl("/docflows/index?mode=new"),
              array('title' => 'Показати лише без відповіді')) 
            : '')
           .'</div>',
         'value' => function ($data, $row) use ($controller){
            echo '<span id="df-'.$data->idDocFlow.'"></span>';
            if ($data->CanDownloadAllDocs()){
            $controller->widget(
                "bootstrap.widgets.TbButton", array(
                  'buttonType' => 'link',
                  'type' => 'button',
                  "size" => "normal",
                  'url' => Yii::app()->CreateUrl(
                    "files/DownloadAll/", array(
                "id" => $data->idDocFlow)),
                  'icon' => 'hdd',
                  'htmlOptions' => array(
                    'title' => 'Завантажити',
                    'class' => 'ctrl_button',
                  ),
                )
            );
            }
            $controller->widget(
                "bootstrap.widgets.TbButton", array(
                  'buttonType' => 'link',
                  'type' => 'button',
                  "size" => "normal",
                  'url' => Yii::app()->CreateUrl('/docflows/createflow?DocFlowID='.$data->idDocFlow),
                  'icon' => 'random',
                  'htmlOptions' => array(
                    'title' => 'Переслати',
                    'class' => 'ctrl_button',
                  ),
                )
            );
            if (Yii::app()->user->id == $data->docFlowGroup->OwnerID){
              $form_delete = $controller->beginWidget(
                  'bootstrap.widgets.TbActiveForm', array(
                  'id' => 'deleteflow-form',
                  'method' => 'POST',
                  'action' => Yii::app()->createUrl("docflows/deleteflow"),
                  'enableAjaxValidation' => false,
                      )
              );
              /* @var $form_delete TbActiveForm */
              echo CHtml::hiddenField('id', $data->idDocFlow);
              echo CHtml::hiddenField('url', Yii::app()->request->url);
              $controller->widget(
                  "bootstrap.widgets.TbButton", array(
                    'buttonType' => 'submit',
                    'type' => 'danger',
                    "size" => "normal",
                    'icon' => 'trash',
                    'htmlOptions' => array(
                      'title' => 'Видалити',
                      'class' => 'ctrl_del_button', 
                    ),
                  )
              );
              $controller->endWidget();
            }
         },
         'htmlOptions' => array('class' => 'span1'),
         'type' => 'raw'
      ),
      
      array('header' => 'Розсилки',
         'name' => 'searchField',
          'filter' => '<div class="row-fluid">'
             .'<div class="span2">'
             .'<span>'
             .CHtml::link("[розширений]",Yii::app()->CreateUrl("/docflows/search",array(
             'mode'=>((isset($_GET['mode']))? $_GET['mode']:'in'))))
             .' </span>'
             .'Пошук</div>'
             .'<div class="span10">'
             .CHtml::activeTextField($model, 'searchField')
             .'</div></div>',
         'htmlOptions' => array('class' => 'span11'),
         'value' => function ($data, $row) use ($controller) { 
           /* @var $data Docflows */
              $controller->renderPartial('_docflow_node', array(
                  'data' => $data,
                  'controller' => $controller,
              ));
             }, //end function value
             ),
       ),
));

?>
