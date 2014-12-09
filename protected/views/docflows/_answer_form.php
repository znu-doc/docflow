<?php
/* @var $controller DocflowsController */
/* @var $data Docflows */

$controller->BeginWidget(
    'bootstrap.widgets.TbActiveForm', array(
    'id' => 'createanswer-form-'.$data->idDocFlow,
    'action' => Yii::app()->createUrl("docflowanswers/createanswer"),
    'enableAjaxValidation' => false,
        )
      );
    ?>
    <div class="form">
    <div class='row-fluid'>
      <div class="span8">
        <div class="dfheader span12">Коментар (необов\'язково)</div>
        <?php 
          echo CHtml::textArea('Comment',
            '',
            array(
              'id' => 'answer-text-'.$data->idDocFlow,
              'class' => 'span12'
          ));
          echo CHtml::hiddenField('returl',Yii::app()->request->requestUri);
          echo CHtml::hiddenField('DocFlowID',$data->idDocFlow);
        ?>
      </div>
      <div class="span4">
        <div class="dfheader span12">Тип інформування</div>
        <?php
          echo CHtml::dropDownList('AnswerTypeID', 
                  array(), 
                  Answertypes::model()->DropDown(), 
                  array('class' => 'span12'));
        ?>
      </div>
    </div>
    <div class='row-fluid'>
      <a href="#" class="dfheader span12" style="text-align: right;" 
      onclick="DOC.show_hide('<?php echo 'answer-docblock-'.$data->idDocFlow; ?>');return false;">
        Додати документ (за необхідністю)
      </a>
      <div class="span12 answer_doc_block" id="<?php echo 'answer-docblock-'.$data->idDocFlow; ?>" 
        style="display:none;">
        <input type="text" 
          id="<?php echo 'answer-docid-'.$data->idDocFlow; ?>" 
          name="AnswerDocID" class="AnswerDocField span12"
        />
      </div>
    </div>

    <div class='row-fluid'>
      <div class="span4 dfattention blxx">
        Необхідно підтвердити надходження
        <span style="float:right;">
        <i class="icon-arrow-right"></i>
        <i class="icon-arrow-right"></i>
        <i class="icon-arrow-right"></i>
        </span>
      </div>
      <div class="span4">

        <?php 
    $controller->widget(
        "bootstrap.widgets.TbButton", array(
          'buttonType' => 'submit',
          'type' => 'primary',
          'icon' => 'bell',
          "size" => "normal",
          'loadingText'=>'Зачекайте...',
          'htmlOptions' => array(
              'class' => 'span12 createanswer_button',
          ),
          'label' => 'Інформувати',
        )
    );
        ?>
      </div>
      <div class="span4">
      </div>
    </div>
    </div>
    <?php 
      $controller->endWidget();