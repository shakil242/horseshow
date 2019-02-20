    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <html>

    <head>

    <style>

        @page{

            height: auto;

        }

        h1 {
            margin:3px auto;
            font-size:18px;
            color:blue;
        }
        p {
            background:#fefeda;
            text-align:left;
        }
        a {
            border:1px dotted #01da02;
            font-size:16px;
            padding:4px;
        }
        table th
        {
            color: #FFF;
            font-size: 12px;
            background-color: #8593a3 !important;
            color: #FFF;
            padding: 11px 5px;
        }
        table td
        {
            color: #FFF;
            font-size: 12px;
            background-color: #ffffff !important;
            color: #333333;
            padding: 11px 5px;
            border:1px solid gray;
        }
        table{
            border-collapse: collapse;
            border:1px solid gray;
            background-color: #FFF;
            font-size: 16px;
            color: #39424C;
            width:100%;
        }
        body{
            margin: 0;
            padding: 0;
            font-family:arial;
            font-size: 18px;
        }

    </style>
    </head>

    <body>
    <div class="container">
    <div class="white-box">

    <table style="border: none" border="0">

    <tr>
    <?php $src = public_path() . '/adminstyle/images/logo.png';?>
    <td colspan="2" style="border: none;padding: 5px; text-align: justify" id="header">
    <a href="{{url('/')}}" class="logo"><img width="60" alt="logo" src="{{$src}}"></a>
    </td>
    </tr>
    <tr>
    <td style="padding: 5px; text-align: justify"><strong>Submitted By</strong></td>
    <td style="padding: 5px; text-align: justify">{{ getUserNamefromid($Asset->user_id) }}</td>
    </tr>
    {{--<tr>--}}
    {{--<td style="padding: 5px; text-align: justify"><strong>Asset Name</strong></td>--}}
    {{--<td style="padding: 5px; text-align: justify">@if($Asset->participant) {{GetAssetNamefromId($Asset->participant->asset_id) }} @else {{GetAssetNamefromId($Asset->asset_id) }} @endif</td>--}}
    {{--</tr>--}}
    @if($formid!='')
        <tr>
        <td style="padding: 5px; text-align: justify"><strong>Event Name</strong></td>
        <td style="padding: 5px; text-align: justify">{{ getShowName($show_id) }}</td>
        </tr>
    @endif
    <tr>
    <td style="padding: 5px; text-align: justify"><strong>Registered Date</strong></td>
    <td style="padding: 5px; text-align: justify">{{  getDates($Asset->created_at) }}</td>
    </tr>

    </table>


    @if($FormTemplate !== null)
    <div class="participants-responses">
    <div class="TD-main-container">
    @if(!empty($TD_variables["TD_logo_image"]))
    <!-- TD_logo_position = 1 TOP Position of the logo -->
    @if($TD_variables["TD_logo_position"] == 1)
    <div class="col-xs-12">
    <table width="100%" style="width: 100%; border: none">
    <tr>
    <td class="form-group {{ $TD_variables['TD_logo_allignment']}}">
        <img src="{{ getImageS3($TD_variables['TD_logo_image']) }}"
             width="{{$TD_variables['TD_logo_resolution_width']}}"
             height="{{$TD_variables['TD_logo_resolution_hight']}}">
    </td>
    </tr>
    </table>
    </div>
    @endif
    <!-- END: TD_logo_position = 1 TOP Position of the logo -->
    @endif

    <!-- Number of forms in Master Template to be previewed -->

    <?php
    //dd($answer_fields);
    if (isset($answer_fields)) {
    $duplicate = array_where($answer_fields, function ($value, $key) {
    return isset($value['duplication_batch']);
    });
    }
    ?>

    <div class="custom-class">
    <table width="100%" style="width: 100%; border: none">
    <tr>
    <td style="text-align: center" class="{{ $TD_variables['TD_title_font_allignment']}}"><h3>{{$FormTemplate->name }}</h3></td>
    </tr>
    <div  style="{{$TD_variables['TD_field_font_size']}}{{$TD_variables['TD_field_font_color']}}">
    <!-- Start: Field Loop $pre_fields -->
    <?php //ArrayPrint($pre_fields); ?>
    @if(is_array($pre_fields))
    @foreach ($pre_fields as $key => $field)

    <!-- Edit saved form -->
    <?php
    $disk = getStorageDisk();
    $answer = "";
    $search_location = "";
    $place_id = "";
    $latitude = "";
    $longitude = "";
    $address = "";
    $signatureImg = "";
        $NumericAnswer = "";

    //ArrayPrint($answer_fields);
    if (isset($answer_fields)) {
        $indexer = array_search($field['unique_id'], array_column($answer_fields, 'form_field_id'));
        if (isset($answer_fields[$indexer]['signatureImg'])) {
            $signatureImg = $answer_fields[$indexer]['signatureImg'];
        }
        if ($answer_fields[$indexer]['form_field_type'] == OPTION_IMAGE) {
            if (isset($answer_fields[$indexer]["upload_files"])) {
                $answer = $answer_fields[$indexer]['upload_files'];
            }
        } elseif ($answer_fields[$indexer]['form_field_type'] == OPTION_ATTACHMENT || $answer_fields[$indexer]['form_field_type'] == OPTION_VIDEO) {
            if (isset($answer_fields[$indexer]["upload_filess"])) {
                $answer = $answer_fields[$indexer]['upload_filess'];
            }
        } elseif ($answer_fields[$indexer]['form_field_type'] == OPTION_NUMARIC) {
        if (isset($answer_fields[$indexer]["answer"])) {
        $NumericAnswer = $answer_fields[$indexer]['answer'];
        }
        } else {


            if (isset($answer_fields[$indexer]['answer'])) {
                $answer = $answer_fields[$indexer]['answer'];
                if ($answer_fields[$indexer]['form_field_type'] == OPTION_ADDRESS_MAP) {
                    $search_location = $answer_fields[$indexer]['search_location'];
                    $place_id = $answer_fields[$indexer]['place_id'];
                    $latitude = $answer_fields[$indexer]['latitude'];
                    $longitude = $answer_fields[$indexer]['longitude'];
                    $address = $answer_fields[$indexer]['address'];
                }
            }

        }
    } else {
        $answer = "";
    }

        ?>
        @if($field["form_name"]!='Video')
    <div class="form-group">

        <table width="100%" style="width: 100%">
            <tr>
                <td style="width:20%">
                    <label>{{$field["form_name"]}} </label>
                </td>
                <td style="width:80%">

                    <div class="col-sm-8">
                        <?php if($field['form_field_type'] == OPTION_TEXT){ ?>
                        <p>{{ $answer }}</p>
                        <?php } ?>
                        <?php if($field['form_field_type'] == OPTION_TEXTAREA){ ?>
                        <p>{{ $answer }}</p>
                        <?php } ?>
                        <?php if($field['form_field_type'] == OPTION_DATE_PICKER){ ?>
                        <p>{{ $answer }}</p>
                        <?php } ?>
                        <?php if($field['form_field_type'] == OPTION_TIME_PICKER){ ?>
                        <p>{{ $answer }}</p>
                        <?php } ?>
                        <?php if($field['form_field_type'] == OPTION_RATINGS){ ?>
                        <p>{{$answer}}</p>
                        <?php } ?>
                        <?php if($field['form_field_type'] == OPTION_EMAIL){ ?>
                        <p>{{ $answer }}</p>
                        <?php } ?>
                        <?php if($field['form_field_type'] == OPTION_MONETERY){ ?>
                        <div class="input-group col-sm-5">
                            <span class="input-group-addon">$</span>
                            <span>{{ $answer }}</span>
                        </div>
                        <?php } ?>
                        <?php if($field['form_field_type'] == OPTION_SIGNATURE){ ?>

                        @if($signatureImg)
                            <img src="{{ $signatureImg }}">
                        @endif

                        <?php } ?>
                        <?php if($field['form_field_type'] == OPTION_NUMARIC){ ?>
                        <span>{{ $NumericAnswer }}</span>
                        <?php } ?>
                        <?php if($field['form_field_type'] == OPTION_ADDRESS_MAP){ ?>
                        <div class="col-sm-12">

                            <div class="input-group">

    <span class="input-group-addon text-red" onclick="js:initialize();" data-placement="left" data-toggle="tooltip"
    data-title="Load Map">
    <i class="fa fa-fw fa-lg fa-map-marker"></i>
    </span>

                            </div>
                            <div id="map_wrapper">
                                <!--<input id="pac-input" class="controls" type="text" placeholder="Search Box">-->
                                <div id="map_canvas" class="mapping"></div>
                            </div>
                        </div>
                        <script src="{{ asset('/js/google-map-script-form.js') }}"></script>

                        <?php } ?>
                        <?php if( $field['form_field_type'] == OPTION_RADIOBUTTON){ ?>
                        @if( isset($field['form_field_options'] ))
                            <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">

                                <?php if (isset($indexer)) {
                                    echo AddPDFOptionsFrontend($field['form_field_options'], $field, $answer_fields[$indexer]);
                                } else {
                                    echo AddPDFOptionsFrontend($field['form_field_options'], $field);
                                }

                                ?>
                            </div>
                        @endif
                        <?php } ?>

                            <?php if(
                            $field['form_field_type'] == OPTION_CHECKBOX ||
                            $field['form_field_type'] == OPTION_DROPDOWN ||
                            $field['form_field_type'] == OPTION_AUTO_POPULATE ||
                            $field['form_field_type'] == OPTION_LABEL ||
                            $field['form_field_type'] == OPTION_HYPERLINK
                            ){ ?>
                            @if( isset($field['form_field_options'] ))
                                <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">

                                    <?php if (isset($indexer)) {
                                        echo AddPDFOptionsFrontend($field['form_field_options'], $field, $answer_fields[$indexer]);
                                    } else {
                                        echo AddPDFOptionsFrontend($field['form_field_options'], $field);
                                    }

                                    ?>
                                </div>
                            @endif
                            <?php } ?>


                    <!-- Video -->

                        <!-- Image -->
                        <?php if($field['form_field_type'] == OPTION_IMAGE){ ?>
                        @if( isset($field['form_field_options'] ))
                            @if( $field['form_field_options'][1]['upload_files'] != "")
                                <div class="form-group">

                                            <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?>
                                            <img src="{{ getImageS3($field['form_field_options'][1]['upload_files']) }}"
                                                 height="240"/>
                                    <!-- Popup -->
                                </div>
                                @endif
                                @endif
                        <?php } ?>
                    <!-- Attachment -->


                    </div>


                </td>
            </tr>
        </table>


    </div>
    @endif
    @endforeach
    @endif
    <!-- End: Field Loop $pre_fields -->
    </div>
            @if(isset($duplicate))
            <tr>
                <table width="100%">
                @foreach ($duplicate as $key => $field)

                    @if(isset($field['DF_title']))
                        <tr>
                            <td style="text-align: center;">
                                <h3>{{$field['DF_title']}}</h3>
                            </td>
                        </tr>
                    @else
                        <?php
                        $disk = getStorageDisk();
                        $answer = "";
                        $required = "";

                        $indexer = array_search($field['duplicated_from'], array_column($pre_fields, 'unique_id'));
                        $question = $pre_fields[$indexer];
                        if ($field['form_field_type'] == OPTION_IMAGE) {
                            if (isset($field["upload_files"])) {
                                $answer = $field["upload_files"];
                            }
                        } else {
                            if (isset($field['answer'])) {
                                $answer = $field['answer'];
                            }

                        }

                        ?>
                            @if($field["form_name"]!='Video')
                             <tr>
                            <td>

                                <table width="100%" style="width: 100%">

                                    <tr>
                                        <td style="width:20%">
                                            <label>{{$field["form_name"]}} </label>
                                        </td>

                                        <td style="width:80%">
                                            <div class="col-sm-8">
                                                <?php if($field['form_field_type'] == OPTION_TEXT){ ?>
                                                <p>{{ $answer }}</p>
                                                <?php } ?>
                                                <?php if($field['form_field_type'] == OPTION_TEXTAREA){ ?>
                                                <p>{{ $answer }}</p>
                                                <?php } ?>
                                                <?php if($field['form_field_type'] == OPTION_DATE_PICKER){ ?>
                                                <p>{{ $answer }}</p>
                                                <?php } ?>
                                                <?php if($field['form_field_type'] == OPTION_TIME_PICKER){ ?>
                                                <p>{{ $answer }}</p>
                                                <?php } ?>
                                                <?php if($field['form_field_type'] == OPTION_RATINGS){ ?>
                                                <p>{{ $answer }}</p>
                                                <?php } ?>
                                                <?php if($field['form_field_type'] == OPTION_EMAIL){ ?>
                                                <p>{{ $answer }}</p>
                                                <?php } ?>
                                                <?php if($field['form_field_type'] == OPTION_MONETERY){ ?>
                                                <div class="input-group col-sm-5">
                                                    <span class="input-group-addon">$</span>
                                                    <p>{{ $answer }}</p>
                                                </div>
                                                <?php } ?>
                                                <?php if($field['form_field_type'] == OPTION_SIGNATURE){ ?>
                                                <div class="input-group col-sm-10">
                                                    @if($signatureImg)
                                                        <img src="{{ $signatureImg }}">
                                                    @endif
                                                </div>
                                                <?php } ?>
                                                <?php if($field['form_field_type'] == OPTION_NUMARIC){ ?>
                                                <p>{{ $answer }}</p>
                                                <?php } ?>
                                                <?php if( $field['form_field_type'] == OPTION_RADIOBUTTON ||
                                                $field['form_field_type'] == OPTION_CHECKBOX ||
                                                $field['form_field_type'] == OPTION_DROPDOWN ||
                                                $field['form_field_type'] == OPTION_AUTO_POPULATE ||
                                                $field['form_field_type'] == OPTION_LABEL ||
                                                $field['form_field_type'] == OPTION_HYPERLINK
                                                ){ ?>

                                                <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">
                                                    <?php
                                                    if (isset($indexer)) {
                                                        echo AddPDFOptionsFrontend($pre_fields[$indexer]['form_field_options'], $pre_fields[$indexer], $field);
                                                    }
                                                    ?>
                                                </div>

                                                <?php } ?>
                                            <!-- Video -->
                                                <?php if($field['form_field_type'] == OPTION_IMAGE){ ?>
                                                @if( isset($question['form_field_options'] ))
                                                    @if( $question['form_field_options'][1]['upload_files'] != "")
                                                        <div class="form-group">
                                                            <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                                                                <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?>
                                                                <img src="{{ getImageS3($question['form_field_options'][1]['upload_files']) }}"
                                                                     height="240"/>
                                                                </a>
                                                            </div>
                                                            <!-- Popup -->
                                                        </div>
                                                        @endif
                                                        @endif

                                                    <?php } ?>
                                                    <!-- Attachment -->
                                            </div>
                                        </td>
                                    </tr>


                                </table>
                            </td>
                        </tr>
                            @endif
                    @endif
                @endforeach
                </table>
            </tr>
            @endif

    </table>

    <!-- END: TD_logo_position = 1 TOP Position of the logo -->
    @if(!empty($TD_variables["TD_logo_image"]))
    <!-- TD_logo_position = 2 Bottom Position of the logo -->
    @if($TD_variables["TD_logo_position"] == 2)
    <div class="col-xs-12">
    <div class="row">
    <div class="form-group {{ $TD_variables['TD_logo_allignment']}}">
        <img src="{{ getImageS3($TD_variables['TD_logo_image']) }}"
             width="{{$TD_variables['TD_logo_resolution_width']}}"
             height="{{$TD_variables['TD_logo_resolution_hight']}}">
    </div>
    </div>
    </div>
    @endif
    <!-- END: TD_logo_position = 2 Bottom Position of the logo -->
    @endif
    </div>

    </div>
    @else
    <div class="col-xs-12">
    <div class="row">
    {{NO_FORM_MESSAGES}}
    </div>
    </div>
    @endif

    </div>
    </div>
    </div>
    </body>
    </html>