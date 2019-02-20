<?php
    /**
     * This is Timeline Controller to control all the Time line 
     * Post, Events and functions.
     *
     * @author Faran Ahmed (Vteams)
     */
namespace App\Http\Controllers;

use App\Spectators;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\User;
use App\InvitedUser;
use App\Participant;
use App\Post;
use App\Template;
use App\Comment;
use App\PostTemplate;
use Illuminate\Support\Facades\Storage;
use App\Mail\InviteUser;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

class TimelineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filtered=null)
    {
        $filtered_temp = 0;
        if (isset($filtered)) {
            $filtered_temp = nxb_decode($filtered);
        }
        $user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;
        $template_access_array = user_template_accessable_appwise();
        $template_access_array2 = user_template_accessable();
        $friends = user_app_friends();
        //$associated =  Template::whereIn('id',$template_access_array)->get();
        $associated = $template_access_array;
        $associatedTemplate = $template_access_array2;
        //dd($template_access_array);
        $posts = Post::where('accessable_to',1)->where(function ($query) use ($template_access_array) {
                        foreach ($template_access_array as $index => $temp_id) {
                           $app = getAppFromInviteeAndTemplateIds($temp_id["invitee_id"],$temp_id["template_id"]);
                           if ($index == 0) {
                                $query->where('app_ids', 'like', '%"'.$app->id.'"%');
                           }else{
                                $query->orWhere('app_ids', 'like', '%"'.$app->id.'"%');  
                           }
                        }
                    });
                //->whereIn('user_id',$friends);
                
                if ($filtered_temp != 0) {
                    $posts->where('app_ids','like','%"'.$filtered_temp.'"%');
                }
                   
        $posts = $posts->with('comments','comments.Commentowners')->orderBy('id', 'desc')->paginate(3);
        //dd($posts->toArray());
        //This is to get the posts that are public
        $postsTemplate =Post::where('accessable_to',1)->whereHas('postTemplate',function ($query) use ($template_access_array2) {
                        $query->whereIn('template_id',array_column($template_access_array2,'template_id'));
                     });
        if ($filtered_temp != 0) {
            $postsTemplate->where('app_ids','like','%"'.$filtered_temp.'"%');
        }
        $postsTemplate =$postsTemplate->with('comments','comments.Commentowners')->orderBy('id', 'desc')->paginate(3);
        return view('timeline.index')->with(compact('user_id','associated','associatedTemplate','posts','postsTemplate'));
    }
    /**
     * Add Post on timeline.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddPost(Request $request)
    {
        $user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;
        $disk = Storage::disk('s3');
        $save = array();

        $post = $request->get('post');
        
        if($request->get('post_types') == 1 ){
          $app_ids = json_encode(array_values($request->get('invited_app_ids')));
        }else{
          $app_ids = null;
        }
        if ($request->file('post')!== null) {
            $list = $request->file('post');
            foreach ($list['images'] as $file) {
                $extension = $file->getClientOriginalExtension();
                $nameoffile = $file->getClientOriginalName();
                $file->move(public_path('uploads'), $user_id.$nameoffile);
                $path = public_path('uploads/').$user_id.$nameoffile;
                 //Resize
                imageResizeHelper($path);
                $save[] = $disk->putFile("timeline/$user_id",new File($path),"public");
                //Delete image
                if(\File::exists($path)){
                    \File::delete($path);
                }
            }
        }
        $post['images'] = $save;
        $jsonPost = json_encode($post);

        $model = new Post();
        $model->user_id = $user_id;
        $model->comment = $jsonPost;
        $model->app_ids = $app_ids;
        $model->accessable_to = 1;
        $model->save();

        if($request->get('post_types') == 2 ){
          $app_ids = $request->get('invited_app_ids');
          foreach ($app_ids  as $template_id) {
              $PT = new PostTemplate();
              $PT->template_id=$template_id;
              $PT->post_id=$model->id;
              $PT->save();
          }
        }

        return redirect()->route('timelinecontroller-index');
    } 
    /**
     * Add Post on timeline.
     *
     * @return \Illuminate\Http\Response
     */
    public function EditPostAjax(Request $request)
    {
        $post = array();
        $model = Post::find($request->get('id'));
        $post = json_decode($model->comment);
        $post->msg = $request->get('post');
        $jsonPost = json_encode($post);
        if ($model) {
            $model->comment = $jsonPost;
            $model->accessable_to = 1;
            $model->update();
            redirect()->route('timelinecontroller-index');
        }
        
    }
    /**
     * Edit Post Comment on timeline.
     *
     * @return \Illuminate\Http\Response
     */
    public function EditCommentAjax(Request $request)
    {
        $post = array();
        $model = Comment::find($request->get('id'));
        
        if ($model) {
            $model->body = $request->get('post');
            $model->update();
            redirect()->route('timelinecontroller-index');
        }   
    }
    /**
     * Like or dislike post. Use toggle for the post, Made post class implement with HasLike.
     *
     * @return \Illuminate\Http\Response
     */
    public function likeDislikeAjax(Request $request)
    {
        $model = Post::find($request->get('id'));
        if ($model) {
            $model->likeToggle(); 
            $likes = $model->likesCount;
            return response()->json([
                'status' => 'true',
                'likes' =>$likes
            ], 200);
        }   
    }
    /**
    * WriteComment, Add a comment on Post.
    *
    * @return json response, Comment.
    */
    public function writeCommentOnPost(Request $request)
    { 
        $user_id   = \Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'comment' => 'required|min:5',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();

            
            return response()->json([
                'status' => 'false',
                'request' => '1',
                'data' =>$errors->first(),

            ], 200);
        }else{
            $post = Post::find($request->get('post_id'));
            if (!$post) {
                return response()->json([
                    'status' => 'false',
                    'request' => '1',
                    'data' =>'Post Id not correct'
                ], 200);
            }

            $commentsOnThread = Comment::create([
                'body' => $request->get('comment'),
                'commentable_id' => $request->get('post_id'),
                'user_id' => $user_id,
                'commentable_type' => 'App/Post',
            ]); 
            $idsofcom = $commentsOnThread->id;
            $LastComment = \App\Comment::where('id',$idsofcom)->with("Commentowners")->first();
        
            $html ='<div class="media comment-single">
                          <div class="media-user-picture">
                              <img class="mr-2" src="'.userImage($commentsOnThread->user_id).'" alt="Sample photo">
                          </div>
                          <div class="media-body">
                              <div class="d-flex flex-wrap">
                                  <div class="mr-auto"><h5>'.$LastComment->Commentowners->name.'<small>'.getTimeOfPost($LastComment->created_at).'</small></h5></div>
                              </div>
                              <div class="blog-content">
                                  <p class="comment_contents">'.$LastComment->body.'</p>
                                  <input type="hidden" class="comment-id" value="'.$idsofcom.'" />
                                  <div class="edit-delete-action post-actions">
                                    <a href="javascript:void(0);" class="edit-comment"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" class="delete-comment"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                  </div>
                              </div>
                        </div>
                      </div>';
            return response()->json([
                'status' => 'true',
                'request' => '0',
                'data' =>$html
            ], 200);
        
            }
    } 
    /**
     * Users who have liked the post will be displayed.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLikedUsersAjax(Request $request)
    {
        $post = Post::find($request->get('id'));
        if ($post->likes->count()) {
            $html = "";
           foreach ($post->likes as $entity) {
               $html .= "<div class=\"row\" style=\"margin-bottom:10px;margin-left:1px\">";
               $html .= "<div style='width: 40px; float: left' class=\"user-image\"><img width='30' src='".userImage($entity->user_id)."' alt='image user' /></div>";
               $html .= "<h4 style=\"padding-top:3px\">".getUserNamefromid($entity->user_id)."</h4>";
               $html .= "</div>";
            }

        }else{
            return response()->json([
            'status' => 'false',
            ], 200);
        }
        
        return response()->json([
            'status' => 'true',
            'html' =>$html
        ], 200); 
    }  
    /**
     * Ajax Delete Post from timeline.
     *
     * @return \Illuminate\Http\Response
     */
    public function deletePostAjax(Request $request)
    {
        $disk = getStorageDisk();
        $id = $request->get('id');
        $model = Post::find($id);
        if ($model) {
            $post = json_decode($model->comment);
            foreach($post->images as $path){
                if($disk->exists($path)) {
                  $done = $disk->delete($path);
                }else{
                  $done = "Fail";
                }
            }
            $success = $model->delete();
            return "true";
        }
        else{
            return "false";
        } 
    } 
    /**
     * Ajax Delete Post from timeline.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCommentAjax(Request $request)
    {
        $id = $request->get('id');
        $model = Comment::find($id);
        if ($model) {
            $success = $model->delete();
            return "true";
        }
        else{
            return "false";
        } 
    } 
}
