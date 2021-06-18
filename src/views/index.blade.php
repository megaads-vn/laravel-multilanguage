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
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js" rel="stylesheet" type="text/css" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-css/1.4.6/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" rel="stylesheet" type="text/css" /> -->
    <link href="/lang-editor/resources/package.css" rel="stylesheet" type="text/css" />
    
    <script src="/lang-editor/resources/jquery.min.1.12.4.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/lang-editor/resources/select2.full.min.js" type="text/javascript"></script>
    <script src="/lang-editor/resources/jquery-ui.min.js" type="text/javascript"></script>
    <script>
        const USERNAME = "{{ Config::get('auth.basicAuthentication.username') }}";
        const PASSWORD = "{{ Config::get('auth.basicAuthentication.password') }}";
    </script>
</head>

<body>
    <div class="container">
        <div class="row" style="margin:15px 0px">
            <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                <h5>Multiple languge file editor</h5>
            </div>
        </div>
        <div class="row">
            <div class="add-key-btn js-add-key-btn">
                <img src="/lang-editor/resources/add_key.png" alt="Add new key" title="Add new key" />
            </div>
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
    @include('multi-language::modal')
</body>
<script type="text/javascript">
    var locale = "<?= isset($locale) ? $locale : 'en' ?>";
    var deleteRoute = "<?= route('frontend::mutilanguage::delete::item') ?>";
</script>
<script type="text/javascript" src="/lang-editor/resources/package.js" ></script>
</html>