
                
                <div class="row">
                  <div class="col-sm-12 padding-25"><h3><strong> Your Scores And Horse Value</strong></h3></div>
                  <div class="col-sm-12 row">
                    <div class="col-sm-8">
                      <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                    <div class="col-sm-4"> 
                      <div class="row">
                        <div class="col-sm-12 tblview">
                          <div class="action-holder">
                            <form action="#">
                              <div class="search-form">
                                <input class="form-control input-sm" placeholder="Search By Name" id="mySearchTerm" type="search">
                              </div>
                            </form>
                          </div>
                          <table id="datatable" class="defaultsort_first table table-striped">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Score</th>
                                <th> Values </th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php  $indexer =0; ?>
                              @foreach($participantOwnResponse as $PR)
                              <tr>
                                  <td>{{$indexer=$indexer+1}}</td>
                                  <td>
                                    <a class="HorseAsset" target="_blank" href="{{URL::to('master-template') }}/{{nxb_encode($PR->id)}}/horseProfile">{{GetAssetName($PR)}}</a>
                                  </td>
                                  <td><?php $myAchivedScore = getAllRankResponseCumulative(0,$PR->id); 
                                            $ScoreOfParticipating = getPointsOfParticipation($PR->id);
                                            $ScoreOfTrainer = getTrainerFeedbackPoints($PR->id);
                                            echo $myAchivedScore+$ScoreOfParticipating+$ScoreOfTrainer;
                                      ?>
                                  </td>
                                  <td><em>($) <?php 
                                  $templatePoints = getPointsForTemplate($PR->template_id);
                                  echo $total = ($myAchivedScore*$templatePoints)+($ScoreOfParticipating*$templatePoints)+($ScoreOfTrainer*$templatePoints); ?></em></td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

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
                              ?>
                              @foreach($participantResponse as $PR)
                              
                              <tr>
                                  <td><i class="fa fa-users" aria-hidden="true"></i></td>
                                  <td><a class="HorseAsset" target="_blank" href="{{URL::to('master-template') }}/{{nxb_encode($PR->id)}}/horseProfile"><?php echo $a_name = GetAssetName($PR) ?></a></td>
                                  <td><?php echo $score = ((getAllRankResponseCumulative(0,$PR->id))+(getPointsOfParticipation($PR->id))+(getTrainerFeedbackPoints($PR->id) )); ?></td>
                              </tr>
                              <?php 
                                $usersResponseArray[] = ["u_name" => $a_name,"score" => $score ]; 
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
                 @include('ranking.graphScript') 
