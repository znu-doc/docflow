<style>
    .dfblock {
      height: 100px;
      background-color: rgb(240, 240, 240);
      margin-top: 3px;
      padding-top: 5px;
      padding-left: 5px;
      border-radius: 10px;
      border: 0px solid rgb(234, 234, 234);
      font-size: 10pt;
      font-family: Verdana;
      overflow: auto;
    }
    
    .dfmetaheader{
      font-family: Verdana; 
      font-size: 11pt; 
      text-align: center;
      border-radius: 0px;
      border: 1px solid #ADADFF;
    }
    
    
    .transparent_background80{
      background-color: rgba(233,233,233,0.8);
    }
    
    .autoheight {
      height: auto;
    }
    
    .dfborderblock {
      border: 1px solid #ADADFF;
    }
    
</style>

<div class="row-fluid" >
    <div class="well well-small span12 transparent_background80">
      <h1 class='span12 dfmetaheader'>Форма зворотньої сторони картки документа</h1>
      <h2><?php
        echo ($dmodel->DocumentDescription)? 
            '"'.$dmodel->DocumentDescription."\"" : 
            $dmodel->DocumentName ;
        ?></h2>
      <div class='row-fluid'>
        <div class="span4">
          <?php echo CHtml::label('Фонд №',"Fund",array(
            'class' => 'span3 dfheader'
          )); ?>
          <?php
          $this->widget('editable.EditableField', array(
           'type' => 'textarea',
           'attribute' => 'Fund',
           'name' => 'Fund',
           'model' => $model,
           'url' => $this->createUrl('/documents/updateEditableCard',array('field' => 'Fund')),
           'title' => 'Фонд №',
           'placement' => 'right', 'mode' => 'inline',
         ));
          ?>
        </div>
        <div class="span4">
          <?php echo CHtml::label('Опис №',"Description",array(
            'class' => 'span3 dfheader'
          )); ?>
          <?php
          $this->widget('editable.EditableField', array(
           'type' => 'textarea',
           'attribute' => 'Description',
           'name' => 'Description',
           'model' => $model,
           'url' => $this->createUrl('/documents/updateEditableCard',array('field' => 'Description')),
           'title' => 'Опис №',
           'placement' => 'right', 'mode' => 'inline',
         ));
          ?>
        </div>
        <div class="span4">
          <?php echo CHtml::label('Справа №',"Fund",array(
            'class' => 'span3 dfheader'
          )); ?>
          <?php
          $this->widget('editable.EditableField', array(
           'type' => 'textarea',
           'attribute' => 'Act',
           'name' => 'Act',
           'model' => $model,
           'url' => $this->createUrl('/documents/updateEditableCard',array('field' => 'Act')),
           'title' => 'Справа №',
           'placement' => 'right', 'mode' => 'inline',
         ));
          ?>
        </div>
      </div>
    <?php 
      echo CHtml::link('Друк',Yii::app()->CreateUrl('/documents/cardprintback/'.$dmodel->idDocument.'?print=1'));
    ?>
    </div>
</div>