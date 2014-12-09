function InvitedWidget(input_field,left_area,right_area,add_all_tool,add_text_tool,
  ids_nm,names_nm,
  echo_classname,
  search_url){
  
  this.$input_field = input_field;
  this.$left_area = left_area;
  this.$right_area = right_area;
  this.$add_all_tool = add_all_tool;
  this.$add_text_tool = add_text_tool;
  this.timerCheckCount = 0;
  this.timer = undefined;
  this.lastValue = "";
  this.ids_nm = ids_nm;
  this.names_nm = names_nm;
  this.echo_classname = echo_classname;
  this.search_url = search_url;
  
  var _self = this;  
  
  this.checkInputChange = function () {
    this.timerCheckCount += 1;
    if (this.lastValue !== this.$input_field.val()) {
      var without = [];
      
      $("#"+_self.$right_area.attr('id')+" .CtrLink .CtrSubBullet").each(function(){
        var dom_id = $(this).parent().attr('id');
        var ctr_id = $("#"+dom_id+" .CtrId input").val();
        if (ctr_id > 0){
          without[without.length] = ctr_id;
        }
      });
      $.ajax({
        type: 'GET',
        url: _self.search_url,
        async: true,
        cache: false,
        data: {q: _self.$input_field.val(), n_ids: JSON.stringify(without)},
        success: function(data){
          var receive = JSON.parse(data);
          _self.$left_area.html("");
          if (receive.length === 0){
            $("."+_self.echo_classname).stop(true, true).fadeIn(0).html(
              "Нічого не знайдено"
            ).fadeOut(2000);
            return false;
          }
          $("."+_self.echo_classname).stop(true, true).fadeIn(0).html(
            "Знайдено записів: "+receive.length
          ).fadeOut(2000);
          for (var i = 0; i < receive.length; i++){
            var append_html = 
             '<div class="CtrLink" id="'+receive[i].id+'_'+_self.$right_area.attr('id')+'">'
             +'<a href="#" class="CtrAddBullet" >'
             +'[+]</a> '
             +'<span class="CtrId">'
             +receive[i].id
             +'</span> '
             +'<span class="CtrName">'
             +receive[i].text
             +'</span>'
             +'</div>';
            _self.$left_area.append(append_html);
            
            $("#"+_self.$left_area.attr('id')+" .CtrLink .CtrAddBullet").click(function (){
              _self.moveItemToR($(this));
              return false;
            });
          }
        },
        error: function(jXHR,txt){
          $("."+_self.echo_classname).stop(true, true).fadeIn(0).html(
            txt
          ).fadeOut(2000);
        }
      });
      this.lastValue = this.$input_field.val();
    }
  }
  
  this.startTimer = function () {
    this.timer = setInterval(function(){_self.checkInputChange()}, 200); // (1/5 sec)
  }
  
  this.endTimer = function endTimer() {
    clearInterval(this.timer);
    this.timerCheckCount = 0;
  }

  this.$add_all_tool.click(function(){
    $("#"+_self.$left_area.attr('id')+" .CtrLink .CtrAddBullet").each(function(elem){
      _self.moveItemToR($(this));
    });
    return false;
  });
  
  this.$add_text_tool.click(function(){
    var text_value = _self.$input_field.val();
    var id_add = Math.uuid();
    if (text_value.length === 0){
      return false;
    }
    if ($("#"+_self.$right_area.attr('id')+" .CtrLink .CtrName.NoFlow input[value='"+text_value+"']").length !== 0){
      return false;
    }
    var append_html = 
     '<div class="CtrLink" id="'+id_add+'">'
     +'<a href="#" class="CtrSubBulletNoFlow" onclick="$(this).parent().remove();return false;">[-]</a> '
     +'<span class="CtrId">'
     +'<input type="hidden" name="'+_self.ids_nm+'[]" value="-1" />'
     +'</span> '
     +'<span class="CtrName NoFlow">'
     +'<input type="hidden" name="'+_self.names_nm+'[]" value="'+text_value+'" />'
     +'<input type="text" name="'+_self.names_nm+'_comment[]" value="" class="'+_self.names_nm+'_comment" />'
     +text_value
     +'</span>'
     +'</div>';
     if ($("#"+id_add).length === 0){
      _self.$right_area.append(append_html);
    }
    return false;
  });
  
  this.$input_field.focus(function() {
      // turn on timer
      _self.startTimer();
      $("."+_self.echo_classname).stop(true, true).fadeIn(0).html('далі...').fadeOut(2000);
  }).blur(function() {
      // turn off timer
      _self.endTimer();
  });

  this.moveItemToR = function (_bullet) {
    var dom_id = _bullet.parent().attr('id');
    var dom_id_parent_id = $("#"+dom_id).parent().attr('id');
    var bullet_class = _bullet.attr('class');
    var bullet_text = _bullet.text();
    if (bullet_class === 'CtrAddBullet'){
      var ctr_id = $("#"+dom_id+" .CtrId").text();
      var ctr_name = $("#"+dom_id+" .CtrName").text();
      $("#"+dom_id+" .CtrId").html("");
      $("#"+dom_id+" .CtrName").html("");
      $("#"+dom_id+" .CtrId").append("<input type='hidden' "
        +"name='"+_self.ids_nm+"[]' "
        +"value='"+InvitedWidget.prototype.trim1(ctr_id)+"' />");
      $("#"+dom_id+" .CtrName").append("<input type='hidden' "
        +"name='"+_self.names_nm+"[]' "
        +"value='"+InvitedWidget.prototype.trim1(ctr_name)+"' />");
      $("#"+dom_id+" .CtrName").append("<input type='text' "
        +"name='"+_self.names_nm+"_comment[]' class='"+_self.names_nm+"_comment' "
        +"value='' />");
      $("#"+dom_id+" .CtrName").append(ctr_name);
      _bullet.text("[-]");
      _bullet.attr('class','CtrSubBullet');
      
      if (ctr_id < 0){
        $("#"+dom_id).remove();
      } else {
        $("#"+dom_id).appendTo("#"+_self.$right_area.attr('id'));
      }
        
      _bullet.click(function(){
        _self.moveItemToL(_bullet);
        return false;
      });
      return false;
    } 
  };
  
  this.moveItemToL = function (_bullet) {
    var dom_id = _bullet.parent().attr('id');
    var dom_id_parent_id = $("#"+dom_id).parent().attr('id');
    var bullet_class = _bullet.attr('class');
    var bullet_text = _bullet.text();
    if (bullet_class === 'CtrSubBullet'){
      var ctr_id = $("#"+dom_id+" .CtrId input").val();
      var ctr_name = $("#"+dom_id+" .CtrName input").val();
      $("#"+dom_id+" .CtrId").text(ctr_id);
      $("#"+dom_id+" .CtrName").text(ctr_name);
      _bullet.attr('class','CtrAddBullet');
      _bullet.text("[+]");
    }
    if (ctr_id < 0){
      $("#"+dom_id).remove();
    } else {
      $("#"+dom_id).appendTo("#"+_self.$left_area.attr('id'));
    }
    
    _bullet.click(function(){
      _self.moveItemToR(_bullet);
      return false;
    });
    return false;
  };
}

InvitedWidget.prototype.trim1 = function(str) {
  return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
}

