<?php
/***********
@aurthor: Faran Ahmed Khan (Vteams)
 Champion Controller  will have all the functions related to champion. Like listing, save response

************/
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\InvitedUser;
use App\ManageShows;
use App\Form;
use App\TemplateDesign;
use App\ManageShowsRegister;
use App\Asset;
use App\ParticipantResponse;
use App\AssetModules;
use App\Participant;
use App\AdditionalCharges;
use App\ShowPrizingListing;
use App\ClassHorse;
use App\ShowScratchPenalty;
use App\SchedulerFeedBacks;
use App\User;
use App\ChampionDivision;
use App\ChampionDivisionClass;

class ChampionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($app_id)
    {
        $app_id = nxb_decode($app_id);
        $invite = InvitedUser::find($app_id);
        $template_id = $invite->template_id;
        $manageShows = ManageShows::with("champion")->where("app_id",$app_id)->get();
        return view('shows.champion.index')->with(compact("manageShows","template_id","app_id"));
    }
    /**
     * Display division creation.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDivision($app_id, $show_id,$CD_id=0)
    {

        $app_id = nxb_decode($app_id);
        $show_id = nxb_decode($show_id);
        $user_id =\Auth::user()->id;
        $invite = InvitedUser::find($app_id);
        $template_id = $invite->template_id;
        $CD_id = nxb_decode($CD_id);
        $CD = null;
        if ($CD_id!=0) { 
            $CD = ChampionDivision::find($CD_id);
            $existing = ChampionDivisionClass::where('show_id',$show_id)->where("cd_id","!=",$CD_id)->pluck('class_id');
            if (count($existing)>0) {
                $existingClass = ChampionDivisionClass::where('cd_id',$CD_id)->pluck("class_id")->toArray();
            }else{
                $existingClass = ChampionDivisionClass::where('show_id',$show_id)->pluck('class_id')->toArray();
            }
        }else{
            $existing = ChampionDivisionClass::where('show_id',$show_id)->pluck('class_id');
            $existingClass= array();
        }
        if(!empty($existing)){
            $classes = Asset::with("ShowAssetInvoice")->where('template_id',$template_id)->where('user_id',$user_id)->where('asset_type',0)
                                    ->whereHas("SchedulerRestriction",function ($query) use ($show_id) {
                                         $query->where('show_id',$show_id);
                                    })->whereNotIn('id',$existing)->pluck("fields","id");
        }else{
            $classes = Asset::with("ShowAssetInvoice")->where('template_id',$template_id)->where('user_id',$user_id)->where('asset_type',0)
                                    ->whereHas("SchedulerRestriction",function ($query) use ($show_id) {
                                         $query->where('show_id',$show_id);
                                    })->pluck("fields","id");
        }
        $app_id = nxb_encode($app_id);
        return view('shows.champion.create')->with(compact("app_id","show_id","classes","CD",'existing','existingClass'));
    }
        /**
     * Save division creation.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveDivision(Request $request)
    {
        $show_id = $request->show_id;
        $app_id = nxb_decode($request->app_id);
        $divName = $request->division;
        $classes = $request->classes;
        $cd_id = $request->cd_id;
        $TotalUserScore= array();
        $score=array();
        //Get Points for user
        $show = ManageShows::find($show_id);
        $horses = ClassHorse::where('show_id',$show_id)->whereIn("class_id",$classes)->groupBy("horse_id")->get();
        

        if ($cd_id != 0) {
            $champ = ChampionDivision::find($cd_id);
        }else{
            $champ = new ChampionDivision();       
        }
            $champ->division_name = $divName;
            $champ->show_id = $show_id;
            $champ->app_id = $app_id;
            if (count($horses)>0) {
                foreach($horses as $PR) {
                    $TotalUserScore[$PR->id]= getAllRankResponseChampionDiv($PR->class_id, $show->template_id ,$show_id,$PR->horse_id,$classes);
                }
                //dd($TotalUserScore);
                arsort($TotalUserScore);
                //$score = array_slice($TotalUserScore,0, 3,true);
                $score = $TotalUserScore;
                if(is_array($score)){
                    $champ->champions = json_encode($score);
                }
            }
            
            $champ->save();
            $champion_id = $champ->id;
            
            //Champion Piviot table
            //For edit
            if ($cd_id != 0) {
                $champ = ChampionDivisionClass::where('cd_id',$cd_id)->delete();
            }
            
            $data = array();
            foreach ($classes as $value) {
               $data[] = ['cd_id'=>$champion_id, 'class_id'=>$value,'show_id'=>$show_id];
            }
            
            ChampionDivisionClass::insert($data);
        
        $result = ['app_id'=>nxb_encode($app_id),'show_id'=>nxb_encode($show_id),"CD_id"=>nxb_encode($champion_id)];

            \Session::flash('message', 'Saved Division successfully');
            //$geter = redirect()->action('ChampionController@createDivision',['app_id'=>nxb_encode($app_id),'show_id'=>nxb_encode($show_id),"CD_id"=>nxb_encode($champion_id)]);
            return json_encode([
                'status' => "success",
                'message' => "",
                'result' => $result
            ]);
       }
    /**
     * Delete Trainer from system.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteDivision($id)
    {
        $id = nxb_decode($id);
        $CD = ChampionDivision::find($id);
        ChampionDivisionClass::where('cd_id',$id)->delete();
        $CD->delete();
        \Session::flash('message', 'You have Deleted the division.');
        return redirect()->back();
    }



}
