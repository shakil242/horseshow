@extends('layouts.equetica2')


@section('main-content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); 
          $title = post_value_or($ya_fields,'show_participants','Shows Participants');
          $added_subtitle = Breadcrumbs::render('shows-participants-listing', $template_id);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

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
                                <div class="table-responsive">
                                    <div class="accordion-light" role="tablist" aria-multiselectable="true">
                                       @if($manageShows->count()>0)
                                        @foreach($manageShows as $idx => $show)
                                         @php $emailUsers = array(); @endphp
                                        <div class="slide-holder">
                                            <h5 class="card-header">
                                                <a class="d-block title collapsed" data-toggle="collapse" href="#collapse{{$idx}}" aria-expanded="true" aria-controls="collapse-example" id="heading-example">
                                                    @if($show->title != null) {{$show->title}} @else Show Title @endif
                                                </a>
                                            </h5>
                                            <div id="collapse{{$idx}}" class="collapse" aria-labelledby="heading-example">
                                                <div class="card-body">
                                                  <?php $collection = $show->participants; ?>
                                                    @if(!$collection->count())
                                                        <div class="">
                                                          <div class="col-lg-5 col-md-5 col-sm-12">No Invoice added yet.</div>
                                                        </div>
                                                      @else
                                                      <?php $data = getScratchHorseCount($show->id); ?>
                                                           <div class="row" style="background: #001e46;color:white;line-height: 30px;padding: 10px; margin: 10px 0px;">
                                                                <div class=""> <div class="col-sm-12"><strong>Total Entries : </strong><span class="unScratchHorses">{{$data['unScratch']}}</span> </div></div>
                                                                <div class=""> <div class="col-sm-12"><strong>Scratched Entries : </strong><span class="scratchHorses">{{$data['scratch']}}</span> </div></div>

                                                              <div class="col-sm-12"><button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModal{{$idx}}">Send Email</button></div>

                                                            </div>
                                                           <table class="table table-line-braker mt-10 custom-responsive-md dataView" data-id="{{$show->id}}">
                                                              <thead class="hidden-xs">
                                                                 <tr>
                                                                    <th>User Name</th>
                                                                     <th>Rider</th>
                                                                     <th>Horse</th>
                                                                     <th>Class</th>
                                                                     <th>Scheduler</th>
                                                                     @if($show->show_type == 'Dressage')
                                                                     <th>Qualifying</th>
                                                                     @endif
                                                                     <th>Registered On</th>
                                                                      <th class="action">Action</th>
                                                                  </tr>
                                                              </thead>
                                                               <tfoot>
                                                               <tr>
                                                                 <td colspan="2">&nbsp;</td>
                                                                   <td>&nbsp;</td>
                                                                  <td colspan="2">Class</td>
                                                              </tr>
                                                              </tfoot>

                                                                  <tbody>
                                                                @foreach($collection as $pResponse)
                                                                    <?php $serial = $loop->index + 1;
                                                                       $userId =  getIdFromEmail($pResponse->email);
                                                                       $show_id = $pResponse->show_id;
                              //                                      if($pResponse->payinoffice($show_id,$userId)){
                              //                                        $invoicStatus = 'Pay In Office';
                              //                                      }else{
                              //                                         if($pResponse->invoicePaid($show_id,$userId) > 0)
                              //                                          $invoicStatus = 'Paid';
                              //                                        else
                              //                                          $invoicStatus = 'Pending';
                              //                                      }
                                                                    $horses = getHorsesForUser($pResponse->id ,$pResponse->asset_id );
                                                                      

                                                                    if (sizeof($horses)<=0) {
                                                                        $html = "No Horse Added";

                                                                        }else{
                                                                        foreach ($horses as $horse)
                                                                            {
                                                                            $html = "";
                                                                           $rider ="";
                                                                                if ($horse->scratch == HORSE_SCRATCHED) {
                                                                                    $html .= "<div class='scratched-horses'>";
                                                                                }else{
                                                                                    $html .= "<div class='not-scratched-horses'>";
                                                                                }
                                                                                $html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_id) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_id) . "</a>"." [Entry# ".$horse->horse_reg."]";

                                                                                $html .= "<a class=\"HorseAsset\" title='scratch' href=\"/shows/" . nxb_encode($horse->id) . "/horse/scratch\" onclick=\"return confirm('Are you sure you want to Scratch this horse?')\" > Scratch </a>";
                                                                                if ($horse->scratch == HORSE_SCRATCHED) {
                                                                                    $html .= "</div>";
                                                                                    $html .= "<a title='scratch' href=\"/shows/" . nxb_encode($horse->id) . "/horse/scratch/1\" onclick=\"return confirm('Are you sure you want to Un Scratch this horse?')\" > Un-Scratch </a>";
                                                                                }else{
                                                                                    $html .= "</div>";
                                                                                }

                                                                    if($horse->horse_rider!='') {
                                                                        $rider = "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_rider) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_rider) . "</a> ";
                                                                    }else
                                                                    {
                                                                        $rider = "No Rider Added";
                                                                    }

                                                                  $schedulerTime = getSchedulerTime($show->id,$pResponse->id,$pResponse->asset_id,$horse->horse_id);
                                                                  $emailUsers[] = $pResponse->email;

                                                                    ?>
                                                                    <tr>
                                                                        <td data-title="{{$pResponse->asset_id}}">{{getUserNamefromEmail($pResponse->email)}}</td>
                                                                        <td data-title="{{$pResponse->asset_id}}"> <?php echo $rider ?></td>
                                                                        <td data-title="{{$pResponse->asset_id}}"> <?php echo $html ?></td>
                                                                        <td data-title="{{$pResponse->asset_id}}"><input type="hidden" class="test" value="{{$pResponse->asset_id}}"> {{GetAssetNamefromId($pResponse->asset_id)}}
                                                                        </td>
                                                                        <td><?php echo $schedulerTime ?></td>
                                                                        @if($show->show_type == 'Dressage')
                                                                          <td data-title="{{$pResponse->asset_id}}"> {{getHorseQualifying($horse->horse_id,$pResponse->asset_id)}}</td>
                                                                        @endif
                                                                        <td data-title="{{$pResponse->asset_id}}"> {{getDates($pResponse->created_at)}}</td>
                                                                        <td class="action">
                                                                          <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse->manage_show_reg_id)}}/registration/view"><i class="fa fa-eye"></i></a>
                                                                          <a  class="viewInvoiceBtn" href="{{URL::to('master-template') }}/ExportRegistrationView/{{nxb_encode($pResponse->manage_show_reg_id)}}" class="ic_bd_export"><i class="fa fa-file-pdf-o" data-toggle="tooltip" title="" data-original-title="Download pdf"></i></a>

                                                                        </td>
                                                                    </tr>
                                                                    <?php }
                                                                    }

                                                                    ?>
                                                                @endforeach
                                                              </tbody>
                                                            </table>
                                                    @endif
                                                  </div>

                                                 <!-- Tab containing all the data tables -->
                                                   
                                                    <!-- Modal -->
                                                     @include('layouts.partials.email-marketing')


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

@endsection

@section('footer-scripts')

   @include('layouts.partials.datatable')
   <script src="{{ asset('/js/vender/tinymce/tinymce.min.js') }}"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
       <script src="{{ asset('/js/marketing-email.js') }}"></script>
   <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />

   <script src="{{ asset('/js/showParticipants.js') }}"></script>


    <style>

        .dataTables_paginate {
            float: none !important;
        }
        .table-filter-container {
            text-align: right;
        }
        tfoot input {
            width: 50%!important;
            padding: 3px!important;
            box-sizing: border-box!important;
        }

        tfoot {
            display: table-header-group;
        }
        select { width: 100%;
            padding: 3px!important;
            text-align: center;
            box-sizing: border-box!important;}
.dataTables_filter{
    float: right;}

        .sorting_asc {
            width: 4% !important;
        }

    </style>
@endsection