<?php

namespace App\Http\Controllers\Auth;

use App\Template;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\InvitedUser;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/user/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

     return Validator::make($data, [
            'name' => 'required|max:255',
            'business_name'=>'max:255',
            'email' => 'required|unique:users,email',
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'termsCondition' => 'required',

        ]);


    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'business_name' => $data['business_name'],
            'email' => $data['email'],
            'location' => $data['location'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);

        if(isset($data['application']))
        {

         $royalty=  Template::where('id',$data['application'])->pluck('royalty')->first();

        $model =  new InvitedUser();

        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->template_id = $data['application'];
        $model->invited_by = 1;
        $model->email_confirmation = 1;
        $model->status = 1;
        $model->royalty=$royalty;
            $model->save();

        }

        //---->Assigning the values to the invited users table.
        // if (isset($data['invite_id']) && $data['invite_id'] !== '' ) {
        //     $inviteds = InvitedUser::where('id',$data['invite_id'])->first();
        //     $inviteds->user_id = $user->id;
        //     $inviteds->update();
        // }
        return $user;
    }

    public function terms()
    {
       return view('terms');
    }

    public function register(Request $request)
    {

         $validate = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|confirmed',
             'username' => 'required|unique:users',
             'termsCondition' => 'required'
         ]);
    if($validate->fails()) {
        $registers = $request->get('register_via');
        if(isset($registers) && $request->get('register_via')==1){
            $register_via = REGISTER_VIA_EMAIL;
            $data = $request->all();
            return view('auth.register')->with(compact('data','register_via'))->withErrors($validate->errors());
        }
        return redirect()->route('register')->withInput()->withErrors($validate->errors());
    }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect('user/dashboard');
    }

    public function showRegistrationForm()
    {
        $regisCollection  = Template::where('is_registration_on',1)->orderBy('id','desc')->get();
        return view('auth.register')->with(compact('regisCollection'));
    }
}
