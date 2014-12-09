<!-- The file upload form used as target for the file upload widget -->
<?php echo CHtml::beginForm($this->url, 'post', $this->htmlOptions); ?>
<div class="row-fluid dfbox">
<div class="span3 fileupload-buttonbar">
    <div class="span7">
        <!-- The fileinput-button span is used to style the file input field as button -->
    <span class="btn btn-success btn-small fileinput-button"><span>+</span>
      <?php
      if ($this->hasModel()) :
        echo CHtml::activeFileField($this->model, $this->attribute, $htmlOptions) . "\n"; else :
        echo CHtml::fileField($name, $this->value, $htmlOptions) . "\n";
      endif;
      ?>
    </span>
    </div>
    <div class="span5 fileupload-progress fade">
        <!-- The global progress bar -->
        <!--div class="progress progress-success progress-striped active" role="progressbar">
            <div class="bar" style="width:0%;"></div>
        </div-->
        <!-- The extended global progress information -->
        <!--div class="progress-extended">&nbsp;</div-->
    </div>
</div>
<!-- The loading indicator is shown during image processing -->
<!--div class="fileupload-loading"></div-->
<!-- The table listing the files available for upload/download -->
<div class="span9">
    <table class="table table-striped" style="width: 200px !important;">
        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
    </table>
</div>
</div>
<?php echo CHtml::endForm(); ?>

