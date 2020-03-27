<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Config - freeshipping</title>
    <link rel="shortcut icon" sizes="16x16" href="/images/logo.svg?" type="image/x-icon" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-css/1.4.6/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/multi-language/config.js?' }}" type="text/javascript"></script>
    <style>
        .clickable,
        .clickedit {
            cursor: pointer;
        }

        .panel-heading span {
            margin-top: -20px !important;
            font-size: 15px;
        }

        .vertical-align {
            vertical-align: middle !important;
        }
    </style>
    <script>
        const USERNAME = "{{ Config::get('app.basicAuthentication.username') }}";
        const PASSWORD = "{{ Config::get('app.basicAuthentication.password') }}";
    </script>
</head>

<body>
    <div class="container">
        <div class="row" style="margin:15px 0px">
            <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                <center></center>
                <h5>Multiple languge file editor</h5>
            </div>
        </div>
        <div class="row">

            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-hand-right"></span>
                            <span class="lable-name-store" style="display: block;  margin-left: 25px; padding-top:5px;">
                                <span class="title-store">&nbsp;</span>
                            </span>
                        </h3>
                        <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover table-bordered table-store-for-search">
                            <thead>
                                <tr>
                                    <th class="col-md-1 text-center vertical-align">Index</th>
                                    <th class="col-md-5 text-center vertical-align">Key</th>
                                    <th class="col-md-6 text-center vertical-align">Value</th>
                                    <th class="col-md-6 text-center vertical-align">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ( !empty($objectContent) )
                                @php $index = 1; @endphp
                                @foreach($objectContent as $key => $item )
                                <tr>
                                    <td class="text-center vertical-align"><?= $index ?></td>
                                    <td class="vertical-align"><?= $key ?></td>
                                    <td class="text-center vertical-align lang-value" data-value="<?= $key ?>">
                                        <span class="lang-text"><?= $item ?></span>
                                        <input id="lang-editor" type="text" style="display:none" name="editor" class="form-control" value="<?= $item ?>" />
                                    </td>
                                    <td>
                                        <button id="js-btn-delete" class="btn btn-danger" data-key="<?= $key ?>"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                @php $index++; @endphp
                                @endforeach
                                @else
                                <tr class="config-coupon-nodata">
                                    <td colspan="3">No data...</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<style>
    .lang-text {
        display: block;
        width: 100%;
        height: auto;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
    }
</style>
<script type="text/javascript">
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
                    key: indexValue,
                    value: textValue
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
    $(document).on('click', '#js-btn-delete', function() {
        var isDelete = confirm('Are you sure to delete this item?');
        if (isDelete) {
            var dataKey = $(this).attr('data-key');
            $.ajax({
                url: '<?= route('frontend::mutilanguage::delete::item') ?>',
                type: 'delete',
                data: {
                    key: dataKey
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
</script>

</html>