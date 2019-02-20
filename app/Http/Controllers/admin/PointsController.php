<?php
/**
 * Handle All Admin Controller Main requests . and Master templates are manage in these 
 *
 * @author Faran Ahmed Khan
 * @date 12-Jan-2017
 */
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\InviteUser;
use App\InvitedUser;
Use App\TemplateButtonLabel;
Use App\TemplateCategory;
Use App\ShowType;
use Response;
use App\HorseClassType;

class PointsController extends Controller
{
	/**
    * index function, Template list view
    *
    * @param none
    * @return Object of the templates
    */
    public function index()
    {
    	return view("admin.points.index");
    }
    /**
    * index function, Template list view
    *
    * @param none
    * @return Object of the templates
    */
    public function show()
    {
        $collection = ShowType::orderBy('id','desc')->get();
        return view("admin.points.shows")->with(compact('collection'));
    }
   
	/**
    * Create, MasterTemplate Create 
    *
    * @param none
    * @return model of templates
    */
	public function store(Request $request){
		$rules = array (
                'name' => 'required',
                'showpoints' => 'numeric|min:0'
        );
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails ()){
            $errors = $validator->errors();
             return response()->json ( array (  
                    'errors' => $errors->first()
            ));
        }
        else {
                $data = new ShowType();
                $data->name = $request->name;
                $data->points = $request->showpoints;
                $data->save();
                return response()->json($data);
        }
	}

	 /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {
        $data = ShowType::find( $request->id );
        $data->name = $request->name;
        $data->points = $request->points;
        $data->save();
        return response()->json($data);
        
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {   
        $template = ShowType::findOrFail($request->id);
        $template->delete();
        \Session::flash('message', 'Deleted Successfully.');
	 	return response()->json();
    }

    /**
    * index function, Template list view
    *
    * @param none
    * @return Object of the templates
    */
    public function classshow()
    {
        $collection = HorseClassType::orderBy('id','desc')->get();
        return view("admin.points.classes")->with(compact('collection'));
    }
   
    /**
    * Create, MasterTemplate Create 
    *
    * @param none
    * @return model of templates
    */
    public function classstore(Request $request){
        $rules = array (
                'name' => 'required'
        );
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails ()){
            $errors = $validator->errors();
             return response()->json ( array (  
                    'errors' => $errors->first()
            ));
        }
        else {
                $data = new HorseClassType();
                $data->name = $request->name;
                $data->save();
                return response()->json($data);
        }
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function classedit(Request $request) {
        $data = HorseClassType::find( $request->id );
        $data->name = $request->name;
        $data->save();
        return response()->json($data);
        
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function classdelete(Request $request)
    {   
        $template = HorseClassType::findOrFail($request->id);
        $template->delete();
        \Session::flash('message', 'Deleted Successfully.');
        return response()->json();
    }

        /**
     * Shows Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function positionIndex($HCT_id)
    {
        //show
        $showPrizing = HorseClassType::where('id',$HCT_id)->first();
        $positions = null;
        $prizing_id = 0;
        if ($showPrizing) {
            $positions = json_decode($showPrizing->position_fields);
            $prizing_id = $showPrizing->id;
        }
        return view('admin.points.position.index')->with(compact("HCT_id","positions","prizing_id"));
        
    }
    /**
     * Shows Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function positionStore(Request $request)
    {
        $HCT_id =$request->HCT_id;
        $user_id = \Auth::user()->id;

        //show
        if ($request->prizing_id != 0) {
            $model = HorseClassType::find($HCT_id);
        }else{
            $model = new HorseClassType();
        }
        $model->position_fields = json_encode($request->placingprice);
        $model->save();
        \Session::flash('message', 'Prizing Values have been updated!');
        //return redirect()->route('PositionController-index', ['asset_id' => nxb_encode($asset_id)]);
        return \Redirect::back();
    }
}
