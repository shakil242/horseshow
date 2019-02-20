@extends('admin.layouts.app')

@section('main-content')
          <div class="row">
            <div class="col-sm-8">
              <h1>Button Label for {{$template->name}}</h1>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="">
                {!! Breadcrumbs::render('admin-edit-m-template',$template->id) !!}
            </div>
          </div>
          <div class="row">
            <div class="info">
                @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
            </div>
          </div>
          {!! Form::open(['url'=>'admin/template/button-label/save','method'=>'post']) !!}
           <input type="hidden" name="template_id" value="{{$template->id}}">
           @if(sizeof($TBlabel)>0)
            <input type="hidden" name="TBlabel_id" value="{{$TBlabel->id}}">
           @endif
           <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#appss">Your Apps</a></li>
              <li><a data-toggle="tab" href="#invited_assets">Activity Zone</a></li>
              <li><a data-toggle="tab" href="#overall_responses">Overall Responses</a></li>
              <li><a data-toggle="tab" href="#spectators">Spectators</a></li>
               <li><a data-toggle="tab" href="#ManageScheduler">Manage Scheduler</a></li>
               <li><a data-toggle="tab" href="#inviteParticipants">Invite Participants</a></li>


          </ul>
          <div class="tab-content">
            <div id="appss" class="tab-pane active">
              <div class="row">
                <div class="col-sm-12">
                <h3>Edit (Your App) Labels</h3>
                <div class="padding-25"></div>
                
                <div class="col-sm-12">
                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Invite Participants</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[invite_participants]" value="{{post_value_or($ya_fields,'invite_participants')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[invite_participants_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'invite_participants_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">View App</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[view_app]" value="{{post_value_or($ya_fields,'view_app')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[view_app_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'view_app_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Assets</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[assets]" value="{{post_value_or($ya_fields,'assets')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[assets_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'assets_description')}}</textarea></div>

                    </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Participants Response</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label"  name="yap[participants_response]" value="{{post_value_or($ya_fields,'participants_response')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="yap[participants_response_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'participants_response_description')}}</textarea></div>
                  </div>

                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Invite Spectator</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[invite_spectator]" value="{{post_value_or($ya_fields,'invite_spectator')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[invite_spectator_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'invite_spectator_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Ranking</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[ranking]" value="{{post_value_or($ya_fields,'ranking')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[ranking_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'ranking_description')}}</textarea></div>

                  </div>
                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Manage Scheduler(s)</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[manage_scheduler]" value="{{post_value_or($ya_fields,'manage_scheduler')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[manage_scheduler_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'manage_scheduler_description')}}</textarea></div>

                  </div>
                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Master Scheduler</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[master_scheduler]" value="{{post_value_or($ya_fields,'master_scheduler')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[master_scheduler_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'master_scheduler_description')}}</textarea></div>

                  </div>
                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Invite Users</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[invite_users]" value="{{post_value_or($ya_fields,'invite_users')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[invite_users_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'invite_users_description')}}</textarea></div>

                  </div>
                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Feedback</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[feedback]" value="{{post_value_or($ya_fields,'feedback')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[feedback_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'feedback_description')}}</textarea></div>

                  </div>
                    <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Invoice</div>
                     <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[invoice]" value="{{post_value_or($ya_fields,'invoice')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[invoice_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'invoice_description')}}</textarea></div>
                  </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Scratch Options</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[show_scratch_option]" value="{{post_value_or($ya_fields,'show_scratch_option')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[show_scratch_option_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'show_scratch_option_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Judges FeedBack</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[judges_feed_back]" value="{{post_value_or($ya_fields,'judges_feed_back')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[judges_feed_back_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'judges_feed_back_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Add/Scratch entries</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[add_scratch_entries]" value="{{post_value_or($ya_fields,'add_scratch_entries')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[add_scratch_entries_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'add_scratch_entries_description')}}</textarea></div>

                    </div>


                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Low Participants</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[low_participants]" value="{{post_value_or($ya_fields,'low_participants')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[low_participants_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'low_participants_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Sponsors</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[show_sponsors]" value="{{post_value_or($ya_fields,'show_sponsors')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[show_sponsors_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'show_sponsors_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Show Stable</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[show_stables]" value="{{post_value_or($ya_fields,'show_stables')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[show_stables_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'show_stables_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">1099 Form</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[1099Forms]" value="{{post_value_or($ya_fields,'1099Forms')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[1099Forms_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'1099Forms_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Manage sponsor categories</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[manage_sponsor_categories]" value="{{post_value_or($ya_fields,'manage_sponsor_categories')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[manage_sponsor_categories_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'manage_sponsor_categories_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Shows Spectators</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[show_spectators]" value="{{post_value_or($ya_fields,'show_spectators')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[show_spectators_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'show_spectators_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Show Additional Charges</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[shows_additional_charges]" value="{{post_value_or($ya_fields,'shows_additional_charges')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[shows_additional_charges_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'shows_additional_charges_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Champion Calculator</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[champion_calculator]" value="{{post_value_or($ya_fields,'champion_calculator')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[champion_calculator_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'champion_calculator_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Shows Participants</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[show_participants]" value="{{post_value_or($ya_fields,'show_participants')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[show_participants_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'show_participants_description')}}</textarea></div>
                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Shows Invoice</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[show_invoice]" value="{{post_value_or($ya_fields,'show_invoice')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[show_invoice_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'show_invoice_description')}}</textarea></div>
                    </div>


                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Manage Employee</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[manage_employee]" value="{{post_value_or($ya_fields,'manage_employee')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[manage_employee_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'manage_employee_description')}}</textarea></div>

                    </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Manage Participants</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[manage_participants]" value="{{post_value_or($ya_fields,'manage_participants')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[manage_participants_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'manage_participants_description')}}</textarea></div>
                    </div>

                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Order Supplies Requests</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="yap[order_supplies]" value="{{post_value_or($ya_fields,'order_supplies')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="yap[order_supplies_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ya_fields,'order_supplies_description')}}</textarea></div>
                    </div>

                </div>
               
              </div>
              </div>
            </div>
            <div id="invited_assets"  class="tab-pane fade">
              <div class="row">
                <div class="col-sm-12">
                <h3>Edit (Invited Assets) Labels</h3>
                <div class="padding-25"></div>
                
                <div class="col-sm-12">
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">View Details</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[view_details]" value="{{post_value_or($ia_fields,'view_details')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[view_details_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'view_details_description')}}</textarea></div>

                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">View App</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[view_app]" value="{{post_value_or($ia_fields,'view_app')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[view_app_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'view_app_description')}}</textarea></div>

                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">View Assets</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[view_assets]" value="{{post_value_or($ia_fields,'view_assets')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[view_assets_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'view_assets_description')}}</textarea></div>

                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">My Response History</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[my_response_history]" value="{{post_value_or($ia_fields,'my_response_history')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[my_response_history_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'my_response_history_description')}}</textarea></div>

                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Asset History</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[asset_history]" value="{{post_value_or($ia_fields,'asset_history')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[asset_history_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'asset_history_description')}}</textarea></div>

                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Ranking</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[ranking]" value="{{post_value_or($ia_fields,'ranking')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[ranking_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'ranking_description')}}</textarea></div>

                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Manage Scheduler(s)</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[manage_scheduler]" value="{{post_value_or($ia_fields,'manage_scheduler')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[manage_scheduler_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'manage_scheduler_description')}}</textarea></div>

                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Master Scheduler</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[master_scheduler]" value="{{post_value_or($ia_fields,'master_scheduler')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[master_scheduler_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'master_scheduler_description')}}</textarea></div>

                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Feedback</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[feedback]" value="{{post_value_or($ia_fields,'feedback')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[feedback_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'feedback_description')}}</textarea></div>

                  </div>

                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">Judges FeedBack</div>
                        <div class="col-sm-4" style="margin-top:10px"><input placeholder="Enter Label" name="ia[judges_feed_back]" value="{{post_value_or($ia_fields,'judges_feed_back')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="ia[judges_feed_back_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'judges_feed_back_description')}}</textarea></div>

                    </div>

                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Invoice</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[invoice]" value="{{post_value_or($ia_fields,'invoice')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[invoice_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'invoice_description')}}</textarea></div>
                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">Invite Sub-Participant</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="ia[sub_participants]" value="{{post_value_or($ia_fields,'sub_participants')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="ia[sub_participants_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'sub_participants_description')}}</textarea></div>

                  </div>
                    <div class="row" style="padding:20px 0px;">
                        <div class="col-sm-2" style="margin-top:10px">View Sub-Participant response</div>
                        <div class="col-sm-4" style="margin-top:10px"><input name="ia[view_sub_participants_response]" value="{{post_value_or($ia_fields,'view_sub_participants_response')}}" class="form-control" ></div>
                        <div class="col-sm-6"><textarea name="ia[view_sub_participants_response_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($ia_fields,'view_sub_participants_response_description')}}</textarea></div>

                    </div>

                </div>
               
              </div>
              </div>
            </div>
            <div id="overall_responses"  class="tab-pane fade">
              <div class="row"><div class="col-sm-12">overall responses</div></div>
            </div>
            <div id="spectators"  class="tab-pane fade">

              <div class="row">
                <div class="row">
                <div class="col-sm-12">
                <h3>Edit (Invited Assets) Labels</h3>
                <div class="padding-25"></div>
                
                <div class="col-sm-12">
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">View Details</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="spec[feedback]" value="{{post_value_or($spec_fields,'feedback')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="spec[feedback_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($spec_fields,'feedback_description')}}</textarea></div>

                  </div>
                  <div class="row" style="padding:20px 0px;">
                     <div class="col-sm-2" style="margin-top:10px">View App</div>
                     <div class="col-sm-4" style="margin-top:10px"><input name="spec[master_scheduler]" value="{{post_value_or($spec_fields,'master_scheduler')}}" class="form-control" ></div>
                      <div class="col-sm-6"><textarea name="spec[master_scheduler_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($spec_fields,'master_scheduler_description')}}</textarea></div>

                  </div>
                  
                
                </div>
               
              </div>
              </div>
              </div>
            </div>
              <div id="ManageScheduler"  class="tab-pane fade">

                  <div class="row">
                      <div class="row">
                          <div class="col-sm-12">
                              <h3>Edit (Manage Scheduler) Labels</h3>
                              <div class="padding-25"></div>

                              <div class="col-sm-12">
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Show Title</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[showTitle]" value="{{post_value_or($m_s_fields,'showTitle')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[showTitle_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'showTitle_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">USEF Competition ID</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[USEF_number]" value="{{post_value_or($m_s_fields,'USEF_number')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[USEF_number_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'USEF_number_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Date From</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[DateFrom]" value="{{post_value_or($m_s_fields,'DateFrom')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[DateFrom_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'DateFrom_description')}}</textarea></div>

                                  </div>

                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Date To</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[DateTo]" value="{{post_value_or($m_s_fields,'DateTo')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[DateTo_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'DateTo_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Show Rating</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[ShowRating]" value="{{post_value_or($m_s_fields,'ShowRating')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[ShowRating_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'ShowRating_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Show Type</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[show_type]" value="{{post_value_or($m_s_fields,'show_type')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[show_type_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'show_type_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Location</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[Location]" value="{{post_value_or($m_s_fields,'Location')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[Location_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'Location_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Schedular Name</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[SchedularName]" value="{{post_value_or($m_s_fields,'SchedularName')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[SchedularName_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'SchedularName_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Make Ristriction</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[MakeRistriction]" value="{{post_value_or($m_s_fields,'MakeRistriction')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[MakeRistriction_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'MakeRistriction_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Select Block Time</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[SelectBlockTime]" value="{{post_value_or($m_s_fields,'SelectBlockTime')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[SelectBlockTime_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'SelectBlockTime_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Block Time Title</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[BlockTimeTitle]" value="{{post_value_or($m_s_fields,'BlockTimeTitle')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[BlockTimeTitle_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'BlockTimeTitle_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Multiple Time Selection</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[MultipleTimeSelection]" value="{{post_value_or($m_s_fields,'MultipleTimeSelection')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[MultipleTimeSelection_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'MultipleTimeSelection_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Slots Duration of Classes</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[SlotsDurationOfClasses]" value="{{post_value_or($m_s_fields,'SlotsDurationOfClasses')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[SlotsDurationOfClasses_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'SlotsDurationOfClasses_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Select Class Types</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[selectClassTypes]" value="{{post_value_or($m_s_fields,'selectClassTypes')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[selectClassTypes_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'selectClassTypes_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Select Class</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[selectClass]" value="{{post_value_or($m_s_fields,'selectClass')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[selectClass_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'selectClass_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="margin-top: 10px; margin-bottom: 20px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Class Name</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[ClassName]" value="{{post_value_or($m_s_fields,'ClassName')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[ClassName_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'ClassName_description')}}</textarea></div>
                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Add Classes</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[AddClasses]" value="{{post_value_or($m_s_fields,'AddClasses')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[AddClasses_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'AddClasses_description')}}</textarea></div>
                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Add Prices Classes/divisions</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[AddPricesClasses]" value="{{post_value_or($m_s_fields,'AddPricesClasses')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[AddPricesClasses_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'AddPricesClasses_description')}}</textarea></div>
                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Show Description</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[ShowDescription]" value="{{post_value_or($m_s_fields,'ShowDescription')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[ShowDescription_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'ShowDescription_description')}}</textarea></div>
                                  </div>
                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Info On Invoice</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[ShowInfoOnInvoice]" value="{{post_value_or($m_s_fields,'ShowInfoOnInvoice')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="m_s[ShowInfoOnInvoice_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($m_s_fields,'ShowInfoOnInvoice_description')}}</textarea></div>
                                  </div>

                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Button: Save Schedular</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[SaveSchedularBTN]" value="{{post_value_or($m_s_fields,'SaveSchedularBTN')}}" class="form-control" ></div>
                                  </div>

                                  <div class="row" style="margin-top: 10px;">
                                      <div class="col-sm-2" style="float:left; line-height: 38px;">Button: Add Schedular </div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="m_s[AddSchedularBTN]" value="{{post_value_or($m_s_fields,'AddSchedularBTN')}}" class="form-control" ></div>
                                  </div>

                              </div>

                          </div>
                      </div>
                  </div>
              </div>
              <div id="inviteParticipants"  class="tab-pane fade">

                  <div class="row">
                      <div class="row">
                          <div class="col-sm-12">
                              <h3>Edit (Invite Participants) Labels</h3>
                              <div class="padding-25"></div>

                              <div class="col-sm-12">
                                  <div class="row" style="padding:20px 0px;">
                                      <div class="col-sm-2" style="margin-top:10px">Select Asset</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="i_p[selectAsset]" value="{{post_value_or($i_p_fields,'selectAsset')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="i_p[selectAsset_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($i_p_fields,'selectAsset_description')}}</textarea></div>

                                  </div>
                                  <div class="row" style="padding:20px 0px;">
                                      <div class="col-sm-2" style="margin-top:10px">Select Show</div>
                                      <div class="col-sm-4" style="margin-top:10px"><input name="i_p[selectShow]" value="{{post_value_or($i_p_fields,'selectShow')}}" class="form-control" ></div>
                                      <div class="col-sm-6"><textarea name="i_p[selectShow_description]" placeholder="Label Description"  class="form-control" >{{post_description_or($i_p_fields,'selectShow_description')}}</textarea></div>

                                  </div>


                              </div>

                          </div>
                      </div>
                  </div>
              </div>

            <div class="row">
                  <div class="buttons-holder">
                    {!! Form::submit("Save" , ['class' =>"btn btn-lg btn-success",'id'=>'storeonly']) !!} 
                    <a  href="{{URL::to('/admin')}}" class="btn btn-lg btn-default" value="Cancel">Cancel</a>
                  </div>
            </div>

          </div>
          {!! Form::close() !!}

@endsection

@section('footer-scripts')
   <script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>
@endsection

