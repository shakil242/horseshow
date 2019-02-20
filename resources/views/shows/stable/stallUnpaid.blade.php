@extends('layouts.equetica2')
@section('main-content')

<div id="ajaxMsg" class="alert alert-info alert-dismissible" style="display: none">


    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

    <span class="alertMsg"></span>

</div>
     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = getShowName($show_id);
          $added_subtitle = Breadcrumbs::render('shows-view-unPaid-stalls');

        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

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
                               <!-- Accordion START -->
                                @if(count($collection)>0)
                                    <div class="row">

                                        <div class="col-sm-12">

                                            <table class="table table-line-braker mt-10 custom-responsive-md dataViews">
                                                <thead class="hidden-xs">
                                                <tr>
                                                    <th style="width:4%">#</th>
                                                    <th style="width:15%">Name</th>
                                                    <th style="width:25%">Remaining Quantity</th>
                                                    <th  style="width:25%">Remaining Stalls Numbers</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php $serial=0;
                                                @endphp
                                                @foreach($collection as $key=>$pResponse)
                                                    @php $serial = $loop->index + 1; @endphp
                                                    <tr>
                                                        <td>{{ $serial }}</td>
                                                        <td>{{getUserNamefromid($key)}}</td>
                                                        <td>{!! getRemainingStalls($pResponse,'quantity') !!}</td>
                                                        <td>{!! getRemainingStalls($pResponse,'stalls') !!}</td>
                                                        <td>

                                                            <form action="javascript:"  onsubmit="stallRequestInOffice($(this),'{{$key}}','{{$show_id}}')" id="requestResponse-{{$key}}" name="form{{$key}}" method="post">
                                                            <div class="row">
                                                            {!! getRemainingStalls($pResponse,'action') !!}

                                                           <div class="col-sm-2" style="padding: 0px;"> <button type="submit"  style="color: #FFFFFF;float: left;margin-left: 0px;" class="btn btn-primary">Paid</button>

                                                           </div>
                                                            </div>
                                                            </form>
                                                            <div class="col-sm-6" style="padding: 0px;">  <a onclick="sendNotification('{{json_encode($pResponse)}}','{{$key}}','{{getShowName($show_id)}}')" href="javascript:" style="color: #FFFFFF;float: left;margin-left: 0px;" class="btn btn-secondary">Send Notification</a></div>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                                   
                                        </div>

                                    </div>
                                @else
                                    <div class="panel panel-default">
                                        <label>No Request added yet!</label>
                                    </div>
                                @endif
                            
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
    <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
    <script src="{{ asset('/js/stable.js') }}"></script>


    <style>
        .marginClass{ margin-top: 10px;}

         .dataTables_filter {
             float: right;
         }
        /*.dropdown-menu.inner li a {*/
        /*width: 100%;*/
            /*float: left;*/
            /*margin-left: 0px;*/
        /*}*/

        .primary-table2 {
            width: 100%;
            border: 1px solid #efefef;
            table-layout: auto;
        }
        .primary-table2 tr {
            border-bottom: 1px solid #efefef;
        }
        .primary-table2 tr td {
            font-weight: 400;
            font-size: 14px;
            padding: 10px;
            color: #535353;
        }
        .primary-table2 tr th {
            font-weight: 400;
            font-size: 18px;
            padding: 10px;
            color: #651e1c;
        }
    </style>

@endsection