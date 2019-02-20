
                <!--Ranking WRT Leadership Chart !-->
                <div class="row top-buffer"></div>


                <div class="row">

                  <div class="col-sm-12 padding-25"><h3><strong> Leaderboard & Positions </strong></h3></div>
                  <div class="col-sm-12 row">
                    <div class="col-sm-4"> 
                      <div class="row">
                        <div class="col-sm-12 tblview">
                          <div class="action-holder">
                            <form action="#">
                              <div class="search-form">
                                <input class="form-control input-sm" placeholder="Search By Name" id="mySearchTerm2" type="search">
                                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                              </div>
                            </form>
                          </div>
                          <table id="datatable_secondary" class="defaultsort_second table table-striped">
                            <thead>
                              <tr>
                                <th>Position</th>
                                <th>Name</th>
                                <th>Score</th>
                              </tr>
                            </thead>
                            <tbody>
                             <?php 
                                $total_users_responses = 0;
                                $score = 0;
                                $highest_score =0;
                                $usersResponseArray = array();
                                $myAchivedScore = 0;
                              ?>
                              @foreach($participantResponse as $PR)
                              
                              <tr>
                                  <td><i class="fa fa-users" aria-hidden="true"></i></td>
                                  <td><?php echo $u_name = getUserNamefromid($PR->user_id);?></td>
                                  <td><?php echo $score = getParticipantRankResponse($asset_id,$invitee_id,$template_id,$PR->user_id,$form_id) ?></td>
                              </tr>
                              <?php 
                                //getcurrent User score
                                if (\Auth::user()->id == $PR->user_id) {
                                  $myAchivedScore = $score;
                                }
                                $usersResponseArray[] = ["u_name" => $u_name,"score" => $score,"user_id"=>$PR->user_id ]; 
                                $total_users_responses = $total_users_responses+1;
                                if ( $score >= $highest_score){
                                  $highest_score = $score;
                                }
                              ?>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-8">
                      <div id="container2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                  </div>
                </div>
                 @include('ranking.participants.graphScript') 

                 <!--Ranking WRT Leadership Chart !-->
                <div class="row top-buffer"></div>
                <div class="row">
                  <div class="col-sm-12 padding-25"><h3><strong> Value Chart </strong></h3></div>
                  <div class="col-sm-12 row">
                    <div class="col-sm-4"> 
                      <div class="row">
                        <div class="col-sm-12">
                          <p> <label>Score Achived = </label> <em>{{$myAchivedScore }}</em></p>
                          <p> <label>Total Value = </label> <em><?php echo $total = $myAchivedScore * getPointsForTemplate($template_id) ?> $ </em></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>