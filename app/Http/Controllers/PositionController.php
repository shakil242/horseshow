<?php
/***********
@aurthor: Faran Ahmed Khan (Vteams)
 Show Controller  will have all the functions related to show. Like listing, save response

************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InvitedUser;
use App\ManageShows;
use App\Form;
use App\TemplateDesign;
use App\ManageShowsRegister;
use App\Asset;
use App\ShowAssetInvoice;
use App\AssetModules;
use App\Participant;
use App\ShowPrizing;
use App\HorseClassType;

class PositionController extends Controller
{
    /**
     * Shows Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($asset_id)
    {
        //show
        $user_id = \Auth::user()->id;
        $asset_id = nxb_decode($asset_id);
        $showPrizing = ShowPrizing::where('asset_id',$asset_id)->first();
        $positions = null;
        $prizing_id = 0;
        $template_id = 0;
        if ($showPrizing) {
            $positions = json_decode($showPrizing->fields);
            $prizing_id = $showPrizing->id;
        }
        $classType = HorseClassType::orderBy('id','desc')->get();
        $template = Asset::select("template_id",'asset_type')->where("id",$asset_id)->first();
        $template_id = $template->template_id;
        $ownerShows = ManageShows::where('template_id', $template_id)->where('user_id', $user_id)->get();
        if ($template->asset_type == ASSET_PARENT_TYPE) {
            \Session::flash('message', 'Added Parent asset successfully!');
            return redirect()->route('master-template-manage-assets', ['template_id' => nxb_encode($template_id)]);
        }

        // $template_id = $invited->template_id;
        // $additional_charges =  AdditionalCharges::where('app_id',$app_id)->get();
        return view('shows.position.index')->with(compact("asset_id","ownerShows",'template_id',"classType","positions","prizing_id"));
        
    }
    /**
     * Shows Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $asset_id =$request->asset_id;
        $user_id = \Auth::user()->id;

        //show
        if ($request->prizing_id != 0) {
            $model = ShowPrizing::find($request->prizing_id);
        }else{
            $model = new ShowPrizing();
        }
        $model->asset_id = $asset_id;
        $model->user_id = $user_id;
        $model->fields = json_encode($request->placingprice);
        $model->save();

        $template = Asset::select('template_id')->where('id',$asset_id)->first();
        \Session::flash('message', 'Prizing Values have been updated!');
        return redirect()->route('master-template-manage-assets', ['template_id' => nxb_encode($template->template_id)]);
        //return redirect()->route('PositionController-index', ['asset_id' => nxb_encode($asset_id)]);
      
    }
        /**
     * Delete Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function additionalCdelete($id)
    {
        $id = nxb_decode($id);
        $additional = AdditionalCharges::find($id);
        $app_id = $additional->app_id;
        $additional->delete();
        \Session::flash('message', 'Show\'s additional charges has been deleted successfully');
        return redirect()->route('ShowController-additionalCharges', ['app_id' => nxb_encode($app_id)]);
        
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
