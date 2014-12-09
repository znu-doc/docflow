<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div class="span9">
        <div id="content">
     
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
    <div class="span3">
        <div id="sidebar">
            <div class="well" style="padding: 8px 0; position: fixed; width: 270px;">
                
           <?php
            $this->widget('bootstrap.widgets.TbMenu', array(
                'items'=> array_merge(
                                array(array('label'=>'Доступні дії')),
                                $this->menu),
                'type'=>'list',
                
                
            ));
           
            ?>
            </div>
        </div><!-- sidebar -->  
    </div>
</div>
<?php $this->endContent(); ?>