    @extends('layouts.equetica2')


    @section('main-content')


@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Champion Calculator";
          $added_subtitle = Breadcrumbs::render('shows-champion-calculator-create', $app_id);
          
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">  
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
                                  <div class="row">
                                    <div class="col-sm-12 insideTable" style="margin-bottom: 10px;">
                                     <form name="ajax-form-submits" class="ajax-form-submits" >
                                     <!-- { Form::open(['url' => "shows/champion/saved", 'method'=>'post','files'=>true,'class'=>"ajax-form-submit"]) } -->

                                     <table class="table borderless">
                                          <input type="hidden" name="show_id" id="show_id" value="{{$show_id}}">
                                          <input type="hidden" name="app_id" id="app_id" value="{{$app_id}}">
                                        <tr>
                                        <td width="40%" class="text-right">
                                          Selected Show:
                                        </td>
                                        <td>
                                          {{getShowName($show_id)}}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="text-right">
                                           <span class="top-5">Division:</span> 
                                        </td>
                                        <td>
                                          @if($CD)
                                            <input name="division" id="divname" type="text" placeholder="Add Name For Division" class="form-control" value="{{$CD->division_name}}" required="required">
                                            <input name="cd_id" id="cd_id" value="{{$CD->id}}" type="hidden">
                                          @else
                                            <input name="cd_id" id="cd_id" value="0" type="hidden">
                                            <input name="division" id="divname" type="text" placeholder="Add Name For Division" class="form-control" required="required">
                                          @endif
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="text-right">
                                            <span class="top-5">Select Class:</span>
                                        </td>
                                        <td>
                                          <select multiple name="classes[]" class="selectpicker show-tick form-control classesSelect" title="Select classes" id="allClasses" data-live-search="true" data-min="1">
                                                @if($classes->count())
                                                    @foreach($classes as $id => $option)
                                                      <option value="{{ $id }}" {{ (in_array($id,$existingClass)) ? 'selected':'' }}> {{ GetAssetName($option,1) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </td>
                                      </tr>
                                      @if(isset($CD->champions))
                                      <tr>
                                        <td class="text-right">
                                            <span class="top-5">Champion</span>
                                        </td>
                                        <td>
                                          <?php echo getDivisionChampion($CD); ?>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="text-right">
                                            <span class="top-5">Reserve Champion</span>
                                        </td>
                                        <td>
                                          <?php echo getDivisionChampion($CD,2); ?>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="text-right">
                                            <span class="top-5">Calculated On:</span>
                                        </td>
                                        <td>
                                          <?php echo getDates($CD->updated_at); ?>
                                        </td>
                                      </tr>
                                      @endif
                                      <tr>
                                        <td colspan="2" class="text-center">
                                            <button class="btn btn-primary">Calculate and Save</button>
                                        </td>
                                      </tr>
                                      
                                      </table>
                                      </form>
                                       <!-- { Form::close() } -->

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

    
@endsection

@section('footer-scripts')
    <div id="ajax-loading" class="loading-ajax" style="display: none;"></div>
    <!-- <script src="{{ asset('/js/ajax-calculate-champ.js') }}"></script> -->
    <script>
    $('form.ajax-form-submits').on('submit', function(e) {
        e.preventDefault();
        var base_url = window.location.protocol + "//" + window.location.host + "/";

        
        $.ajax({
            url: "{{ route('champion.saved') }}",
            type: 'POST',
            async: false,
            dataType: "json",
            data: {"show_id":$("#show_id").val(), "app_id":$("#app_id").val() , "cd_id":$("#cd_id").val() ,"division":$("#divname").val(), 'classes':$("#allClasses").val()},
            beforeSend: function (xhr) {
              $("#ajax-loading").show();
                var token = '{{ csrf_token() }}';
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            success: function(data) {
                $("#ajax-loading").hide();
                //console.log(data);
                window.location = base_url + "shows/champion/"+data.result.app_id+'/'+data.result.show_id+'/create/'+data.result.CD_id;
                
            },
            error: function(err){
              console.log(err);
              $("#ajax-loading").hide();
              alert("There was an error, Please try again!");
            }
        });

    });
    </script>
  @include('layouts.partials.datatable')
@endsection