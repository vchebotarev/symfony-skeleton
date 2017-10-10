
$(document).ready(function(){

    //отправка сообщения
    var form_message_create = $('form.chat-message-create');
    form_message_create.find('textarea').keydown(function (e) {
        if ((e.ctrlKey || e.metaKey) && (e.keyCode === 13 || e.keyCode === 10)) {
            form_message_create.trigger('submit');
        }
    });
    form_message_create.submit(function(e){
        e.preventDefault();
        var submit = $(this).find('button[type=submit]');
        var textarea = $(this).find('textarea');
        var fg = textarea.closest('.form-group');
        if ($.trim(textarea.val()) === '') {
            return;
        }
        submit.attr('disabled', 'disabled');
        textarea.attr('readonly', 'readonly');
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json'
        }).done(function(data){

            if (data.success === true) {
                fg.removeClass('is-invalid');
                $('.chat-message-list').append(data.message);
                $('.chat-list-empty').addClass('d-none');
                textarea.val('');
            } else {
                fg.addClass('is-invalid');
                fg.find('.invalid-feedback').html('');
                fg.find('.invalid-feedback').html(data.error);
            }
        }).fail(function(){
            fg.addClass('is-invalid');
            fg.find('.invalid-feedback').html('Ошибка отправки сообщения');
        }).always(function () {
            submit.removeAttr('disabled');
            textarea.removeAttr('readonly');
        });
    });

    //удаление сообщений
    $(document).on('click', '.chat-message-delete', function (e) {
        var chat_message_block = $(this).closest('.chat-message-list-item');
        var id = chat_message_block.data('id');
        $.ajax({
            method: 'POST',
            url: Routing.generate('app_private_chat_message_delete', {'id': id}),
            success: function() {
                chat_message_block.remove();
                if ($('.chat-message-list-item').length === 0 && $('.chat-message-list-more').is(':hidden')) {
                    $('.chat-list-empty').removeClass('d-none');
                    //window.location.replace(Routing.generate('app_private_chat_list'));
                }
            },
            error: function() {
                alert('ошибка удаления');
            }
        });
    });

    //удаление чата
    $(document).on('click', '.chat-delete', function (e) {
        var chat_block = $(this).closest('.chat-list-item');
        var id = chat_block.data('id');
        $.ajax({
            method: 'POST',
            url: Routing.generate('app_private_chat_delete', {'id': id}),
            success: function() {
                chat_block.remove();
                if ($('.chat-list-item').length === 0) {
                    var clm = $('.chat-list-more');
                    if (clm.is(':hidden')) {
                        $('.chat-list-empty').removeClass('d-none');
                    } else {
                        clm.trigger('click');
                    }
                }
            },
            error: function() {
                alert('ошибка удаления');
            }
        });
    });

    //подгрузка старых чатов
    $(document).on('click', '.chat-list-more', function (e) {
        if ($(this).hasClass('disabled')) {
            return;
        }
        var clm  = $(this);
        var page = $(this).data('page');

        clm.addClass('disabled');
        $.get(Routing.generate('app_private_chat_list', {'page': page}), function (data) {
            $('.chat-list').append(data.html);
            clm.removeClass('disabled');
            if (data.total_count > $('.chat-list-item').length + data.count) {
                clm.removeClass('d-none');
                clm.data('page', page + 1);
            } else {
                clm.addClass('d-none');
            }
        }, 'json');
    });

    //подгрузка старых сообщений
    $(document).on('click', '.chat-message-list-more', function (e) {
        if ($(this).hasClass('disabled')) {
            return;
        }
        var clm  = $(this);
        var lastId = $('.chat-message-list').find('.chat-message-list-item:first-of-type').data('id');
        clm.addClass('disabled');
        $.get(Routing.generate('app_private_chat_message_list', {'chatId': clm.data('chat-id'), 'to': lastId}), function (data) {
            $('.chat-message-list').prepend(data.html);
            clm.removeClass('disabled');

            if (data.total_count > $('.chat-message-list-item').length + data.count) {
                clm.removeClass('d-none');
            } else {
                clm.addClass('d-none');
            }
        }, 'json');
    });

    //изменение статуса прочитанности моих сообщений
    $(document).ready(function(){
        //todo timer
    });

    //прочитать сообщения, отправленные мне
    $(document).ready(function(){
        //todo
    });

    //подгрузка новых сообщений
    $(document).ready(function(){
        //todo
    });

});
