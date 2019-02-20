@extends('layouts.equetica2')

@section('custom-htmlheader')
   @include('layouts.partials.form-header')
@endsection

@section('main-content')
         <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

        @php 
          $title = "Course Preview";
          $added_subtitle = Breadcrumbs::render('setting-form-view');
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">
            <div class="col-md-12">


            <div class="row">
                    <div class="col-md-12">

                        <!-- TAB CONTENT -->
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')

                                <div class="participants-responses searchable-contents form-horizontal">
                                        @include('MasterTemplate.form.template')   
                                </div>


                        </div>
                        <!-- ./ TAB CONTENT -->
                    </div>
                </div>
                <!-- ./ Content Panel -->
            </div>
        <!-- ./ Content Panel -->  
        </div>
    </div>

<!-- <div class="col-sm-5 pull-right" id="floatable-search-header">
                  <div class="search-form">
                    <input type="text" class="find-form-search" placeholder="Search By Name"/>
                    <button class="search-for-value" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
                  <button data-search="next">&darr;</button>
                  <button data-search="prev">&uarr;</button>
                </div> -->

<!-- ================= ./ CONTENT AREA ================ -->

@endsection

@section('footer-scripts')

  <script src="{{ asset('/js/vender/jquery.mark.min.js') }}"></script>
  <script src="{{ asset('/js/vender/mark.min.js') }}"></script>

  <script src="{{ asset('/js/disable-form.js') }}"></script>
  
  <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
  <script src="{{ asset('/js/form-searchable.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
