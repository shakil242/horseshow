<div class="row">
                  <div class="col-sm-8 padding-25"><h3><strong> Modules for {{$MT_name['name']}}</strong></h3></div>
                    
                    <div class="col-sm-4">
                      {!! Form::open(['url'=>'master-template/search/','method'=>'post','class'=>'']) !!}
                        <div class="search-form">
                          <input name="keywords" class="typeahead form-control" type="text" placeholder="Search By Name" />
                          <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                          <input type="hidden" name="template_id" value="{{nxb_encode($template_id)}}">
                        </div>
                      {!! Form::close() !!}
                    </div>
                  </div>
               
                  <!--- All modules -->
                  <section id="all-modules">
                    <div class="row">
                       @if(sizeof($collection)>0)
                      
                      <div class="col-sm-8">
                        <div class="row searchables">
                        
                          @foreach($collection as $module)
                            @if(isset($permission))
                              @if(getModulePermission($module['id'],$permission) != 0)
                                <div class="col-md-4 col-sm-6 Smodule">
                                  <a href="{{URL::to('master-template') }}/{{nxb_encode($module['template_id'])}}/{{nxb_encode($participant->id)}}/{{nxb_encode($module['id'])}}/sub-modules" class="modules-clicked module-holder">
                                  <img src="{{ URL::asset($module['logo']) }}" width="120" height="80" alt="Logo Image" />
                                    <p class="moduleName" data-class="{{ strtolower($module['name'])}}">{{$module['name']}} <span class="hidden">{{ strtolower($module['name'])}}</span></p>
                                  </a>
                                </div>
                              @endif
                            @else
                              <div class="col-md-4 col-sm-6 Smodule">
                                <a href="#" module-id="{{nxb_encode($module['id'])}}" template-id="{{nxb_encode($module['template_id'])}}" class="module-holder modules-clicked">
                                <img src="{{ URL::asset($module['logo']) }}" width="120" height="80" alt="Logo Image" />
                                  <p class="moduleName">{{$module['name']}}<span class="hidden">{{ strtolower($module['name'])}}</span></p>
                                </a>
                              </div>
                            @endif

                          @endforeach
                        
                        </div>
                      </div>
                      @else
                       <div class="col-sm-8">
                          <div>
                            <p> No modules added to this master template yet</p>
                          </div>
                        </div>
                      @endif
                      <!-- General Modules -->
                      @if(count($generalCollection) && !isset($permission))
                      <aside class="col-sm-4" id="modules-sidebar">
                        <h4>General Modules</h4>
                        <nav class="general-modules">
                          <ul>
                          @foreach($generalCollection as $module)
                            <li>
                              <a href="#" class="modules-clicked" module-id="{{nxb_encode($module['id'])}}" template-id="{{nxb_encode($module['template_id'])}}">
                                <img src="{{ URL::asset($module['logo']) }}" width="120" height="70" alt="Logo Image" />
                                <div>
                                  {{$module['name']}}
                                </div>
                              </a>
                            </li>
                          @endforeach
                          </ul>
                        </nav>
                      </aside>
                      @endif
                    </div>
                  </section>