@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')

    @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif

    <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">
        @php

            $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields');
           $title = post_value_or($ya_fields,'low_participants','Low Participants');
           $added_subtitle = Breadcrumbs::render('shows-participants-listing', $data = ['template_id' => $template_id]);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])



        <!-- Content Panel -->
        <div class="white-board">

            <div class="row">
                <div class="info text-center col-md-12 mt-10">
                    @if(Session::has('message'))
                        <div class="alert {{ Session::get('alert-class', 'alert-success') }}" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                </div>
            </div>


            
            <div class="row">
                    <div class="col-md-12">
                        <div class="row">

                        <!-- TAB CONTENT -->
                            <!-- Tab Data Divisions -->
                                <div class="table-responsive">
                                    <div class="accordion-light" role="tablist" aria-multiselectable="true">
                                       @if($manageShows->count()>0)
                                        @foreach($manageShows as $idx => $show)
                                        <div class="slide-holder">
                                            <h5 class="card-header">
                                                <a class="d-block title collapsed" data-toggle="collapse" href="#collapse{{$idx}}" aria-expanded="true" aria-controls="collapse-example" id="heading-example">
                                                    @if($show->title != null) {{$show->title}} @else Show Title @endif
                                                </a>
                                            </h5>
                                            <div id="collapse{{$idx}}" class="collapse" aria-labelledby="heading-example">
                                                <div class="card-body">
                                                  <?php $collection = $show->classTypes; ?>
                                                  @if(!$collection->count())
                                                          <div class="col-lg-5 col-md-5 col-sm-12">No Participants yet.</div>
                                                      @else
                                                        <div class="col-sm-12" style="padding: 0px;">
                                                        <div class="row">
                                                          <div class="col-sm-12">
                                                            <p> Here you can see all the classes having entries less then 3.</p>
                                                            <p> In Order to combine class, Go to  <a class="" href="{{URL::to('master-template') }}/{{nxb_encode($template_id)}}/manage/assets">{{post_value_or($ya_fields,'assets','Assets')}}</a> and add a class with combined class type.</p>
                                                          </div>
                                                        </div>
                                                      <table class="table table-line-braker mt-10 custom-responsive-md dataView" id="" data-id="{{$show->id}}">
                                                        <thead>
                                                            <tr>
                                                              <th style="width:4%!important; text-align: center">#</th>
                                                              <th style="width:12%; padding: 0px; text-align: center;font-size: 17px;">Class</th>
                                                              <th style="width:15%; padding: 0px; text-align: center;font-size: 17px;">Entries</th>
                                                            </tr>
                                                        </thead>
                                                        <!-- <tfoot>
                                                          <tr>
                                                              <td>#</td>
                                                              <td>Class</td>
                                                              <td>Entries</td>
                                                          </tr>
                                                          </tfoot> -->
                                                          <tbody>
                                                            @php $i=0; @endphp
                                                            @foreach($collection as $pResponse)
                                                            <?php 
                                                              $classCounter = GetClassCount($pResponse->show_id,$pResponse->sclasses->id)
                                                            ?>
                                                              @if($classCounter <= 3)
                                                              @php $i = $i + 1; @endphp
                                                              <tr>
                                                                  <td>{{ $i }}</td>
                                                                  <td data-title="{{$pResponse->id}}">
                                                                      <input type="hidden" class="test" value="{{$pResponse->asset_id}}">{{GetAssetName($pResponse->sclasses)}}</td>
                                                                  <td data-title="{{$pResponse->id}}"> {{$classCounter}} </td>
                                                              </tr>
                                                              @endif
                                                          @endforeach
                                                      </tbody>

                                                    </table>
                                                        </div>
                                                    @endif

                                              </div>
                                          </div>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="panel panel-default">
                                          <label>No show added yet!</label>
                                        </div>
                                        @endif 
                                        </div> 
                                </div>
                                <!-- PAGINATION -->
                                </div>


                        </div>
                            
                        <!-- ./ TAB CONTENT -->
                </div>
        <!-- ./ Content Panel -->  
        </div>
    </div>
<!-- ================= ./ CONTENT AREA ================ -->

@endsection

@section('footer-scripts')

   @include('layouts.partials.datatable')
   <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
   <script src="{{ asset('/js/shows/showLowParticipants.js') }}"></script>
@endsection