@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Auto populate select multiple-->
@endsection

@section('main-content')
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = getFormNamefromid($form_id)." Assoicate Modules";
          $added_subtitle = Breadcrumbs::render('master-template-assets-modules', $data = ['template_id' => $template_id,'asset_id' => $form_id]);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">  
          <div class="row">
                <!-- <div class="col-md-12">
                    <div class="back"><a onclick="history.go(-1);" class="back-to-all-modules" href="#"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a></div>
                </div> -->
            </div>
            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                                @if($modules)

                                {{ Form::open(array('url' => 'master-template/template/submit/modules')) }}

                                <input type="hidden" value="{{$form_id}}" name="form_id">
                                <input type="hidden" value="{{$template_id}}" name="template_id">

                                <table  class="table-bordered table table-condensed">
                                    <thead>
                                    <tr>
                                        <th style="width: 40%" >Module Name</th>
                                        <th class="short-one">
                                            <label>
                                                <input class="readCheckBox" type='checkbox' onchange="CheckAll($(this),'readOnly');" /> 
                                                <span>Read Only</span>
                                            </label>
                                        </th>
                                        <th class="short-one">
                                            <label>
                                                <input class="writeCheckBox" class="readOnly" type='checkbox' onchange="CheckAll($(this),'readWrite');" />
                                                <span>Read &amp; Write</span>
                                            </label>
                                        </th>
                                        <th class="short-one">
                                            <label>
                                                <input class="noAccessCheckBox" type='checkbox' onchange="CheckAll($(this),'noAccess');" />
                                                <span>No Access</span>
                                            </label>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($modules as $module)
                                        @if($module->childModule->count() > 0 && is_null($module->parentModule))
                                            <tbody class="labels">
                                            <tr>
                                                <td colspan="5"  class="ParentShow">
                                                    <label for="accounting">{{$module->name}}</label>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tbody class="child">
                                            @foreach($module->childModule as $mod)
                                                 @if($mod->childModule->count()>0)

                                                      
                                                        <tr >
                                                            <td colspan="5" style=" width: 100%; padding-left: 30px;"  >

                                                                <label for="accounting" style="font-weight: normal">{{$mod->name}}</label>
                                                            </td>
                                                        </tr>
                                                
                                            <tbody>
                                            @foreach($mod->childModule as $m)
                                                        <tr>
                                                            <td><storng class="visible-xs">Module Name</storng>
                                                                <span class="childClass" style="padding-left: 50px;">{{$m->name}}</span><span style="font-size: 11px;"> (Form)</span></td>
                                                            <td>
                                                                
                                                                <label>  
                                                                <input type="radio" class="radioButton readOnly"
                                                                       @if (in_array($m->id, $readOnlyArray)) checked="checked" @endif name="module[{{$module->id}},{{$mod->id}},{{$m->id}}]" value="1" />
                                                                <span><storng class="">Read Only</storng></span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                            <label> 
                                                                <storng class="visible-xs">Read &amp; Write</storng>
                                                                <input type="radio" class="radioButton readWrite" @if (in_array($m->id, $modulesArray)) checked="checked" @endif
                                                                name="module[{{$module->id}},{{$mod->id}},{{$m->id}}]" value="2"  />
                                                                <span><storng class="">Read &amp; Write</storng></span>
                                                            </label>
                                                            </td>
                                                            <td>
                                                            <label> 
                                                                <storng class="visible-xs">No Access</storng>
                                                                <input type="radio" class="radioButton noAccess" name="module[{{$module->id}},{{$mod->id}},{{$m->id}}]"
                                                                       @if(!in_array($m->id, $modulesArray) && !in_array($m->id, $readOnlyArray)) checked="checked" @endif   value="0"  />
                                                                <span><storng class="">No Access</storng></span>
                                                            </label>
                                                            </td>

                                                            

                                                        </tr>
                                            @endforeach
                                            </tbody>

                                                @else

                                                    <tr>
                                                        <td style="padding-left: 30px;"><storng class="visible-xs">Module Name</storng>
                                                         <label for="accounting" style="font-weight: normal"> {{$mod->name}}</label><span style="font-size: 11px;"> (Form)</span></td>
                                                        <td>
                                                            <storng class="visible-xs">Read Only</storng>
                                                            <label>
                                                                <input type="radio" class="radioButton readOnly" @if (in_array($mod->id, $readOnlyArray)) checked="checked" @endif name="module[{{$module->id}},{{$mod->id}}]" value="1" />
                                                                <span><storng class="">Read Only</storng></span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <storng class="visible-xs">Read &amp; Write</storng>
                                                            <label>
                                                            
                                                                <input type="radio" class="radioButton readWrite" @if (in_array($mod->id, $modulesArray)) checked="checked" @endif
                                                                name="module[{{$module->id}},{{$mod->id}}]" value="2"  />
                                                                <span><storng class="">Read &amp; Write</storng></span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <storng class="visible-xs">No Access</storng>
                                                            <label> <input type="radio" class="radioButton noAccess" name="module[{{$module->id}},{{$mod->id}}]"
                                                                   @if(!in_array($mod->id, $modulesArray)  && !in_array($mod->id, $readOnlyArray)) checked="checked" @endif   value="0"  />
                                                                <span><storng class="">No Access</storng></span>
                                                            </label>
                                                        </td>

                                                       
                                                    </tr>

                                              
                                                @endif
                                            @endforeach
                                            </tbody>
                                            </tbody>

                                        @else
                                            @if(is_null($module->parentModule))
                                                <tbody  class="labels">
                                                <tr>
                                                    <td>
                                                        <storng class="visible-xs">Module Name</storng>
                                                        {{$module->name}}<span style="font-size: 11px;"> (Form)</span>
                                                    </td>

                                                    <td>
                                                        <label> 
                                                            <input type="radio" class="radioButton readOnly" @if (in_array($module->id, $readOnlyArray)) checked="checked" @endif name="module[{{$module->id}}]" value="1" />
                                                            <span><storng class="">Read Only</storng></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <storng class="visible-xs">Read &amp; Write</storng>
                                                        <label> 
                                                            <input type="radio" class="radioButton readWrite" @if (in_array($module->id, $modulesArray)) checked="checked" @endif name="module[{{$module->id}}]" value="2"  />
                                                            <span><storng class="">Read &amp; Write</storng></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <storng class="visible-xs">No Access</storng>
                                                        <label> 
                                                            <input type="radio" class="radioButton noAccess" name="module[{{$module->id}}]"
                                                               @if(!in_array($module->id, $modulesArray) && !in_array($module->id, $readOnlyArray) ) checked="checked" @endif   value="0"  />
                                                            <span><storng class="">No Access</storng></span>
                                                        </label>
                                                    </td>

     
                                                </tr>

                                    @endif
                                    @endif
                                    @endforeach

                                </table>
                        <table>
                                <tr><td><input type="submit" style="border: 2px solid transparent;" class="btn-success btn-lg btn-block saveBtnPos" value="Save"> </td>
                                    <td>
                                        <a style="text-align: center; border: 2px solid transparent; color: #FFFFFF" class="btn-primary btn-lg btn-block"
                                           href="javascript:" onclick="window.history.back();">Back</a>
                                    </td>
                                    <td>
                                        <a style="text-align: center; border: 2px solid transparent; color: #FFFFFF" class="btn-default btn-lg btn-block"
                                           href="{{URL::to('master-template') }}/{{nxb_encode($template_id)}}/manage/assets">Cancel</a>
                                    </td>



                                </tr>
                        </table>

                                {!! Form::hidden('redirects_to', URL::previous()) !!}

                                {{ Form::close() }}

                            @else
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-6">{{MASTER_TEMPLATE_NO_MODULES_TEXT}}</div>
                                </div>
                            @endif

                            </div>
                            
                        </div>
                        <!-- ./ TAB CONTENT -->
                    </div>
                </div>
        <!-- ./ Content Panel -->  
        </div>
    </div>
</div>

<!-- ================= ./ CONTENT AREA ================ -->


@endsection

@section('footer-scripts')
    <script src="{{ asset('js/cookie.js') }}"></script>
    <script src="{{ asset('/js/vender/jquery-ui.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/vender/jquery-ui.css') }}" />
    <script src="{{ asset('js/permission.js') }}"></script>
<script>

    function  CheckAll(_this,obj) {

            if(_this.is(':checked')) {
                $('.' + obj).prop('checked', true);
            }else
            {
                $('.' + obj).prop('checked', false);
                $('.noAccess').prop('checked', true);

            }
    if(obj=='readOnly')
    {
        //jcf.customForms.destroyAll();
        $(".writeCheckBox").attr('checked',false);
        $(".noAccessCheckBox").attr('checked',false);
        //jcf.customForms.replaceAll();
    }
    if(obj=='readWrite')
    {
        //jcf.customForms.destroyAll();
        $(".readCheckBox").attr('checked',false);
        $(".noAccessCheckBox").attr('checked',false);
        //jcf.customForms.replaceAll();
    }
    if(obj=='noAccess')
    {
        //jcf.customForms.destroyAll();
        $(".writeCheckBox").attr('checked',false);
        $(".readCheckBox").attr('checked',false);
        //jcf.customForms.replaceAll();
    }


    }

</script>
<style>

    .aser{ display: none}
    table {
        width: 100%;
        border-collapse: separate;

    }

    th {
        background: #3498db;
        color: white!important;
        font-weight: bold;
    }

    td, th {
        padding: 10px;
        text-align: left;
        font-size: 18px;
    }

    .labels tr td {
        background-color: #2cc16a;
        font-weight: bold;
        color: #fff;
        border-bottom: 1px solid #cdcdcd!important;

    }

    .label tr td label {
        display: block;
        font-size: 14px!important;
    }


</style>

@endsection
