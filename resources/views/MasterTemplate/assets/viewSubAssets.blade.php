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
          $title = GetAssetNamefromId($id);
          $added_subtitle = Breadcrumbs::render('master-template-assets-secondary',['asset_id'=>$id,'template_id'=>$template_id]);
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
                                    
                                       
                                    @if(!$subAsset->count())
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
                                        </div>
                                    @else
                                        
                                            <div class="module-holer rr-datatable">
                                                <table class="table primary-table table-line-braker mt-10 custom-responsive-md dataViews">
                                                    <thead class="hidden-xs">
                                                    <tr>
                                                        <!-- <th style="width:5%">#</th> -->
                                                        <th>Asset Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @if(sizeof($subAsset)>0)
                                                        @foreach($subAsset as $pResponse)
                                                            <?php
                                                            $serial = $loop->index + 1;
                                                            $roleName = '';
                                                            ?>
                                                            <tr>
                                                                <!-- <td>{{ $serial }}</td> -->
                                                                <td><strong class="visible-xs">Asset Name</strong>{{ GetAssetNamefromId($pResponse->asset_id) }}</td>
                                                            <td>

                                                                <a href="{{URL::to('master-template')}}/{{nxb_encode($pResponse->asset_id)}}/history/assets" data-toggle='tooltip' data-placement='top'
                                                                data-original-title='View History'><i class='fa fa-eye' aria-hidden='true'></i></a>

                                                                <a href="{{URL::to('master-template')}}/{{nxb_encode($pResponse->asset_id)}}/edit/assets" data-toggle='tooltip' data-placement='top'
                                                                data-original-title='Edit Assets'><i class='fa fa-pencil' aria-hidden='true'></i></a>

                                                            </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
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
    @include('layouts.partials.datatable')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
@endsection
