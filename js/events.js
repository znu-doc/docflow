  
  function MultiCalendar(
    input_field_id,
    calendar_block_id,
    calendar_msg_block_id,
    start_month,
    start_year,
    n_months,
    calendar_url
  ){
    this.n_months = n_months;
    this.$input_field = $('#'+input_field_id);
    if (this.$input_field.length > 0){
      this.input_field_id = input_field_id;
    } else {
      this.input_field_id = null;
      this.$input_field = null;
    }
    this.$calendar_block = $('#'+calendar_block_id);
    this.calendar_block_id = calendar_block_id;
    this.$calendar_msg_block = $('#'+calendar_msg_block_id);
    this.calendar_msg_block_id = calendar_msg_block_id;
    this.c_year = start_year;
    this.c_month = start_month - 1;
    
    this.m_years = 10;
    this.h_dates = [];
    this.calendar_url = (typeof(calendar_url) !== undefined)? calendar_url : null;
    
    this._start_date = new Date(this.c_year,this.c_month,1);
    this._end_date = new Date(this.c_year,this.c_month+this.n_months,0,23,59,59);
    
    this.months = ["Січень","Лютий","Березень","Квітень","Травень","Червень",
      "Липень","Серпень","Вересень","Жовтень","Листопад","Грудень"];
    this.w_days = ["нд","пн","вт","ср","чт","пт","сб"];
    this.w_days_assoc = [7,1,2,3,4,5,6];
    this.patterns = [
      /^\s*(\d{1,2})\.(\d{1,2})\.(\d{4,4})\s*-\s*(\d{1,2})\.(\d{1,2})\.(\d{4,4})\s*$/,
      /^\s*(\d{1,2})\s*-\s*(\d{1,2})\.(\d{1,2})\.(\d{4,4})\s*$/,
      /^\s*(\d{1,2})\.(\d{1,2})\.(\d{4,4})\s*$/,
      /^\s*(\d{1,2})\.(\d{1,2})\s*$/,
      /^\s*(\d{1,2})\.(\d{1,2})\s*-\s*(\d{1,2})\.(\d{4,4})\s*$/,
      /^\s*(пн|вт|ср|чт|пт|сб|нд)\s*(\/\s*(\d{1,2})\.(\d{1,2})\.(\d{4,4})\s*-\s*(\d{1,2})\.(\d{1,2})\.(\d{4,4})\s*)?$/i,
    ];
    this.counters = [0,0];
    
    var _self = this;
    
    
    this.getCalendarArray = function(p_month,p_year){
      var current_start_date = new Date(p_year,p_month,1);
      var current_end_date = new Date(p_year,p_month+1,0);
      var prev_end_date = new Date(p_year,p_month,0);
      var current_ndays = current_end_date.getDate();
      var prev_ndays = prev_end_date.getDate();
      var current_start_wday = _self.w_days_assoc[current_start_date.getDay()];
      var local_array = [];
      var local_sub_array = [];
      var current_date_value = 1, j = 0;
      
      for (var i = 1; i <= 7; i++){
        var val = prev_ndays-(current_start_wday-i-1);
        local_sub_array.push( ( val % (prev_ndays+1) ) 
            + ( ( val > prev_ndays)? 1 : 0 ) 
        );
        current_date_value = ( val % (prev_ndays+1) ) 
            + ( ( val > prev_ndays)? 1 : 0 ) ;
      }
      local_array.push(local_sub_array);
      local_sub_array = [];
      
      
      for (var i = current_date_value+1; 
        (i <= current_ndays); i++){
        local_sub_array.push(i);
        j = j + 1;
        if (j === 7){
          local_array.push(local_sub_array);
          local_sub_array = [];
          j = 0;
        }
      }
      for (var i = 1; (i <= 7-j && j > 0); i++){
        local_sub_array.push(i);
      }
      if (local_sub_array.length > 0){
        local_array.push(local_sub_array);
      }
      return local_array;
    };
    
    this.appendMonthCalendarTable = function(p_month,p_year){
      var table_id = "table_"+p_year+"-"+(p_month+1);
      var calendar_array = _self.getCalendarArray(p_month,p_year);
      
      _self.$calendar_block.append("<table "
        +"class='calendar_preview' "
        +"id='"+table_id+"'>"
        +"</table>");
      
      $("#"+table_id).append('<thead></thead>');
      $("#"+table_id).append('<tbody></tbody>');
      
      //додає заголовок таблиці календаря місяця
      $("#"+table_id+" thead").append('<tr class="month_title"></tr>');
      $("#"+table_id+" thead").append('<tr class="month_days_title"></tr>');
      $("#"+table_id+" thead tr.month_title").append(
        '<th colspan="7" >'
        +_self.months[p_month]+", "+p_year
        +'</th>');
      for (var i = 1; i <= 7; i++){
        $("#"+table_id+" thead tr.month_days_title").append(
          '<th colspan="dow" >'
          +_self.w_days[i%7]
          +'</th>');
      }
      
      //додає календар місяця
      for (var i = 0; i < calendar_array.length; i++){
        var tr_class = 'week_'+(i+1);
        $("#"+table_id+" tbody").append('<tr class="'+tr_class+'"></tr>');
        for (var j = 0; j < 7; j++){
          var td_id = _self.calendar_block_id+'_'+p_year+'-'
            +MultiCalendar.prototype.padDigits(p_month+1,2)+'-'
            +MultiCalendar.prototype.padDigits(calendar_array[i][j],2);
          if ((i===0 && calendar_array[i][j] < 10) || (i===(calendar_array.length-1) && calendar_array[i][j] > 10) 
            || (i > 0 && i < calendar_array.length-1)){
            $("#"+table_id+" tbody tr."+tr_class).append('<td id="'+td_id+'">'+calendar_array[i][j]+'</td>');
          } else {
            $("#"+table_id+" tbody tr."+tr_class).append('<td class="passive">'+calendar_array[i][j]+'</td>');
          }
        }
      }
    };
    
    this.putCalendar = function(){
      var dts = [];
      $(".badge.badge-info.counter_circle").remove();
      if (_self.calendar_url){
	_self.$calendar_block.append(
	  "<a href='#' class='icon-backward' id='"+_self.calendar_block_id+"_prev'></a>"
	);
	$("#"+_self.calendar_block_id+"_prev").click(function(){
	  var new_base_date = new Date(_self.c_year,--_self.c_month,1);
	  _self.c_year = new_base_date.getFullYear();
	  _self.c_month = new_base_date.getMonth();
	  _self.$calendar_block.html("");
	  _self._start_date = new_base_date;
	  _self._end_date = new Date(_self.c_year,_self.c_month+_self.n_months,0,23,59,59);
	  _self.putCalendar();
	  if (_self.$input_field !== null){
	    _self.$input_field.keyup();
	  }
	  return false;
	});
      }
      for (var i = 0; i < _self.n_months; i++){
        var d = new Date(_self.c_year,_self.c_month+i,1);
        var p_month = d.getMonth(),
            p_year = d.getFullYear();
        _self.appendMonthCalendarTable(p_month,p_year);
        dts.push({ 'p_month': p_month, 'p_year': p_year});
      }
      if (_self.calendar_url){
	$.ajax({
	  type: 'GET',
	  url: _self.calendar_url,
	  async: true,
	  cache: false,
	  data: {
	    date1: _self._start_date.getFullYear()+'-'
	      +MultiCalendar.prototype.padDigits((_self._start_date.getMonth()+1),2)
	      +'-01',
	    date2: _self._end_date.getFullYear()+'-'
	      +MultiCalendar.prototype.padDigits((_self._end_date.getMonth()+1),2)+'-'
	      +MultiCalendar.prototype.padDigits((_self._end_date.getDate()),2)
	  },
	  success: function(data){
	    var receive = JSON.parse(data);
	    var append_template = '<a href="'+_self.calendar_url+'/../admin?Events[date_search]={EventDate}" '
	    +'class="badge badge-important counter_circle" '
	    +'id="'+_self.calendar_block_id+'_{EventDate}_cnt">'
	    + '{cnt}'
	    + '</a>';
	    for (var i = 0; i < receive.length; i++){
	      var append_block = append_template
		.replace("{EventDate}",receive[i].EventDate)
		.replace("{EventDate}",receive[i].EventDate)
		.replace("{cnt}",receive[i].cnt);
	      $("#"+_self.calendar_block_id).append(append_block);
	      $('#'+_self.calendar_block_id+'_'+receive[i].EventDate+'_cnt').offset({
		top: $("#"+_self.calendar_block_id+"_"+receive[i].EventDate).position().top - 3,
		left: $("#"+_self.calendar_block_id+"_"+receive[i].EventDate).position().left 
		  + $("#"+_self.calendar_block_id+"_"+receive[i].EventDate).width()- 2
		});
	    }
	  },
	  error: function(jXHR,txt){
	    alert(txt);
	  }
	});
	_self.$calendar_block.append(
	  "<a href='#' class='icon-forward' id='"+_self.calendar_block_id+"_next'></a>"
	);
	$("#"+_self.calendar_block_id+"_next").click(function(){
	  var new_base_date = new Date(_self.c_year,++_self.c_month,1);
	  _self.c_year = new_base_date.getFullYear();
	  _self.c_month = new_base_date.getMonth();
	  _self._start_date = new_base_date;
	  _self._end_date = new Date(_self.c_year,_self.c_month+_self.n_months,0,23,59,59);
	  _self.$calendar_block.html("");
	  _self.putCalendar();
	  if (_self.$input_field !== null){
	    _self.$input_field.keyup();
	  }
	  return false;
	});
      }
      return dts;
    }
    
    this.parseRule = function(rule_string){
      var ret = {
        rule_str :   rule_string,
        start_date : _self._start_date,
        end_date :   _self._end_date, 
        step :       [0,0,1]
      };
      for (var  i = 0; i < _self.patterns.length; i++){
        var mtchs = ret.rule_str.match(_self.patterns[i]);
        if (mtchs !== null){
          switch (i){
            case 0:
              ret.start_date = new Date(mtchs[3]+'-'+mtchs[2]+'-'+mtchs[1]);
              ret.end_date = new Date(mtchs[6]+'-'+mtchs[5]+'-'+mtchs[4]);
              if (
                  ret.start_date == 'Invalid Date'
                  || ret.end_date == 'Invalid Date'
                 ){
                return null;
              }
              return ret;
              break;
            case 1:
              ret.start_date = new Date(mtchs[4]+'-'+mtchs[3]+'-'+mtchs[1]);
              ret.end_date = new Date(mtchs[4]+'-'+mtchs[3]+'-'+mtchs[2]);
              if (
                  ret.start_date == 'Invalid Date'
                  || ret.end_date == 'Invalid Date'
                 ){
                return null;
              }
              return ret;
              break;
            case 2:
              ret.start_date = new Date(mtchs[3]+'-'+mtchs[2]+'-'+mtchs[1]);
              ret.end_date = ret.start_date;
              if (ret.start_date == 'Invalid Date'){
                return null;
              }
              return ret;
              break;
            case 3:
              ret.start_date = new Date(_self._start_date.getFullYear()+'-'+mtchs[2]+'-'+mtchs[1]);
              ret.end_date = new Date((_self._start_date.getFullYear() + _self.m_years)+'-'+mtchs[2]+'-'+mtchs[1]);
              ret.step = [1,0,0];
              if (
                  ret.start_date == 'Invalid Date'
                  || ret.end_date == 'Invalid Date'
                 ){
                return null;
              }
              return ret;
              break;
            case 4:
              ret.start_date = new Date(mtchs[4]+'-'+mtchs[2]+'-'+mtchs[1]);
              ret.end_date = new Date(mtchs[4]+'-'+mtchs[3]+'-'+mtchs[1]);
              if (
                  ret.start_date == 'Invalid Date'
                  || ret.end_date == 'Invalid Date'
                 ){
                return null;
              }
              ret.step = [0,1,0];
              return ret;
              break;
            case 5:
              var d_w = _self.w_days.indexOf(mtchs[1].toLowerCase());
              var k = 0;
              if (mtchs[2] === undefined){
                ret.start_date = _self._start_date;
              } else {
                ret.start_date = new Date(mtchs[5]+'-'+mtchs[4]+'-'+mtchs[3]);
              }
              if (
                  ret.start_date == 'Invalid Date'
                  || ret.end_date == 'Invalid Date'
                 ){
                return null;
              }
              while (ret.start_date.getDay() !== d_w && k < 10){
                ret.start_date = new Date(ret.start_date.getFullYear(),
                  ret.start_date.getMonth(),
                  ret.start_date.getDate()+1 );
                k++;
              }
              if (mtchs[2] === undefined){
                ret.end_date = new Date(ret.start_date.getFullYear(),ret.start_date.getMonth()+4,0);
              } else {
                ret.end_date = new Date(mtchs[8]+'-'+mtchs[7]+'-'+mtchs[6]);
              }
              ret.step = [0,0,7];
              return ret;
              break;
          }
        }
      }
      return null;
    }
    
    this.getDatesByRules = function(rules_str){
      var a_dates = [], k =0, a_rules = [];
      if (rules_str.indexOf(',') !== -1){
        var r_parts = rules_str.split(',');
        for (var i = 0; i < r_parts.length; i++){
          var c_rule;
          if (r_parts[i] === ""){
            return null;
          }
          c_rule = _self.parseRule(r_parts[i]);
          //console.log(c_rule);
          if ( c_rule ){
            a_rules.push(c_rule);
          } else {
            return null;
          }
        }
      } else 
      {
        var c_rule = _self.parseRule(rules_str);
        if (c_rule){
          a_rules.push(c_rule);
        }
      }
      var temp_d = 0; 
      for (var i = 0; i < a_rules.length && k < 1000; i++ ){
        for (var dt = a_rules[i].start_date; dt <= a_rules[i].end_date  && k < 1000; k++ ){
          var y = dt.getFullYear()+a_rules[i].step[0],
            m = dt.getMonth()+a_rules[i].step[1],
            d = ((temp_d)? temp_d:dt.getDate())+a_rules[i].step[2],
            pdt = new Date(y,m+1,0);
          var month_length = pdt.getDate();
          if (k > 40){   return -1;   }
          a_dates.push(dt);
          dt = new Date(y,m,d);
          if (a_rules[i].step[2] === 0 && a_rules[i].step[1] > 0 && month_length < d){
            dt = pdt;
            temp_d = d;
          } else {
            temp_d = 0;
          }
        }
      }
      return a_dates;
    }
    
    this.hightLightCurrentDate = function(){
      var current_date = new Date();
      $('#'+_self.calendar_block_id+'_'
        +current_date.getFullYear()+'-'
        +MultiCalendar.prototype.padDigits(current_date.getMonth()+1,2)+'-'
        +MultiCalendar.prototype.padDigits(current_date.getDate(),2)
      ).attr('class','extra_highlighted');
      return false;
    }
    
    this.hightLightDates = function(rules_str){
      var dates = _self.getDatesByRules(rules_str);
      var counters = [0,0];
      var $passives = $("#"+_self.calendar_block_id+" table tbody tr td.passive");
      $("#"+_self.calendar_block_id+" table tbody tr td").attr('class','');
      $("#"+_self.calendar_block_id+" input[type=hidden]").remove();
      $passives.attr('class','passive');
      _self.hightLightCurrentDate();
      _self.h_dates = [];
      _self.counters = counters;
      if (dates === null){
        return counters;
      }
      if (dates === -1){
        return [-1,-1];
      }
      for (var i = 0; i < dates.length; i++){
        var date_value = dates[i].getFullYear()
          +'-'+MultiCalendar.prototype.padDigits(dates[i].getMonth()+1,2)
          +'-'+MultiCalendar.prototype.padDigits(dates[i].getDate(),2);
        var td_id = _self.calendar_block_id +'_'+date_value;
        var current_date = new Date();
          if ($("#"+_self.calendar_block_id+'_x_'+date_value).length === 0 && dates[i] > current_date){
            counters[0]++;
            _self.h_dates.push(dates[i]);
            $("#"+_self.calendar_block_id).append('<input type="hidden" id="'
              +_self.calendar_block_id
              +'_x_'+date_value+'" '
            +'name="eventdates[]" value="'+date_value+'" />');
          }
        if ($('#'+td_id) && dates[i] > current_date){
          $('#'+td_id).attr('class','highlighted');
        }
        counters[1] = $("#"+calendar_block_id+" table tbody tr td.highlighted").length;
      }
      _self.counters = counters;
      return counters;
    }
    
    if (_self.input_field_id !== null){
      this.$input_field.keyup(function(){
	var counters = _self.hightLightDates($(this).val());
	var $msg_node = _self.$calendar_msg_block;
	if ($msg_node.length){
	  $msg_node.html("");
	}
	if (counters[0] == -1){
	  $msg_node.append("<div class='msg_val'>занадто великий інтервал</div>");
	  $(this).css('border-color','rgb(255,0,0)');
	  return ;
	}
	for (var i = 0; (i < _self.h_dates.length && $msg_node.length); i++){
	  if (i === 0){
	    $msg_node.append("<div class='msg_val'>Обрані дати:</div>");
	    $msg_node.append(""
	      +MultiCalendar.prototype.padDigits(_self.h_dates[i].getDate(),2)+'.'
	      +MultiCalendar.prototype.padDigits((_self.h_dates[i].getMonth()+1),2)+'.'
	      +(_self.h_dates[i].getFullYear()));
	  } else {
	    $msg_node.append(""+", "
	      +MultiCalendar.prototype.padDigits(_self.h_dates[i].getDate(),2)+'.'
	      +MultiCalendar.prototype.padDigits((_self.h_dates[i].getMonth()+1),2)+'.'
	      +(_self.h_dates[i].getFullYear()));
	  }
	}
	if (_self.counters[0] > 0 && _self.counters[1] === 0){
	  $(this).css('border-color','rgb(255,225,0)');
	}
	if (_self.counters[0] > 0 && _self.counters[1] > 0){
	  $(this).css('border-color','rgb(0,225,0)');
	}
	if (_self.counters[0] === 0){
	  $(this).css('border-color','rgb(255,0,0)');
	}
      });
    }
    
    
    _self.putCalendar();
    if (_self.$input_field !== null){
      _self.$input_field.keyup();
    }
    
  };
  
  /**
   * Функція видалення пробільних символів спереду і позаду
   * @param {String} str вхідна строка
   * @returns {String}
  */
  MultiCalendar.prototype.trim1 = function(str) {
    return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
  }
  
  /**
   * Функція доповнення числа нулями спереду
   * @param {Number} number вхідне число
   * @param {Number} digits кількість десяткових знакомісць
   * @returns {String}
  */
  MultiCalendar.prototype.padDigits = function(number, digits) {
      return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
  }
  
  
  //////////////////////////////////////////////////////////////////////