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
                        {{post_value_or($ya_fields,'sponsor','Sponsor Category Registration')}}
                        <small> {!! Breadcrumbs::render('shows-appowner-sponsor-category-listing', nxb_encode($template_id)) !!} </small>
                    </h1>
                    </div>
                </div>
                <div class="right-panel">
                    <div class="desktop-view">
                        <form class="form-inline justify-content-end">
                         <!--    <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Trake Price</button>
                            <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Export All Assets</button>
                             -->
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
                                                <div class="row">
                                                 <div class="col-sm-2" style="float: right">

                                                 <a href="javascript:"  onclick="getCategoryForm('{{$show->id}}',0)" class="btn btn-secondary">Add Category</a>
                                                 </div>
                                                 </div>
                                                 <?php $sponsor = $show->sponsorCategories;?>
                                                      @if(!$sponsor->count())
                                                        <div class="">
                                                          <div class="col-lg-5 col-md-5 col-sm-12">No Request yet.</div>
                                                        </div>
                                                      @else
                                                         
                                                          <table class="table table-line-braker mt-10 custom-responsive-md dataViews" id="" data-id="{{$show->id}}">
                                                            <thead class="hidden-xs">
                                                                 <tr>
                                                                    <th style="width:15%;">Category Title</th>
                                                                    <th style="width:15%;">Price</th>
                                                                     <th style="width:15%;">Sponsor Name on Invoice</th>
                                                                     <th class="action" style="width:15%">Action</th>
                                                                  </tr>
                                                              </thead>

                                                                  <tbody>

                                                                  @foreach($sponsor as $sp)
                                                                          <?php $serial = $loop->index + 1; ?>

                                                                              <tr class="tr-row">
                                                                                  <td>{{$sp->category_title}}</td>
                                                                                  <td>${{$sp->category_price}}</td>
                                                                                  <td>{{($sp->sponsor_on_invoice==1)? 'Yes':'No'}}</td>
                                                                                  <td class="action">
                                                                                      <a href="javascript:" onclick="getCategoryForm('{{$show->id}}','{{$sp->id}}')" data-toggle="tooltip" data-placement="top" data-original-title="Edit Category"><i class="fa fa-pencil" aria-hidden="true"></i> </a>
                                                                                      <a onclick="return confirm('Are you sure?')" href="{{URL::to('shows') }}/delete/{{nxb_encode($sp->id)}}"  data-toggle="tooltip" data-placement="top" data-original-title="Delete Category"><i class="fa fa-trash-o" aria-hidden="true"></i> </a>

                                                                                  </td>
                                                                              </tr>
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
                                          <label>No show added yet!</label>
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



    <div id="sponsorCategories" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Sponsor Category Form</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {!! Form::open(['url'=>'/shows/submitSponsorcategory','method'=>'post','class'=>'form-horizontal','id'=>'sponsors']) !!}

                <div class="modal-body">
                    <div class="col-md-12" style="width: 90%; margin-left: 10px;">
                        <div class="form-group">

                            <label>Category Title</label>
                            <input required type="text" name="category_title" class="form-control" id="category_title">
                        </div>

                        <div class="form-group">
                            <label>Category Price ($)</label>
                            <input required type="number" name="category_price" class="form-control" id="category_price">
                        </div>

                        <div class="form-group">
                            <label>Category Description</label>
                            <textarea name="category_description"  id="category_description" class="form-control"></textarea>
                        </div>

                        <div class="form-group ml-15">
                            <label>
                            <input type="checkbox" name="sponsor_on_invoice" value="1"  id="sponsor_on_invoice" class="form-control" />
                            <span>Show sponsor name on invoice</span>
                            </label>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">

                    <input type="hidden" name="show_id" class="show_id">
                    <input type="hidden" name="sponsor_id" class="sponsor_id">

                    <button type="submit"   class="btn btn-success">Submit</button>

                    {{--<button type="submit" class="btn btn-default">Submit</button>--}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}

            </div>

        </div>
    </div>



    @endsection

@section('footer-scripts')

    <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />

   @include('layouts.partials.datatable')

   <script>

       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });

            function getCategoryForm(id,sponsor_id) {
           $(".show_id").val(id);

           if(sponsor_id!=0)
           {
             $(".sponsor_id").val(sponsor_id);

               $.ajax({
                   url: "/shows/getSponsorCategories/"+sponsor_id,
                   method:"get",

                   "success": function(data) {
                       if(data)
                       {
                           //jcf.customForms.destroyAll();
                           $("#sponsor_on_invoice").prop('checked', false);
                          // jcf.customForms.replaceAll();

                           $("#category_title").val(data.category_title);
                           $("#category_price").val(data.category_price);
                           $("#category_description").val(data.category_description);
                           $(".sponsor_id").val(data.id);

                           if(data.sponsor_on_invoice==1){
                               //jcf.customForms.destroyAll();
                               $("#sponsor_on_invoice").prop('checked', true);
                               //jcf.customForms.replaceAll();

                           }
                       }
                   }
               });

           }else{
               //jcf.customForms.destroyAll();
               $("#sponsors").trigger('reset');
               //jcf.customForms.replaceAll();
           }


            $("#sponsorCategories").modal('show');
            $('#sponsorCategories').modal('show');
            $(".modal-backdrop").addClass('show');
       }
       
   </script>

    <style>

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
    </style>
@endsection