@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

    @php
        $title = $stableName." Details";
        $added_subtitle = Breadcrumbs::render('shows-view-stable-details');
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>0])

    <!-- Content Panel -->
        <div class="white-board">

            <div class="row">
                <div class="info">
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>
    <div class="row" style="padding: 0px 15px;">
        <!-- Accordion START -->
        @if($collection->count()>0)


                <div class="col-sm-12">

                                <div class="display-success alert alert-success" style="display: none">
                                    Stall request has been responded successfully</div>
                                <table class="table table-line-braker mt-10 custom-responsive-md" id="viewDetail">
                                    <thead class="hidden-xs">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Stall Type</th>
                                        <th scope="col">Total Stalls</th>
                                        <th scope="col">Occupied By</th>
                                        <th scope="col">Horses</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $serial=0; ?>
                                    @foreach($collection as $pResponse)
                                    <?php $serial = $loop->index + 1; ?>
                                            <tr>
                                                <td>{{ $serial }}</td>
                                                <td>{{$pResponse->stallType->stall_type}}</td>
                                                <td>{{getTotalStallTypeOfStable($pResponse->approve_stable_id,$pResponse->stall_type_id)}}</td>
                                                <td>{!! getUserOccupiedStalls($pResponse->approve_stable_id,$pResponse->stall_type_id) !!}</td>
                                                <td>{!! horsesLinkedStallType($pResponse->approve_stable_id,$pResponse->stall_type_id) !!}</td>
                                            </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>


        @else
            <div class="panel panel-default">
                <label>No Request added yet!</label>
            </div>
        @endif
    </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @include('layouts.partials.datatable')
    <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
    <script src="{{ asset('/js/stable.js') }}"></script>

<style>
    .marginClass{ margin-top: 10px;}


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