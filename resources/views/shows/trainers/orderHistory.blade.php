@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

    @php
        $title = "Order Supply History";
        $added_subtitle =  Breadcrumbs::render('shows-trainer-order-history');
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

    <!-- Content Panel -->
        <div class="white-board">
            <div class="col-sm-12">
                <div class="row">
                    <div class="info text-center">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-responsive module-holer rr-datatable">
                    <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">

            <thead class="hidden-xs">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Order Title</th>
                <th scope="col">Total Amount</th>
                <th scope="col">Ordered On</th>
                <th scope="col">Status</th>
                <th class="action">Action</th>

                <!-- <th style="width:22%">Type</th> -->
            </tr>
            </thead>
            <tbody>
            <?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields');?>

            @if(sizeof($suppliesOrders)>0)
                @foreach($suppliesOrders as $row)
                    <?php
                    $serial = $loop->index + 1;
                    // exit;
                    ?>
                    <tr>
                        <td>{{ $serial }}</td>
                        <td><span class="table-title">Name</span>{{ $row->order_title }}</td>
                        <td><span class="table-title">total Amount</span>${{ ($row->total_amount>0)?$row->total_amount:0 }}</td>
                        <td><span class="table-title">Created On</span>{{  getDates($row->created_at) }}</td>
                        <td><span class="table-title">Status</span>{{ ($row->status==1?'Closed':'Open')  }}</td>
                        <td class="action"><span class="table-title">Action</span><a href="{{url('shows')}}/trainer/viewOrderDetail/{{nxb_encode($row->id)}}/1"><i class="fa fa-eye" data-toggle="tooltip" title="View Order Detail"></i> </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="5" style="text-align: center">No Order Exist</td></tr>
            @endif
            </tbody>
        </table>
    </div>
            </div>
        </div>
    </div>



@endsection
@section('footer-scripts')
    @include('layouts.partials.datatable')
@endsection
