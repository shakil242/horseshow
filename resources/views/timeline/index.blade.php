@extends('layouts.equetica2')

@section('main-content')

@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Blog";
          $added_subtitle = "";
          $remove_search = 1;
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">  
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tabs-header mb-30">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#TabPrivate">Private</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#TabPublic">Public</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTab">
                                <div class="tab-pane fade show active" id="TabPrivate">
                                <div class="row">
                                  <div class="col-md-3 contetn-aside">
                                    <div class="row">
                                            <div class="col-md-12"><h3 class="mb-20">Filters</h3></div>
                                            <div class="col-lg-12 detail-area pb-30">
                                                <ul class="listing nav-pills nav-filters-added">
                                                    <li class="active"><span class="icon"></span><a class="text-secondary" href="{{url('timeline/index')}}">All</a></li>
                                                     @if($associated)
                                                        @foreach($associated as $templat)
                                                          <?php $app = getAppFromInviteeAndTemplateIds($templat["invitee_id"],$templat["template_id"])?>
                                                          <li><span class="icon"></span><a class="text-secondary" href="{{url('timeline/index/filter').'/'.nxb_encode($app->id) }}">{{GetTemplateNameTimeline($templat["template_id"],$templat["invitee_id"])}}</a></li>
                                                        @endforeach
                                                    @endif
                                                  </ul>
                                            </div>
                                            
                                        </div>
                                  </div>
                                  <!-- Right Panel -->
                                    <div class="col-md-9 card-small-area">
                                      
                                  
                                      <div class="timeline-share">
                                          {!! Form::open(['url'=>'timeline/addPost/','method'=>'post','files'=>true,'class'=>'']) !!}
                                               <!-- Post Message -->
                                        <div class="timeline-holder post-message box-shadow bg-white mb-40">
                                            <div class="container-fluid pt-10">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                      <div class="share-text">
                                                          <textarea name="post[msg]" placeholder="Share An Update" class="mCustomScrollbar" required="required"></textarea>
                                                          <input type="text" class="form-control videoLink" placeholder="Paste the video url" name="post[video]">
                                                          <div class="video-preview">
                                                              <span class="video_msg">Please paste/add youtube link for the video here.</span>
                                                          </div>
                                                          <input type="hidden" name="post_types" value="1">
                                                          <output id="result" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        
                                                        <div class="form-group">
                                                            
                                                              @if($associated)
                                                              <select name="invited_app_ids[]"  title="Select Industry" multiple class="selectpicker show-tick form-control" data-live-search="true" required>
                                                                @foreach($associated as $templat)
                                                                    <?php $app = getAppFromInviteeAndTemplateIds($templat["invitee_id"],$templat["template_id"])?>
                                                                    <option value="{{$app->id}}">{{GetTemplateNameTimeline($templat["template_id"],$templat["invitee_id"])}}</option>
                                                                @endforeach
                                                              </select>
                                                              @endif
                                                            
                                                        </div>
                                                        
                                                        <!-- For Video Image -->
                                                        <!-- <div class="selected-image">
                                                            <img src="img/image.jpg" />
                                                            <a href="#" class="delete"><i class="fa fa-trash"></i></a>
                                                        </div> -->
                                                        <!-- For Video URL -->
<!--
                                                        <div class="video-url">
                                                            <div class="form-group">
                                                                <input class="form-control form-control-bb-only" id="" placeholder="Enter Value" type="text">
                                                            </div>
                                                        </div>
-->
                                                    </div>
                                                </div>
                                                <div class="row bottom-area">
                                                    <div class="share-options col-auto mr-auto pt-5 row">
                                                      <div id="fileInput" class="col-sm-1 mr-5">
                                                          <a href="javascript:void(0);"><label for="FileID"><i class="fa fa-2x fa-picture-o" aria-hidden="true"></i></label> </a>
                                                          <input type="file" class="uploadimage-post" multiple name="post[images][]" accept="image/*" id="FileID"/>
                                                      </div>
                                                      <a  href="javascript:void(0);" class="video-camera-click col-sm-2" >
                                                        <i class="fa fa-2x fa-video-camera" aria-hidden="true"></i>
                                                      </a>
                                                    </div>
                                                    <div class="">
                                                        <button class="btn btn-primary rounded-0 pr-50 pl-50">Share <i class="fa ml-10 fa-paper-plane"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                          {!! Form::close() !!}
                                      </div>

                                       <!-- Blog Felds -->
                                        <div class="infinite-scroll">
                                        @foreach($posts as $post)
                                        <div class="history-holder media blog-chat mb-30">
                                            <div class="media-user-picture">
                                                <img src="{{userImage($post->user_id)}}" alt="image user" />
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-wrap">
                                                    <div class="mr-auto"><h5> {{ $uname = getUserNamefromid($post->user_id) }} <small>{{getTimeOfPost($post->created_at)}}</small></h5></div>
                                                    <div class="">
                                                        <?php $assigned_apps = json_decode($post->app_ids); ?>
                                                          @if(count($assigned_apps)>0)
                                                            
                                                            @foreach($assigned_apps as $l_template)
                                                                <span class="badge mr-5 mb-5">@<?php echo GetTemplateNameFromAppId($l_template) ?> </span>
                                                            @endforeach
                                                          @endif
<!--                                                        <span class="badge mr-5 mb-5">Horse Show </span>-->
                                                    </div>
                                                </div>
                                                <div class="blog-content">
                                                    <p class="post_contents"> <?php echo $postedMSG = decoded_post($post->comment); ?> </p>
                                                    <div class="edit-delete-action">
                                                      @if($post->user_id == $user_id)
                                                        <a href="javascript:void(0);" class="edit-post"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                        <a href="javascript:void(0);" class="delete-post"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                      @endif
                                                    </div>
                                                    <div class="row">
                                                      @if(doCommentHave($post->comment,'Image'))
                                                          @foreach(decoded_post($post->comment,1) as $images)
                                                          <div class="share-image-holder col-sm-4">
                                                            <a class="thumbnail" href="#" data-image-id="" data-title="{{$uname}} post" data-caption="{{limit_words($postedMSG,8)}}" data-toggle="modal" data-image="{{getImageS3($images)}}" data-target="#image-gallery">
                                                              <img src="{{getImageS3($images)}}" alt="shared images" class="img-responsive" />
                                                            </a>
                                                          </div>
                                                          @endforeach
                                                      @endif
                                                      @if(doCommentHave($post->comment,'Video'))
                                                        <div class="share-image-holder">
                                                            <iframe width="580" height="320" src="http://www.youtube.com/embed/{{getYoutubeId(decoded_post($post->comment,2))}}" frameborder="0" allowfullscreen></iframe>
                                                        </div>
                                                     @endif
                                                      <input type="hidden" class="post-id" value="{{$post->id}}" />
                                                    </div>
                                                </div>
                                                <div class="history-actions-holder">
                                                  <div class="row">
                                                      <div class="col-sm-6">
                                                      <ul class="Activity-Report">
                                                          <?php ( $post->liked ? $clicked ='clicked'  : $clicked = '' ); ?>
                                                          <li class="value"><a href="javascript:void(0);" class="like-post {{$clicked}}"><span class="fa fa-thumbs-up"></span> <a href="javascript:void(0);" class="like-count-viewer"> <span class="counts">{{$post->likesCount}}</span></a></li>
                                                          <li class="value"><a href="javascript:void(0);" class="add-comment"><span class="fa fa-commenting"></span></a><span class="pl-10">{{count($post->comments)}}</span></li>
                                                      </ul> 
                                                      </div>
                                                  </div>
                                                
                                              </div>
                                                <!-- Normal Icons
                                                <ul class="Activity-Report">
                                                    <li class="value"><a class="pr-10" href=""><img src="img/icons/icon-rank-normal.svg" /></a> 5</li>
                                                    <li class="value"><a class="pr-10" href=""><img src="img/icons/icon-chat-normal.svg" /></a>  5</li>
                                                </ul>
                                                -->
                                                
                                                <!-- Others User Commetns -->
                                                <div class="others-comments">
                                                    <div class="history-inner">
                                                      <div class="comment-list">
                                                            @foreach($post->comments as $comment)

                                                            <div class="media comment-single">
                                                              <div class="media-user-picture">
                                                                  <img class="mr-2" src="{{userImage($comment->user_id)}}" alt="Sample photo">
                                                              </div>
                                                              
                                                              <div class="media-body">
                                                                  <div class="d-flex flex-wrap">
                                                                      <div class="mr-auto"><h5>{{$comment->Commentowners->name}} <small>{{getTimeOfPost($comment->created_at)}}</small></h5></div>
                                                                  </div>
                                                                  
                                                                  <div class="blog-content">
                                                                      <p class="comment_contents"> {{$comment->body}} </p>
                                                                      <input type="hidden" class="comment-id" value="{{$comment->id}}" />
                                                                      
                                                                          @if($comment->user_id == $user_id)
                                                                          <div class="edit-delete-action post-actions">
                                                                          <a href="javascript:void(0);" class="edit-comment"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                                          </div>
                                                                          @endif
                                                                          @if($post->user_id == $user_id || $comment->user_id == $user_id) 
                                                                          <div class="edit-delete-action post-actions">
                                                                          <a href="javascript:void(0);" class="delete-comment"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                                          </div>
                                                                          @endif
                                                                    
                                                                  </div>
                                                            </div>
                                                          </div>
                                                            @endforeach

                                                      </div>
                                                      
                                                    </div>
                                                </div>
                                                
                                                <!-- Enter Comment / Footer -->
                                                <div class="footer">
                                                    <div class="">
                                                        <div class="form-group d-flex flex-wrap mb-5">
                                                            <div class="row col-sm-12 reply-comment-holder">
                                                                <div class="col-sm-10"><textarea name="comment" class="commentable form-control message" placeholder="Add Comment"></textarea></div>
                                                                <div class="col-sm-2"><button type="button" class="btn btn-primary btn-comment">Comment</button></div>
                                                                <div class="error-msg"></div>
                                                                <input type="hidden" class="post_id" value="{{$post->id}}">
                                                              </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        @endforeach
                                      {{ $posts->links() }}
                                      </div>
                                    </div>
                                </div>


                                </div>
                                 <!-- Tab Public Data-->
                                <div class="tab-pane fade" id="TabPublic"><div class="row">
                                  <div class="col-md-3 contetn-aside">
                                    <div class="row">
                                            <div class="col-md-12"><h3 class="mb-20">Filters</h3></div>
                                            <div class="col-lg-12 detail-area pb-30">
                                                <ul class="listing nav-pills nav-filters-added">
                                                    <li class="active"><span class="icon"></span><a class="text-secondary" href="{{url('timeline/index')}}">All</a></li>
                                                     @if($associatedTemplate)
                                                        @foreach($associatedTemplate as $templat)
                                                          <?php $app = getAppFromInviteeAndTemplateIds($templat["invitee_id"],$templat["template_id"])?>
                                                          <li><span class="icon"></span><a class="text-secondary" href="{{url('timeline/index/filter').'/'.nxb_encode($app->id) }}">{{GetTemplateNameTimeline($templat["template_id"],$templat["invitee_id"])}}</a></li>
                                                        @endforeach
                                                    @endif
                                                  </ul>
                                            </div>
                                            
                                        </div>
                                  </div>
                                  <!-- Right Panel -->
                                    <div class="col-md-9 card-small-area">
                                      
                                  
                                      <div class="timeline-share">
                                          {!! Form::open(['url'=>'timeline/addPost/','method'=>'post','files'=>true,'class'=>'']) !!}
                                               <!-- Post Message -->
                                        <div class="timeline-holder post-message box-shadow bg-white mb-40">
                                            <div class="container-fluid pt-10">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                      <div class="share-text">
                                                          <textarea name="post[msg]" placeholder="Share An Update" class="mCustomScrollbar" required="required"></textarea>
                                                          <input type="text" class="form-control videoLink" placeholder="Paste the video url" name="post[video]">
                                                          <div class="video-preview">
                                                              <span class="video_msg">Please paste/add youtube link for the video here.</span>
                                                          </div>
                                                          <input type="hidden" name="post_types" value="2">

                                                          <output id="result2" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        
                                                        <div class="form-group">
                                                            
                                                              @if($associatedTemplate)
                                                              <select name="invited_app_ids[]" multiple title="Select Industry" class="selectpicker show-tick form-control" data-live-search="true" required>
                                                               @foreach($associatedTemplate as $templat)
                                                                    <option value="{{$templat['template_id']}}">{{GetTemplateName($templat["template_id"])}}</option>
                                                                @endforeach
                                                              </select>
                                                              @endif
                                                            
                                                        </div>
                                                        
                                                        <!-- For Video Image -->
                                                        <!-- <div class="selected-image">
                                                            <img src="img/image.jpg" />
                                                            <a href="#" class="delete"><i class="fa fa-trash"></i></a>
                                                        </div> -->
                                                        <!-- For Video URL -->
<!--
                                                        <div class="video-url">
                                                            <div class="form-group">
                                                                <input class="form-control form-control-bb-only" id="" placeholder="Enter Value" type="text">
                                                            </div>
                                                        </div>
-->
                                                    </div>
                                                </div>
                                                <div class="row bottom-area">
                                                    <div class="share-options col-auto mr-auto pt-5 row">
                                                      <div id="fileInput" class="col-sm-1 mr-5">
                                                          <a href="javascript:void(0);"><label for="FileID2"><i class="fa fa-2x fa-picture-o" aria-hidden="true"></i></label> </a>
                                                          <input type="file" class="uploadimage-post" multiple name="post[images][]" accept="image/*" id="FileID2"/>
                                                      </div>
                                                      <a  href="javascript:void(0);" class="video-camera-click col-sm-2" >
                                                        <i class="fa fa-2x fa-video-camera" aria-hidden="true"></i>
                                                      </a>
                                                    </div>
                                                    <div class="">
                                                        <button class="btn btn-primary rounded-0 pr-50 pl-50">Share <i class="fa ml-10 fa-paper-plane"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                          {!! Form::close() !!}
                                      </div>

                                       <!-- Blog Felds -->
                                       <div class="infinite-scroll">
                                        @foreach($postsTemplate as $post)
                                        <div class="history-holder media blog-chat mb-30">
                                            <div class="media-user-picture">
                                                <img src="{{userImage($post->user_id)}}" alt="image user" />
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-wrap">
                                                    <div class="mr-auto"><h5> {{ $uname = getUserNamefromid($post->user_id) }} <small>{{getTimeOfPost($post->created_at)}}</small></h5></div>
                                                    <div class="">
                                                        <?php $assigned_apps = json_decode($post->app_ids); ?>
                                                          @if(count($assigned_apps)>0)
                                                            
                                                            @foreach($assigned_apps as $l_template)
                                                                <span class="badge mr-5 mb-5">@<?php echo GetTemplateNameFromAppId($l_template) ?> </span>
                                                            @endforeach
                                                          @endif
<!--                                                        <span class="badge mr-5 mb-5">Horse Show </span>-->
                                                    </div>
                                                </div>
                                                <div class="blog-content">
                                                    <p class="post_contents"> <?php echo $postedMSG= decoded_post($post->comment); ?> </p>
                                                      @if($post->user_id == $user_id)
                                                    <div class="edit-delete-action">
                                                        <a href="javascript:void(0);" class="edit-post"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                        <a href="javascript:void(0);" class="delete-post"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    </div>
                                                      @endif
                                                    <div class="row">
                                                      @if(doCommentHave($post->comment,'Image'))
                                                          @foreach(decoded_post($post->comment,1) as $images)
                                                          <div class="share-image-holder col-sm-4">
                                                            <a class="thumbnail" href="#" data-image-id="" data-title="{{$uname}} post" data-caption="{{limit_words($postedMSG,8)}}" data-toggle="modal" data-image="{{getImageS3($images)}}" data-target="#image-gallery">
                                                              <img src="{{getImageS3($images)}}" alt="shared images" class="img-responsive" />
                                                            </a>
                                                          </div>
                                                          @endforeach
                                                      @endif
                                                      @if(doCommentHave($post->comment,'Video'))
                                                        <div class="share-image-holder">
                                                            <iframe width="580" height="320" src="http://www.youtube.com/embed/{{getYoutubeId(decoded_post($post->comment,2))}}" frameborder="0" allowfullscreen></iframe>
                                                        </div>
                                                     @endif
                                                      <input type="hidden" class="post-id" value="{{$post->id}}" />
                                                    </div>
                                                </div>
                                                <div class="history-actions-holder">
                                                  <div class="row">
                                                      <div class="col-sm-6">
                                                      <ul class="Activity-Report">
                                                          <?php ( $post->liked ? $clicked ='clicked'  : $clicked = '' ); ?>
                                                          <li class="value"><a href="javascript:void(0);" class="like-post {{$clicked}}"><span class="fa fa-thumbs-up"></span> <a href="javascript:void(0);" class="like-count-viewer"> <span class="counts">{{$post->likesCount}}</span></a></li>
                                                          <li class="value"><a href="javascript:void(0);" class="add-comment"><span class="fa fa-commenting"></span></a><span class="pl-10">{{count($post->comments)}}</span></li>
                                                      </ul> 
                                                      </div>
                                                  </div>
                                                
                                              </div>
                                                <!-- Normal Icons
                                                <ul class="Activity-Report">
                                                    <li class="value"><a class="pr-10" href=""><img src="img/icons/icon-rank-normal.svg" /></a> 5</li>
                                                    <li class="value"><a class="pr-10" href=""><img src="img/icons/icon-chat-normal.svg" /></a>  5</li>
                                                </ul>
                                                -->
                                                
                                                <!-- Others User Commetns -->
                                                <div class="others-comments">
                                                    <div class="history-inner">
                                                      <div class="comment-list">
                                                            @foreach($post->comments as $comment)

                                                            <div class="media comment-single">
                                                              <div class="media-user-picture">
                                                                  <img class="mr-2" src="{{userImage($comment->user_id)}}" alt="Sample photo">
                                                              </div>
                                                              
                                                              <div class="media-body">
                                                                  <div class="d-flex flex-wrap">
                                                                      <div class="mr-auto"><h5>{{$comment->Commentowners->name}} <small>{{getTimeOfPost($comment->created_at)}}</small></h5></div>
                                                                  </div>
                                                                  
                                                                  <div class="blog-content">
                                                                      <p class="comment_contents"> {{$comment->body}} </p>
                                                                      <input type="hidden" class="comment-id" value="{{$comment->id}}" />
                                                                      
                                                                          @if($comment->user_id == $user_id)
                                                                          <div class="edit-delete-action post-actions">
                                                                          <a href="javascript:void(0);" class="edit-comment"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                                          </div>
                                                                          @endif
                                                                          @if($post->user_id == $user_id || $comment->user_id == $user_id) 
                                                                          <div class="edit-delete-action post-actions">
                                                                          <a href="javascript:void(0);" class="delete-comment"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                                          </div>
                                                                          @endif
                                                                      
                                                                  </div>
                                                            </div>
                                                          </div>
                                                            @endforeach

                                                      </div>
                                                      
                                                    </div>
                                                </div>
                                                
                                                <!-- Enter Comment / Footer -->
                                                <div class="footer">
                                                    <div class="">
                                                        <div class="form-group d-flex flex-wrap mb-5">
                                                            <div class="row col-sm-12 reply-comment-holder">
                                                                <div class="col-sm-10"><textarea name="comment" class="commentable form-control message" placeholder="Add Comment"></textarea></div>
                                                                <div class="col-sm-2"><button type="button" class="btn btn-primary btn-comment">Comment</button></div>
                                                                <div class="error-msg"></div>
                                                                <input type="hidden" class="post_id" value="{{$post->id}}">
                                                              </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        @endforeach
                                      {{ $postsTemplate->links() }}
                                      </div>
                                    </div>
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
<div id="likeModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">People Liked this post</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Image</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <img src="" class="img-responsive" id="responsivess" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div> -->

<div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="image-gallery-title"></h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">
                <img id="image-gallery-image" class="img-responsive" src="">
            </div>
            <div class="modal-footer">

                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="show-previous-image">Previous</button>
                </div>

                <div class="col-md-8 text-justify" id="image-gallery-caption">
                   
                </div>
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                <div class="col-md-2">
                    <button type="button" id="show-next-image" class="btn btn-default">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="ajax-loading" class="loading-ajax"></div>
@endsection
@section('footer-scripts')
<script src="{{ asset('/js/vender/jquery.jscroll.js') }}"></script>
<script src="{{ asset('/js/custom-tabs-cookies-new.js') }}"></script>
<script src="{{ asset('/js/timeline.js') }}"></script>

@endsection