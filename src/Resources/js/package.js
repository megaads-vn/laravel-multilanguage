   

var createSuccess = function(result) {
    var className = '';
    if (result.status == 'successful') {
        className = 'success';
        $('.message').html(result.message);
        $('#language_key').val('');
        $('#language_value').val('');
        $('#add-key-modal').modal('toggle');
        location.reload();
    } else {
        className = 'error';
        $('.message').html(result.message);
    }
    $('.message').addClass(className);
    setTimeout(function() {
        $('.message').removeClass(className);
    }, 3000);
}


$(document).ready(function () {

    $.fn.select2.defaults.set("theme", "bootstrap");

    $('.lang-text').click(function() {
        $('input[type=text]').hide();
        $('span').show();
        $(this).hide();
        $(this).parent().find('#lang-editor').show();
        $(this).parent().find('#lang-editor').focus();
    });

    $("input").on("keypress", function() {
        var keycode = event.keyCode || event.which;
        var textInput = $(this);
        var parentInput = $(this).parent();
        if (keycode == '13') {
            var textValue = $(this).val();
            var indexValue = $(this).parent().attr('data-value');
            $.ajax({
                url: "/lang-editor",
                type: "post",
                data: {
                    key: JSON.stringify(indexValue),
                    value: textValue,
                    locale: locale
                },
                success: function(result) {
                    if (result.status == 'successful') {
                        textInput.val('');
                        parentInput.children('.lang-text').text(result.data);
                        $('input[type=text]').hide();
                        $('span').show();
                    }
                }
            });
        }
    });

    $.ajaxSetup({
        headers: {
            'Authorization': "Basic " + btoa(USERNAME + ":" + PASSWORD)
        }
    });

    $(document).on('click', '.panel-heading span.clickable', function (e) {
        var $this = $(this);
        if (!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });
});

$(document).on('click', '#js-btn-delete', function() {
    var isDelete = confirm('Are you sure to delete this item?');
    if (isDelete) {
        var dataKey = $(this).attr('data-key');
        $.ajax({
            url: deleteRoute,
            type: 'delete',
            data: {
                key: JSON.stringify(dataKey),
                locale: locale
            },
            success: function(result) {
                if (result.status == 'successful') {
                    location.reload();
                }
            }
        }); 
    }
});

$(document).keydown(function(e) {
    var code = e.keyCode || e.which;
    if (code == '27') {
        $('input[type=text]').hide();
        $('span').show();
    }
});

$('.js-add-key-btn').click(function() {
    $('#add-key-modal').modal();
});

$('.js-btn-save').click(function () {
    if ($('#language_key').is(':hidden')) {
        $('#language_key').show();
    }
    if ($('#language_value').is(':hidden')) {
        $('#language_value').show();
    }
    $('#add-key-form').submit();
});

$('#add-key-form').submit(function(e) {
    e.preventDefault();
    var langKey = $('#language_key').val();
    var langValue = $('#language_value').val();
    var endpoint = 'lang-editor/add-key';
     var params = {
        key: JSON.stringify(langKey.trim()),
        value: langValue.trim(),
        locale: locale
    }
    ajaxRequest(endpoint, params, 'POST',createSuccess);
});

$('.js-select-locale').change(function() {
    var baseUrl = window.location.origin;
    var lc = $(this).val();
    window.location = `${baseUrl}/lang-editor?locale=${lc}`;
});

ajaxRequest = function(endpoint, params, method, callBack, element) {
    if (typeof method == 'undefined') {
        method = 'POST';
    }
    $.ajax({
        url: endpoint,
        type: method,
        data: params,
        success: function(result) {
            if (typeof callBack == 'function') {
                callBack(result, element);
            }
        }
    });
}
