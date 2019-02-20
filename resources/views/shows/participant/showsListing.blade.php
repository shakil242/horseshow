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
          $title = post_value_or($ya_fields,'export_shows','Shows');
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>''])

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
                                <div class="table-responsive">
                                                    @if($manageShows->count()>0)
                                                    <table class="table table-line-braker mt-10 custom-responsive-md dataView">
                                                    <thead class="hidden-xs">
                                                    <tr>
                                                    <th>Show Title</th>
                                                    <th class="action">Action</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($manageShows as $idx => $show)
                                                    <?php $serial = $loop->index + 1;
                                                    ?>
                                                    <tr>
                                                    <td>{{$show->title}}</td>
                                                    <td class="action">
                                                    <a  class="viewInvoiceBtn" href="{{URL::to('shows') }}/{{nxb_encode($show->id)}}/exportShowsDetails" class="ic_bd_export">
                                                    <i class="fa fa-file-pdf-o" data-toggle="tooltip" title="" data-original-title="Download Excel"></i></a>

                                                    </td>
                                                    </tr>

                                                    @endforeach
                                                    </tbody>
                                                    </table>
                                                      @else
                                                          <div class="panel panel-default">
                                                              <label>No show Exists.</label>
                                                          </div>
                                                      @endif

                                                 <!-- Tab containing all the data tables -->
                                                   
                                                    <!-- Modal -->


                                              </div>

                                </div>
                                
                            </div>
                            
                        </div>
                        <!-- ./ TAB CONTENT -->

    </div>
</div>


<!-- ================= ./ CONTENT AREA ================ -->

@endsection
@section('footer-scripts')

   @include('layouts.partials.datatable')

@endsection