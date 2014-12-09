<?php
/* incoming ,... */
/* @var $already_answered integer */
/* @var $model  Monitoringanswers */

$form_addflow = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm', array(
    'id' => 'anketa-form',
    'action' => Yii::app()->createUrl("site/anketa"),
    'enableAjaxValidation' => false,
        )
);

?>

<script>
$(function () {
  $('#anketa_submit').click(function(){
    if ($('input:checked').length < 12){
      alert("Будь-ласка, надайте відповідь на всі питання");
      return false;
    }
  });
});
</script>

<?php 
  if (isset($model)){
    foreach ($model->errors as $err){
      ?>
<div class='well alert alert-error'>
  <?php
    echo str_replace('цілим числом','надано',$err[0]);
  ?>
</div>
      <?php
    }
  }
?>
<h1><?php 
if (!Yii::app()->user->CheckAccess('showProperties')){
  echo (!isset($already_answered))? 'Анкетування користувачів ПЗ "Документообіг"' 
  : 'Дякуємо за надання відповідей';
}
?></h1>
<br/><br/>
Результати анкетування будуть використані ВИКЛЮЧНО для вдосконалення системи електронного документообігу.
<br/><br/><ol>
<li> Як часто до Вашого структурного підрозділу надходять документи через електронний документообіг?
<br/>а) <?php if(!isset($already_answered)){ ?><input type="radio" name="q1" value="3" > <?php } else {echo "<sup>".$model->count('q1=3')."&nbsp;&cross;&nbsp;</sup>";  } ?> кожного дня
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q1" value="2"> <?php } else {echo "<sup>".$model->count('q1=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>  декілька разів на тиждень
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q1" value="1"> <?php } else {echo "<sup>".$model->count('q1=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>  приблизно раз на тиждень
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q1" value="0"> <?php } else {echo "<sup>".$model->count('q1=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>  рідше ніж раз на тиждень
</li>
<br/>
<li> Чи зробила система електронного документообігу зручнішою Вашу роботу з документами?
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q2" value="2" > <?php } else {echo "<sup>".$model->count('q2=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>   так
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q2" value="1"> <?php } else {echo "<sup>".$model->count('q2=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>   ні
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q2" value="0"> <?php } else {echo "<sup>".$model->count('q2=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>   зовсім ні, тільки збільшила обсяг роботи
</li>
<br/>
<li> Чи ознайомлювались Ви з інструкцією користувача ПЗ "Документообіг"?
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q3" value="3" > <?php } else {echo "<sup>".$model->count('q3=3')."&nbsp;&cross;&nbsp;</sup>";  } ?>   так, читаю кожного дня
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q3" value="2"> <?php } else {echo "<sup>".$model->count('q3=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>   читав/читала
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q3" value="1"> <?php } else {echo "<sup>".$model->count('q3=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>   вона мені не потрібна
<br/>г) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q3" value="0"> <?php } else {echo "<sup>".$model->count('q3=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>   ні, а де це?
</li>

<?php
Yii::app()->user->setFlash('info', 
'<b>УВАГА!</b> Посилання на інструкцію знаходиться в верхній панелі ПЗ "Документообіг" та називається "Інструції"');
      $this->widget('bootstrap.widgets.TbAlert', array(
              'fade'=>true, // use transitions?
              'block'=>true,
              'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
              'alerts'=>array( // configurations per alert type
                  'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
              ),
              'htmlOptions' => array(
                  'style' =>''
                  . 'font-family: "Tahoma";'
                  . 'font-size: 11pt;'
              )
          ));
?>
<br/>
<li> Оцініть, будь-ласка, якість інструкції.
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q4" value="0" > <?php } else {echo "<sup>".$model->count('q4=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>  оцінювати не буду, бо не читав/читала
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q4" value="3"> <?php } else {echo "<sup>".$model->count('q4=3')."&nbsp;&cross;&nbsp;</sup>";  } ?>  повна та зрозуміла
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q4" value="2"> <?php } else {echo "<sup>".$model->count('q4=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>  описано не все, що потрібно
<br/>г) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q4" value="1"> <?php } else {echo "<sup>".$model->count('q4=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>  дуже погана, нічого не зрозіміло
</li>
<br/>
<li> Чи знаете Ви про можливість налаштування виринаючих вікон при надходженні нового документу (зникає необхідність ручного оновлення сторінки для перевірки наявності нових документів)?
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q5" value="3" > <?php } else {echo "<sup>".$model->count('q5=3')."&nbsp;&cross;&nbsp;</sup>";  } ?>  так, користуюсь
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q5" value="2"> <?php } else {echo "<sup>".$model->count('q5=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>  так, але не користуюсь
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q5" value="1"> <?php } else {echo "<sup>".$model->count('q5=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>  ні, не хочу користуватись
<br/>г) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q5" value="0"> <?php } else {echo "<sup>".$model->count('q5=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>  налаштуйте мені, буду користуватись
</li>
<br/>
<li> Хто знає Ваш пароль від електронного документообігу?
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q6" value="2" > <?php } else {echo "<sup>".$model->count('q6=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>  тільки я
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q6" value="1"> <?php } else {echo "<sup>".$model->count('q6=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>  я і всі мої колеги (бо, наприклад, він записаний на папірці під клавіатурою)
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q6" value="0"> <?php } else {echo "<sup>".$model->count('q6=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>  я, всі колеги та всі друзі в соціальних мережах, мені немає чого приховувати
</li>

<?php
Yii::app()->user->setFlash('info','
<b>УВАГА!</b>
<br/>1. Якщо так сталось, що Ваш пароль уже хтось знає, зверніться будь-ласка до нас (1 корпус 22а, 289-41-09) і ми змінемо Ваш пароль на новий.
<br/>2. Якщо Ви йдете в відпустку, не передавайте свій пароль колегам, оформіть, будь-ласка, службову і ми включимо Вашого колегу в електронний документообіг як окремого користувача зі своїм логіном та паролем.
');
      $this->widget('bootstrap.widgets.TbAlert', array(
              'fade'=>true, // use transitions?
              'block'=>true,
              'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
              'alerts'=>array( // configurations per alert type
                  'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
              ),
              'htmlOptions' => array(
                  'style' =>''
                  . 'font-family: "Tahoma";'
                  . 'font-size: 11pt;'
              )
          ));
?>
<br/>
<li> Оцініть швидкість роботи ПЗ "Документообіг" (чи довго Ви чекаєте, пока завантажаться сторінки і т.п.)
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q7" value="2" > <?php } else {echo "<sup>".$model->count('q7=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>  працює досить швидко
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q7" value="1"> <?php } else {echo "<sup>".$model->count('q7=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>  не досить швидко, але я терпляче чекаю
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q7" value="0"> <?php } else {echo "<sup>".$model->count('q7=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>  дуже повільно
</li>
<br/>
<li> Якщо Ви звертались до нас з питаннями чи за допомогою щодо ПЗ "Документообіг", то оцініть, будь-ласка, нашу роботу.
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q8" value="2" > <?php } else {echo "<sup>".$model->count('q8=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>  все вирішили та допомогли досить оперативно
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q8" value="1"> <?php } else {echo "<sup>".$model->count('q8=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>  все вирішили та допомогли, але прийшлось довго чекати
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q8" value="0"> <?php } else {echo "<sup>".$model->count('q8=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>  нічим не допомогли, хоч і звертався
</li>
<br/>
<li> Оцініть, будь-ласка, зовнішній вигляд, зручність розташування кнопок та т. п.
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q9" value="2" > <?php } else {echo "<sup>".$model->count('q9=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>  все гарно та зручно
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q9" value="1"> <?php } else {echo "<sup>".$model->count('q9=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>  загалом задовільняє, але дуже далеко до досконалості
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q9" value="0"> <?php } else {echo "<sup>".$model->count('q9=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>  просто жах
</li>
<br/>
<li> Якщо у Вас є конкретні пропозиції по вдосконаленню зовнішнього вигляду та розташуванню окремих елементів (наприклад, збільшіть шрифт), то впишіть їх <br/>
<?php if (!isset($model)) {echo CHtml::textArea('q10', '',array('style'=>'width: 400px;')); }
else {
	foreach ($model->findAll() as $propo){
		?>
		<div class="label label-info" style="width: 90%;">
		<?php
			echo $propo->q10;
		?>
		</div>
		<?php
	}
}
 ?>
</li>
<br/>
<br/>
<strong>Питання стосовно інших аспектів роботи лабораторії інформаційних систем</strong> <br/>

<li> Чи знаете Ви про можливість подачі електронної заявки, в разі виникнення проблем з комп'ютером, Інтернетом та програмним забезпеченням, до ІТ служб ЗНУ
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q11" value="1" > <?php } else {echo "<sup>".$model->count('q11=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>  знаю
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q11" value="0"> <?php } else {echo "<sup>".$model->count('q11=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>  не знаю
</li>

<?php
Yii::app()->user->setFlash('info','
<b>УВАГА!</b> Електронні заявки. В разі виникнення проблем з комп\'ютером, Інтернетом та програмним забезпеченням відкрийте будь-який браузер (Chrome, Opera або інший) та введіть <a href="http://help.znu.edu.ua">help.znu.edu.ua</a>.
');
      $this->widget('bootstrap.widgets.TbAlert', array(
              'fade'=>true, // use transitions?
              'block'=>true,
              'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
              'alerts'=>array( // configurations per alert type
                  'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
              ),
              'htmlOptions' => array(
                  'style' =>''
                  . 'font-family: "Tahoma";'
                  . 'font-size: 11pt;'
              )
          ));
?>
<br/>
<li> Оцініть швидкість доступу до Інтернет в ЗНУ
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q12" value="2" > <?php } else {echo "<sup>".$model->count('q12=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>  швидко
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q12" value="1"> <?php } else {echo "<sup>".$model->count('q12=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>  не дуже швидко
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q12" value="0"> <?php } else {echo "<sup>".$model->count('q12=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>  дуже повільно
</li>
<br/>
<li> Оцініть наявність доступу до Інтернет в ЗНУ
<br/>а) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q13" value="2" > <?php } else {echo "<sup>".$model->count('q13=2')."&nbsp;&cross;&nbsp;</sup>";  } ?>   практично завжди є доступ
<br/>б) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q13" value="1"> <?php } else {echo "<sup>".$model->count('q13=1')."&nbsp;&cross;&nbsp;</sup>";  } ?>   інколи доступ відсутній, саме в той момент, коли потрібен
<br/>в) <?php if(!isset($already_answered)){ ?> <input type="radio" name="q13" value="0"> <?php } else {echo "<sup>".$model->count('q13=0')."&nbsp;&cross;&nbsp;</sup>";  } ?>   часто немає доступу до Інтернет
</li>
</ol>
<br/>

  <div style = 'width: 600px; margin: 0px auto;'>
  Дякуємо, що допомагаєте нам робити програмне забезпечення зручнішим!
    <?php
    if (!(Yii::app()->user->CheckAccess('showProperties') || isset($already_answered))){
      $this->widget(
          "bootstrap.widgets.TbButton", array(
            'buttonType' => 'submit',
            'type' => 'primary',
            "size" => "large",
            'loadingText'=>'Зачекайте...',
            'htmlOptions' => array(
                'id' => 'anketa_submit',
            ),
            'label' => 'Відправити відповіді',
          )
      );
    }
    $this->endWidget();
    ?>
  </div>

