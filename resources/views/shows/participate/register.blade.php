@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="main-contents">
        <div class="container-fluid">

        @php
            $title = "Show Registration";
            $added_subtitle = Breadcrumbs::render('shows-register',nxb_encode($show_id));
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
            <div class="white-board">
                <div class="row">
                    <div class="col">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <!--<li class="nav-item">
                                <a class="nav-link active" id="division-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Orders</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="showclasses-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Show Classes</a>
                            </li> -->
                        </ul>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">

                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                                @include('admin.layouts.errors')

                                <div class="participants-responses">
                                    @if(!is_null($FormTemplate)>0)
                                        {!! Form::open(['url'=>'shows/store','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                                        <input type="hidden" name="template_id" value="{{$FormTemplate->template_id}}">
                                        <input type="hidden" name="form_id" value="{{$FormTemplate->id}}">
                                        <input type="hidden" name="show_id" value="{{$show_id}}">

                                        @if(count($answer_fields)>0)
                                            <div class="col-md-12" style="height: 40px;">
                                            <div class="row pull-right">
                                                <div class="col-sm-4">{!! Form::submit("Next Step" , ['class' =>"btn btn-primary btn-close"]) !!}</div>
                                                <!-- <div class="col-sm-2"><input type="button" class="btn btn-lg btn-primary btn-duplicate-form" value="Duplicate"></div> -->
                                                <div class="col-sm-3 ml-30"><a style="text-align: center" class="btn btn-primary" href="{{URL::to('shows') }}/dashboard">Cancel</a></div>
                                            </div>
                                            </div>

                                        @endif
                                        @include('MasterTemplate.form.template')
                                        <div class="row">
                                            <div class="col-sm-12 mb-20">
                                                @if(isset($trainers))
                                                    <div class="form-group fields-container-div row">
                                                        <label class="col-sm-2 control-label text-right">Trainer: </label>
                                                        <div class="col-sm-8 ml-20 input-container">
                                                            <select multiple name="trainer" class="selectpicker show-tick form-control" title="Select trainer if applicable"id="allAssets" data-live-search="true" data-max-options="1">
                                                                @if($trainers->count())
                                                                    @foreach($trainers as $id => $option)
                                                                        <option value="{{$option->id}}"  {{ (old("trainer",$trainer_id) != null && in_array($option->id, old("trainer",$trainer_id)) ? "selected":"") }}> {{ $option->user->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row pull-right">
                                            <div class="col-sm-4">{!! Form::submit("Next Step" , ['class' =>"btn btn-primary btn-close"]) !!}</div>
                                            <!-- <div class="col-sm-2"><input type="button" class="btn btn-lg btn-primary btn-duplicate-form" value="Duplicate"></div> -->
                                            <div class="col-sm-3 ml-30"><a class="btn btn-primary" href="{{URL::to('shows') }}/dashboard">Cancel</a></div>
                                        </div>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    @else
                                        <div class="row">
                                            <div class="col-sm-12"> No registration form added for this show uptill now!</div>
                                        </div>

                                    @endif
                                </div>


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

    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
    <script src="{{ asset('/js/duplicate-form.js') }}"></script>

@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
