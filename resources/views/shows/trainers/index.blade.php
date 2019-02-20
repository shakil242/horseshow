@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')


    <div class="container-fluid">

        @php
            $title = $manageShow->template->name;
            $added_subtitle = Breadcrumbs::render('shows-trainer-list', $manageShow->id) ;
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <div class="white-board">
            <div class="row">
                <div class="col-md-6">&nbsp</div>
            <div class="col-md-6 pull-right">
                @if(is_null($MST))
                    @if($trainerApp>0)
                        <a href="{{URL::to('shows') }}/{{nxb_encode($manageShow->id)}}/add-trainers" class="btn btn-primary btn-primary pull-right" title="Register as a trainer">Register as Trainer</a>
                    @else
                        <a href="#trainerEnhancement" data-toggle="modal"
                           class="btn btn-primary pull-right" title="Register as a trainer">Register as Trainer</a>
                    @endif
                @endif
                @if($manageShow->appowner->email == \Auth::user()->email)
                      <button type="button" class="btn btn-secondary pull-right" data-toggle="modal" data-target="#myModal">Send Email</button>
                @endif
            </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="info">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($collection == null)
                <div class="">
                    <div class="col-lg-5 col-md-5 col-sm-6">No trainer registered yet!</div>
                </div>
            @else
                <div class="row">
          <div class="col-md-12">

            <!-- if(!$collection->count()) -->
            @if($collection == null)
                  <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12">No trainer registered yet!</div>
                  </div>
            @else

                      <div class="table-responsive">
                          <table class="table table-line-braker mt-10 custom-responsive-md " id="crudTable2">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Name</th>
                                      <th scope="col">Date of Registration</th>
                                      <th class="action">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(sizeof($collection)>0)
                                        @foreach($collection as $pResponse)
                                            <?php
                                              $serial = $loop->index + 1;
                                              $roleName = '';
                                            ?>
                                            @if($pResponse->user)
                                            @php $emailUsers[] = $pResponse->user->email; @endphp
                                            <tr>
                                                <td>{{ $serial }}</td>
                                                <td><span class="visible-xs">Name</span> {{$pResponse->user->name}}</td>
                                                <td><span class="visible-xs">Date</span>{{getDates($pResponse->created_at)}}</td>
                                                 <td class="pull-left">
                                                    @if (Auth::check())
                                                    <span class="visible-xs">Actions</span>
                                                    @if(Auth::user()->id == $pResponse->user_id)
                                                      <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse->manage_show_id)}}/add-trainers/{{nxb_encode($pResponse->id)}}" title="Edit Your Information"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Your Information"></i></a>
                                                      <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/delete-trainer"  onclick="return confirm('Are you sure you want to un-register your self form being a trainer?')"><i class="fa fa-trash" data-toggle="tooltip" title="Delete Trainer"></i></a>
                                                             <a href="{{URL::to('shows') }}/view-trainers/{{nxb_encode($pResponse->id)}}"  title="View Info"><i class="fa fa-eye" data-toggle="tooltip" title="View Info"></i></a>
                                                         @else
                                                      <a href="{{URL::to('shows') }}/view-trainers/{{nxb_encode($pResponse->id)}}"  title="View Info"><i class="fa fa-eye" data-toggle="tooltip" title="View Info"></i></a>
                                                    @endif
                                                        @else
                                                         <a href="{{URL::to('shows') }}/view-trainers/{{nxb_encode($pResponse->id)}}" title="View Info"><i class="fa fa-eye" data-toggle="tooltip" title="View Info"></i></a>
                                                     @endif

                                                   </td>

                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>

            @endif
          </div>
        </div>

            @endif
        </div>
        <!-- Tab containing all the data tables -->
        <div id="trainerEnhancement" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Information</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-12 text-center mb-15">
                            <h5>Please join a Trainer Enhancement App to register as trainer!</h5>
                            </div>
                            <div class="col-sm-12">
                                <div class="row mb-20">
                                <div class="col-md-3 mt-5"><strong>Join Trainer Application:</strong></div>
                                    <div class="col-md-9">

                                    @foreach($trainerApps as $trainer)
                                        <a class="btn btn-default ml-10" href="{{URL::to('master-template') }}/{{nxb_encode($trainer->id)}}/{{nxb_encode($manageShow->id)}}/joinTrainerAppBYSelf">{{$trainer->name}}</a>
                                    @endforeach

                                        </div>
                            </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>


            </div>

        </div>

        @include('layouts.partials.email-marketing')

    </div>
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
