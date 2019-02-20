@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = GetAssetName($asset)."- Placement History";
          $added_subtitle = Breadcrumbs::render('template-asset-prizing-listing',$template_id);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

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
                               <div class="row">
                                 <div class="col-sm-12">
                                    @if(!$collection->count())
                                      <div class="">
                                        <div class="col-lg-5 col-md-5 col-sm-6">No Positioning and pricing set yet.</div>
                                      </div>
                                    @else
                                      @foreach($collection as $pRes)
                                      <div class="col-sm-6">
                                        <div class="tab-content">
                                          <h3>{{$pRes->shows->title}}</h3>
                                                    <div class="module-holer rr-datatable">
                                                        <table class="table primary-table table-line-braker mt-10 custom-responsive-md dataViews">
                                                        <thead>
                                                           <tr>
                                                              <th style="width:5%">#</th>
                                                              <th>Placement</th>
                                                              <th>Name</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(sizeof($pRes)>0)
                                                            <?php $coll = json_decode($pRes->position_fields); ?>
                                                                @foreach($coll as $pResponse)
                                                                    <?php 
                                                                      $serial = $loop->index + 1; 
                                                                      $roleName = '';
                                                                    ?>
                                                                    @if(isset($pResponse->horse_id))
                                                                    <tr>
                                                                        <td>{{ $serial }}</td>
                                                                        <td><strong class="visible-xs">Placement</strong> {!! getPostionText($pResponse->position) !!}</td>
                                                                        <td><strong class="visible-xs">Name</strong>{{GetAssetNamefromId($pResponse->horse_id)}}</td>
                                                                        
                                                                    </tr>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                               </div>
                                        
                                        </div>
                                      </div>
                                      @endforeach
                                    @endif
                                  </div>
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
    @include('layouts.partials.datatable')
@endsection
