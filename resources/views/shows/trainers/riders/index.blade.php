@extends('layouts.equetica2')

@section('main-content')
    <div class="container-fluid">
    @php
        $title = "Your Riders for ".getShowName($show_id);
        $added_subtitle = Breadcrumbs::render('shows-trainer-order-history');
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

    <!-- Content Panel -->
        <div class="white-board">


    <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModal">Send Email</button>
        </div>
    </div>
    <div class="table-responsive">
                <table class="table table-line-braker mt-10 custom-responsive-md " id="crudTable2">

            <thead class="hidden-xs">
            <tr>
                <th scope="col">#</th>
                <th scope="col">User Name</th>
                <th scope="col">Amount</th>
                <th scope="col">Registered On</th>
                <th class="action">Action</th>
            </tr>
            </thead>
            <tbody>
            @php $emailUsers =array(); @endphp
            @if(sizeof($registerList)>0)
                @foreach($registerList as $row)
                    <?php
                        $serial = $loop->index + 1;
                        $emailUsers[] = $row->user->email;
                    ?>
                    <tr>
                        <td>{{ $serial }}</td>
                        <td><span class="table-title">Name</span>{{ $row->user->name }}</td>
                        <td><span class="table-title">Total</span>($){{  $row->total_price }}</td>
                        <td><span class="table-title">Created On</span>{{  getDates($row->created_at) }}</td>
                        <td class="action">
                            <span class="table-title">Action</span>
                            <a href="{{url('shows')}}/trainer/rider-detail/{{nxb_encode($row->id)}}"><i data-toggle="tooltip" data-placement="top" title="" class="fa fa-eye" data-original-title="View / Edit Detail"></i></a>
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

     @include('layouts.partials.email-marketing')



@endsection
@section('footer-scripts')
    @include('layouts.partials.datatable')
@endsection
