<?php

//define('SECRET_KEY', "nxbEncrypted2017"); // you can change it
define('SECRET_KEY', "nxvt2017"); // you can change it
//General Usage: Warning. Donot change GENERAL values. It is used in many places

define('GENERAL_ACTIVE', 1);
define('GENERAL_DEACTIVE', 0);

//Alignment 
define('DEFAULT_ALIGNMENT_LEFT', 1);
define('DEFAULT_ALIGNMENT_CENTER', 2);
define('DEFAULT_ALIGNMENT_RIGHT', 3);
//Background Image
define('DEFAULT_BACKGROUND_STRECH', 1);
define('DEFAULT_BACKGROUND_REPETE', 3);
//Category of templates
define('CONST_SHOW', 1);
define('CONST_HORSE_TEMPLATE', 3);
define('CONST_TRAINERS', 4);

//ASSET type
define('ASSET_PARENT_TYPE', 1);

//Form type
define('DATAINPUT', 1);
define('F_ASSETS', 2);
define('F_PARENT_ASSETS', 7);
define('PROFILE_ASSETS', 5);
define('F_REGISTRATION', 8);
define('F_SHOW_INVOICE', 9);
define('F_SHOW_TRAINER_REG', 10);
define('F_COURSE_CONTENT',11);
define('FEEDBACK',3);
define('SPECTATOR_REGISTRATION',12);
define('F_PENALTY',6);
define('RIDER_ASSETS',13);
define('JUDGES_FEEDBACK',14);

define('SPONSOR_REGISTRATION',15);
define('F_PROJECT_OVERVIEW', 16);



//Form Builder

define('DIVIDER_PANEL', 100);
define('OPTION_DROPDOWN', 1);
define('OPTION_RADIOBUTTON', 2);
define('OPTION_CHECKBOX', 3);
define('OPTION_AUTO_POPULATE', 4);
define('OPTION_TEXT', 5);
//define('OPTION_AUTOPLAY', 6);
define('OPTION_IMAGE', 7);
define('OPTION_VIDEO', 8);
define('OPTION_DATE_PICKER', 9);
define('OPTION_TIME_PICKER', 10);
define('OPTION_LABEL', 11);
define('OPTION_HYPERLINK', 12);
define('OPTION_ATTACHMENT', 13);
define('OPTION_EMAIL', 14);
define('OPTION_MONETERY', 15);

define('OPTION_RATINGS', 16);
define('OPTION_TEXTAREA', 17);
define('OPTION_NUMARIC', 18);
define('OPTION_ADDRESS_MAP', 19);
define('OPTION_SIGNATURE', 20);

define('OPTION_BREEDS_AUTO_POPULATE', 21);
define('OPTION_BREEDS_STATUS_AUTO_POPULATE', 22);
define('OPTION_HORSE_AGE_AUTO_POPULATE', 23);
define('OPTION_RIDER_AGE_AUTO_POPULATE', 24);
define('OPTION_CALCULATE_TOTAL', 25);
define('OPTION_STATE_TAX', 26);
define('OPTION_FEDERAL_TAX', 27);


//Permissions to Modules
define('READ_ONLY',1);
define('READ_AND_WRITE',2);
define('NO_ACCESS',0);

//Upload Path
define('PATH_TO_UPLOAD', 'uploads');
define('PATH_UPLOAD_FORMS', 'uploads/master_template/');

define('PATH_TO_MASTERTEMPLATE_LOGO', 'uploads/master_template_design/logo');
define('PATH_TO_MASTERTEMPLATE_BACKGROUND', 'uploads/master_template_design/background');

//Texts
define('NO_FORM_MESSAGES', 'No Form assigned to this template.');
define('MASTER_TEMPLATE_NO_MODULES_TEXT', 'This master template donot have any Modules yet!');
define('MASTER_TEMPLATE_NO_ASSET_INVITE_TEXT', 'You have not been invited to on any asset yet!');
define('NO_ASSET_FORM_FIELDS_ADDED', 'No Fields added to this asset form yet!');
define('NO_ASSET_FORM_DATA_ADDED', 'You have not added any asset for this template yet!');
define('NO_PARTICIPANT_RESPONSE', 'No Response has been submited to this asset yet!');
define('NO_CLASSES_RESPONSE', 'No classes has been added by this show owner!');

//Assets
define('Assets_Name', 'Name');

//Show
define("HORSE_SCRATCHED", 1);
define("HORSE_NOT_SCRATCHED", 0);

//User Types:
define('ADMIN_USER', 1);
define('NORMAL_USER', 2);

//Show Scratch Penality Types:
define('SCROPT_SCRATCH_PENALITY', 1);
define('SCROPT_CLASS_JOINING_PENALITY', 2);


//Design Master template by user
define('ADMIN_ID',1);
define('ADMIN_EMAIL','meganschnebly@gmail.com');
define('TEMPLATE_DESIGN_CUSTOMIZABLE',1);

//scheduler
define("SCHEDULAR_CHECKED",1);
define("SCHEDULAR_NOT_CHECKED",0);
define("DEFAULT_VALUE",0);

//template Type

define("SHOW",1);
define("FACILTY",2);
define("HORSE",3);
define('TRAINER', 4);

//Timeline
define('TIMELINE_BLOG_TEMPLATEWISE', 0);
define('TIMELINE_BLOG_APPWISE', 1);


//Profile Users:
define('PROFILE_APP_OWNER', 1);
define('PROFILE_APP_NORMAL_USER', 2);

//Cumulative ranking
define('ASSET_TYPE_CHILD', 0);
define('TEMPLATE_CUMULATIVE_TRUE', 1);

//Show Types (trainer)
define('SHOW_TYPE_SHOWS', 1);
define('SHOW_TYPE_TRAINER', 2);

define('PAID',1);
define('UNPAID',0);

//Show Sponsor
	
define('SHOW_SPONSOR_SHOWONINVOICE',1);

// Show Stall Types
define('SHOW_STALL_UTILITY',1);

//Register constants
define('REGISTER_VIA_EMAIL',1);


// Division

define('DIVISION_MUST_REQ',1);

//Time zone
define('CT','America/Chicago');
define('PT','America/Los_Angeles');
define('MT','America/Denver');
define('ET','America/New_York');

define('FACILITY_SLOT_DURATION','30:00');

$heights =[
    "2'3"=>"2'3",
    "2'5"=>"2'5",
    "2'7"=>"2'7",
    "2'9"=>"2'9",
    "2'11"=>"2'11",
    "3'1"=>"3'1",
    "3'3"=>"3'3",
    "3'5"=>"3'5",
    "3'7"=>"3'7",
    "3'9"=>"3'9",
    "3'11"=>"3'11",
    "4'1"=>"4'1",
    "4'3"=>"4'3",
    "4'5"=>"4'5",
    "4'7"=>"4'7",
    "4'9"=>"4'9",
    "4'11"=>"4'11",
    "5'1"=>"5'1",
    "5'3"=>"5'3"
    ];

define('HEIGHTS',$heights);

$faults =[
    "E"=>"Eliminated",
    "S"=>"Scratched",
    "NS"=>"No Show",
    "HC"=>"Hors de Concours",
    "EX"=>"Excused"
    ];

define('SHOWS_FAULTS',$faults);