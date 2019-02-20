@extends('admin.layouts.app')

@section('htmlheader_title')
    Log in
@endsection

@section('main-content')
        
        <div class="row">
            <div class="col-sm-8">
              <h1>Points System</h1>
            </div>
            <div class="col-sm-4 action-holder">
              <form action="#">
                <div class="search-form">
                  <input type="text" placeholder="Search By Name" id="myInputTextField"/>
                  <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
                {!! Breadcrumbs::render('points-dashboard-shows') !!}
            </div>
          </div>
          <div class="row">
            <div class="info">
                @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
            </div>
          </div>

          <!--- template list -->
        <div class="row"> 
          <div class="form-group add">
              <div class="col-md-12"><p class="error text-center alert alert-danger hidden"></p></div>
              <div class="col-md-4">
                  <input type="text" class="form-control" id="name" name="name"
                      placeholder="Enter Show name" required>
              </div>
              <div class="col-md-4">
                  <input type="number" min="0" class="form-control NumaricRistriction" id="showpoints" name="showpoints"
                      placeholder="Enter Points For Show" required>
              </div>
              <div class="col-md-4">
                  <button class="btn btn-primary" type="submit" id="add">
                      <span class="glyphicon glyphicon-plus"></span> ADD
                  </button>
              </div>
          </div>
        </div>
        <div class="module-holer rr-datatable">
                    <table id="crudTable2" class="table primary-table">
                    <thead class="hidden-xs">
                       <tr>
                          <th style="width:5%">#</th>
                          <th style="width:25%">Name</th>
                          <th>Points</th>
                          <th style="width:22%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(sizeof($collection)>0)
                            @foreach($collection as $showtype)
                                <?php 
                                $serial = $loop->index + 1; 
                                $roleName = '';
                                ?>
                                <tr class="showtype{{$showtype->id}}">
                                    <td>{{ $serial }}</td>
                                    <td>{{ $showtype->name }}</td>
                                    <td>{{ $showtype->points }}</td>
                                    <td><button class="edit-modal btn btn-info" data-id="{{$showtype->id}}"
                                            data-name="{{$showtype->name}}" data-points="{{$showtype->points}}">
                                            <span class="glyphicon glyphicon-edit"></span> Edit
                                        </button>
                                        <button class="delete-modal btn btn-danger" data-id="{{$showtype->id}}"
                                            data-name="{{$showtype->name}}" data-points="{{$showtype->points}}">
                                            <span class="glyphicon glyphicon-trash"></span> Delete
                                        </button></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

<div id="myModal" class="modal fade" role="dialog">
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">×</button>
      <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">
      <form class="form-horizontal" role="form">
        <div class="form-group">
          <label class="control-label col-sm-2" for="id">ID:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="fid" disabled>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Name:</label>
          <div class="col-sm-10">
            <input type="name" class="form-control" id="n">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Points:</label>
          <div class="col-sm-10">
            <input type="number" class="form-control NumaricRistriction" id="points">
          </div>
        </div>
      </form>
    </div>
      <div class="deleteContent">
        Are you Sure you want to delete <span class="dname"></span> ? <span
          class="hidden did"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn actionBtn" data-dismiss="modal">
          <span id="footer_action_button" class='glyphicon'> </span>
        </button>
        <button type="button" class="btn btn-warning" data-dismiss="modal">
          <span class='glyphicon glyphicon-remove'></span> Close
        </button>
      </div>
    </div>
  </div>
</div>
  
 

@endsection
@section('footer-scripts')
<script src="{{ asset('adminstyle/js/points-system.js') }}"></script>
    @include('admin.layouts.partials.datatable')
@endsection