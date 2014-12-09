<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/_main'); ?>
<h2>Керування групами користувачів</h2>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2">
        <?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
                        'htmlOptions'=>array('class'=>'aa')
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'nav nav-tabs nav-stacked'),
		));
		$this->endWidget();
	?>
    </div>
    <div class="span10">
       <div class="navbar">
        <div class="navbar-inner">
            <a class="brand" href="#">Title</a>
            <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            </ul>
        </div>
        </div>
        <div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
    </div>
  </div>
</div>
<?php $this->endContent(); ?>