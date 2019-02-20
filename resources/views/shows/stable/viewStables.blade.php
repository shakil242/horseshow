@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')

    @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif

    <!-- ================= CONTENT AREA ================== -->
    <div class="main-contents">
        <div class="container-fluid">
        @php

            $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields');
           $title = post_value_or($ya_fields,'show_spectators','Shows Stables');
           $added_subtitle = Breadcrumbs::render('shows-stables-listing', $data = ['template_id' => $template_id]);
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
                                                <a class="d-block title collapsed show-{{$show->id}}" data-toggle="collapse" href="#collapse{{$show->id}}" aria-expanded="true" aria-controls="collapse-example" id="heading-example">
                                                    @if($show->title != null) {{$show->title}} @else Show Title @endif
                                                </a>
                                            </h5>
                                            <div id="collapse{{$show->id}}" class="collapse" aria-labelledby="heading-example">
                                                <div class="card-body">
                                                   <?php $collection = $show->showStables; ?>
                                                    @if($collection->count()>0)
                                                        <table class="table table-line-braker mt-10 custom-responsive-md" id="viewDetail" data-id="{{$show->id}}">
                                                           <thead class="hidden-xs">
                                                             <tr>
                                                                <th style="width:7%">#</th>
                                                                <th style="width:25%">Stable Name</th>
                                                                <th style="width:25%">Number Of Stalls</th>
                                                                <th style="width:25%">Remaining</th>
                                                                <th style="width:15%" class="action">Action</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>
                                                              @foreach($collection as $pResponse)
                                                                <?php $serial = $loop->index + 1;
                                                                   $show_id = $pResponse->show_id;
                                                                ?>
                                                                <tr>
                                                                    <td>{{ $serial }}</td>
                                                                    <td>{{$pResponse->name}}</td>
                                                                    <td>{!! getStallTypes($pResponse->stall_types) !!}</td>
                                                                    <td>{!! getRemainingStallTypes($pResponse->id,$pResponse->stall_types) !!}</td>
                                                                    <td class="action">
                                                                      <a href="javascript:" onclick="editStable('{{$pResponse->id}}','{{$pResponse->show_id}}')"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                      <a href="javascript:" onclick="deleteStable('{{nxb_encode($pResponse->id)}}','{{nxb_encode($pResponse->show_id)}}','{{nxb_encode($template_id)}}')" class="ic_bd_export"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                                      <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/viewStableDetails"  class="ic_bd_export"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                          </tbody>
                                                        </table>
                                                      @else
                                                       <div class="">
                                                          <div class="col-lg-5 col-md-5 col-sm-12">No Champions yet.</div>
                                                        </div>
                                                    @endif
                                                    <div class="row" style="margin-top: 20px;" >
                                                          <div class="col-sm-offset -2 col-sm-2">
                                                               <a href="{{URL::to('shows') }}/{{nxb_encode($show->id)}}/viewUnpaidStalls" class="btn btn-secondary">View Unpaid Stalls</a>
                                                           </div>
                                                           <div class="col-sm-2">
                                                           <a href="{{URL::to('shows') }}/{{nxb_encode($show->id)}}/viewStallRequests" class="btn btn-secondary">View Stall Requests</a>
                                                           </div>
                                                           <div class="col-sm-2">
                                                           <a href="javascript:" onclick="showStallTypes('{{$show->id}}',$(this))" class="btn btn-secondary">Add Stall Types</a> </div>
                                                       <div class="col-sm-2">
                                                           <a href="#AddStable{{$show->id}}"  data-toggle="modal" class="btn btn-secondary">Add Stables</a> </div>
                                                   </div>
                                                  </div>
                                              </div>
                                            
                                                   <div id="AddStable{{$show->id}}" class="modal fade" role="dialog">
                                                      <div class="modal-dialog">
                                                          <!-- Modal content-->
                                                          <div class="modal-content">
                                                              <div class="modal-header">
                                                                  <h4 class="modal-title">Add Stable</h4>
                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                              </div>
                                                              <div class="modal-body">
                                                                  {!! Form::open(['url'=>'/shows/saveStable','method'=>'post','class'=>'form-horizontal']) !!}

                                                                      <div class="form-group types" style="margin-top: 10px;" >
                                                                          <div class="col-xs-12">
                                                                              <label  style="width:18%;">Stable Name</label>
                                                                              <input type="text" class="form-control stable_name_{{$show->id}}" style="width:50%; display: inline" name="name" required  />
                                                                          </div>
                                                                          <?php $stallTypes = $show->stallTypes;?>
                                                                      @if($stallTypes->count()>0)
                                                                              @foreach($stallTypes as $stall)
                                                                                  <div class="col-xs-12" style="margin-top: 5px;">
                                                                                      <label  style="width:18%;">{{$stall->stall_type}}</label>
                                                                                      <input type="number" placeholder="Enter Quantity"  class="form-control stable_type_{{$show->id}}_{{$stall->id}}"  style="width:50%; display: inline" name="stall_types[{{$stall->id}}]"  />
                                                                                  </div>
                                                                              @endforeach
                                                                          @endif
                                                                      </div>
                                                                  <!-- The template for adding new field -->


                                                                  <div class="modal-footer">

                                                                      <input type="hidden" name="show_id" class="show_id" value="{{$show->id}}">
                                                                      <input type="hidden" name="stable_id" class="stable_id" value="">

                                                                      <button type="submit"   class="btn btn-success">Submit</button>
                                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                  </div>
                                                                  {!! Form::close() !!}
                                                              </div>
                                                          </div>
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

    <div id="stallTypes" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Stall Types</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    {!! Form::open(['url'=>'/shows/saveStallTypes','method'=>'post','class'=>'form-horizontal','id'=>'bookForm']) !!}
                    <div id="typesContainer">
                        <div class="row types mb-30" >
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="stallTypes[0][stall_type]" placeholder="Type 1" />
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="stallTypes[0][price]" placeholder="$ Price" />
                            </div>
                            <div class="col-sm-1">
                                <button type="button" class="btn btn-default addButton"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>



                        <!-- The template for adding new field -->
                        <div class="row hide mb-30" id="bookTemplate">
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="typ" placeholder="Type" />
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="prc" placeholder="Price" />
                            </div>
                            <div class="col-sm-1">
                                <button type="button" onclick="removeFileds($(this))" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                    <div class="row  mb-30" >
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="stallTypes[100][utility_type]" placeholder="Utility Type" />
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="stallTypes[100][utility_price]" placeholder="$ Utility Price" />
                        </div>
                    </div>
                    </div>

                        <div class="modal-footer mt-15">

                            <input type="hidden" name="show_id" class="show_id">

                            <button type="submit"   class="btn btn-primary">Submit</button>
                            {{--<button type="submit" class="btn btn-default">Submit</button>--}}
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>

                    {!! Form::close() !!}


                </div>
                </div>


            </div>

        </div>


<!-- ================= ./ CONTENT AREA ================ -->


    @endsection

@section('footer-scripts')
  @include('layouts.partials.datatable')
  <script src="{{ asset('/js/stable.js') }}"></script>
  <style>
    .dataTables_filter {
        float: right;
    }

</style>
@endsection