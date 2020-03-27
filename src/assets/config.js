$(document).ready(function () {

    $.fn.select2.defaults.set("theme", "bootstrap");

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

    $('#reset-coupon').select2({
        placeholder: 'Search for a coupon...',
        ajax: {
            url: '/service/coupon/find',
            delay: 250,
            dataType: 'json',
            data: function (params) {
              var query = {
                title: params.term
              }
              return query;
            },
            beforeSend: function (xhr, settings) {
                var params = new URL(window.location.origin + settings.url);
                var check = params.searchParams.get("title");
                if(typeof(check) == 'undefined' || check == '' || check == null) {
                    return false;
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.result.data, function (item) {
                        return {
							text: item.title,
                            id: JSON.stringify(item)
						}
                    })
                };
            },
            cache: true
        }
    });

    $(document).on('change', '#reset-coupon', function () {
        if(typeof($(this).val()) != 'undefined' && $(this).val() != '' && $(this).val() != null) {
            var couponValue = JSON.parse($(this).val());
            $("#btn-reset-vote, #btn-save-coupon").val(couponValue.id);
            if(typeof(couponValue.voteUp) != 'undefined' && couponValue.voteUp != '' && couponValue.voteUp != null) {
                $("#vote-up").val(parseInt(couponValue.voteUp));
            } else {
                $("#vote-up").val(0);
            }
            if(typeof(couponValue.voteDown) != 'undefined' && couponValue.voteDown != '' && couponValue.voteDown != null) {
                $("#vote-down").val(parseInt(couponValue.voteDown));
            } else {
                $("#vote-down").val(0);
            }
        }
    });

    $(document).on('click', '#btn-save-coupon', function () {
        if (typeof ($(this).val()) != 'undefined' && $(this).val() != '' && $(this).val() != null) {
            var voteUp = $('#vote-up').val();
            var voteDown = $('#vote-down').val();
            if(typeof(voteUp) != 'undefined' && voteUp != '' && voteUp != null && typeof(voteDown) != 'undefined' && voteDown != '' && voteDown != null) {
                saveCoupon(this, { id: $(this).val(), voteUp: parseInt(voteUp), voteDown: parseInt(voteDown) });
            } else {
                alert('Enter value for VoteUp and VoteDown');
            }
        } else {
            alert('Please choose a coupon...');
        }
    });

    $(document).on('click', '#btn-reset-vote', function () {
        if(typeof($(this).val()) != 'undefined' && $(this).val() != '' && $(this).val() != null) {
            saveCoupon(this, { id: $(this).val(), voteUp: 0, voteDown: 0 });
        } else {
            alert('Please choose a coupon...');
        }
    });

    function saveCoupon(selector, data) {
        $(selector).button('loading');
        $.post('/service/coupon/reset-vote', data).success(function (response) {
            if (response.status == "successful") {
                $('#reset-coupon, #vote-up, #vote-down, #btn-reset-vote, #btn-save-coupon').val('');
                $('#reset-coupon').empty().trigger('change');
            } else {
                alert('Error. Please check again...');
            }
            $(selector).button('reset');
            return;
        });
    }
});