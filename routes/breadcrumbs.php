<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
// use App\User;
// use App\Modules\Front\Models\Appointment;
// use App\Modules\Patient\Models\Message;


try {
    throw new Exception("Add action name into Breadcrumbs File");
} catch (Exception $e) {

    $e->getMessage();
}





Breadcrumbs::register('dashboard', function($breadcrumbs) {
    if (Auth::check()) {
        $inviter_id = \Auth::user()->user_type;
        if ($inviter_id == ADMIN_USER) {
            $breadcrumbs->push('Dashboard', route('admin-dashboard'));
        } else {
            $breadcrumbs->push('Dashboard', route('user.dashboard'));
        }
    }else{
        $breadcrumbs->push('Dashboard', route('user.dashboard'));
    }
});
/**================= Admin Breadcrumbs ==================**/


Breadcrumbs::register('points-dashboard', function($breadcrumbs) {
    $breadcrumbs->push('Points Dashboard', route('points-dashboard'));
});
Breadcrumbs::register('points-dashboard-shows', function($breadcrumbs) {
    $breadcrumbs->parent('points-dashboard');
    $breadcrumbs->push('Shows Points');
});

Breadcrumbs::register('points-dashboard-classes', function($breadcrumbs) {
    $breadcrumbs->parent('points-dashboard');
    $breadcrumbs->push('Class Points', route('admin-points-classes'));
});
Breadcrumbs::register('points-dashboard-points-positions', function($breadcrumbs) {
    $breadcrumbs->parent('points-dashboard-classes');
    $breadcrumbs->push('Class Positions Points');
});



Breadcrumbs::register('admin-create-m-template', function($breadcrumbs) {
   $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('App');
});

//Edit master template
Breadcrumbs::register('admin-edit-m-template', function($breadcrumbs,$templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Edit Template', route('admin-edit-master-template', $templateID));
});
//Create Module
Breadcrumbs::register('admin-create-module', function($breadcrumbs,$templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Edit Template', route('admin-edit-master-template', $templateID));
    $breadcrumbs->push('Create Module');
});
//Edit Module
Breadcrumbs::register('admin-edit-module', function($breadcrumbs,$templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Edit Template', route('admin-edit-master-template', $templateID));
    $breadcrumbs->push('Edit Module');
});
//Create Form
Breadcrumbs::register('admin-create-form', function($breadcrumbs,$templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Edit Template', route('admin-edit-master-template', $templateID));
    $breadcrumbs->push('Create Form');
});
//Edit form
Breadcrumbs::register('admin-edit-form', function($breadcrumbs,$templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Edit Template', route('admin-edit-master-template', $templateID));
    $breadcrumbs->push('Edit Form');
});
//Design form
Breadcrumbs::register('admin-design-form', function($breadcrumbs,$templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Edit Template', route('admin-edit-master-template', $templateID));
    $breadcrumbs->push('Design Form');
});
//Manage users
Breadcrumbs::register('admin-users-view', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Users', route('admin-users-listing'));
});

//Manage Users of master template
Breadcrumbs::register('admin-template-users-view', function($breadcrumbs,$templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Template Users',route('admin-users-participants',$templateID));
});
//Manage users
Breadcrumbs::register('admin-users-participants', function($breadcrumbs,$templateID) {
    $breadcrumbs->parent('admin-template-users-view',$templateID);
    $breadcrumbs->push('Invited Participants');
});

/**================= Frontend Breadcrumbs ==================**/
Breadcrumbs::register('master-template', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('App', route('master-template', $templateID));
});
//For Participants
Breadcrumbs::register('master-template2', function($breadcrumbs, $data) {
    $templateID = $data['template_id'];
    $participantID = $data['participant_id'];
    $asset_id = $data['asset_id'];
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('App', route('participant-launch-master-template', [$templateID,$participantID,$asset_id]));
});

Breadcrumbs::register('launch-master-template', function($breadcrumbs, $data) {
    $templateID = $data['template_id'];
    $app_id = $data['app_id'];
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('App', route('launch-master-template', [$templateID,$app_id]));
});



//For Sub Participants
Breadcrumbs::register('master-template3', function($breadcrumbs, $data) {
    $templateID = $data['template_id'];
    $participantID = $data['participant_id'];
    $asset_id = $data['asset_id'];
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('App', route('Subparticipant-launch-master-template', [$templateID,$participantID,$asset_id]));
});


Breadcrumbs::register('mastertemp-id-submodule-multiple', function($breadcrumbs, $data,$breadcrumbsParent) {

    $templateID = nxb_encode($data['template_id']);
    $moduleID = nxb_encode($data['module_id']);
    $app_id = nxb_encode($data['app_id']);
    $breadcrumbs->parent($breadcrumbsParent,  ['template_id'=>$templateID,'app_id'=>$app_id]);
    $breadcrumbs->push('Sub Module', route('mastertemp-id-submodule', [$templateID , $moduleID,$app_id]));
});


Breadcrumbs::register('mastertemp-id-submodule', function($breadcrumbs, $data) {



    $template_id = nxb_encode($data['template_id']);
    $moduleID = nxb_encode($data['module_id']);
    $app_id = nxb_encode($data['app_id']);

    $breadcrumbs->parent('launch-master-template', ['template_id'=>$template_id,'app_id'=>$app_id]);
    // foreach ($category as $ancestor) {
    //     $breadcrumbs->push($ancestor['name'], route('mastertemp-id-submodule', $ancestor['id']));
    // }
    $breadcrumbs->push('Sub Module', route('mastertemp-id-submodule', [$template_id , $moduleID,$app_id]));
});
//for Participants
Breadcrumbs::register('mastertemp-id-submodule2', function($breadcrumbs, $data) {
    $templateID = nxb_encode($data['template_id']);
    $moduleID = nxb_encode($data['module_id']);
    $asset_id = nxb_encode($data['asset_id']);
    $ParticipantID = nxb_encode($data['participant_id']);
    $app_id = nxb_encode($data['app_id']);

    $breadcrumbs->parent('master-template2', ["template_id"=>$templateID,"participant_id"=>$ParticipantID,"asset_id"=>$asset_id]);
    $breadcrumbs->push('Sub Module', route('participant-mastertemp-id-submodule', [$templateID , $ParticipantID,$moduleID,$asset_id,$app_id]));
});
//for Sub Participants
Breadcrumbs::register('mastertemp-id-submodule3', function($breadcrumbs, $data) {
    $templateID = nxb_encode($data['template_id']);
    $moduleID = nxb_encode($data['module_id']);
    $asset_id = nxb_encode($data['asset_id']);
    $ParticipantID = nxb_encode($data['participant_id']);
    $app_id = nxb_encode($data['app_id']);

    $breadcrumbs->parent('master-template3', ["template_id"=>$templateID,"participant_id"=>$ParticipantID,"asset_id"=>$asset_id]);
    $breadcrumbs->push('Sub Module', route('Subparticipant-mastertemp-id-submodule', [$templateID , $ParticipantID,$moduleID,$asset_id,$app_id]));
});

Breadcrumbs::register('master-template-form-view', function($breadcrumbs, $data,$participant=0,$subpart=0) {
    //If participant has access to limited master template resources.
    if ($participant == 0) {
        $template_id = nxb_encode($data['template_id']);
        $app_id = nxb_encode($data["app_id"]);
        $breadcrumbs->parent('launch-master-template', ['template_id'=>$template_id,'app_id'=>$app_id]);
        // $breadcrumbs->parent('master-template', $templateID);
        $breadcrumbs->push('Form');
    }
    else{
        $data['template_id'] = nxb_encode($data['template_id']);
        $formID = nxb_encode($data["form_id"]);
        $data['participant_id'] =nxb_encode($participant);
        if ($subpart == 1) {
            $breadcrumbs->parent('master-template3', $data);
        }else{
            $breadcrumbs->parent('master-template2', $data); 
        }
        $breadcrumbs->push('Form', route('master-template', $formID));
    }
    
});
Breadcrumbs::register('mastertemp-id-search', function($breadcrumbs, $data) {
    $templateID = nxb_encode($data['template_id']);
    $breadcrumbs->parent('master-template', $templateID);
    $breadcrumbs->push('Search Results');
});

//Participant View form for particular accet
Breadcrumbs::register('master-template-participants-readonly', function($breadcrumbs, $accet_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('View Assets', route('participant-mastertemp-view-accet', $accet_id));
});
//Participant View assets history
Breadcrumbs::register('participant-asset-history', function($breadcrumbs, $accet_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('History', route('participant-asset-history', $accet_id));
});
//Sub-Participant View assets history
Breadcrumbs::register('subparticipant-asset-history', function($breadcrumbs, $participant_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('History', route('Subparticipant-responses-view', $participant_id));
});

//Users
Breadcrumbs::register('mastertemplate-list-users', function($breadcrumbs, $template_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Participant List', route('master-template-participant-listing', $template_id));
});


//Project overview

Breadcrumbs::register('master-template-participants-viewProjectOverview', function($breadcrumbs, $participant_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Project Overview', route('project-overview-history', $participant_id));
});

//App owner side
Breadcrumbs::register('master-template-projectoverview', function($breadcrumbs, $template_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Project Overview', route('master-template-manage-Project', $template_id));
});



Breadcrumbs::register('project-template-assets-form', function($breadcrumbs, $data) {
    $templateID = nxb_encode($data['template_id']);
    $breadcrumbs->parent('master-template-projectoverview', $templateID);
    $breadcrumbs->push('Project Form');
});

//App owner side
Breadcrumbs::register('master-template-projectoverview-listresponse', function($breadcrumbs, $data) {
    $template_id = nxb_encode($data['template_id']);
    $projectoverview_id = nxb_encode($data['projectoverview_id']);

    $data['template_id'] =$template_id;
    $data['projectoverview_id'] =$projectoverview_id;

    $breadcrumbs->parent('master-template-projectoverview',$template_id);
    $breadcrumbs->push('Submissions List', route('project-overview-submissions', $data));
});

//Shows

//Show index
Breadcrumbs::register('show-dashboard', function($breadcrumbs) {
    $breadcrumbs->push('Show Dashboard', route('ShowController-index'));
});
//Show Register step 1
Breadcrumbs::register('shows-register', function($breadcrumbs,$show_id) {
    $breadcrumbs->parent('show-dashboard');
    $breadcrumbs->push('Step 1', route('ShowController-create', $show_id));
});
//Show asset step 2
Breadcrumbs::register('shows-step2', function($breadcrumbs,$data) {
    $show_id = nxb_encode($data['show_id']);
    $breadcrumbs->parent('shows-register',$show_id);
    $breadcrumbs->push('Step 2', route('ShowController-create', $show_id));
});

// shows trainer Index
Breadcrumbs::register('shows-trainer-list', function($breadcrumbs,$show_id) {
    $breadcrumbs->parent('show-dashboard');
    $breadcrumbs->push('Trainers', route('ShowController-trainers', $show_id));
});
//shows-register-trainer
Breadcrumbs::register('shows-register-trainer', function($breadcrumbs,$show_id) {

    $breadcrumbs->parent('shows-trainer-list',$show_id);
    $breadcrumbs->push('Register');
});
//Show trainer split invoice
Breadcrumbs::register('shows-trainer-split-invoice', function($breadcrumbs) {
    $breadcrumbs->parent('show-dashboard');
    $breadcrumbs->push('Split Invoice');
});

Breadcrumbs::register('shows-trainer-order-supplies', function($breadcrumbs) {
    $breadcrumbs->parent('show-dashboard');
    $breadcrumbs->push('Order Form');
});

Breadcrumbs::register('shows-trainer-order-history', function($breadcrumbs) {
    $breadcrumbs->parent('show-dashboard');
    $breadcrumbs->push('Order History');
});

Breadcrumbs::register('shows-sponsor-history', function($breadcrumbs) {
    $breadcrumbs->parent('show-dashboard');
    $breadcrumbs->push('Sponsor History');
});


Breadcrumbs::register('shows-view-stall-request', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('View Stall Type Requests');
});


Breadcrumbs::register('shows-view-unPaid-stalls', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Unpaid Stalls');
});

Breadcrumbs::register('shows-view-stable-details', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('View Stable Details');
});

Breadcrumbs::register('shows-stall-request', function($breadcrumbs) {
    $breadcrumbs->parent('show-dashboard');
    $breadcrumbs->push('Stall Request');
});

//show trainer list history
Breadcrumbs::register('shows-trainer-list-history', function($breadcrumbs,$show_id) {
    $breadcrumbs->parent('show-dashboard');
    $breadcrumbs->push('Split History', route('ShowController-split-invoice-trainers-history', $show_id));
});
Breadcrumbs::register('shows-trainer-split-history-detail', function($breadcrumbs,$show_id) {
    $breadcrumbs->parent('shows-trainer-list-history', $show_id);
    $breadcrumbs->push('Details');
});




//Show additonal charges.
Breadcrumbs::register('master-template-additional-charges', function($breadcrumbs,$app_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Additional Charges', route('ShowController-additionalCharges', $app_id));
});
//Show additonal charges.
Breadcrumbs::register('shows-invoice', function($breadcrumbs,$msr_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Shows Invoice', route('ShowController-additionalCharges', $msr_id));
});

//Show Prizing.
Breadcrumbs::register('shows-prizing', function($breadcrumbs,$msr_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Shows Invoice', route('ShowController-additionalCharges', $msr_id));
});

//show champion Calculator
Breadcrumbs::register('shows-champion-calculator', function($breadcrumbs, $app_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Champion Calculator', route('champion-calculator', $app_id));
});
Breadcrumbs::register('shows-champion-calculator-create', function($breadcrumbs, $app_id) {
    $breadcrumbs->parent('shows-champion-calculator',$app_id);
    $breadcrumbs->push('Create');
});

//show participants
Breadcrumbs::register('shows-participants-listing', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Participants History', route('shows-all-participants', $templateID));
});

//show Invoices
Breadcrumbs::register('shows-appowner-invoices-listing', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Invoice Listing', route('billing-show-invoices-appowner-listing', $templateID));
});

Breadcrumbs::register('shows-appowner-invoices-detail', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('shows-appowner-invoices-listing', $templateID);
    $breadcrumbs->push('Details');
});

Breadcrumbs::register('shows-appowner-prize-form-listing', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Prize Listing');
});

Breadcrumbs::register('shows-appowner-sponsor-category-listing', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Sponsor Category');
});
Breadcrumbs::register('shows-stables-listing', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Show Stables');
});
//show spectators
Breadcrumbs::register('shows-spectator-listing', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Shows Spectators', route('shows-all-spectators', $templateID));
});
Breadcrumbs::register('shows-sponsor-listing', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Shows Sponsors', route('shows-all-sposnors', $templateID));
});

Breadcrumbs::register('shows-register-view', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('shows-spectator-listing', $templateID);
    $breadcrumbs->push('View Register');
});

//Show registration
Breadcrumbs::register('shows-register-history', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('shows-participants-listing', $templateID);
    $breadcrumbs->push('View Register');
});
//Show scratch options
Breadcrumbs::register('shows-scratch-option', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Shows scratch');
});

Breadcrumbs::register('Show-trainer-view-Scheduler-Form', function($breadcrumbs, $templateID) {
    $breadcrumbs->push('Show Dashboard', route('ShowController-index'));
    $breadcrumbs->push('View Scheduler Form');
});

//Trainers Shows

//trainer index
Breadcrumbs::register('trainer-dashboard', function($breadcrumbs) {
    $breadcrumbs->push('Trainer Dashboard', route('TrainerController-index'));
});


//Assets
Breadcrumbs::register('master-template-assets', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Assets', route('master-template-manage-assets', $templateID));
});

Breadcrumbs::register('master-template-assets-form', function($breadcrumbs, $data) {
    $templateID = nxb_encode($data['template_id']);
    $breadcrumbs->parent('master-template-assets', $templateID);
    $breadcrumbs->push('Assets Form');
});
  
Breadcrumbs::register('master-template-assets-positions', function($breadcrumbs, $templateID) {
    $templateID = nxb_encode($templateID);
    $breadcrumbs->parent('master-template-assets', $templateID);
    $breadcrumbs->push('Positions');
});


Breadcrumbs::register('master-template-assets-modules', function($breadcrumbs, $data) {
        $templateID = nxb_encode($data['template_id']);
        $asset_id = nxb_encode($data["asset_id"]);
        $breadcrumbs->parent('master-template-assets', $templateID);
        $breadcrumbs->push('Assets Modules', route('master-template', $asset_id));
    });

//Asset History
Breadcrumbs::register('template-asset-history', function($breadcrumbs, $data) {
    $templateID = nxb_encode($data['template_id']);
    $asset_id = nxb_encode($data["asset_id"]);
    $breadcrumbs->parent('master-template-assets', $templateID);
    $breadcrumbs->push('Feedback', route('master-template-history-assets', $asset_id));
});

//Asset Prizing listing
Breadcrumbs::register('template-asset-prizing-listing', function($breadcrumbs, $template_id) {
    $templateID = nxb_encode($template_id);
    $breadcrumbs->parent('master-template-assets', $templateID);
    $breadcrumbs->push('Prizing Listing');
});

Breadcrumbs::register('template-asset-all-history', function($breadcrumbs,$template_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('History', route('master-template-all-history-assets', $template_id));
});
//Participant View form for particular accet
Breadcrumbs::register('master-template-participants-all-readonly', function($breadcrumbs, $template_id) {
    $templateID = nxb_encode($template_id);
    $breadcrumbs->parent('template-asset-all-history', $templateID);
    $breadcrumbs->push('View Response');
});


//Assets Details
Breadcrumbs::register('assets-details', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Assets Details');
});
//settings of user
Breadcrumbs::register('settings', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    //$breadcrumbs->push('Settings',route('Settings-page'));
});
//Form view of setting page
Breadcrumbs::register('setting-form-view', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    //$breadcrumbs->push('Settings',route('Settings-page'));
    $breadcrumbs->push('Form View'); 
});

//setting-form-viewonly
Breadcrumbs::register('setting-form-viewonly', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Form View'); 
});

//Participants
Breadcrumbs::register('master-template-participants', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Participants', route('master-template-manage-assets', $templateID));
});

//Participants
Breadcrumbs::register('master-template-subparticipants', function($breadcrumbs, $element_id,$key) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Sub Participants', route('master-template-invite-subparticipants', $element_id,$key));
});
//Schedular
Breadcrumbs::register('master-template-breadcrumbs-list-schedular', function($breadcrumbs, $templateID, $AppID) {

    $data['id'] = $templateID;
    $data['appId'] = $AppID;

    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Schedular', route('master-template-list-schedular', $data));
});


//Report Index page
Breadcrumbs::register('ranking-index', function($breadcrumbs, $templateID) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Ranking', route('rankingcontroller-index', $templateID));
});

//Report modules Ranking page
Breadcrumbs::register('ranking-moduleRanking', function($breadcrumbs, $templateID) {
    $templateID = nxb_encode($templateID);
    $breadcrumbs->parent('ranking-index', $templateID);
    $breadcrumbs->push('Module Ranking');
});

//Participant Ranking module wise
//Report Index page
Breadcrumbs::register('ranking-index-participant', function($breadcrumbs, $participant_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Ranking', route('rankingcontroller-participant-index', $participant_id));
});

//Report modules Ranking page
Breadcrumbs::register('ranking-moduleRanking-participant', function($breadcrumbs, $participant_id) {
    $templateID = nxb_encode($participant_id);
    $breadcrumbs->parent('ranking-index-participant', $participant_id);
    $breadcrumbs->push('Module Ranking');
});

Breadcrumbs::register('template-user-settings', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Template Settings');
});
    
    
    
    
    
    

//Overall responses
Breadcrumbs::register('template-overall-allHistory', function($breadcrumbs, $data) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('All responses', route('master-template-overall-response', $data));
});
Breadcrumbs::register('template-overall-graphical', function($breadcrumbs, $data) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('All responses', route('master-template-overall-response', $data));
    $breadcrumbs->push('Graphical');
});

Breadcrumbs::register('show-supplies-order-requests', function($breadcrumbs, $template_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Order Supplies Request', route('master-template-view-order-supplies', $template_id));
});

Breadcrumbs::register('show-supplies-order-details', function($breadcrumbs, $template_id) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Order Supplies Request', route('master-template-view-order-supplies', $template_id));
    $breadcrumbs->push('Order Detail');
});


Breadcrumbs::register('show-supplies-order-history-detail', function($breadcrumbs, $dataBreadcrum) {
    $breadcrumbs->parent('show - dashboard');
    $breadcrumbs->push('Order History', route('ShowController-trainer-viewOrderHistory', $dataBreadcrum));
    $breadcrumbs->push('Order Detail');
});




// FeedBack
      Breadcrumbs::register('Asset-FeedBack-list', function($breadcrumbs, $dataBreadcrum) {
        $bc['asset'] = nxb_encode($dataBreadcrum['asset_id']);
        $bc['templateID'] = nxb_encode($dataBreadcrum['templateID']);
        
        $breadcrumbs->parent('master-template-assets', $bc['templateID']);
        $breadcrumbs->push('Feedback', route('ShowController-trainers-feedback-given', $bc['asset']));
    });
    
    Breadcrumbs::register('view-FeedBack-details', function($breadcrumbs, $dataBreadcrum) {
        $breadcrumbs->parent('Asset-FeedBack-list', $dataBreadcrum);
        $breadcrumbs->push('Details');
    });
    

    Breadcrumbs::register('participant-asset-getFeedBack', function($breadcrumbs, $asset_id) {
        $breadcrumbs->parent('dashboard');
        $breadcrumbs->push('Feedback', route('participant-asset-getFeedBack', $asset_id));
    });
    
    Breadcrumbs::register('participant-view-FeedBack', function($breadcrumbs, $asset_id) {
        $asset_id = nxb_encode($asset_id);
        $breadcrumbs->parent('participant-asset-getFeedBack', $asset_id);
        $breadcrumbs->push('View Feedback', route('participant-view-FeedBack', $asset_id));
    });
    
// Scheduler
    
    Breadcrumbs::register('master-template-list-schedular-forms-schedule', function($breadcrumbs) {
        $breadcrumbs->parent('dashboard');
        $breadcrumbs->push('Manage Scheduler');
    });
    
//Invoices
    
    Breadcrumbs::register('participant-invoice-listing', function($breadcrumbs,$invoice) {
        $breadcrumbs->parent('dashboard');
        $breadcrumbs->push('Invoices',route('participant-invoice-listing', $invoice));
    });
    
    Breadcrumbs::register('master-template-billing-invoice-form', function($breadcrumbs, $dataBreadcrum) {
    
      $invoice['id'] = nxb_encode($dataBreadcrum['id']);
      $invoice['asset_id'] = $dataBreadcrum['asset_id'];
      $invoice['invite_asociated_key'] = $dataBreadcrum['invite_asociated_key'];
        
        $breadcrumbs->parent('participant-invoice-listing', $invoice);
    
        $breadcrumbs->push('Invoice Form', route('master-template-billing-invoice-form', $dataBreadcrum));
    });
    
    
    Breadcrumbs::register('master-template-invoice-listing', function($breadcrumbs,$dataBreadCrumb) {
        $breadcrumbs->parent('dashboard');
        $breadcrumbs->push('Invoices',route('master-template-invoice-listing', $dataBreadCrumb));
    });

    
    
    Breadcrumbs::register('participant-invoice-view', function($breadcrumbs,$dataBreadCrumb) {
    
        $invoice['id'] = $dataBreadCrumb['templateId'];
        $invoice['asset_id'] = $dataBreadCrumb['assetId'];
        $invoice['invite_asociated_key'] = '';
    
        $breadcrumbs->parent('participant-invoice-listing', $invoice);
        $breadcrumbs->push('Invoices',route('master-template-invoice-listing',$dataBreadCrumb));
    });
    
        Breadcrumbs::register('master-owner-invoice-view', function($breadcrumbs, $dataBreadCrumb) {
    
            $data ['id'] = $dataBreadCrumb['templateId'];
            
            $breadcrumbs->parent('master-template-invoice-listing',$data);
    
            $breadcrumbs->push('Invoice Form', route('master-owner-invoice-view', $dataBreadCrumb));
        });

Breadcrumbs::register('master-singleInvoice-billing', function($breadcrumbs, $dataBreadCrumb) {

    $data ['id'] = $dataBreadCrumb['templateId'];

    $breadcrumbs->parent('master-template-invoice-listing',$data);

    $breadcrumbs->push('Invoice Detail', route('master-singleInvoice-billing', $dataBreadCrumb));
});


    
    
    Breadcrumbs::register('payment-detail-paypal', function($breadcrumbs, $dataBreadCrumb) {
        
        $data ['id'] = $dataBreadCrumb['templateId'];
        
        $breadcrumbs->parent('master-template-invoice-listing',$data);
        
        $breadcrumbs->push('Invoice Detail', route('payment-detail-paypal', $dataBreadCrumb));
    });
    
    Breadcrumbs::register('master-template-masterSchedular', function($breadcrumbs,$dataBreadCrumb) {
        $breadcrumbs->parent('dashboard');
        $breadcrumbs->push('Master Scheduler');
    });
    
    Breadcrumbs::register('participant-payment-methods', function($breadcrumbs) {
        $breadcrumbs->parent('dashboard');
        $breadcrumbs->push('Payment Method');
    });
    
    Breadcrumbs::register('master-Event-invoice-view-form', function($breadcrumbs, $dataBreadCrumb) {
    
        $data ['id'] = $dataBreadCrumb['templateId'];
    
        $breadcrumbs->parent('master-template-invoice-listing',$data);
        
        $breadcrumbs->push('Invoice Event Form', route('master-Event-invoice-view-form', $dataBreadCrumb));
    });


Breadcrumbs::register('master-template-employee-manager', function($breadcrumbs, $templateID, $AppID) {

    $data['id'] = $templateID;
    $data['appId'] = $AppID;

    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Schedular', route('EmployeeController-index', $data));
});


Breadcrumbs::register('master-template-assets-secondary', function($breadcrumbs, $data) {
    $templateID = nxb_encode($data['template_id']);
    $asset_id = nxb_encode($data["asset_id"]);
    $breadcrumbs->parent('master-template-assets', $templateID);
    $breadcrumbs->push('Secondary Assets', route('master-template', $asset_id));
});


/**============ Admin Dashboards ======================**/
