@extends('layouts.equetica2')
@section('main-content')


    <div class="container-fluid">
            <div class="page-menu">
                <div class="row">
                    <div class="col left-panel">
                        <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg') }}" />
                    </span>
                            <h1 class="title flex-shrink-1">{{getShowName($show_id)}}
                                <small>{!! Breadcrumbs::render('shows-sponsor-history') !!}</small>
                            </h1>
                        </div>
                    </div>
                    <div class="right-panel">
                        <div class="desktop-view">
                            <form class="form-inline justify-content-end">
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
                            <i class="fa fa-navicon"></i>
                        </span>

                            <div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">
                                <form class="form-inline justify-content-end">
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

                    @if(!$collection->count())
                        <div class="col-md-12">
                            <div class="text-center">No Sponsors yet.</div>
                        </div>
                    @else
                    <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-line-braker mt-10 custom-responsive-md " id="crudTable9">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th  scope="col">Categories</th>
                                <th  scope="col">Sponsored On</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($collection as $pResponse)
                                @php $serial = $loop->index + 1;
                                $userId =  $pResponse->user_id;
                                $show_id = $pResponse->show_id;
                                @endphp
                                <tr>
                                    <td>{{ $serial }}</td>
                                    <td>{{GetSponsorName($pResponse->sponsor_form_id,$pResponse->user->id)}}</td>
                                    <td>
                                        @if(!is_null($pResponse->hasCategory))
                                            <?php echo getCategories($pResponse->hasCategory) ?>
                                        @endif
                                    </td>
                                    <td> {{getDates($pResponse->created_at)}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    </div>
                    @endif
                </div>
                <!-- ./ Content Panel -->
            </div>
        </div>



@endsection

@section('footer-scripts')
  @include('layouts.partials.datatable')
  <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
@endsection