    @extends('layouts.equetica2')


    @section('main-content')

<!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">

        <div class="page-menu">
            
            <div class="row">
                <div class="col left-panel">
                    <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg') }}" />
                    </span>
                    <h1 class="title flex-shrink-1"><?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); ?>
                        {{post_value_or($ya_fields,'PrizeClaims','Prize Claims')}}
                        <small> {!! Breadcrumbs::render('shows-appowner-prize-form-listing', nxb_encode($template_id)) !!} </small>
                    </h1>
                    </div>
                </div>
                <div class="right-panel">
                    <div class="desktop-view">
                        <form class="form-inline justify-content-end">
                         <!--    <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Trake Price</button>
                            <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Export All Assets</button>
                             -->
                             @if($manageShows->count()>0)
                              <div class="col-sm-6 mt-5" style="">
                                  <a href="{{URL::to('Billing') }}/exportClaimForm/{{$template_id}}/0/all" class="btn btn-secondary">
                                  Export All 1099 Forms</a>
                              </div>
                              @endif
                            <div class="search-field mr-10">
                                <div class="input-group">
                                <input type="text" class="form-control" placeholder="" id="myInputTextField" aria-label="Username" aria-describedby="basic-addon1">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
                                </div>
                                </div>
                            </div>
                          </form>
                    </div>
                    <div class="mobile-view">
                        <span class="menu-icon mr-15" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
<!--                            <i class="fa fa-expand"></i>-->
                            <i class="fa fa-navicon"></i>
                        </span>
                        
                        <div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">
                            @if($manageShows->count()>0)
                              <div class="col-sm-6 mt-5" style="">
                                  <a href="{{URL::to('Billing') }}/exportClaimForm/{{$template_id}}/0/all" class="btn btn-secondary">
                                  Export All 1099 Forms</a>
                              </div>
                              @endif
                            <form class="form-inline justify-content-end">
                            <!-- <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Trake Price</button>
                            <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Export All Assets</button>
                             -->
                            <div class="search-field">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="myInputTextField" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
                                </div>
                                </div>
                            </div>
                          </form>

                          </div>
                    </div>
                </div>
            </div>
            <div class="collapse-box menu-holder">
                
                <div class="collapse menu-box MobileViewRightPanel" id="collapseMoreAction">
                    <span class="close-menu" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
                        <img src="{{asset('img/icons/icon-close.svg') }}" />
                    </span>
                    <div class="menu-links">
                        <div class="row">
                            <!-- col-md-6  -->
                            <div class="col-md-6 mb-10">
                                  @if($manageShows->count()>0)
                                <div class="col-sm-6 mt-5" style="">
                                    <a href="{{URL::to('Billing') }}/exportClaimForm/{{$template_id}}/0/all" class="btn btn-secondary">
                                    Export All 1099 Forms</a>
                                </div>
                                @endif
                                <form class="form-inline justify-content-end">
                                <div class="search-field">
                                    <div class="input-group">
                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
                                    </div>
                                    </div>
                                </div>
                                </form>  
                            </div>
                            <!-- /.col-md-6  -->
                            <!-- col-md-6  -->
                           <!--  <div class="col-md-6 text-center-sm">
                                <button class="btn btn-sm btn-primary btn-rounded mr-10 mb-10" type="button">Trake Price</button>
                                <button class="btn btn-sm btn-primary btn-rounded mr-10 mb-10" type="button">Export All Assets</button>
                            </div> -->
                            <!-- /.col-md-6  -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        

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
                                                  <?php $prize = $show->prizeWon;?>
                                                    @if(!$prize->count())
                                                        <div class="">
                                                          <div class="col-lg-5 col-md-5 col-sm-12">No Champions yet.</div>
                                                        </div>
                                                      @else
                                                          <div class="col-sm-12" style="text-align: right">
                                                            <a href="{{URL::to('Billing') }}/exportClaimForm/0/{{$show->id}}/multiple" class="btn btn-secondary">
                                                              Export 1099 Forms
                                                            </a>
                                                          </div>
                                                           <table class="table table-line-braker mt-10 custom-responsive-md dataViews" id="" data-id="{{$show->id}}">
                                                           <thead class="hidden-xs">
                                                             <tr>
                                                                <th style="width:15%;">User Name</th>
                                                                <th style="width:15%;">Horse</th>
                                                                 <th style="width:25%;">Class</th>
                                                                 <th style="width:10%;">Position</th>
                                                                 <th style="width:10%;">Prize</th>
                                                                 <th  style="width:15%" class="action">Action</th>
                                                              </tr>
                                                          </thead>
                                                              <tbody>

                                                              @foreach($prize as $one_asset)
                                                                  <?php $decode_asset = json_decode($one_asset->position_fields); ?>
                                                                  @foreach($decode_asset as $pResponse)
                                                                      <?php $serial = $loop->index + 1; ?>
                                                                      @if(isset($pResponse->horse_id))
                                                                          @if(!is_null($one_asset->prizeClaim($show->id,$pResponse->horse_id)))
                                                                          <?php $model = $one_asset->prizeClaim($show->id,$pResponse->horse_id); ?>

                                                                          <tr class="tr-row">
                                                                              <td>{{$model->user->name}}</td>
                                                                              <td>{{GetAssetNamefromId($pResponse->horse_id)}}</td>
                                                                              <td>{{GetAssetNamefromId($one_asset->asset_id)}}</td>
                                                                              <td>{!! getPostionText($pResponse->position) !!}</td>
                                                                              <td>@if(isset($pResponse->price)) <div class="priceinqty">($){{ $pResponse->price}}</div>@endif </td>
                                                                              <td class="action">
                                                                                  <a title="View 1099 Form" href="javascript:" onclick="GetPrizeClaimForm('{{$pResponse->horse_id}}','{{$show->id}}')">
                                                                                    <i data-toggle="tooltip" data-placement="top" title="View 1099 Form" class="fa fa-eye"></i>
                                                                                  </a>
                                                                              </td>
                                                                          </tr>
                                                                      @endif
                                                                    @endif
                                                                  @endforeach
                                                              @endforeach

                                                          </tbody>
                                                        </table>
                                                    @endif
                                                  </div>
                                              </div>
                                          </div>
                                      
                                        @endforeach
                                        @else
                                         <div class="panel panel-default">
                                            <label>Please add a show first by clicking on "Manage Shows" from dashboard.</label>
                                          </div>
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



    <div id="billing_prize_claim" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Prize Claim Form 1099</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {!! Form::open(['url'=>'/Billing/prizeClaimSubmit','method'=>'post','class'=>'form-horizontal']) !!}

                <div class="modal-body">
                    <div class="row" style="">
                      <div class="col-md-12" style="">
                          <div class="form-group">
                              <label>Prize Money/TaxPayer Name</label>
                              <input required type="text" name="prize_amount" class="form-control" id="prize_amount">
                          </div>

                          <div class="form-group">
                              <label>Social Security Number</label>
                              <input required type="text" name="social_security_number" class="form-control" id="social_security_number">
                          </div>

                          <div class="form-group">
                              <label>Federal ID Number</label>
                              <input type="text" name="federal_id_number" class="form-control" id="federal_id_number">
                          </div>
                      </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <input type="hidden" name="show_id" class="show_id">
                    <input type="hidden" name="horse_id" class="horse_id">
                    <input type="hidden" name="claim_id" id="claim_id">
                    <button type="button"  onclick="exportClaimDetails()" class="btn btn-primary">Export Claim Details</button>

                    {{--<button type="submit" class="btn btn-default">Submit</button>--}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}

            </div>

        </div>
    </div>



    @endsection

@section('footer-scripts')

    <script src="{{ asset('/js/shows/already-paid-invoice.js') }}"></script>

    <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />

   @include('layouts.partials.datatable')

@endsection