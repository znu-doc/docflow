var DOC = DOC || {};

/**
 * Асинхронно відкриває модальне вікно анкетування.
 * @param {String} url
 * @returns {Boolean}
 */
DOC.AnnoyingBlank = function(url) {
    $("#AnnoyingBlank-modal-holder").load(url, function() {
        console.log($("#AnnoyingBlankModal").modal("show"));
        return false;
    });
};

/**
 * Виклик WebKit-нотифікації.
 * @param {String} name назва розсилки
 * @param {String} who підрозділ - ініціатор розсилки
 * @returns {Boolean}
 */
DOC.Notification = function(name, who, url) {
    _url = url
    try {
    Notification.requestPermission( function(status) {
      console.log(status); // notifications will only be displayed if "granted"
      var n = new Notification("Нова розсилка!", {body: who}); // this also shows the notification
      n.onclick = function (){
        window.open(_url, '_blank');
      }
    });
    } catch (e){
      console.log(e);
    }
    return true;
};

/**
 * Асинхронно робить виклики WebKit-нотифікації для кожної розсилки.
 * @param {String} url
 * @returns {undefined}
 */
DOC.AjaxNotificationCheck = function(url) {
    $.ajax({
        type: "POST",
        url: url,
        success: function(msg) {
            if (msg) {
                try {
                    datum=JSON.parse(msg);
                } catch(e) {
                    console.log(e);
                }
                for (var i = 0; i < datum.length; i++) {
                    DOC.Notification(datum[i].DocFlowName, datum[i].DeptartmentName, datum[i].Url);
                }
            }
        }
    });
};

/**
 * Асинхронно робить виклики WebKit-нотифікації для кожної розсилки.
 * @param {String} url
 * @param {Integer} period
 * @returns {undefined}
 */
DOC.NotificationCheckWithPeriod = function(url,period) {
  if (typeof ($.notification) === "function") {
    DOC.AjaxNotificationCheck(url);
    var timer = setInterval(function() {
      DOC.AjaxNotificationCheck(url);
    }, period); //асинхронні оновлення кожні 15 хвилин
  }
};

/**
 * Затримка після натиснення на кнопку з ID = id
 * @param {String} id
 * @returns {undefined}
 */
DOC.ButtonDelay = function (id){
  var btn = $('#' + id);    
  btn.button('loading'); // call the loading function
  setTimeout(function() {
      btn.button('reset'); // call the reset function
  }, 6000);
};

/**
 * Мигання усіх елементів класу class_name
 * @param {String} class_name
 * @returns {undefined}
 */
DOC.blinking = function(class_name){
  _class = '.' + class_name;
  $(document).ready(function(){
    $.fn.wait = function(time, type){
      time = time || 10;
      type = type || "fx";
      return this.queue(type, function(){
        var self = this;
        setTimeout(function(){
          $(self).dequeue();
        }, time);
      });
    };
    function runIt() {
      $(_class).wait()
        .animate({"opacity": 0.1},1200)
        .wait()
        .animate({"opacity": 1},300,runIt);
    }
    runIt();
  });
};
  
DOC.show_hide = function(id_showhide) {
    $('#' + id_showhide).toggle();
};
