<?php
    /**
     * This is Setting Controller which contain all the setting related functions of the project
     *
     * @author Faran Ahmed (Vteams)
     */
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use Validator;
//use Aws\Api\Validator;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\ValidationException;



class SettingController extends Controller
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
    
    /**
     * @return string
     */
    public function userProfile()
    {
         $user_id   = \Auth::user()->id;
        $user = User::where('id',$user_id)->first();
        return view('admin.setting.userProfile.index')->with(compact('user'));
        
    }
 
    


}
