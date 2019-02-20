<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'IndexController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/terms', 'Auth\RegisterController@terms');

//Admin Section
Route::get('admin/adminlogin', function () {return view('admin.auth.login');});

//After Login
Route::group(['prefix' => 'admin', 'middleware' => ['web', 'admin']], function()
{
    
	Route::get('/', 'admin\AdminController@index')->name("admin-dashboard");
	
	//Master Templates
	Route::get('/create-template', 'admin\AdminController@create');
    Route::post('/template/store', 'admin\AdminController@store');
    Route::any('/template/edit/{id}', 'admin\AdminController@edit')->name("admin-edit-master-template");
    Route::any('/template/update/{id}', 'admin\AdminController@update');
    Route::any('/template/delete/{id}', 'admin\AdminController@destroy');
    Route::post('/template/sendInvite', 'admin\AdminController@sendInviteMail');
    Route::any('/template/duplicateTemplate', 'admin\AdminController@duplicateTemplate');

    //Modules Controller
    Route::any('modules-managment/reset-launcher/{id}', 'admin\AdminController@restLauncher');
    Route::any('/modules-managment/create/{id}', 'admin\ModuleController@create');
    Route::post('/modules-managment/store/', 'admin\ModuleController@store');
    Route::any('/modules-managment/delete/{id}', 'admin\ModuleController@destroy');
    Route::any('/modules-managment/{id}/edit', 'admin\ModuleController@edit');
    Route::any('/modules-managment/{id}/update', 'admin\ModuleController@update');

    //Templae Users Management
    Route::any('/template/{id}/users', 'admin\UserController@index')->name("admin-users-participants");
    Route::any('/template/{id}/block-user', 'admin\UserController@blockUser');
    Route::any('/template/{id}/unblock-user', 'admin\UserController@unBlockUser');
    
    //template-buttons
    Route::any('/template/{id}/template-buttons', 'admin\AdminController@buttonIndex');
    Route::post('/template/button-label/save', 'admin\AdminController@buttonLabelSave');

    
    //Users Management
    Route::any('/user/listings', 'admin\UserController@usersListing')->name("admin-users-listing");
    Route::any('/{user_id}/{template_id}/participant/listings', 'admin\UserController@uParticipantListing')->name("admin-users-participant-listing");

    Route::post('/template/manageRegistration', 'admin\AdminController@manageRegistration');

   // Route::resource('/modules-managment', 'admin\ModuleController');

    //Design Controller
    Route::get('/master-template-design/create/{id}', 'admin\TemplateDesignController@create');
    Route::post('/master-template-design/store/', 'admin\TemplateDesignController@store');
    Route::get('/template/design/{id}/background-delete', 'admin\TemplateDesignController@background_image_destroy');
    Route::get('/template/design/{id}/logo-delete', 'admin\TemplateDesignController@logo_image_destroy');
    //Forms Controller
    Route::any('/forms-managment/create/{id}', 'admin\FormController@create');
    Route::post('/forms-managment/store', 'admin\FormController@store');
    Route::get('/forms-managment/{id}/edit', 'admin\FormController@edit');
    Route::any('/forms-managment/{id}/update', 'admin\FormController@update');
    Route::any('/forms-managment/{id}/delete', 'admin\FormController@destroy');
    Route::any('/template/{id}/preview', 'admin\FormController@show')->name("admin-preview-form");
    Route::any('/template/{id}/make-a-copy', 'admin\FormController@copyForm');
	//File upload in form
    Route::post('/file/upload/form', 'admin\FormController@optionExcel');

    
    //billing

    Route::get('/billing', 'admin\BillingController@show');
    
    Route::get('/user', 'admin\SettingController@userProfile')->name('admin-settings-user-profile');

    //Points controller
    Route::get('/points-system', 'admin\PointsController@index')->name("points-dashboard");
    Route::get('/points-system/show', 'admin\PointsController@show');
    Route::post('/add/show/points', 'admin\PointsController@store');
    Route::post('/delete/show/points', 'admin\PointsController@delete');
    Route::post('/edit/show/points', 'admin\PointsController@edit');

    Route::get('/points-system/class', 'admin\PointsController@classshow')->name("admin-points-classes");
    Route::post('/add/class/points', 'admin\PointsController@classstore');
    Route::post('/delete/class/points', 'admin\PointsController@classdelete');
    Route::post('/edit/class/points', 'admin\PointsController@classedit');

    Route::get('/class/positions/{id}', 'admin\PointsController@positionIndex')->name("admin-classes-positions");
    Route::post('/save/class/position-points', 'admin\PointsController@positionStore');

    
});

Route::get('getReminders', 'SchedularController@getReminder');

//Frontend routes
//emails
Route::get('template/sendInvite/response/{id}/{response}', 'MailController@responseInviteMail');
Route::get('template/transfer/{id}/{response}', 'MailController@responseTransferInviteMail');
Route::get('participant/sendInvite/response/{id}/{response}/{asset_id?}', 'MailController@responseParticipantMail');
Route::get('participants/sendInvite/response/{id}/{response}', 'MailController@responseParticipantMails');
Route::get('sub-participant/sendInvite/response/{id}/{response}', 'MailController@responseSubParticipantMails');
Route::get('sub-participants/sendInvite/response/{id}/{response}/{asset_id?}', 'MailController@responseSubParticipantMail');
Route::get('participant/response/decline/{id}/{asset_id?}', 'MailController@responseDecline');

//After Login
Route::group(['middleware' => ['web','auth']], function(){

    Route::get('user/dashboard', 'UserController@index')->name('user.dashboard');
     //Test the saving elements
    Route::post('/form/userinput/save/', 'AssetController@store');
    Route::post('/form/userinput/split-class/', 'AssetController@splitClassSave');

    //projectoverview
    Route::post('/form/projectoverview/save/', 'ProjectController@store');

    Route::group(['prefix' => 'master-template'], function()
	{
        //Edit master template name
        Route::get('/{invited_id}/template/setting', 'TemplateController@settings')->name('master-template-settings');
       
        Route::post('/setting/transfer', 'TemplateController@tranferRequest');
        Route::post('/setting/name/edit', 'TemplateController@editName')->name('master-template-settingname');
        Route::post('/setting/save/design', 'TemplateController@storeDesign')->name('master-template-settingdesign');
		Route::get('/setting/design/{id}/background-delete', 'TemplateController@background_image_destroy');
        Route::get('/setting/design/{id}/logo-delete', 'TemplateController@logo_image_destroy');
        //Get Invited by self
        Route::get('/{id}/get/invited/app', 'TemplateController@selfInvite');
        Route::get('/{template_id}/{show_id}/joinTrainerAppBYSelf', 'TemplateController@joinTrainerAppBYSelf');


        //Master Templates
		Route::get('/{id}/modules', 'TemplateController@index')->name('master-template');
		Route::get('/{id}/{moduleid}/sub-modules/{appId?}', 'TemplateController@show')->name('mastertemp-id-submodule');
        Route::get('/{template_id}/modules/launch/{id?}', 'TemplateController@module_launcher')->name('launch-master-template');
        
        Route::any('/autocomplete/{id}',array('as'=>'master.autocomplete','uses'=>'TemplateController@autocomplete'));
        Route::post('/search',array('as'=>'master.search','uses'=>'TemplateController@search'));
        //View Participants History on Master template
        Route::any('/{id}/all/history/assets', 'AssetController@allHistory')->name('master-template-all-history-assets');
        Route::post('/ssssss/paginationRequest', 'AssetController@paginationRequest')->name('paginationRequest');

      //Assets
        Route::get('/{id}/manage/assets', 'AssetController@index')->name('master-template-manage-assets');
        Route::post('user/add/assets', 'AssetController@create')->name('master-template-add-assets');
        Route::get('/{id}/remove/assets', 'AssetController@destroy')->name('master-template-remove-assets');
        Route::get('/{id}/edit/assets', 'AssetController@edit')->name('master-template-edit-assets');
        Route::get('/{id}/horseProfile', 'AssetController@horseProfile')->name('master-template-horse-Profile');
        Route::get('/{id}/horse-view-profile', 'AssetController@secrethorseProfile')->name('master-template-horse-secret-Profile');
        
        Route::get('/{id}/export/assets/positions', 'ExportController@exportAssetPositionCsv');


        //Assets For project overview
        Route::get('/{id}/manage/project-overview', 'ProjectController@index')->name('master-template-manage-Project');
        Route::post('user/add/project-overview', 'ProjectController@create')->name('master-template-add-Project');
        Route::get('/{id}/remove/project-overview-assets', 'ProjectController@destroy')->name('master-template-remove-Project');
        Route::get('/{id}/edit/project-overview-assets', 'ProjectController@edit')->name('master-template-edit-project');
        Route::any('/{id}/update/project-overview-assets', 'ProjectController@update');
        Route::get('get-project-overview-ajax/{id}', 'ProjectController@getDynamicDataTableAjax')->name('ajax-project-overview-data');
        Route::get('/{id}/{template_id}/project-overview/submissions', 'ProjectController@projectSubmissions')->name('project-overview-submissions');
        Route::get('/{id}/manage/project-overview/history', 'ProjectController@projectHistory')->name('project-overview-history');
        Route::get('/{id}/project-overview/email/sended', 'ProjectController@projectEmailList')->name('project-overview-email-list');
        Route::get('/{id}/project-overview/email/full-list', 'ProjectController@projectEmailListFull')->name('project-overview-email-list2');
        Route::get('/{id}/change-status/project-overview-assets/{type}', 'ProjectController@changeAsset')->name('master-template-asset-change-project');



        Route::post('send-mail/project-overview', 'MailController@projectOverview')->name('send-mail-for-Project');
        Route::post('send-mail/marketing-email', 'MailController@marketingEmail')->name('send-marketing-email');
        
        //course-outline
        Route::get('/{id}/course-outline/{type?}', 'TemplateController@couserOutline');
        Route::post('/course-content/submit/response/', 'TemplateController@saveCourseContent');

        //Ajax call
        Route::get('get-info-table/{id}', 'AssetController@getDynamicDataTable');
        Route::get('get-asset-ajax/{id}', 'AssetController@getDynamicDataTableAjax')->name('ajax-dynamic-data');
        Route::get('/{id}/history/assets', 'AssetController@history')->name('master-template-history-assets');

        Route::get('/{id}/sub/assets', 'AssetController@subAssets')->name('master-template-sub-assets');

        Route::get('/{id}/asset/scheduler', 'AssetController@assetManageScheduler')->name('master-template-asset-scheduler');

        Route::get('/{id}/associate/modules', 'AssetController@associateModules')->name('master-template-associate-modules');

        Route::post('template/assets/modules', 'AssetController@associateModulesTemplate')->name('master-template-add-modules-template');
        Route::post('template/submit/modules', 'AssetController@submitModulesTemplate')->name('master-template-submit-modules-template');

        Route::post('asset/submit/modules', 'AssetController@submitModules')->name('master-template-submit-modules');

        Route::any('/{id}/update/assets', 'AssetController@update');
        //Block
        Route::group(['middleware' => ['blocked']], function(){
            Route::get('/{id}/{inviteid}/modules/launch/{asset_id}/{invite_asociated_key?}', 'ParticipantController@viewModules')->name('participant-launch-master-template');
            Route::get('/{id}/{inviteid}/{moduleid}/sub-modules/{asset_id}/{app_id}/{invite_asociated_key?}', 'ParticipantController@viewSubModules')->name('participant-mastertemp-id-submodule');
            
        });
        //Participants 

            Route::get('/{id}/invite/participants', 'ParticipantController@index')->name('master-template-invite-participants');
            Route::post('invite/participant/select', 'ParticipantController@prepareinvite')->name('master-template-invite-participants-prepare');
            Route::post('invite/participant/send', 'ParticipantController@sendinvite')->name('master-template-invite-participants-send');
            
            //Sub-Participants
            Route::get('/{id}/{invite_key}/sub-participants/invite', 'SubParticipantsController@index')->name('master-template-invite-subparticipants');
            Route::post('invite/sub-participants/select', 'SubParticipantsController@prepareinvite')->name('master-template-invite-subparticipants-prepare');
            Route::post('invite/sub-participant/send', 'SubParticipantsController@sendinvite')->name('master-template-invite-subparticipants-send');
            Route::get('/{id}/sub-participant/assets/details', 'SubParticipantsController@show')->name('master-template-subparticipants-asset-detail');
            Route::get('/{id}/{inviteid}/subparticipant-modules/launch/{asset_id}/{invite_asociated_key?}', 'SubParticipantsController@viewModules')->name('Subparticipant-launch-master-template');
            Route::get('/{id}/{inviteid}/{moduleid}/subparticipant-sub-modules/{asset_id}/{app_id}/{invite_asociated_key?}', 'SubParticipantsController@viewSubModules')->name('Subparticipant-mastertemp-id-submodule');
            Route::post('/sub-participants/submit/response', 'SubParticipantsController@saveResponse')->name('Subparticipant-submit-response');
            Route::get('/{id}/sub-participants/response', 'SubParticipantsController@viewResponse')->name('Subparticipant-responses-view');
            Route::get('/{id}/sub-participants/own-response', 'SubParticipantsController@viewOwnResponse')->name('Subparticipant-own-responses-view');

        Route::get('/{id}/sub-participants/own-response', 'SubParticipantsController@viewOwnResponse')->name('Subparticipant-own-responses-view');

        //Users
        Route::get('/{id}/invite/users', 'UserController@InviteUsers')->name('master-template-invite-users');
        Route::post('invite/users/send/', 'UserController@sendinvite')->name('master-template-invite-users-send');
        Route::any('/{template_id}/participants/list', 'UserController@uParticipantListing')->name("master-template-participant-listing");
        Route::any('/{invite_id}/block-invite-user', 'UserController@blockInvite');
        Route::any('/{invite_id}/unblock-invite-user', 'UserController@unblockInvite');
        
        //After Participant Login
        Route::get('/{id}/assets/details', 'ParticipantController@show')->name('master-template-participants-asset-detail');

        //Overall response
        Route::get('/{id}/{invitee_id}/overall/response', 'ParticipantController@overallResponse')->name('master-template-overall-response');
        Route::get('/{form_id}/{invitee_id}/graphics/response', 'ParticipantController@compairFormReport')->name('master-template-overall-response-graphical');

        //Schedular
        Route::get('/{id}/{appId}/list/schedular/{fromPage?}', 'SchedularController@index')->name('master-template-list-schedular');
        Route::get('/{show_id}/class-price', 'SchedularController@classPrice');
        Route::post('/add/class-price', 'SchedularController@addClassPrice');

        Route::post('/schedular/add/restriction', 'SchedularController@save')->name('master-template-save-schedular');

        Route::get('/{id}/{assetId}/{associatedId}/list/schedular/forms', 'SchedularController@getSchedularForms')->name('master-template-list-schedular-forms');
        Route::get('/{id}/{assetId}/{associatedId}/{subId}/subParticipant/list/schedular/forms', 'SchedularController@getSubSchedularForms')->name('master-template-sub-list-schedular-forms');
        
        Route::get('/list/participant/scheduler/{showId}/{formId}/{asset_id}/{associated_id}/{isSubParticipant}/{subId?}', 'SchedularController@getSchedule')->name('master-template-list-participant-scheduler');

        Route::get('/participant/scheduler/{template_id}/{asset_id}/{invite_asociated_key}/{show_id}/{isSubParticipant}/{subId?}', 'SchedularController@participantScheduler')->name('master-template-participant-schedular');

        Route::get('/participant/Horses/{show_id}/{asset_id}/{restriction_id}/{requestType}/{user_id?}', 'SchedularController@getClassHorses')->name('master-template-participant-horses');
        Route::get('/getCourses/{show_id}/{restriction_id}/{user_id}', 'SchedularController@getCourses')->name('master-template-participant-getCourses');
        Route::get('/trainerHorses/{show_id}/{asset_id}/{requestType}/{user_id?}', 'SchedularController@getTrainerHorses')->name('master-template-trainer-horses');

        Route::get('/participant/getHorseName/{horse_id}', 'SchedularController@getHorseName')->name('master-template-participant-getHorseName');

        Route::get('/participant/checkTimeAvailability/{timeFrom}/{timeTo}/{show_id}/{asset_id}/{type}', 'SchedularController@checkTimeAvailability')->name('master-template-participant-checkTimeAvailability');

        Route::get('getTimeSLots/{asset_id}/{form_id}', 'SchedularController@getTimeSLots')->name('master-template-schedular-getTimeSLots');

        Route::get('/asset/participant/scheduler/{showId}/{formId}/{asset_id}/{associated_id}/{isSubParticipant}/{subId?}', 'SchedularController@getAssetSchedule')->name('master-template-asset-participant-scheduler');

        Route::post('schedular/sendInvite', 'SchedularController@sendInvite')->name('master-template-schedular-sendInvite');

        Route::post('schedular/addNotes', 'SchedularController@addNotes')->name('master-template-schedular-addNotes');

        Route::post('schedular/addFaciltyNotes', 'SchedularController@addFaciltyNotes')->name('master-template-schedular-addFaciltyNotes');

        Route::post('schedular/updateTimeSlots', 'SchedularController@updateTimeSlots')->name('master-template-schedular-updateTimeSlots');

        Route::get('schedular/feedBack/{id}/{form_id}/{spectatorId?}', 'SchedularController@feedBack')->name('master-template-schedular-feedBack');

        Route::get('schedular/faciltyFeedBack/{template_id}/{form_id}/{schedule_id}/{spectatorId?}', 'SchedularController@faciltyFeedBack')->name('master-template-schedular-faciltyFeedBack');

        Route::get('getFeedbackLinks/{id}/{template_id}/{type}', 'SchedularController@getFeedbackLinks')->name('master-template-schedular-getFeedbackLinks');




        Route::post('schedular/submit/feedBack', 'SchedularController@saveFeedBack')->name('master-template-schedular-submit-feedBack');
        
        Route::any('schedular/deleteNotes/{id}', 'SchedularController@deleteNotes')->name('master-template-schedular-deleteNotes');


        Route::any('schedular/deleteMultiNotes/{id}', 'SchedularController@deleteMultiNotes')->name('master-template-schedular-deleteMultiNotes');


        Route::get('/{id}/{show_id}/masterSchedular/{spectatorsId?}', 'SchedularController@masterSchedular')->name('master-template-masterSchedular');
        
        Route::get('getAssetForms/{id}/{templateId}', 'SchedularController@getAssetForms')->name('master-template-getAssetForms');

        Route::get('/getEvents/{id}/{templateId}/{formId}/{showId}/{spectatorsId?}', 'SchedularController@getEvents')->name('master-template-masterSchedular-getEvents');

        Route::get('/getPrimaryEvents/{id}/{templateId}', 'SchedularController@getPrimaryEvents')->name('master-template-getPrimaryEvents');

        Route::post('schedular/markDone', 'SchedularController@markDone')->name('master-template-schedular-markDone');

        Route::post('schedular/facilityMarkDone', 'SchedularController@facilityMarkDone')->name('master-template-schedular-facilityMarkDone');

        Route::post('schedular/markDoneAll', 'SchedularController@markDoneAll')->name('master-template-schedular-markDoneAll');

        Route::post('schedular/markDoneAllGroups', 'SchedularController@markDoneAllGroups')->name('master-template-schedular-markDoneAllGroups');


        Route::post('schedular/sendReminder', 'SchedularController@sendReminder')->name('master-template-schedular-sendReminder');
        // Post Position in master schedular
        Route::post('schedular/add-position', 'SchedularController@addPosition')->name('master-template-schedular-position');
        
        Route::get('/{template_id}/invitee/feedBack/{spectatorId?}', 'ParticipantController@getFeedBackMaster')->name('master-template-invitee-feedBack');

        Route::get('/{template_id}/judges/feedBack', 'ParticipantController@getJudgesFeedBack')->name('master-template-judges-feedBack');


        Route::any('/feedback/submitFeedBackRequest', 'ParticipantController@submitFeedBackRequest')->name('master-template-submitFeedBackRequest');


        //Spectators

        Route::get('/{id}/invite/spectators', 'SpectatorController@index')->name('master-template-invite-spectators');
        Route::post('invite/spectators', 'SpectatorController@invite')->name('master-template-spectators-invite');
        Route::get('spectators/sendInvite/response/{id}/{response}', 'MailController@responseSpectatorsMail');
        
        Route::get('{id}/{spectatorsId}/spectators/masterSchedular', 'SpectatorController@masterSchedular')->name('master-template-spectators-masterSchedular');

        //facilty Scheduler

        Route::get('/participant/secondaryScheduler/{templateId}/{primaryId}/{associated_id}/{isSubParticipant}/{subId?}', 'SchedularController@secondaryScheduler')->name('master-template-secondary-participant-scheduler');

        Route::get('/assets/primarySchedular/{template_id}/{id}/{show_id?}', 'SchedularController@primaryScheduler')->name('master-template-asset-primary-scheduler');

        Route::post('PrimarySchedular/sendInvite', 'SchedularController@primarySendInvite')->name('master-template-schedular-PrimarySendInvite');

        //Invoice

        Route::post('invoice/submit/billing', 'InvoiceController@saveInvoice')->name('master-template-invoice-submit-billing');
        
        Route::any('invoice/save/invoice', 'InvoiceController@saveInviteInvoice')->name('master-template-invoice-save-invite-invoice');
        
        Route::post('invoice/save/penaltyinvoice', 'InvoiceController@savePenaltyInviteInvoice')->name('master-template-penalty-invoice-save-invite-invoice');

        Route::get('{id}/{form_id}/{template_id}/{asset_id}/{participantId}/billing/invoice/{invite_asociated_key}/{appOwnerRequest?}/{responseId?}', 'InvoiceController@getInvoiceForm')->name('master-template-billing-invoice-form');
        
        Route::get('{id}/{form_id}/{template_id}/{asset_id}/{participantId}/subParticipant/billing/invoice/{invite_asociated_key}/{subId}', 'SubParticipantsController@getSubParticipantInvoice')->name('master-template-subParticipant-invoice');

        Route::get('{id}/{form_id}/{template_id}/{asset_id}/scheduler/billing/invoice/{invite_asociated_key}', 'InvoiceController@getInvoiceForm2')->name('master-template-scheduler-billing-invoice-form');

        Route::get('/{id}/invoice/listing', 'InvoiceController@OwnerInvoiceListing')->name('master-template-invoice-listing');
        
        Route::get('/{id}/{templateId}/Invoice/viewInvoice', 'InvoiceController@viewOwnerInvoice')->name('master-owner-invoice-view');
        
        Route::get('/{id}/{asset_id}/associatedInvoice/viewInvoice', 'InvoiceController@viewAssociatedInvoice')->name('master-associated-invoice-view');

        Route::get('/{template_id}/{invoiceFormKey}/PenaltyAssociatedInvoice/viewInvoice', 'InvoiceController@viewPenaltyAssociatedInvoice')->name('master-penalty-associated-invoice-view');

        Route::get('/{id}/{templateId}/viewEvent', 'InvoiceController@viewEventForm')->name('master-Event-invoice-view-form');
        
        Route::post('Invoice/saveInvoiceAmount', 'InvoiceController@saveInvoiceAmount')->name('master-template-invoice-save-invoice-amount');
        
//billing information
        
        Route::post('billing/saveBankAccountInfo', 'BillingController@saveBankAccountInfo')->name('master-template-billing-Bank-account-information');
    
        Route::any('/{id}/billing/singleInvoice/{amount?}', 'BillingController@singleInvoice')->name('master-singleInvoice-billing');

        Route::any('/billing/setMultiInvoice', 'BillingController@setMultiInvoice')->name('master-multiInvoice-billing');

        Route::any('/billing/multipleInvocie', 'BillingController@multipleInvocie')->name('master-multipleInvocie-billing');

        Route::get('/{id}/billing/submitByAccount', 'BillingController@submitByAccount')->name('master-template-submit-account');
     
        // Show payment form
        Route::get('/{id}/payment/add-funds/paypal', 'PaypalController@getPaypalRequest')->name('payment-detail-paypal');

        // Post payment details for store/process API request
        Route::any('/payment/paypal/{id}', 'PaypalController@store');


        Route::any('/multipleInvoice/paypal/{id}', 'PaypalController@multipleInvoiceRequest');

        Route::get('/{id}/payment/add-funds/paypal/status', 'PaypalController@getPaymentStatus');
        // Handle status
        Route::post('/createPaypalAccount', 'PaypalController@createPaypalAccount');
    
        Route::post('/EmailVerificationPaypal', 'PaypalController@EmailVerificationPaypal');
        
        Route::any('/Invoice/{id}/preview/{moduleId}/{assetId?}', 'InvoiceController@getInvoiceFormView')->name("invoice-preview-form");
    
        Route::any('/penaltyInvoice/preview/{templateId}', 'InvoiceController@getPanaltyInvoice')->name("panalty-invoice-preview-form");

        Route::any('/exportAssetCsv/{templateId}', 'AssetController@exportAssetCsv');

        Route::any('/exportResponsePdf/{id}', 'ExportController@exportResponsePdf')->name("export-response-pdf");

        Route::any('/ExportRegistrationView/{id}', 'ExportController@ExportRegistrationView');

        Route::any('/ExportOrderSupplies/{order_id}/{typeid}', 'ExportController@ExportOrderSupplies');


        Route::any('/ExportSpectatorView/{id}', 'ExportController@ExportSpectatorView');

        Route::any('/ExportSponsorsView/{show_id}/{id}', 'ExportController@ExportSponsorsView');


        Route::any('/exportOwnerCsv/{id}/{type}', 'ExportController@exportOwnerCsv');

        Route::any('/exportinvoiceCsv/{invoice_id}', 'ExportController@exportinvoiceCsv');

        Route::any('schedular/searchMarkDone/{id}', 'SchedularController@searchMarkDone')->name('master-template-schedular-searchMarkDone');

        Route::post('schedular/saveSchedulerTime/', 'SchedularController@saveSchedulerTime')->name('master-template-schedular-saveSchedulerTime');

        Route::post('/updateModuleLogo', 'FormController@updateModuleLogo')->name('master-template-update-module-logo');
        Route::post('/assets/updateScheduleTime', 'AssetController@updateScheduleTime')->name('master-template-update-schedule-time');
        Route::any('/assets/assetSchedulers/{id}/{primaryId}/{form_id}/{show_id?}', 'AssetController@assetSchedulers')->name('master-template-update-assetSchedulers');

        Route::get('/participant/secondarAssets/{id}', 'ParticipantController@secondarAssets')->name('master-template-participant-secondarAssets');


        Route::get('/participant/classes/{assets}/{restriction_id}/{show_id}/{user_id}/{current_asset}', 'SchedularController@getClassAssets')->name('master-template-participant-scheduler-assets');


        Route::any('/scheduler/inviteeMasterScheduler/', 'SchedularController@inviteeMasterScheduler')->name('master-template-inviteeMasterScheduler');

        Route::get('/getEventsData/{id}', 'SchedularController@getEventsData')->name('master-template-participant-scheduler-getEventsData');


        Route::get('/getEventsParticipants/{show_id}/{form_id}/{asset_id}/{dateFrom}/{dateTo}/{type}/{slot_time}/{restriction_id}', 'SchedularController@getEventsParticipants')->name('master-template-participant-scheduler-getEventsParticipants');

        Route::get('/getGroupParticipants/{show_id}/{schedual_id}/{dateFrom}/{dateTo}/{type}/{slot_time}/{restriction_id}', 'SchedularController@getGroupParticipants')->name('master-template-participant-scheduler-getGroupParticipants');



        Route::get('{template_id}/view/orderSupplies', 'ShowController@ViewOrderSupplies')->name('master-template-view-order-supplies');

        Route::any('/{template_id}/addScratchEntries', 'ShowController@addScratchEntries')->name("ShowController-add-scratch-entries");

        Route::any('/{asset_id}/getQrCode', 'AssetController@getQrCode')->name("ShowController-get-qr-code");

        Route::get('/getHorseHeight/{id}', 'SchedularController@getHorseHeight')->name('master-template-scheduler-getHorseHeight');

        Route::get('/getPositionsScore/{asset_id}/{show_id}/{restriction_id}/{form_id?}', 'SchedularController@getPositionsScore')->name('master-template-scheduler-getPositionsScore');

        Route::get('/getScoreForScheduler/{asset_id}/{show_id}/{horse_id}/{restriction_id}/{form_id}', 'SchedularController@getScoreForScheduler')->name('master-template-scheduler-getScoreForScheduler');

        Route::get('/deleteSchedulerClass/{asset_id}/{scheduler_key}/{show_id}', 'SchedularController@deleteSchedulerClass')->name('master-template-scheduler-deleteSchedulerClass');

        Route::get('/checkAlreadyExist/{asset_id}/{scheduler_key}/{show_id}', 'SchedularController@checkAlreadyExist')->name('master-template-scheduler-checkAlreadyExist');

        Route::get('/deleteScoreClass/{scheduler_key}/{asset_id}/{classes}', 'SchedularController@deleteScoreClass')->name('master-template-scheduler-deleteScoreClass');
        Route::get('/deleteSchduler/{scheduler_key}', 'SchedularController@deleteSchduler')->name('master-template-scheduler-deleteSchduler');


        Route::get('/editSchedulerTime/{scheduler_key}', 'SchedularController@editSchedulerTime')->name('master-template-scheduler-editSchedulerTime');


        Route::post('/schedular/addRestrictions', 'SchedularController@addRestrictions')->name('master-template-addRestrictions');
        Route::post('/schedular/addReminder', 'SchedularController@addReminder')->name('master-template-addReminder');

        Route::get('/getAssetTitles/{asset_id}', 'AssetController@getAssetTitles')->name('master-template-scheduler-getAssetTitles');



    });

    //Participant
     Route::group(['prefix' => 'participant'], function(){
        Route::post('/submit/response', 'ParticipantController@saveResponse')->name('participant-submit-response');
        Route::get('/{id}/history/assets', 'ParticipantController@history')->name('participant-asset-history');
        Route::get('/{id}/attached/history/assets', 'ParticipantController@Attachedhistory')->name('participant-attached-asset-history');
        //View Response
        Route::get('/{accetid}/response/readonly', 'AssetController@viewParticipantResponse')->name('participant-view-accet');
        Route::get('/{accetid}/all/response/readonly', 'ParticipantController@viewParticipantResponseAll')->name('participant-repsones-history');
    
         Route::get('/{asset_id}/participant/getFeedBack', 'ParticipantController@getFeedBack')->name("participant-asset-getFeedBack");
    
         Route::get('/{feedBackId}/FeeBack/viewFeedBack/{asset_history?}', 'ParticipantController@viewFeedBack')->name("participant-view-FeedBack");
    
         Route::get('/{id}/{assetId}/invoice/listing/{associatedKey?}', 'InvoiceController@invoiceListing')->name('participant-invoice-listing');
    
         Route::get('/{id}/{assetId}/subParticipant/invoice/listing/{associatedKey?}', 'InvoiceController@SubInvoiceListing')->name('sub-participant-invoice-listing');

         
         Route::get('/{formId}/{templateId}/{invoiceFormId}/{assetId}/Invoice/viewInvoice', 'InvoiceController@viewInvoice')->name('participant-invoice-view');;
         
         Route::post('/submit/checkout', 'BillingController@submitCheckout')->name('participant-submit-checkout');


         Route::get('/{asset_id}/rider/judges/feedBack', 'ParticipantController@getRiderJudgesFeedBack')->name('master-template-judges-feedBack');


         Route::post('/submit/stripe', 'BillingController@getAccountDetail')->name('participant-submit-stripe');
         Route::post('/edit/stripe', 'BillingController@editStripeDetail')->name('participant-edit-stripe-detail');
         Route::any('/exportParticipantCsv/{id}/{type}', 'ExportController@exportParticipantCsv');

     });
    
    Route::get('/paymentMethods', 'BillingController@paymentMethods')->name('participant-payment-methods');
    
    Route::get('/billing/{user_type?}', 'BillingController@billingDetail')->name('participant-billing-details');
    
    //Reporting
    Route::group(['prefix' => 'report'], function(){
        //Participants Response
        Route::get('/{accetid}/graphics/response', 'ReportController@compairFormReport')->name('compare-graphic-reports');
        Route::post('/assets/graphics/response', 'ReportController@selectAssetReport');
    });

    Route::group(['prefix' => 'sub-participant'], function(){


        Route::get('/{id}/attached/history/assets', 'SubParticipantsController@Attachedhistory')->name('sub-participants-attached-asset-history');
    });

    //Ranking
    Route::group(['prefix' => 'ranking'], function(){
        //Participants Response
        Route::get('/{accetid}/index', 'RankingController@index')->name("rankingcontroller-index");
        Route::get('/{template_id}/{module_id}/getranked', 'RankingController@show')->name("rankingcontroller-show");
        Route::get('/{template_id}/{module_id}/module_wise', 'RankingController@moduleWise')->name("rankingcontroller-module-wise");
        Route::get('/{template_id}/modules-back', 'RankingController@backToAll')->name("rankingcontroller-module-back");
        
        //Participant Ranking
        Route::get('/{participat_id}/participant/ranking', 'RankingController@participantIndex')->name("rankingcontroller-participant-index");
        Route::get('/{template_id}/{module_id}/{invitee_id}/{participant_id}/getranked-participant', 'RankingController@ParticipantShow')->name("rankingcontroller-participant-show");
        Route::get('/{template_id}/{module_id}/{invitee_id}/{participant_id}/module_wise_participant', 'RankingController@moduleWiseParticipant')->name("rankingcontroller-module-wise-participant");
        Route::get('/{template_id}/modules-back-participant', 'RankingController@backToAllParticipant')->name("rankingcontroller-module-back-participants");
        
        //Subparticipant Ranking 
        Route::get('/{participat_id}/sub-participant/ranking', 'RankingController@subParticipantIndex')->name("rankingcontroller-subparticipant-index");

    });
    //feedback
    Route::post('/feedback/send', 'HomeController@sendFeedback')->name("Feedback-send");    

    //Timeline
    Route::group(['prefix' => 'timeline'], function(){
        //User Time line
        Route::any('/index', 'TimelineController@index')->name("timelinecontroller-index");    
        Route::any('/index/filter/{id}', 'TimelineController@index')->name("timelinecontroller-filtered-index");    
        Route::post('/addPost', 'TimelineController@AddPost')->name("timelinecontroller-AddPost");    
        Route::post('/edit/posts', 'TimelineController@EditPostAjax')->name("timelinecontroller-CommentAjax");    
        Route::post('/delete/posts', 'TimelineController@deletePostAjax')->name("timelinecontroller-DeleteCommentAjax");    
        
        Route::post('/add/comments', 'TimelineController@writeCommentOnPost')->name("timelinecontroller-addCommentAjax");    
        Route::post('/delete/comment', 'TimelineController@deleteCommentAjax');  
        Route::post('/edit/comment', 'TimelineController@EditCommentAjax');    

        //Like dislike
        Route::post('/likeUnlike/posts', 'TimelineController@likeDislikeAjax');    
        Route::post('/likeUsers/posts', 'TimelineController@postLikedUsersAjax');    
    });

    //shows
    Route::group(['prefix' => 'shows'], function(){
        //Shows dashboard
        Route::any('/dashboard/{show_duration?}', 'ShowController@index')->name("ShowController-index");
        //trainers
        Route::any('/{asset_id}/feedback-horse', 'ShowController@feedbackTrainer')->name("ShowController-trainers-feedback-given");
        Route::get('trainer/viewOrderDetail/{order_id}/{orderType}', 'ShowController@viewOrderDetail')->name('master-template-view-order-detail');

        Route::post('/checkTrainerRestrictions', 'ShowController@checkTrainerRestrictions');

        Route::any('/{id}/add-trainers/{edit_id?}', 'ShowController@add_trainers')->name("ShowController-add-trainers");
        Route::post('/trainer/store', 'ShowController@store_trainers');    
        Route::any('/{id}/delete-trainer', 'ShowController@deleteTrainer');
        Route::any('/{id}/get/trainers/{msr_id?}', 'ShowController@getTrainersAjax');       
        Route::any('/addAjax/trainer', 'ShowController@saveTrainersAjax');       
        Route::any('/{template_id}/{app_id}/{show_id}/trainer/splite-invoice', 'ShowController@splite_trainers')->name("ShowController-split-invoice-trainers");

        Route::any('/{template_id}/{app_id}/{show_id}/trainer/order-supplies', 'ShowController@orders_supplies')->name("ShowController-orders-supplies-trainers");

        Route::any('/{show_id}/trainer/splite-invoice-history', 'ShowController@splite_trainers_history')->name("ShowController-split-invoice-trainers-history");
        //Route::any('/{show_id}/trainer/splite-invoice-history', 'ShowController@splite_trainers_history')->name("ShowController-split-invoice-trainers-history");       
        Route::any('/view-trainers/{split_id}/history','ShowController@historyTrainerSplit');
        Route::post('/trainer/split/invoice', 'ShowController@splite_trainers_invoice');
        Route::post('/trainer/order/supplies', 'ShowController@order_supplies_save');

        //participants
        Route::any('/{id}/participate', 'ShowController@create')->name("ShowController-create");       
        Route::post('/store', 'ShowController@store');    
        Route::any('/{id}/invoice', 'ShowController@invoice')->name("ShowController-invoice");       
        Route::post('/invoice/save', 'ShowController@saveInvoice');    
        Route::post('/participate', 'ShowController@store');    
        //Additional charges 
        Route::any('/{id}/additional-charges', 'ShowController@additionalCharges')->name("ShowController-additionalCharges");       
        Route::post('additional-charges/store', 'ShowController@additionalCsave');    
        
        Route::get('additional-charges/delete/{id}','ShowController@additionalCdelete');
        Route::get('{id}/{participant_id}/pay/invoice','ShowController@viewInvoice')->name("ShowController-ViewInvoice");
        Route::post('invoice/payment', 'ShowController@payInvoice');    
        Route::get('{id}/prizing/listing','AssetController@prizingListing')->name("AssetController-showPrizingListing");
        Route::get('/{id}/participants', 'ShowController@showParticipants')->name('shows-all-participants');
        Route::get('/{id}/registration/view', 'ShowController@registrationView')->name('shows-registration-view');
        Route::get('{id}/{participant_id}/view/invoice','ShowController@viewParticipantInvoice')->name("ShowController-ViewInvoice-participant");
        Route::post('/invoice/payinoffice', 'ShowController@payInOffice');    
        Route::get('{id}/split/class/new','AssetController@splitClassIndex')->name("AssetController-split-class-new");


        Route::get('/low-participant-view/{id}','ShowController@viewLowParticipants')->name("ShowController-ViewInvoice-participant");

        //PDF
        Route::get('{id}/{participant_id}/pdf/pay/invoice','ExportController@viewInvoice')->name("ShowController-pdf-ViewInvoice");
        Route::get('{id}/pdf/trainer/view-invoice','ExportController@viewtrainerInvoice')->name("ShowController-pdf-ViewInvoice");


        Route::any('trainer/getSplitInvoice', 'ShowController@getSplitInvoice')->name("ShowController-trainer-getSplitInvoice");


        Route::any('/{template_id}/{app_id}/{show_id}/trainer/viewOrderHistory', 'ShowController@viewOrderHistory')->name("ShowController-trainer-viewOrderHistory");

        Route::any('/{template_id}/{app_id}/{show_id}/viewSchedulerForm', 'ShowController@viewSchedulerForm')->name("Show-trainer-view-Scheduler-Form");


        Route::any('/submitSpectatorForm', 'ShowController@submitSpectatorForm')->name("ShowController-trainer-submitSpectatorForm");


        Route::get('{id}/{participant_id}/pdf/view/invoice','ExportController@viewParticipantInvoice')->name("ShowController-ViewInvoice-participant");
        
        //Scratch
        //Route::get('{id}/horse/scratch','ShowController@scratchHorse')->name("ShowController-scratch-horse");
        Route::get('{id}/horse/scratch/{add?}','ShowController@scratchHorse')->name("ShowController-scratch-horse");
        Route::get('{id}/add/scratch','ShowController@addScratch')->name("ShowController-add-scratch");
        Route::post('/save/scratch','ShowController@saveScratch')->name("ShowController-save-scratch");
        Route::get('{id}/delete/scratch','ShowController@destroyScratch');
        //Route::get('{id}/{participant_id}/view/invoice','ShowController@exportBillingPdf')->name("ShowController-pdf-ViewInvoice-participant");
        //Route::any('/step-2', 'ShowController@store')->name("ShowController-create");

        //Riders
        Route::any('/{show_id}/trainer/riders-index', 'ShowController@riderIndex')->name("ShowController-rider-index");       
        Route::any('/trainer/rider-detail/{id}', 'ShowController@riderDetail')->name("ShowController-rider-detail");       
        Route::post('/trainer/participate-for-rider','ShowController@riderParticipate')->name("ShowController-rider-participate");

        // Horse Invoices
        Route::any('/home/invoicing', 'HorseBillingController@showInvoicing')->name("billing-show-invoices-index");       
        Route::get('/{id}/horse/invoices', 'HorseBillingController@invoicingDetail')->name("billing-show-invoices-detail");       
        Route::post('/horse/payment', 'HorseBillingController@billingHorse')->name("billing-show-horses-view");       
        Route::get('/{template_id}/horse/invoice/listing', 'HorseBillingController@appOwnerHorseListing')->name("billing-show-invoices-appowner-listing");       
        Route::get('/{show_id}/{user_id}/horse/invoice/view', 'HorseBillingController@appOwnerHorseInvoice')->name("billing-show-invoices-appowner-invoice");       
        Route::post('/app-owner/update/invoice', 'HorseBillingController@updateInvoice');       
        Route::post('/app-owner/update/additional', 'HorseBillingController@updateAdditional');       
        Route::post('/app-owner/update/split', 'HorseBillingController@updateSplit');       
        Route::post('/app-owner/update/divisions', 'HorseBillingController@updateDivision');       
        Route::get('/invoice-already-paid/{horse_id}/{show_id}', 'HorseBillingController@invoiceAlreadyPaid');
        //Horse Invoice Export

        Route::post('/app-owner/payinoffice','BillingController@appownerPayInOffice');
        Route::post('/app-owner/payinoffice/edit','BillingController@appownerPayInOfficeEdit');
        
        Route::post('/pdf/print/app-owner/horse-invoice','ExportController@printPDFInvoice');

        Route::get('/{template_id}/manageSponsorRequest', 'ShowController@manageSponsorRequest')->name("billing-show-sponsor-request-form");


        Route::get('/{template_id}/horse/prizClaimForms', 'HorseBillingController@prizClaimForms')->name("billing-show-prize-claim-form");
        Route::post('/invoice/add/comment', 'HorseBillingController@addInvoiceComment');

        Route::get('/{id}/spectators', 'ShowController@showSpectators')->name('shows-all-spectators');
        Route::get('/{id}/spectator/view', 'ShowController@spectatorView')->name('shows-registration-view');

        Route::get('GetScratchCount/{class}/{show_id}', 'ShowController@GetScratchCount')->name('shows-registration-view');

        Route::get('/horseBreeds/{id}/{horse_id}', 'ShowController@horseBreeds');

        Route::get('/horseAgeRestriction/{id}/{horse_id}', 'ShowController@horseAgeRestriction');
        Route::get('/riderAgeRestriction/{id}/{horse_id}', 'ShowController@riderAgeRestriction');



        Route::get('/checkShowRestriction/{id}/{horse_id}/{show_id}', 'ShowController@checkShowRestriction');

        Route::get('/checkRiderRestriction/{id}/{rider_id}/{show_id}', 'ShowController@checkRiderRestriction');


        Route::get('/trainerBreeds/{id}/{horse_id}', 'ShowController@trainerBreeds');

        Route::any('/{id}/sponsor/{type}', 'ShowController@sponsorRegistration')->name("ShowController-sponsor-registration");

        Route::any('/{show_id}/{id}/sponsor/{type}', 'ShowController@viewSponsorRequest')->name("ShowController-view-sponsor-registration");


        Route::post('/submitSponsorcategory', 'ShowController@submitSponsorcategory');

        Route::get('getSponsorCategories/{id}', 'ShowController@getSponsorCategories');

        Route::post('/sposnorRequest', 'ShowController@sposnorRequest');

        Route::any('/sponsors/getSelectedCategories/{category_ids}/{show_id}/{sponsor_form_id}', 'ShowController@getSelectedCategories')->name("sponsor-selected-categories");

        Route::any('/delete/{id}', 'ShowController@deleteSponsors')->name("delete-sponsor-categories");

        Route::post('/sponsor/stripeCheckout', 'BillingController@sponsorStripeCheckout')->name('shows-sponsor-stripe-checkout');
        Route::post('/sponsor/payPalCheckout', 'BillingController@sponsorPaypalcheckout')->name('shows-sponsor-stripe-checkout');
        Route::post('/submitPaypal/payPalCheckout', 'BillingController@sponsorPaypalcheckout')->name('shows-sponsor-stripe-checkout');

        Route::get('/submitPaypal/{id}', 'BillingController@getPaypalDetails')->name('payment-detail-paypal');

        Route::get('/{id}/showSponsors', 'ShowController@showSponsors')->name('shows-all-sposnors');

        Route::get('/{id}/sponsorHistory', 'ShowController@sponsorHistory')->name('shows-all-sposnors-history');


//        Route::get('/{id}/showSponsorsDetails', 'ShowController@showSponsorsDetails')->name('shows-all-sposnors-details');

        Route::get('/{id}/showStables', 'ShowController@showStables')->name('shows-all-show-stables');

        Route::post('/saveStallTypes', 'ShowController@saveStallTypes')->name('shows-save-stall-types');

        Route::get('/getStallTypes/{id}', 'ShowController@getStallTypes')->name('shows-stall-types');
        Route::get('/StallTypesListing/{id}', 'ShowController@StallTypesListing')->name('shows-stall-types-listing');


        Route::post('/saveStable', 'ShowController@saveStable')->name('shows-save-stable');


        Route::get('/removeStallType/{id}', 'ShowController@removeStallType')->name('remove-stall-types');


        Route::any('/{id}/stallRequest', 'ShowController@stallRequest')->name("ShowController-stallRequest");

        Route::post('/submitStallRequest', 'ShowController@submitStallRequest')->name("ShowController-submitStallRequest");

        Route::get('{show_id}/viewStallRequests', 'ShowController@viewStallRequests')->name("ShowController-view-stall-request");
        Route::get('/getTrainerHorses/{user_id}/{show_id}', 'ShowController@getTrainerHorses')->name("ShowController-get-trainer-horses");
        Route::get('/getEditStable/{id}', 'ShowController@getEditStable')->name("ShowController-get-edit-stable");
        Route::get('/deleteStable/{id}/{show_id}', 'ShowController@deleteStable')->name("ShowController-delete-stable");

        Route::post('{id}/stallRequestResponse', 'ShowController@stallRequestResponse')->name("ShowController-stallRequestResponse");
        Route::post('{id}/stallAssociateRiders', 'ShowController@stallAssociateRiders')->name("ShowController-stallAssociateRiders");

        Route::get('{id}/viewStableDetails', 'ShowController@viewStableDetails')->name("ShowController-viewStableDetails");

        Route::get('{id}/getRemainigStalls', 'ShowController@getRemainigStalls')->name("ShowController-getRemainigStalls");

        Route::get('{id}/viewUnpaidStalls', 'ShowController@viewUnpaidStalls')->name("ShowController-viewUnpaidStalls");

        Route::post('{user_id}/{show_id}/stallRequestInOffice', 'ShowController@stallRequestInOffice')->name("ShowController-stallRequestInOffice");
        Route::post('stalls/add-utility-stall', 'ShowController@utilityStallDivide')->name("ShowController-utilityStallDivide");



        Route::any('{user_id}/sendNotification', 'ShowController@sendNotification')->name("ShowController-sendNotification");

        Route::any('checkDivisions/{asset_id}', 'ShowController@checkDivisions')->name("ShowController-checkDivisions");

        Route::any('getScoringClasses/{asset_id}', 'ShowController@getScoringClasses')->name("ShowController-getScoringClasses");

        Route::get('/{id}/exportShows', 'ShowController@exportShows')->name('shows-all-exportShows');
        Route::get('/{id}/exportShowsDetails', 'ExportController@exportShowsDetails')->name('shows-all-exportShowsDetails');



        Route::group(['prefix' => 'champion'], function(){ 
            Route::get('/{app_id}/index', 'ChampionController@index')->name('champion-calculator');
            Route::get('/{app_id}/{show_id}/create/{cd_id?}', 'ChampionController@createDivision')->name('champion-calculator-create');
            Route::match(['post'], '/saved', 'ChampionController@saveDivision')->name('champion.saved');
            Route::get('/delete/{cd_id}', 'ChampionController@deleteDivision');

        });


        Route::any('/getUnSelectedClasses/{form_id}/{show_id}/{template_id}', 'ShowController@getUnSelectedClasses')->name("ShowController-getUnSelectedClasses");

        Route::get('viewSponsorInvoice/{billing_id}', 'ShowController@viewSponsorInvoice')->name("ShowController-viewSponsorInvoice");

        Route::any('invoice/addAddionalCharges/{class_id}', 'ShowController@addAddionalCharges')->name("ShowController-addAddionalCharges");

    });

    Route::group(['prefix' => 'trainer'], function(){
        //Shows dashboard
        Route::any('/dashboard', 'TrainerController@index')->name("TrainerController-index");       
        //trainers
        Route::any('/{asset_id}/feedback-horse', 'TrainerController@feedbackTrainer')->name("TrainerController-trainers-feedback-given");
        Route::get('trainer/viewOrderDetail/{order_id}/{orderType}', 'TrainerController@viewOrderDetail')->name('master-template-view-order-detail');

        Route::any('/{template_id}/{app_id}/{show_id}/trainer/order-supplies', 'TrainerController@orders_supplies')->name("TrainerController-orders-supplies-trainers");

        Route::any('/{show_id}/trainer/splite-invoice-history', 'TrainerController@splite_trainers_history')->name("TrainerController-split-invoice-trainers-history");
        //Route::any('/{show_id}/trainer/splite-invoice-history', 'TrainerController@splite_trainers_history')->name("TrainerController-split-invoice-trainers-history");       
        Route::any('/view-trainers/{split_id}/history','TrainerController@historyTrainerSplit');
        Route::post('/trainer/split/invoice', 'TrainerController@splite_trainers_invoice');

        //participants
        Route::any('/{id}/participate', 'TrainerController@create')->name("TrainerController-create");       
        Route::post('/store', 'TrainerController@store');    
        Route::any('/{id}/invoice', 'TrainerController@invoice')->name("TrainerController-invoice");       
        Route::post('/invoice/save', 'TrainerController@saveInvoice');    
        Route::post('/participate', 'TrainerController@store');    
        //Additional charges 
        Route::any('/{id}/additional-charges', 'TrainerController@additionalCharges')->name("TrainerController-additionalCharges");       
        Route::post('additional-charges/store', 'TrainerController@additionalCsave');    
        
        Route::get('additional-charges/delete/{id}','TrainerController@additionalCdelete');
        Route::get('{id}/{participant_id}/pay/invoice','TrainerController@viewInvoice')->name("TrainerController-ViewInvoice");
        Route::post('invoice/payment', 'TrainerController@payInvoice');    
        Route::get('{id}/prizing/listing','AssetController@prizingListing')->name("AssetController-showPrizingListing");
        Route::get('/{id}/participants', 'TrainerController@showParticipants')->name('shows-all-participants');
        Route::get('/{id}/registration/view', 'TrainerController@registrationView')->name('shows-registration-view');
        Route::get('{id}/{participant_id}/view/invoice','TrainerController@viewParticipantInvoice')->name("TrainerController-ViewInvoice-participant");
        Route::post('/invoice/payinoffice', 'TrainerController@payInOffice');    

        //PDF
        Route::get('{id}/{participant_id}/pdf/pay/invoice','ExportController@viewInvoice')->name("TrainerController-pdf-ViewInvoice");
        Route::get('{id}/pdf/trainer/view-invoice','ExportController@viewtrainerInvoice')->name("TrainerController-pdf-ViewInvoice");

        Route::any('/{template_id}/{app_id}/{show_id}/trainer/viewOrderHistory', 'TrainerController@viewOrderHistory')->name("TrainerController-trainer-viewOrderHistory");

        Route::get('{id}/{participant_id}/pdf/view/invoice','ExportController@viewParticipantInvoice')->name("TrainerController-ViewInvoice-participant");
        
        //Scratch
        //Route::get('{id}/horse/scratch','TrainerController@scratchHorse')->name("TrainerController-scratch-horse");
        Route::get('{id}/horse/scratch/{add?}','TrainerController@scratchHorse')->name("TrainerController-scratch-horse");
        Route::get('{id}/add/scratch','TrainerController@addScratch')->name("TrainerController-add-scratch");
        Route::post('/save/scratch','TrainerController@saveScratch')->name("TrainerController-save-scratch");
        Route::get('{id}/delete/scratch','TrainerController@destroyScratch');
        //Trainer Scratch
        Route::get('{id}/horse/scratch/trainer/{trainer_id}/{add?}','TrainerController@scratchHorseTrainer');


    });

    //position
    Route::group(['prefix' => 'position'], function(){
        //Shows dashboard
        Route::any('/{id}/index', 'PositionController@index')->name("PositionController-index");       
        Route::post('/store', 'PositionController@store');       
        
        //Route::any('/step-2', 'ShowController@store')->name("ShowController-create");    
    
    });
    // employee management
    //position
    Route::group(['prefix' => 'employee'], function(){
        //Shows dashboard
        Route::any('/{id}/{app_id}/index', 'EmployeeController@index')->name("EmployeeController-index");
        Route::any('/addEmployee', 'EmployeeController@addEmployee')->name("EmployeeController-addEmployee");
        Route::any('/view/{id}', 'EmployeeController@view')->name("EmployeeController-view");
        Route::any('/delete/{id}', 'EmployeeController@delete')->name("EmployeeController-delete");
        Route::any('/appManager/isEmployee/{tabSelected}', 'EmployeeController@isEmployee')->name("EmployeeController-isEmployee");


        Route::any('/updateStatus/{id}/{status}', 'EmployeeController@updateStatus')->name("EmployeeController-updateStatus");



    });

    Route::get('/overall/horse/rankings', 'RankingController@cumulativeOverall');
    
    Route::any('/template/edit/{id}', 'admin\AdminController@edit');
    
    Route::any('/addBank', 'PaypalController@addBank');

    Route::get('demos/livesearch','SchedularController@liveSearch');

    Route::any('ajaxLiveSearch','SchedularController@search');

    Route::any('liveSearchPage','SchedularController@liveSearchPage');

    Route::get('eloquent/relations/belongs-to-many', 'Eloquent\Relations\BelongsToManyController@index');
    Route::get('eloquent/relations/belongs-to-many-data', 'Eloquent\Relations\BelongsToManyController@data');


    Route::group(['prefix' => 'Billing'], function(){
        //Participants Response
        Route::post('checkout', 'HorseBillingController@checkout');

        Route::any('stripAjax/{amount}/{id}', 'HorseBillingController@stripAjax');
        Route::get('paypalCharges/{amount}', 'HorseBillingController@payPalCharges');

        Route::any('getPrize/claimForm', 'HorseBillingController@getPrizeClaimForm');

        Route::any('exportClaimForm/{horse_id}/{show_id}/{type}', 'ExportController@exportClaimForm');

        Route::any('prizeClaimSubmit', 'HorseBillingController@prizeClaimSubmit');

    });


    Route::group(['prefix' => 'settings'], function(){
        //Participants Response
        Route::get('/main', 'SettingController@index')->name('Settings-page');
        Route::get('/{template_id}/{type}/profile/view', 'SettingController@view');
        //For Participants to view other users profile. Readonly
        Route::post('/profile/submit/response/', 'SettingController@saveResponse');
        Route::post('/s3/delete/File', 'SettingController@DeleteFileS3');
        Route::get('/user', 'SettingController@userProfile')->name('Settings-user-profile');
        Route::post('/updateUser', 'SettingController@updateUser')->name('Settings-update-user-profile');

        Route::post('imageUpload', ['as'=>'imageUpload','uses'=>'SettingController@imageUpload']);
        Route::any('removeProfileImage', ['as'=>'removeProfileImage','uses'=>'SettingController@removeProfileImage']);

    });


});


// For Publiuc Users
Route::group(['prefix' => 'settings'], function(){
    Route::get('/{template_id}/{type}/{user_id}/view', 'SettingController@viewProfile');
});
Route::group(['prefix' => 'shows'], function(){
  Route::get('/{id}/showSponsorsDetails', 'ShowController@showSponsorsDetails')->name('shows-all-sposnors-details');
  Route::any('/{id}/trainers', 'ShowController@trainers')->name("ShowController-trainers");
    Route::any('/view-trainers/{id}', 'ShowController@viewTrainerOnly')->name("ShowController-view-trainer-only");

});

Route::group(['prefix' => 'master-template'], function(){

Route::get('/{accetid}/asset/readonly', 'ParticipantController@viewAsset')->name('participant-mastertemp-view-accet');

});

Route::group(['prefix' => 'ajax-request'], function() {
    Route::get('/loadTemplateApps/{template_id}', 'UserController@loadTemplateApps')->name("UserController-load-apps");

    Route::get('/loadActivityView/{participant_id}/{pageNo}/{asset_id?}', 'UserController@loadActivityView')->name("dashboard-activity-view");
    Route::get('/loadEmployeeView/{template_id?}', 'UserController@loadEmployeeView')->name("UserController-employee-view");
    Route::get('/loadSubParticipantView/{participant_id}', 'UserController@loadSubParticipantView')->name("UserController-subParticipant-view");
    Route::any('/loadActivityDataAjax', 'UserController@loadActivityDataAjax')->name("UserController-load-activity-ajax-view");
    Route::any('/getAppsData', 'UserController@getAppsData')->name("UserController-getAppsData-ajax-view");
    Route::any('/getActivityData', 'UserController@getActivityData')->name("UserController-getActivityData-ajax-view");
    Route::any('/getSubParticipantData', 'UserController@getSubParticipantData')->name("UserController-getSubParticipantData-ajax-view");
    Route::any('/getEmployeeData', 'UserController@getEmployeeData')->name("UserController-getEmployeeData-ajax-view");
    Route::any('/getShowData/{str?}', 'ShowController@getShowPaginateData')->name("ShowController-getEmployeeData-ajax-view");
});

Route::group(['prefix' => 'contactUs'], function(){
        Route::post('/save', 'ContactUsController@save')->name('public-contact-us');
    });
