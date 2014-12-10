<?php
/* @var $model Events */
/* @var $this EventsController */
/* @var $header string */

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/events.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/events.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/Math.uuid.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/invited_widget.js"></script>
<script type="text/javascript" charset="utf-8">
    
  $(function(){
    var cur = new Date();
     mc = new MultiCalendar(
      'Events_DateSmartField',
      'calendar_preview_block',
      'calendar_msg_block',
      cur.getMonth()+1,
      cur.getFullYear(),
      6
    );
    
    $("#Events_StartTime").keyup(function(){
      var str = $(this).val();
      var len = str.length;
      var tm;
      $(this).css('border-color','rgb(255, 0, 0)');
      if (!len){
        $(this).css('border-color','yellow');
        return false;
      }
      tm = str.match(/^\s*\d{1,2}:\d{1,2}(:\d{1,2})?\s*$/);
      if (tm && ($("#Events_FinishTime").css('border-color') !== "rgb(255, 0, 0)") || $("#Events_FinishTime").val().length){
        var c = new Date();
        var ft = ($("#Events_FinishTime").val().length)? $("#Events_FinishTime").val():"23:59:59";
        var sDate = new Date(c.getFullYear()+'-'+MultiCalendar.prototype.padDigits(c.getMonth()+1,2)
                +'-'+MultiCalendar.prototype.padDigits(c.getDate(),2)+"T"+$(this).val());
        var fDate = new Date(c.getFullYear()+'-'+MultiCalendar.prototype.padDigits(c.getMonth()+1,2)
                +'-'+MultiCalendar.prototype.padDigits(c.getDate(),2)+"T"+ft);
        if (sDate <= fDate){
          $(this).css('border-color','rgb(0, 255, 0)');
        }
      }
    });
    
    $("#Events_FinishTime").keyup(function(){
      var str = $(this).val();
      var len = str.length;
      var tm;
      $(this).css('border-color','rgb(255, 0, 0)');
      if (!len){
        $(this).css('border-color','yellow');
        return false;
      }
      tm = str.match(/^\s*\d{1,2}:\d{1,2}(:\d{1,2})?\s*$/);
      if (tm && ($("#Events_StartTime").css('border-color') !== "rgb(255, 0, 0)") && $("#Events_StartTime").val().length){
        var c = new Date();
        var sDate = new Date(c.getFullYear()+'-'+MultiCalendar.prototype.padDigits(c.getMonth()+1,2)
                +'-'+MultiCalendar.prototype.padDigits(c.getDate(),2)+"T"+$("#Events_StartTime").val());
        var fDate = new Date(c.getFullYear()+'-'+MultiCalendar.prototype.padDigits(c.getMonth()+1,2)
                +'-'+MultiCalendar.prototype.padDigits(c.getDate(),2)+"T"+$(this).val());

        if (sDate <= fDate){
          $(this).css('border-color','rgb(0, 255, 0)');
        }
      }
      
    });

    //////////////////////////////////////////////////////////////////////////
    var invWidget = new InvitedWidget(
      $('#_ItemSearchField'),$('#_FoundItemsArea'),
      $('#_AddedItemsArea'),$('#_AddAllItems'),
      $('#_AddText'),"invited_ids","invited_descrs","CtrMsg",
      '<?php echo Yii::app()->CreateUrl('departments/select2');  ?>'
      );  

    var invWidget1 = new InvitedWidget(
      $('#_ItemSearchField1'),$('#_FoundItemsArea1'),
      $('#_AddedItemsArea1'),$('#_AddAllItems1'),
      $('#_AddText1'),"organizer_ids","organizer_descrs","CtrMsg1",
      '<?php echo Yii::app()->CreateUrl('departments/select2');  ?>');    
      
    $("#_AddedItemsArea .CtrLink .CtrSubBullet").click(function (){
      invWidget.moveItemToL($(this));
      return false;
    });
    $("#_AddedItemsArea1 .CtrLink .CtrSubBullet").click(function (){
      invWidget1.moveItemToL($(this));
      return false;
    });
    
    $("#events-form").submit(function(event){
      if (mc.counters[0] == 0){
        event.preventDefault();
      }
      DOC.ButtonDelay("submit__button");
      return ;
    });
  });

</script>

<?php
  //don't afraid, just test :)
  // today is 21.11.2014, Friday
  //setlocale(LC_ALL, 'Ukrainian');
  //echo iconv('windows-1251','utf-8',strftime("%B, %A : %d.%m.%Y",strtotime('Monday'))); //-> "Листопад, понеділок : 24.11.2014"
  $date_field_names = array(
  'StartYear',
  'StartMonth',
  'StartWeekDay',
  'StartDay',
  'StartHour',
  'StartMinute',
  'FinishYear',
  'FinishMonth',
  'FinishWeekDay',
  'FinishDay',
  'FinishHour',
  'FinishMinute'
  );
  $day_names = array(
    'Неділя',
    'Понеділок',
    'Вівторок',
    'Середа',
    'Четвер',
    'П\'ятниця',
    'Субота',
  );
  $day_names[-1] = "неважливо";
  $eventscreate_form = $this->BeginWidget(
  'bootstrap.widgets.TbActiveForm', array(
    'id' => 'events-form',
    'htmlOptions' => array(
      'enctype' => 'multipart/form-data',
    ),
    'action' => '',
    'enableAjaxValidation' => false,
   )
  );
?>
<div class='row-fluid'>
  <div class="span12 dfbox">
    <div class='row-fluid'>
      <h1 class='span12 dfmetaheader'><?php echo $header; ?></h1>
    </div>
    <?php echo $eventscreate_form->errorSummary($model); ?>
    <div class='row-fluid'>
      <div class="span6">
        <div class='row-fluid'>
        <?php 
          echo $eventscreate_form->labelEx($model, 'EventName', array(
            'class' => 'span12 dfheader',
          ));
        ?>
        </div>
        <div class='row-fluid'>
        <?php
          echo $eventscreate_form->textField($model,'EventName',array(
            'class' => 'span12', 
          ));
        ?>
        </div>
        <div class="row-fluid">
          <div class="span6">
          <?php
            echo $eventscreate_form->labelEx($model, 'EventKindID', array(
              'class' => 'span12 dfheader',
            ));
            echo $eventscreate_form->dropDownList($model,'EventKindID',
              Eventkinds::DropDown(),array(
              'class' => 'span12'
            ));
          ?>
          </div>
          <div class="span6">
          <?php
            echo $eventscreate_form->labelEx($model, 'EventTypeID', array(
              'class' => 'span12 dfheader',
            ));
            echo $eventscreate_form->dropDownList($model,'EventTypeID',
              Eventtypes::DropDown(),array(
              'class' => 'span12'
            ));
          ?>
          </div>
        </div>
        <div class='row-fluid'>
        <?php 
          echo $eventscreate_form->labelEx($model, 'EventPlace', array(
            'class' => 'span12 dfheader', 'id'=>"EventPlaceLabel"
          ));
          echo $eventscreate_form->textArea($model,'EventPlace',array(
            'class' => 'span12', 
          ));
        ?>
        </div>
        <div class="row-fluid">
        <?php 
          echo $eventscreate_form->labelEx($model, 'EventDescription', array(
            'class' => 'span12 dfheader',
          ));
          echo $eventscreate_form->textArea($model,'EventDescription',array(
            'class' => 'span12', 'rows' => "4"
          ));
          // $this->widget(
              // "bootstrap.widgets.TbCKEditor", array(
                // 'name' => 'Events[EventDescription]',
                // 'value' => (($model->isNewRecord)? '':$model->EventDescription),
                // 'htmlOptions' => array(
                  // 'class' => 'span12',
                // )
          // ));
        ?>
        </div>
        <div class="row-fluid">
          <div class="span8">
          <?php
            echo $eventscreate_form->labelEx($model, 'Responsible', array(
              'class' => 'span12 dfheader',
            ));
            echo $eventscreate_form->textField($model,'Responsible',array(
              'class' => 'span12', 
            ));
          ?>
          </div>
          <div class="span4">
          <?php
            echo $eventscreate_form->labelEx($model, 'ResponsibleContacts', array(
              'class' => 'span12 dfheader',
            ));
            echo $eventscreate_form->textField($model,'ResponsibleContacts',array(
              'class' => 'span12', 
            ));
          ?>
          </div>
        </div>
      </div>
      
      <div class="span6">
        <div class="row-fluid">
          <div class="span6">
            <div class="row-fluid">
              <?php
                echo $eventscreate_form->labelEx($model, 'DateSmartField', array(
                  'class' => 'span12 dfheader',
                ));
              ?>
            </div>
            <div class="row-fluid">
            <?php
              echo $eventscreate_form->textField($model,'DateSmartField', array('class' => 'span12'));
            ?>
            </div>
          </div>
          <div class="span3">
            <div class="row-fluid">
              <?php
                echo $eventscreate_form->labelEx($model, 'StartTime', array(
                  'class' => 'span12 dfheader',
                ));
              ?>
            </div>
            <div class="row-fluid">
            <?php
              echo $eventscreate_form->textField($model,'StartTime',
                  array( 'class' => 'span12'));
            ?>
            </div>
          </div>
          <div class="span3">
            <div class="row-fluid">
              <?php
                echo $eventscreate_form->labelEx($model, 'FinishTime', array(
                  'class' => 'span12 dfheader',
                ));
              ?>
            </div>
            <div class="row-fluid">
            <?php
              echo $eventscreate_form->textField($model,'FinishTime',
                  array( 'class' => 'span12'));
            ?>
            </div>
          </div>
        </div>
        
        <div class="row-fluid">
          <!-- CALENDAR PLACEHOLDER -->
          <div class="span10" id="calendar_preview_block">
          </div>
          <div class="span2" id="calendar_msg_block">
          </div>
        </div>
      
      </div>
    </div>
  <div class="row-fluid">
    <hr/>
    
    <div class="row-fluid">
      <div class="span6 dfbox">
        <span class="dfheader">Запрошені</span>
        <div class="row-fluid">
          <div class="span6">
            <input type="text" id="_ItemSearchField" />
            <a href="#" id="_AddText"><span class="label label-success">+</span></a>
            <div clear="both"></div>
          </div>
          <div class="span6">
            <span class="CtrMsg"></span>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span6">
            <div class="dfheader">Запропоновані
            <a href="#" id="_AddAllItems"><span class="label label-info">&gt;&gt;</span></a>
            </div>
            <div id = "_FoundItemsArea">
            </div>
          </div>
          <div class="span6">
            <div class="dfheader">Обрані</div>
            <div id = "_AddedItemsArea">
            <?php 
            $vals = $model->getInvited();
            for ($i = 0; ($i < count($vals) && is_array($vals)) ; $i++){
              ?>
              <div class="CtrLink" id="<?php 
                echo $vals[$i]['DeptID']
                  .'_'.md5($vals[$i]['InvitedComment']); ?>">
                <a href="#" class="CtrSubBullet" >[-]</a> 
                <span class="CtrId">
                  <input type="hidden" name="invited_ids[]" 
                    value="<?php echo $vals[$i]['DeptID']; ?>" />
                </span>
                <span class="CtrName">
                  <input type="hidden" name="invited_descrs[]" 
                    value="<?php echo $vals[$i]['InvitedComment']; ?>" />
                  <input type="text" name="invited_descrs_comment[]" class="invited_descrs_comment"
                    value="<?php echo $vals[$i]['Seets']; ?>" />
                  <?php echo $vals[$i]['InvitedComment']; ?>
                </span>
              </div>
              <?php
            }
            ?>
            </div>
          </div>
        </div>

      </div>
      <div class="span6 dfbox">
        <span class="dfheader">Організатори</span>
        <div class="row-fluid">
          <div class="span6">
            <input type="text" id="_ItemSearchField1" />
            <a href="#" id="_AddText1"><span class="label label-success">+</span></a>
            <div clear="both"></div>
            
          </div>
          <div class="span6">
            <span class="CtrMsg1"></span>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span6">
            <div class="dfheader">Запропоновані
            <a href="#" id="_AddAllItems1"><span class="label label-info">&gt;&gt;</span></a>
            </div>
            <div id = "_FoundItemsArea1">
            </div>
          </div>
          <div class="span6">
            <div class="dfheader">Обрані
            </div>
            <div id = "_AddedItemsArea1">
            <?php 
              $vals = $model->getOrganizers();
              for ($i = 0; ($i < count($vals) && is_array($vals)); $i++){
                ?>
                <div class="CtrLink" id="org_<?php 
                  echo $vals[$i]['DeptID']
                    .'_'.md5($vals[$i]['OrganizerComment']); ?>">
                  <a href="#" class="CtrSubBullet" >[-]</a> 
                  <span class="CtrId">
                    <input type="hidden" name="organizer_ids[]" 
                      value="<?php echo $vals[$i]['DeptID']; ?>" />
                  </span>
                  <span class="CtrName">
                    <input type="hidden" name="organizer_descrs[]" 
                      value="<?php echo $vals[$i]['OrganizerComment']; ?>" />
                    <?php echo $vals[$i]['OrganizerComment']; ?>
                  </span>
                </div>
                <?php
              }
            ?>
            </div>
          </div>
        </div>


      </div>
    </div>
    <hr/>
    <div class='row-fluid' style="text-align: center;">
      <div class="span6">
	<?php
	if (!$model->FileID){
	?>
	<div class="dfheader">Можна прикріпити один файл (необов`язково)</div>
	<?php
	} else {
	?>
	<div class="dfheader">
	<?php
	echo CHtml::link("Файл",Yii::app()->CreateUrl('/events/attachment',array('id' => $model->idEvent)));
	?> прикріплено ,можна замінити 
	<?php
	  if ($model->attfile->UserID == Yii::app()->user->id){
	    echo "чи ".CHtml::link("видалити",Yii::app()->CreateUrl('/events/attachmentrm',array('id' => $model->idEvent)),
	      array("style" => "color: red;"));
	  }
	?> (необов`язково)</div>
	<?php
	}
	echo $eventscreate_form->fileField($model,"attachment");
	?>
      </div>
    </div>
    <div class='row-fluid' style="text-align: center;">
      <?php
      if ($model->isNewRecord){
        $this->widget(
            "bootstrap.widgets.TbButton", array(
              'buttonType' => 'submit',
              'type' => 'primary',
              "size" => "large",
              'loadingText'=>'Зачекайте...',
              'htmlOptions' => array(
                  'id' => 'submit__button',
              ),
              'label' => 'Створити',
            )
        );
      } else {
        $this->widget(
            "bootstrap.widgets.TbButton", array(
              'buttonType' => 'submit',
              'type' => 'success',
              "size" => "large",
              'loadingText'=>'Зачекайте...',
              'htmlOptions' => array(
                  'id' => 'submit__button',
              ),
              'label' => 'Оновити',
            )
        );
      }
      ?>
    </div>
  </div>
</div>
<?php 
  $this->endWidget();
?>
