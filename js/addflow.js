$(function() {

    $('#filterDept').keyup(function() {
        /*
         * При введенні тексту у текстове поле (фільтр) 
         * показувати підрозділи.
         */
        var str = $('#filterDept').val();
        var collection;
        $('#check_all_dept').prop('checked', false);
        if (str === '') {
            $('.dept_item').hide();
            $('.dept_check').hide();
            $('.check_row').hide();
            return false;
        }
        if (str === '*') {
            $('.dept_item').show();
            $('.dept_check').show();
            $('.check_row').show();
            collection = $('.dept_item');
        } else {
            $('.dept_item').hide();
            $('.dept_check').hide();
            $('.check_row').hide();
            collection = $('.dept_item:contains("' + str + '")');
        }
        collection.each(
                function(index, elem) {
                    var id = getIdNum($(elem).attr('id'));
                    var checkbox = $('#group_' + id + '_check');
                    var checkrow = $('#check_row_' + id);
                    $(elem).show();
                    checkbox.show();
                    checkrow.show();
                }
        );
    });

    $('.dept_item').click(function() {
        /*
         * Відмітити підрозділ, натискаючи на текст його назви .
         */
        id = getIdNum($(this).attr('id'));
        checkbox = $('#group_' + id + '_check');
        checkbox.prop('checked', !checkbox.is(':checked'));

        //необхідно для того, щоб з'являлися мітки обраних
        checkbox.change();

    });

    $('#check_all_dept').click(function() {
        /*
         * Обрати або зняти усі видимі підрозділи.
         */
        var collection = $('.dept_item');
        //обрати усі блоки підрозділів
        collection.each(function(index, elem) {
            if ($(elem).is(":visible")) {
                //якщо видимий
                $('#' + $(elem).attr('id') + '_check').prop(
                        'checked', $('#check_all_dept').is(':checked'));

                //необхідно для того, щоб з'являлися мітки обраних
                $('#' + $(elem).attr('id') + '_check').change();

            }
        });
    });

    $('.dept_check').change(function() {
        /*
         *  Робить видимими мітки справа - обрані підрозділи.
         */
        var id = $(this).attr('id');
        var id_num = getIdNum(id);
        $('#group_' + id_num).css('background-color', (
                $(this).is(':checked')) ? '#AABBCC' : '#f5f5f5');
        $('#group_' + id_num).css('border', (
                $(this).is(':checked')) ? '2px solid black' : '1px solid grey');
        //у мене був поганий настрій (
        label = document.getElementById('dept_label_' + id_num);
        label.style.display = ($(this).is(':checked')) ? 'block' : 'none';
        $('#select_group_checkbox').attr('checked', false);
        $('#idDocFlowGroupSelected').css('display', 'none');
    });

    $('#check_save_group').change(function() {
        /*
         * Метод при події зміни стану прапорця 
         * "Зберегти групу розсилки з назвою".
         */
        $('#DocFlowGroupName_text').slideToggle();
        $('#select_group_checkbox').attr('checked', false);
        $('#idDocFlowGroupSelected').slideUp();

    });

    $('#select_group_checkbox').change(function() {
        /*
         * Метод при події зміни стану прапорця 
         * "Вибрати існуючу групу для розсилки".
         */
        //Показуємо випадаючий список з назвами груп
        $('#idDocFlowGroupSelected').slideToggle();
        //якщо прапорець "Зберегти групу розсилки з назвою" встановлено
        if ($('#check_save_group').is(':checked')) {
            //текст для введення назви групи плавно сховати
            $('#DocFlowGroupName_text').slideUp();
        }
        //скинути прапорець "Зберегти групу розсилки з назвою"
        $('#check_save_group').attr('checked', false);
        //якщо поточний прапорець "Вибрати існуючу групу для розсилки" встановлено
        if ($(this).is(':checked')) {
            //виклик методу при зміні стану випадаючого списка з назвами груп
            $('#idDocFlowGroupSelected').change();
        }
    });


    $('#idDocFlowGroupSelected').change(function() {
        /*
         * Виділення визначених підрозділів при виборі існуючої групи розсилки
         * з використанням асинхронного підвантаження ID.
         */
        var group_id = $(this).attr('value');
        $('#check_all_dept').prop('checked', false);
        $.ajax({
            type: 'GET',
            url: '../docflowgroups/depts'
                    + '/' + group_id
        }).done(function(data) {
            depts = JSON.parse(data);
            $('.dept_check').attr('checked', false);
            $('.dept_item').css('background-color', '#f5f5f5');
            $('.dept_item').css('font-weight', 'normal');
            $('.dept_item').css('border', '1px solid grey');
            $('.chosen_depts').hide();
            for (var i = 0; i < depts.length; i++) {
                $('#group_' + depts[i] + '_check').attr('checked', 'true');
                $('#group_' + depts[i]).css('background-color', '#AABBCC');
                $('#group_' + depts[i]).css('font-weight', 'normal');
                $('#group_' + depts[i]).css('border', '2px solid black');
                label = document.getElementById('dept_label_' + depts[i]);
                if (label) {
                    label.style.display = 'block';
                }
            }
        });
    });

    $("#DocumentsField").select2({
        /*
         * Метод для асинхронного формування списку документів (select2 поле)
         */
        placeholder: "Пошук документів",
        multiple: true,
        quietMillis: 200,
        //minimumInputLength: 3,
        ajax: {
            url: "../documents/select2",
            dataType: 'json',
            data: function(term, page) {
                return {
                    q: term // search term
                };
            },
            results: function(data, page) {
                return {results: data};
            }
        }
    });
    $('#add_docflow_button').click(function(){
      /*
       * Метод оброблює натискання на кнопку "Ініціювати"
       */
      var btn = $(this);
      btn.button('loading'); // call the loading function
      setTimeout(function() {
          btn.button('reset'); // call the reset function
      }, 5000);
    });
    
    $('#createflow-form').submit(function(event){
      /*
       * Валідація полів перед відправкою форми
       */
      // к-сть обраних підрозділів (встановлених прапорців)
      var numberOfChecked = $('.dept_check:checked').length;
      var ret = true;
      if (numberOfChecked === 0){
        alert('Необхідно обрати підрозділи');
        event.preventDefault();
        ret = false;
      }
      if ($('#Docflows_DocFlowName').val().length === 0){
        alert('Назва документообігу не може бути порожньою');
        event.preventDefault();
        ret = false;
      }
      //а тут зроблено хитро: підрахунок усіх "хрестиків" у select2-полі
      if ($('a.select2-search-choice-close').length === 0 && $('#s2id_DocumentsField').length){
        alert('Необхідно обрати документи');
        event.preventDefault();
        ret = false;
      }
      return ret;
    });

});



function getIdNum(id) {
    /* 
     * Повертає рядок, що містить усі цифри - необхідно для логічної взаємодії 
     * елементів (один ІД залежить від іншого, а всі вони мають однакове число).
     */
    var id_num = '';
    for (var i = 0; i < id.length; i++) {
        if (id[i] >= 0 && id[i] <= 9)
            id_num += id[i];
    }
    return id_num;
}