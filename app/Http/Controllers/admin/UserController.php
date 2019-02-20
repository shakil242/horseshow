<?php
    /**
     * This is Module Controller to control all the Templates in admin project
     *
     * @author Faran Ahmed (Vteams)
     */


namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\InvitedUser;
use App\Template;
use App\Participant;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($template_id)
    {
        $collection = InvitedUser::where('template_id',$template_id)->get();
        $template = Template::where('id',$template_id)->first();
        return view('admin.mastertemplates.user.index')->with(compact('template','collection'));
    }
    /**
     * Display all listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function usersListing()
    {
        $collection = User::where('id', '!=', \Auth::id())->get();
        return view('admin.user.index')->with(compact('collection'));
    }
    /**
     * Display all User's invited participants.
     *
     * @return \Illuminate\Http\Response
     */
    public function uParticipantListing($user_id,$template_id)
    {
        $collection = Participant::where('invitee_id', $user_id)->where('template_id',$template_id)->orderBy('id','desc')->get();
        return view('admin.user.participants')->with(compact('collection','template_id'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($masterid)
    {
        //$design_template = TemplateDesign::where('template_id',$masterid)->first();
        return view('admin.mastertemplates.design.create')->with(compact('masterid','design_template'));;
    }

    /**
     * Block user to deny access the master template/ App.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function blockUser($invited_id)
    {
        
        $model = InvitedUser::findOrFail($invited_id);
        $model->block = 1;
        $model->save();
        \Session::flash('message', 'User has been blocked successfully');
        return \Redirect::back();
    }
     /**
     * Un Block user to access the master template/ App.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unBlockUser($invited_id)
    {
        
        $model = InvitedUser::findOrFail($invited_id);
        $model->block = 0;
        $model->save();
        \Session::flash('message', 'User has been Un-blocked successfully');
        return \Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
