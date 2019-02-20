<?php
/**
 * Handle All Constant Variable in constants.php and all function to be used through out the application
 *
 * @author Faran Ahmed Khan
 * @date 18-Jan-2017
 */    
use App\AssetModules;
use App\AssetParent;
use App\InviteInvoices;
use App\inviteParticipantinvoice;
use App\Invoice;
use App\ManageShows;
use App\ManageShowsRegister;
use App\PrizeClaimForm;
use App\SchedulerRestriction;
use App\ScoreFromClass;
use App\ShowAssetInvoice;
use App\ShowClassSplit;
use App\ShowDivision;
use App\ShowPrizing;
use App\ShowPrizingListing;
use App\ShowSponsors;
use App\ShowStables;
use App\ShowStallRequest;
use App\Spectators;
use App\SponsorCategories;
use App\StallTypes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Cookie\CookieJar;
use Carbon\Carbon;
use App\Template;
use App\Asset;
use App\User;
use App\Form;
use App\Participant;
use App\ParticipantAsset;
use App\ParticipantResponse;
use App\Module;
use App\Schedual;
use App\InvitedUser;
use App\inviteTemplatename;
use App\SchedulerReminder;
use App\Mail\ReminderEmail;
use App\Mail\SchedulerTimeUpdate;
use App\ManageShowTrainer;
use App\SchedualNotes;
use App\TemplateButtonLabel;
use App\Mail\TimeUpdateEmail;
use App\Mail\TimeUpdateEmailTrainer;
use App\subParticipants;
use App\AdditionalCharges;
use App\ClassHorse;
use App\ClassTypePoint;
use App\ManageShowTrainerSplit;
use App\SchedulerFeedBacks;
use App\HorseInvoiceComment;
use App\Division;
use App\SponsorCategoryBilling;
use App\CombinedClass;
use App\HorseRiderStall;
use App\HorseOwner;
use App\ShowStallUtility;
use App\ManageShowOrderSupplies;
use App\TemplateDesign;
use App\ShowPayInOffice;
use App\HorseInvoices;
use App\ShowScratchPenalty;

//--- Add Constants into app
require __DIR__.'/Constants.php';

function pre($value=[]){
	if($value!=NULL){
		echo '<pre>';
			print_r($value);
		echo '</pre>';
	}
}

/**--- User-ID or Record-ID Encoded Technique  ---*/
function safe_b64encode($string) {

        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

function safe_b64decode($string) {
	$data = str_replace(array('-', '_'), array('+', '/'), $string);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
	    $data .= substr('====', $mod4);
	}
	return base64_decode($data);
}

function nxb_encode($text) {

        if (!$text) {
            return false;
        }

        //$encrypted = openssl_encrypt($text, 'aes-256-cbc', SECRET_KEY, 0, SECRET_KEY);
        $encrypted = openssl_encrypt($text, 'rc2-cbc', SECRET_KEY, 0, SECRET_KEY);
        return trim(safe_b64encode($encrypted));

}

function nxb_decode($value, $decodeJson = FALSE) {

        if (!$value) {
            return false;
        }
        $crypttext = safe_b64decode($value);
        //$decrypttext = openssl_decrypt($crypttext, 'aes-256-cbc', SECRET_KEY, 0, SECRET_KEY);
        $decrypttext = openssl_decrypt($crypttext, 'rc2-cbc', SECRET_KEY, 0, SECRET_KEY);

        //--- if json decode is TRUE
        if ($decodeJson == TRUE) {
            $decrypttext = trim($decrypttext);

            if (validate_json($decrypttext)) {
                $decrypttext = Json::decode($decrypttext);
                return $decrypttext;
            }
        }
        return trim($decrypttext);

}
/**--- END of User-ID. Recrod-ID ---*/


/** Delete file from the server **/
/**
* Upload the files/image/videos
* Also
* 	params $image_paths i.e., folder_id the folder to be uploaded,
*	Uploaded file, Resize width and height.
*/
function UploadAllFiles($folder_id=1,$Uploaded_file, $resize_width=null, $resize_height=900)
{
	// $image_paths = "uploads/master_template/master_temp_$folder_id";
	// if (!\File::exists($image_paths))
	// {
	//     $directorys = \File::makeDirectory("uploads/master_template/master_temp_$folder_id/", 0777, true);
	// }
	$image_paths = PATH_UPLOAD_FORMS;
	$file = $Uploaded_file;
    $destinationPath = public_path($image_paths);
    $extension = $file->getClientOriginalExtension();
    $nameoffile = $file->getClientOriginalName();
    $rand = rand(10000000000, 99999999999).'.'.$extension;
    $pathofimage = $image_paths.$rand;
    $image_array = array('jpg','JPG', 'jpeg', 'JPEG', 'png' ,'PNG','gif','GIF');
    $textfile_array = array("pdf","txt","ini","md","doc","docx","xls","xlsx","ppt","pptx","zip","rar","tar","gzip","gz","7z");
    //check if file or not
    if(in_array($extension, $textfile_array)){
			$filename = $file->getClientOriginalName();
			$rand = $filename;
			$pathofimage = $image_paths.$rand;
    }
    //check if image or not
    if(in_array($extension, $image_array)){
    	Image::make($Uploaded_file)->resize($resize_width, $resize_height, function ($constraint) {
		            $constraint->aspectRatio();
		           	$constraint->upsize();
		        })->save($pathofimage);
    }else{
		$file->move($destinationPath, $rand);
    }

    return $pathofimage;
}
/*
* Upload the files/image/videos For our form
* Also
* 	params $image_paths i.e., folder_id the folder to be uploaded,
*	Uploaded file, Resize width and height.
*/
function UploadFormFiles($folder_id=1,$Uploaded_file,$form_id=0,$resize_width=null, $resize_height=900)
{

		// $image_paths = PATH_UPLOAD_FORMS."master_temp_$folder_id/form_$form_id";
		// if (!\File::exists($image_paths))
		// {
		// 	$directorys = \File::makeDirectory(PATH_UPLOAD_FORMS."master_temp_$folder_id/form_$form_id", 0777, true);
		// }
	$image_paths = PATH_UPLOAD_FORMS;
	$file = $Uploaded_file;
    $destinationPath = public_path($image_paths);
    $extension = $file->getClientOriginalExtension();
    $nameoffile = $file->getClientOriginalName();
    $rand = rand(10000000000, 99999999999).date('hia').'.'.$extension;
    $pathofimage = $image_paths.'/'.$rand;
    $image_array = array('jpg','JPG', 'jpeg', 'JPEG', 'png' ,'PNG','gif','GIF');
    $textfile_array = array("pdf","txt","ini","md","doc","docx","xls","xlsx","ppt","pptx","zip","rar","tar","gzip","gz","7z");
    //check if file or not
    if(in_array($extension, $textfile_array)){
			$filename = $file->getClientOriginalName();
			$rand = $filename;
			$pathofimage = $image_paths.'/'.$rand;
    }
    //check if image or not
    if(in_array($extension, $image_array)){
    	Image::make($Uploaded_file)->resize($resize_width, $resize_height, function ($constraint) {
		            $constraint->aspectRatio();
		           	$constraint->upsize();
		        })->save($pathofimage);
    }else{
		$file->move($destinationPath, $rand);
    }


    return $destinationPath.$rand;
}


/**
* Delete folder the file/image
* params $image_paths i.e., attribute name
*/
function DeleteFormFolderImage($folder_id,$form_id)
{
	$directory = PATH_UPLOAD_FORMS."master_temp_$folder_id/form_$form_id";
	$success =\File::deleteDirectory($directory);
    return $success;
}

/**
* Delete folder the file/image
* params $image_paths i.e., attribute name
*/
function DeleteFolderImage($folder_id)
{
	// $directory = "uploads/master_template/master_temp_$folder_id";
	// $success =\File::deleteDirectory($directory);
 //    return $success;
	$disk = getStorageDisk();
	if($disk->exists("admin/template_$folder_id")) {
        $disk->deleteDirectory("admin/template_$folder_id");
    }
}


// Get date formate
function getDates($date){
	return date("m-d-Y", strtotime($date));
}
// Get Email status formate
function EmailStatus($status){
	if ($status == 1) {
		$Thestatus = "Accepted";
	}elseif($status == 2) {
		$Thestatus = "Decline";
	}else{
		$Thestatus = "Pending";
	}
	return $Thestatus;
}
// Get User status formate
function UserType($type){
	if ($type == 1) {
		$Thestatus = "Admin";
	}
	else{
		$Thestatus = "Users";
	}
	return $Thestatus;
}
//Get templates from user email
function getMasterTemplatesFromUserEmail($email){
	$colect = InvitedUser::with("hastemplate")->where("email",$email)->groupBy("template_id")->get()->toArray();
    $templateNames = "No template Associated";
    if ($colect) {
    	$len = count($colect);
    	$templateNames = "";
    	$i = 0;
    	foreach ($colect as $temp_Name) {
    		$templateNames .= $temp_Name["hastemplate"]["name"];
    		if ($i != $len - 1) {
    			$templateNames .=" , ";
    		}
    		$i++;
    	}
    }

    return $templateNames;
}
// Get Template Name
function GetTemplateName($template_id,$invitee_id=null){

    if ($invitee_id == null) {
    	$colect = Template::where("id",$template_id)->first()->toArray();
    	return $colect['name'];
    }else{
    	//$invited_id= nxb_decode($invited_id);
        $colect = inviteTemplatename::where('template_id',$template_id)->where('user_id',$invitee_id)->first();
        if ($colect['name']) {
        	return $colect['name'];
        }else{
			$colect = Template::where("id",$template_id)->first()->toArray();
	    	return $colect['name'];
        }
    }
   //  else{

   //  	$user = User::select('id')->where('email',$invitee_id)->first();
   //  	$colect = inviteTemplatename::where('template_id',$template_id)->where('user_id',$user->id)->first();
   //      if ($colect['name']) {
   //      	return $colect['name'];
   //      }else{
			// $colect = Template::where("id",$template_id)->first()->toArray();
	  //   	return $colect['name'];
   //      }
   //  }

}

// Get Template Name
function GetTemplateNameTimeline($template_id,$invitee_id=null){

    if ($invitee_id == null) {
    	$colect = Template::where("id",$template_id)->first()->toArray();
    	return $colect['name'];
    }else{
    	//$invited_id= nxb_decode($invited_id);
        $colect = inviteTemplatename::where('template_id',$template_id)->where('user_id',$invitee_id)->first();
        if ($colect['name']) {
        	return $colect['name'];
        }else{
			$colect = Template::where("id",$template_id)->first()->toArray();
	    	//return $colect['name'] ." --(".getUserNamefromid($invitee_id).")";
	    	return $colect['name'];
        }
    }
 }
// Get Template Name
function GetTemplateNameFromAppId($app_id){


    	//$invited_id= nxb_decode($invited_id);
        $colect = inviteTemplatename::where('invited_user_id',$app_id)->first();
        if ($colect['name']) {
        	return $colect['name'];
        }else{
			$colect = Template::where("id",$colect['template_id'])->first();
	    	//return $colect['name'] ." --(".getUserNamefromid($invitee_id).")";
	    	if (isset($colect->name)) {
	    		return $colect->name;
	    	}
	    	
        }
 }

// Get Template Name
function getPointsForTemplate($template_id){
    	$colect = Template::where("id",$template_id)->first()->toArray();
    	return $colect['value'];
}
/**
 * Copy a file, or recursively copy a folder and its contents
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       int      $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
    	\File::makeDirectory($dest, $permissions,true);
       //mkdir($dest, $permissions);
    }

    // Loop through the folder
    if (is_dir($source)) {
	    $dir = dir($source);
	    while (false !== $entry = $dir->read()) {
	        // Skip pointers
	        if ($entry == '.' || $entry == '..') {
	            continue;
	        }

	        // Deep copy directories
	        xcopy("$source/$entry", "$dest/$entry", $permissions);
	    }
	    // Clean up
    	$dir->close();
	}


    return true;
}

//Preview: Template design variable passed to an array
function getTemplateDesign($TemplateDesign){
	$DesignArray = array();
	if (isset($TemplateDesign)) {
		$DesignArray["TD_logo_image"] = $TemplateDesign->logo_image;
		//Setting CSS classes for required settings.
			//Logo Allignment
			if ($TemplateDesign->logo_allignment == DEFAULT_ALIGNMENT_CENTER) {
				$logo_position = "TD-center";
			}elseif($TemplateDesign->logo_allignment == DEFAULT_ALIGNMENT_RIGHT){
				$logo_position = "TD-right";
			}else{
				$logo_position = "TD-left";
			}
			//Backgorund Image or Color

			if(!empty($TemplateDesign->background_color)){
				$background_img_color = "background-color:".$TemplateDesign->background_color;
			}else{
				$background_img_color = "background-color:#ffffff";
			}
			if (!empty($TemplateDesign->background_image)) {
				//background image repeat
				if($TemplateDesign->background_image_repeat == DEFAULT_BACKGROUND_STRECH){
					$background_img_color = "background: url(".getImageS3($TemplateDesign->background_image).") no-repeat center center fixed;";
				}elseif ($TemplateDesign->background_image_repeat == DEFAULT_BACKGROUND_REPETE) {
					$background_img_color = "background: url(".getImageS3($TemplateDesign->background_image).") left top repeat;";
				}else{
					$background_img_color = "background-image: url(".getImageS3($TemplateDesign->background_image).");";
				}
			}

			//<----------------------Title---------->
			//color and size
			if($TemplateDesign->title_font_size){
				$tlt_font_size = "font-size:".$TemplateDesign->title_font_size."px;";
			}else{
				$tlt_font_size = "font-size:12px";
			}
			if($TemplateDesign->title_font_color){
				$tlt_font_color = "color:".$TemplateDesign->title_font_color.";";
			}else{
				$tlt_font_color = "color:#000000";
			}
			//Font alignment
			if ($TemplateDesign->title_font_allignment == DEFAULT_ALIGNMENT_CENTER) {
				$title_position = "TD-center";
			}elseif($TemplateDesign->title_font_allignment == DEFAULT_ALIGNMENT_RIGHT){
				$title_position = "TD-right";
			}else{
				$title_position = "TD-left";
			}

			//<------------------ Text color and size ------------->
			//color and size
			if($TemplateDesign->field_font_size){
				$fld_font_size = "font-size:".$TemplateDesign->field_font_size."px;";
			}else{
				$fld_font_size = "font-size:12px";
			}
			if($TemplateDesign->field_font_color){
				$fld_font_color = "color:".$TemplateDesign->field_font_color.";";
			}else{
				$fld_font_color = "color:#000000";
			}
			//<------------------ Option color and size ------------->
			//color and size
			if($TemplateDesign->options_font_size){
				$opt_font_size = "font-size:".$TemplateDesign->options_font_size."px;";
			}else{
				$opt_font_size = "font-size:12px";
			}
			if($TemplateDesign->options_font_color){
				$opt_font_color = "color:".$TemplateDesign->options_font_color.";";
			}else{
				$opt_font_color = "color:#000000";
			}

		//Assigning values to array.
		$DesignArray["TD_logo_resolution_width"] = $TemplateDesign->logo_resolution_width;
		$DesignArray["TD_logo_resolution_hight"] = $TemplateDesign->logo_resolution_hight;
		$DesignArray["TD_logo_position"] = $TemplateDesign->logo_position;
		$DesignArray["TD_logo_allignment"] = $logo_position;
		$DesignArray["background_img_color"] = $background_img_color;
		$DesignArray["TD_background_image_repeat"] = $TemplateDesign->background_image_repeat;
		$DesignArray["TD_title_font_size"] = $tlt_font_size;
		$DesignArray["TD_title_font_color"] = $tlt_font_color;
		$DesignArray["TD_title_font_allignment"] = $title_position;
		$DesignArray["TD_field_font_size"] = $fld_font_size;
		$DesignArray["TD_field_font_color"] = $fld_font_color;
		$DesignArray["TD_options_font_size"] = $opt_font_size;
		$DesignArray["TD_options_font_color"] = $opt_font_color;
	}else{
		$DesignArray["TD_logo_image"] = null;
		$DesignArray["TD_logo_resolution_width"] = null;
		$DesignArray["TD_logo_resolution_hight"] = null;
		$DesignArray["TD_logo_position"] = null;
		$DesignArray["TD_logo_allignment"] = "null";
		$DesignArray["background_img_color"] = "";
		$DesignArray["TD_background_image"] = null;
		$DesignArray["TD_background_image_repeat"] = null;
		$DesignArray["TD_background_color"] = "#ffffff";
		$DesignArray["TD_title_font_size"] = "font-size:18px;";
		$DesignArray["TD_title_font_color"] = "color:#000000;";
		$DesignArray["TD_title_font_allignment"] = 1;
		$DesignArray["TD_field_font_size"] = "font-size:14px;";
		$DesignArray["TD_field_font_color"] = "color:#000000;";
		$DesignArray["TD_options_font_size"] ="font-size:14px;";
		$DesignArray["TD_options_font_color"] = "color:#000000;";
	}
	return $DesignArray;
}
//--- Get Template design qry
function getTemplateDesignQry($template_id,$owner_id = 0){
	if($owner_id==0){
		return TemplateDesign::where('template_id', $template_id)->first();
	}else{
		$temp = TemplateDesign::where('template_id', $template_id)->where('user_id',$owner_id)->first();
		if (!is_null($temp)>0) {
			return $temp;
		}else{
			return TemplateDesign::where('template_id', $template_id)->first();
		}
	}
}

//--- Create Dropdown for font size
function CreateFontsizeDrp($title_font_size="defaults",$selected_v="",$starts=8,$ends=60){
	$select = " <select class='option-selector-drp' name='".$title_font_size."'>";
	$select .= "<option value=''>Please select</option>";
	for($i=$starts; $i<= $ends;$i++){
		if ($selected_v != "" ) {
			if ($selected_v == $i) {
				$selected_vals = "selected='selected'";
			}else{
				$selected_vals = "";
			}
		}else{
			$selected_vals = "";
		}
		$select .= "<option value='".$i."'".$selected_vals." >".$i." px</option>";
	}
	$select .="</select>";
	return $select;
}

// ---------------- Option on preview form ------------->
//Field and option as Arguments.
function AddOptionsFrontend($fieldoptions,$field,$required,$answer=null){

	$html = "";
	$index = 1;
	if($field['form_field_type'] == OPTION_DROPDOWN){
		 $html .= '<div class="col-sm-8">
		 	<div class="form-group">
			<select class="form-control" name="fields['.$field["unique_id"].'][answer]" '.$required.'><option value="">Select Option</option>';
	}
	if($field['form_field_type'] == OPTION_AUTO_POPULATE || $field['form_field_type'] == OPTION_BREEDS_AUTO_POPULATE || $field['form_field_type'] == OPTION_BREEDS_STATUS_AUTO_POPULATE || $field['form_field_type'] == OPTION_HORSE_AGE_AUTO_POPULATE || $field['form_field_type'] == OPTION_RIDER_AGE_AUTO_POPULATE){
		 $html .= '<div class="col-sm-12">
		 	<select class="form-control autopopulate-basic-multiple" multiple="multiple" name="fields['.$field["unique_id"].'][answer][]" '.$required.'>';
	}

	foreach ($fieldoptions as $okey => $option){
		//get default value
			if(!empty($option["opt_default"])){
				$selec = "checked='checked'";
			}else{
				$selec = "";
			}
		//end: get default value
		//get Answer value
			if($answer!=null && isset($option["opt_name"])){
           		$indexer = recursive_array_search($option["opt_name"], $answer);
				if($indexer){
					$selec = "checked='checked'";
				}else{
					$selec = "";
				}
			}

		//end: get Answer value
		if($field['form_field_type'] == OPTION_DROPDOWN){
			if(!empty($option["opt_default"])){
				$selec = "selected='selected'";
			}else{
				$selec = "";
			}
			//get Answer value
			if($answer!=null && isset($option["opt_name"])){
           		//dd($answer);
           		$indexer = recursive_array_search($option["opt_name"].'|||'.$option["opt_weightage"], $answer);
				if($indexer){
					$selec = "selected='selected'";
				}else{
					$selec = "";
				}
			}
			//end: get Answer value

			$html .= '<option value="'.$option["opt_name"].'|||'.$option["opt_weightage"].'" '.$selec.'>'.$option["opt_name"].'</option>';
		}
		if($field['form_field_type'] ==OPTION_AUTO_POPULATE || $field['form_field_type'] == OPTION_BREEDS_AUTO_POPULATE || $field['form_field_type'] == OPTION_BREEDS_STATUS_AUTO_POPULATE || $field['form_field_type'] == OPTION_HORSE_AGE_AUTO_POPULATE || $field['form_field_type'] == OPTION_RIDER_AGE_AUTO_POPULATE){
			if(!empty($option["opt_default"])){
				$selec = "selected='selected'";
			}else{
				$selec = "";
			}
			//get Answer value
			if($answer!=null && isset($option["opt_name"])){
           		$indexer = recursive_array_search($option["opt_name"].'|||'.$option["opt_weightage"], $answer);
				if($indexer){
					$selec = "selected='selected'";
				}else{
					$selec = "";
				}
			}
			//end: get Answer value

			$html .= '<option value="'.$option["opt_name"].'|||'.$option["opt_weightage"].'" '.$selec.'>'.$option["opt_name"].'</option>';
		}
		if($field['form_field_type'] == OPTION_RADIOBUTTON){
			 $html .= '<div class="checkbox">
		          <label><input name="fields['.$field["unique_id"].']['.$index.'][opt_weightage]" type="hidden" value="'.$option["opt_weightage"].'" />
		          <input data-attr="'.$field["unique_id"].'" name="fields['.$field["unique_id"].']['.$index.'][answer]" value="'.$option["opt_name"].'" type="radio" '.$selec.' /><span>'.$option["opt_name"].'</span></label>
		        </div>';
				// $html .= '<div class="checkbox">
		  //         <label><input data-attr="'.$field["unique_id"].'" name="fields['.$field["unique_id"].'][answer]" value="'.$option["opt_name"].'" type="radio" '.$selec.' '.$required.' />'.$option["opt_name"].'</label>
		  //          <input name="fields['.$field["unique_id"].']['.$index.'][opt_weightage]" type="hidden" value="'.$option["opt_weightage"].'" />
		  //       </div>';
		}
		if($field['form_field_type'] == OPTION_CHECKBOX){
			 $html .= '<div class="checkbox">
		          <label><input name="fields['.$field["unique_id"].']['.$index.'][opt_weightage]" type="hidden" value="'.$option["opt_weightage"].'" />
		          <input data-attr="'.$field["unique_id"].'" name="fields['.$field["unique_id"].']['.$index.'][answer]" value="'.$option["opt_name"].'" type="checkbox" '.$selec.' /><span>'.$option["opt_name"].'</span></label>
		        </div>';
		}
		if($field['form_field_type'] == OPTION_LABEL){
			 $html .= '<span class="form-mr-15" style="padding-top: 7px;">'.$option["opt_label"].'</span>';
		}
		if($field['form_field_type'] == OPTION_HYPERLINK){
			 $html .= '<a target="_blank" href="http://'.$option["opt_hyperlink"].'" style="padding-top: 7px;">'.$option["opt_hyperlink"].'</a>';
		}

      $index = $index+1;
    }
    	//OutSide Loop end:
    	if($field['form_field_type'] == OPTION_DROPDOWN || $field['form_field_type'] == OPTION_BREEDS_AUTO_POPULATE || $field['form_field_type'] == OPTION_BREEDS_STATUS_AUTO_POPULATE || $field['form_field_type'] == OPTION_HORSE_AGE_AUTO_POPULATE || $field['form_field_type'] == OPTION_RIDER_AGE_AUTO_POPULATE || $field['form_field_type'] == OPTION_AUTO_POPULATE ){
		 $html .= '</select>';
            if($field['form_field_type'] != OPTION_DROPDOWN) {
                $html .= '<label style="float: right; margin-top: 6px;"> 
			<input type="checkbox" class="selectAllPop" onchange="selectAllPop($(this))" ><span>Select All</span></label>';
            }else{
            	$html .= '</div>';
            }
            $html .= '</div>';
		}
    return $html;
}
// ---------------- Upload Options on preview form ------------->
//Field and option as Arguments.
function AddPDFOptionsFrontend($fieldoptions,$field,$answer=null){

    $html = "";
    $index = 1;
    if($field['form_field_type'] == OPTION_DROPDOWN){
        $html .= '<div class="col-sm-8">';
    }
    if($field['form_field_type'] == OPTION_AUTO_POPULATE){
        $html .= '<div class="col-sm-12">';
    }

    foreach ($fieldoptions as $okey => $option){
        //get default value
        if(!empty($option["opt_default"])){
            $selec = "checked='checked'";
        }else{
            $selec = "";
        }
        //end: get default value
        //get Answer value
        if($answer!=null && isset($option["opt_name"])){
            $indexer = recursive_array_search($option["opt_name"], $answer);
            if($indexer){
                $selec = "checked='checked'";
            }else{
                $selec = "";
            }
        }

        //end: get Answer value
        if($field['form_field_type'] == OPTION_DROPDOWN){
            if(!empty($option["opt_default"])){
                $html .= '<p>'.$option["opt_name"].'</p>';
            }
            //get Answer value
            if($answer!=null && isset($option["opt_name"])){
                //dd($answer);
                $indexer = recursive_array_search($option["opt_name"].'|||'.$option["opt_weightage"], $answer);
                if($indexer){
                    $html .= '<p>'.$option["opt_name"].'</p>';
                }
            }
            //end: get Answer value

           // $html .= '<p>'.$option["opt_name"].'</p>';
        }
        if($field['form_field_type'] ==OPTION_AUTO_POPULATE){
            if(!empty($option["opt_default"])){
                $html .= '<p>'.$option["opt_name"].'</p>';
            }
            //get Answer value
            if($answer!=null && isset($option["opt_name"])){
                $indexer = recursive_array_search($option["opt_name"].'|||'.$option["opt_weightage"], $answer);
                if($indexer){
                    $html .= '<li>'.$option["opt_name"].'</li>';
                }
            }
            //end: get Answer value

            //$html .= '<option value="'.$option["opt_name"].'|||'.$option["opt_weightage"].'" '.$selec.'>'.$option["opt_name"].'</option>';
        }
        if($field['form_field_type'] == OPTION_RADIOBUTTON){
            $html .= '<input data-attr="'.$field["unique_id"].'" name="fields['.$field["unique_id"].']['.$index.'][answer]" value="'.$option["opt_name"].'" type="radio" '.$selec.' />'.$option["opt_name"];
        }
        if($field['form_field_type'] == OPTION_CHECKBOX){
            $html .= '<input data-attr="'.$field["unique_id"].'" name="fields['.$field["unique_id"].']['.$index.'][answer]" value="'.$option["opt_name"].'" type="checkbox" '.$selec.' />'.$option["opt_name"];
        }
        if($field['form_field_type'] == OPTION_LABEL){
            $html .= '<span style="padding-top: 7px;">'.$option["opt_label"].'</span>';
        }
        if($field['form_field_type'] == OPTION_HYPERLINK){
            $html .= '<a target="_blank" href="http://'.$option["opt_hyperlink"].'" style="padding-top: 7px;">'.$option["opt_hyperlink"].'</a>';
        }

        $index = $index+1;
    }
    //OutSide Loop end:
    if($field['form_field_type'] == OPTION_DROPDOWN || $field['form_field_type'] == OPTION_AUTO_POPULATE ){
        $html .= '</div>';
    }

    return $html;
}



function AddUploadOptionsFrontend($fieldoptions,$field){
	$html = "";
	$index = 1;
	if(isset($fieldoptions[1]['upload_files']) && $fieldoptions[1]['upload_files'] != ""){
      	$uploadfile_val = $fieldoptions[1]['upload_files'];
      	$html .='<div class="col-sm-4">
                    <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                        <input type="text" value="" />      
                      </div></div>';
  	}else{
  		$uploadfile_val = "";
	}
	$html .= '<input type="hidden" name="fields['.$field["unique_id"].'][admin_upload_file]" value="'.$uploadfile_val.'">';

    return $html;
}
//Excluding fields in datatables
function exclueded_fields_datatable($field){
	if($field == OPTION_LABEL || $field == OPTION_IMAGE || $field == OPTION_VIDEO || $field == OPTION_ATTACHMENT || $field == DIVIDER_PANEL){
		return false;
	}else{
		return true;
	}
}
function getCurrentUserAsTrainer($show_id){
    $user_id = \Auth::user()->id;
	$manage = ManageShowTrainer::where('manage_show_id',$show_id)->where('user_id',$user_id)->first();
	if(!is_null($manage)>0) {
		return true;
	}else{
		return false;
	}

}
//Array finding
function recursive_array_search($needle,$haystack) {
    if(isset($haystack) && $haystack!=false) {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value OR (is_array($value) && recursive_array_search($needle, $value) !== false)) {
                return $current_key;
            }
        }
    }
    return false;
}
//getSelectedValuesMultiple
function getSelectedValuesMultiple($pos_answers,$index,$user_id){
if(isset($pos_answers))
	if(isset($pos_answers[$index]['horse_id'])){
		if($pos_answers[$index]['horse_id'] == $user_id){
			return "selected='selected'";
		}
	}
	return false;
}

function getScore($pos_answers,$index,$rId){
    if(isset($pos_answers[$index]['rounds'][$rId])){
            return $pos_answers[$index]['rounds'][$rId];
    }else{
        return 0;
    }
    return false;
}

//Get position text
function getPostionText($num){

	if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'<sup>st</sup>';
        case 2:  return $num.'<sup>nd</sup>';
        case 3:  return $num.'<sup>rd</sup>';
      }
    }
    return $num.'<sup>th</sup>';
}
//Get position text
function getPostionTextNoFormate($num){

	if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.' st';
        case 2:  return $num.' nd';
        case 3:  return $num.' rd';
      }
    }
    return $num.'<sup>th</sup>';
}
function getpriceFormate($price){
	$price =(float)$price;
	return "$ ".number_format($price,2);
}
//Get all the approved stall
function getApprovedStalls($show_id){
	$user_id = \Auth::user()->id;
	return ShowStallRequest::select('id')
		->where('show_id',$show_id)
		->where('user_id',$user_id)
		->where('status',1)
		->get()->count();
}
function parseGridRow(&$row, $key, $params =[]){

    $res = $params["parent"]->count();
    $sub = $params["sub"]->count();
    $last = $params["lengths"];
	$showExist = $params["showExist"];
    $asset_type = $params["asset_type"];
    $template_id = $params["template_id"];
    $templateCategory = GetTemplateType($template_id);

    $newRow = [];
	$id = 0;
	$key = 0;
	foreach($row as $data){
		if (exclueded_fields_datatable($data["form_field_type"])) {
			if ($data["form_field_type"] == OPTION_DROPDOWN) {
				//$newRow[$key] = isset($data["answer"])?$data["answer"]:"-";
				if(isset($data["answer"]) && is_string($data["answer"])){
					$assete = explode("|||", $data["answer"]);
					$assete = $assete[0];
				}else{
					$assete = "-";

				}
				$newRow[$key] = $assete;
			}elseif($data["form_field_type"] == OPTION_AUTO_POPULATE) {
				//$newRow[$key] = isset($data["answer"])?$data["answer"]:"-";
				if(isset($data["answer"]) && is_array($data["answer"])){
					$assete = "";
					foreach ($data["answer"] as $view) {
						$newBlob = explode("|||", $view);
						$assete .=$newBlob[0]." <br>";
					}
					
				}else{
					$assete = "-";

				}
				$newRow[$key] = $assete;
			}else{
				$newRow[$key] = isset($data["answer"])?$data["answer"]:"-";
			}
			$key = $key+1;
		}
		//$id = $data["id"];
	}

//    <a href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/primary/scheduler"."' data-toggle='tooltip' data-placement='top'
//	 data-original-title='View Shcheduler'>View Shcheduler</a>
    //	   <a class=\"app-action-link\" href=\"{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/{{nxb_encode(show_id)}}/masterSchedular\">Manage Scheduler</a>

    $assets = Asset::where('id',$params["assetid"])->first();
    if($asset_type==1)
    {
        if ($sub > 0) {
            //Change titles for show industry
        	if ($templateCategory==SHOW) {
	            $htmls="<div class='action'><span class='table-title'>Action</span><a href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/remove/assets" . "' data-toggle='tooltip' 
				data-placement='top' onclick='return confirm(".'"'."Are you sure?".'"'.");' data-original-title='Delete'>
				<i class='fa fa-trash-o' aria-hidden='true'></i></a>
				<a href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/edit/assets" . "' data-toggle='tooltip' data-placement='top'
				 data-original-title='Edit Assets'><i class='fa fa-pencil' aria-hidden='true'></i></a>
				 <a href='#' class='more' type='button' id='dropdownMenuButton' data-toggle='dropdown'><i data-toggle='tooltip' title='More Action' class='fa fa-list-ul'></i></a>
            		<div class='dropdown-menu dropdown-menu-custom' aria-labelledby='dropdownMenuButton'>
				  <a class='dropdown-item' href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/sub/assets" . "' data-toggle='tooltip' data-placement='top'
				 data-original-title='View Classes'>View Classes</a>";
				 // $htmls .= "<a class='dropdown-item' href='" . URL::to('/shows/') . '/' . nxb_encode($params["assetid"]) . "/invoice" . "' data-toggle='tooltip' data-placement='top'
				 // data-original-title='Add Show Invoice'>Show Invoice</a>";
				 $htmls .= "</div>";
				}else{
					$htmls="<div class='action'><span class='table-title'>Action</span><a href='#" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/remove/assets" . "' data-toggle='tooltip' 
					data-placement='top' onclick='return confirm(".'"'."Are you sure?".'"'.");' data-original-title='Delete'>
					<i class='fa fa-trash-o' aria-hidden='true'></i></a>
					<a href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/edit/assets" . "' data-toggle='tooltip' data-placement='top'
					 data-original-title='Edit Assets'><i class='fa fa-pencil' aria-hidden='true'></i></a>
					 <a href='#' class='more' type='button' id='dropdownMenuButton' data-toggle='dropdown'><i data-toggle='tooltip' title='More Action' class='fa fa-list-ul'></i></a>
            		<div class='dropdown-menu dropdown-menu-custom' aria-labelledby='dropdownMenuButton'> 
            		<a class='dropdown-item' href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/sub/assets" . "' data-toggle='tooltip' data-placement='top'
					 data-original-title='View Secondary'>View Secondary</a>";


				}
			if ($templateCategory!=SHOW) {

				 $htmls .= "<a class='dropdown-item' href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/asset/scheduler" . "' data-toggle='tooltip' data-placement='top'
				 data-original-title='Manage Scheduler'>Manage Scheduler</a>
				 	 <a class='dropdown-item' href='" . URL::to('/master-template/') . '/assets/primarySchedular/' . nxb_encode($assets->template_id). '/' . nxb_encode($params["assetid"]) .  "' data-toggle='tooltip' data-placement='top'
				 data-original-title='Master Scheduler'>Master Scheduler</a>
				  ";
			}
			$htmls.="</div>";
           $newRow[$last] = $htmls;
        }
            else {


                $htmls = "<div class='action'><span class='table-title'>Action</span><a href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/remove/assets" . "' data-toggle='tooltip' 
						data-placement='top' onclick='return confirm(".'"'."Are you sure?".'"'.");' data-original-title='Delete Template'>
						<i class='fa fa-trash-o' aria-hidden='true'></i></a>";
					if ($templateCategory==SHOW) {
					$htmls .= "<a href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/edit/assets" . "' data-toggle='tooltip' data-placement='top'
					 data-original-title='Edit Assets'><i class='fa fa-pencil' aria-hidden='true'></i></a>
					 <a href='#' class='more' type='button' id='dropdownMenuButton' data-toggle='dropdown'><i data-toggle='tooltip' title='More Action' class='fa fa-list-ul'></i></a>
            		<div class='dropdown-menu dropdown-menu-custom' aria-labelledby='dropdownMenuButton'>
					 <a class='dropdown-item' data-toggle='tooltip' data-placement='top'>No Class Attached</a></div>";
           			}else{
           				$htmls .= "<a href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/edit/assets" . "' data-toggle='tooltip' data-placement='top'
					 data-original-title='Edit Assets'><i class='fa fa-pencil' aria-hidden='true'></i></a>
					 <a href='#' class='more' type='button' id='dropdownMenuButton' data-toggle='dropdown'><i data-toggle='tooltip' title='More Action' class='fa fa-list-ul'></i></a>
            		<div class='dropdown-menu dropdown-menu-custom' aria-labelledby='dropdownMenuButton'>
            		<a class='dropdown-item' data-toggle='tooltip' data-placement='top'>No Secondary</a></div>";
           			
           			}
           			$htmls .="</div>";

           		$newRow[$last] = $htmls;
            }
    }
    else {

        if ($res > 0)
            $newRow[$last] = "<div class='TD-left'>" . getPrentAssets($params['assetid']) . "</div> ";
        else
            $newRow[$last] = "<div class='TD-left'><h6>No Primary</h6></div> ";
    }

    $newRow1 ="<div class='action'><span class='table-title'>Action</span>
    <a href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/remove/assets"."' data-toggle='tooltip' 
	data-placement='top' onclick='return confirm(".'"'."Are you sure?".'"'.");' data-original-title='Delete'>
	<i class='fa fa-trash-o' aria-hidden='true'></i></a>
	<a href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/edit/assets"."' data-toggle='tooltip' data-placement='top'
	 data-original-title='Edit'><i class='fa fa-pencil' aria-hidden='true'></i></a>
	<a href='#' class='more' type='button' id='dropdownMenuButton' data-toggle='dropdown'><i data-toggle='tooltip' title='More Action' class='fa fa-list-ul'></i></a>
            <div class='dropdown-menu dropdown-menu-custom' aria-labelledby='dropdownMenuButton'>";



    if($asset_type!=1) {
    	 $newRow1 .= "<a class='dropdown-item' href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/history/assets" . "' data-toggle='tooltip' data-placement='top'
	 	data-original-title='View Feedback'>View Responses</a>";
		
    	if($showExist ==1) {
			// $newRow1 .= "<a class='dropdown-item' href='" . URL::to('/shows/') . '/' . nxb_encode($params["assetid"]) . "/invoice" . "' data-toggle='tooltip' data-placement='top'
			// 	 data-original-title='Add Show Invoice'><i class='glyphicon glyphicon-usd' aria-hidden='true'></i></a>";
	    	
    	}
    	 if ($templateCategory==HORSE) {
    	 		$newRow1 .= "<a class='dropdown-item' href='" . URL::to('/shows/') . '/' . nxb_encode($params["assetid"]) . "/feedback-horse" . "' data-toggle='tooltip' data-placement='top'
				 data-original-title='View Feedback'>View Feedback</a>";
    	 }
    	 if ($templateCategory==SHOW) {
    	// 	$newRow1 .= "<a class='dropdown-item' href='" . URL::to('/shows/') . '/' . nxb_encode($params["assetid"]) . "/invoice" . "' data-toggle='tooltip' data-placement='top'
				 // data-original-title='Add Show Invoice'>Show Invoice</a>";
	    	
    	 	$newRow1 .= "<a class='dropdown-item' href='" . URL::to('/position/') . '/' . nxb_encode($params["assetid"]) . "/index" . "' data-toggle='tooltip' data-placement='top'
			 data-original-title='Placement/Prize'>Placement/Prize</a>";
			 $newRow1 .= "<a class='dropdown-item' href='" . URL::to('/shows/') . '/' . nxb_encode($params["assetid"]) . "/prizing/listing" . "' data-toggle='tooltip' data-placement='top'
			 data-original-title='Placement History'>Placement History</a>";
			 
			 if ($assets->is_split != 1 && $assets->is_combined != 1) {
			 	$newRow1 .= "<a class='dropdown-item' href='" . URL::to('/shows/') . '/' . nxb_encode($params["assetid"]) . "/split/class/new" . "' data-toggle='tooltip' data-placement='top'
			 	data-original-title='Split this Class'>Split Class</a>";
			 }
			  
    		
    	}
       $newRow1 .="<a class='dropdown-item' href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/associate/modules" . "' data-toggle='tooltip' data-placement='top'
	 data-original-title='Manage Modules'>Manage Feedback</a>";
        $newRow1 .= "<a class='dropdown-item' onclick='getQrCode(\"".$params["assetid"]."\")' href='javascript:' data-toggle='tooltip' data-placement='top'
			 data-original-title='View QR Code'>View QR Code</a>";
    }
	$newRow1 .="</div></div>";

    $newRow[$last+1] = $newRow1;
    return $row = $newRow;
}
//Get name of the asset in dropdown.
function GetAssetName($row,$classNumber=1){
    if(isset($row->fields)){
    	$pre_fields = json_decode($row->fields);
    }else{
    	$pre_fields = json_decode($row);
    }
    
    $name = "";
    $number = "";

    if (isset($pre_fields)) {
    	foreach ($pre_fields as $key => $value) {


            if ($value->form_name == "Class Number") {
                $number = $value->answer;
            }
            if ($value->form_name == "Class Title" || $value->form_name == "Class Name" || $value->form_name == "Class Number and Name" ) {
                $name =$value->answer;
            }

            if($name=='')
            {
                if (is_array($value->answer)) {
                    $name = $value->answer[0];
                }
                else{
    				$name = $value->answer;
    			}
            }
    	}
    }else{
    	if (isset($row->id)) {
    		$name = "Asset ID ".$row->id;
    	}else{
    		$name =" Not found";
    	}
    }


    if($number!='' && $classNumber==1)
        return $number.' '.$name;
    else
        return $name;
}


function GetAssetNameFromLabel($row,$label){
    $pre_fields = json_decode($row->fields);

   //dd($pre_fields);
    $name = "";

    if (isset($pre_fields)) {
        foreach ($pre_fields as $key => $value) {
            if (trim($value->form_name) == $label) {
                $name = $value->answer;
                    break;
                }
        }
    }
    return $name;
}


function GetAssetNameAndNumber($row){

    $pre_fields = json_decode($row->fields);

    $name = "";
    $number = "";

    if (isset($pre_fields)) {
        foreach ($pre_fields as $key => $value) {
            if ($value->form_name == "Class Number") {
                $number = $value->answer;
            }
            if ($value->form_name == "Class Title" || $value->form_name == "Class Name" || $value->form_name == "Class Number and Name" ) {
                $name =$value->answer;
            }
        }
    }
    return $number.' '.$name;
}




function GetAssetBreed($row,$type){
    $pre_fields = json_decode($row);

    $name = "";
    $data = [];
    if (isset($pre_fields)) {
        foreach ($pre_fields as $key => $value) {


            if ($type == 1) {
                if ($value->form_field_type == 21) {
                    if (isset($value->answer) && is_array($value->answer) && !is_null($value->answer)) {
                        foreach ($value->answer as $answer) {
                            $answerExplod = explode("|||", $answer);
                            $data[] = $answerExplod[0];
                        }
                    }
                }
            }
            elseif(($type == 2)) {
                if ($value->form_field_type == 22) {
                    if (is_array($value->answer) && !is_null($value->answer)) {
                        foreach ($value->answer as $answer) {
                            $answerExplod = explode("|||", $answer);
                            $data[] = $answerExplod[0];
                        }
                    }
                }
            }
            elseif(($type == 3)) {
                if ($value->form_field_type == 23) {

                    if (isset($value->answer) && is_array($value->answer) && !is_null($value->answer)) {
                        foreach ($value->answer as $answer) {
                            $answerExplod = explode("|||", $answer);
                            $data[] = $answerExplod[0];
                        }
                    }
                }
            }
            elseif(($type ==4)) {
                if ($value->form_field_type == 24) {
                    if (isset($value->answer) && is_array($value->answer) && !is_null($value->answer)) {
                        foreach ($value->answer as $answer) {
                            $answerExplod = explode("|||", $answer);
                            $data[] = $answerExplod[0];
                        }
                    }
                }
            }

        }

    }


    return $data;


}

//Get name of the asset in dropdown.
function GetAssetNameField($field){
    $pre_fields = json_decode($field);
    $name = "";
    if (isset($pre_fields)) {
    	foreach ($pre_fields as $key => $value) {
    		if ($value->answer != "") {
    			if (is_array($value->answer)) {
    				$name = $value->answer[0];
    				break;
    			}else{
    				$name = $value->answer;
    				break;
    			}
    		}else{
    			$name = "Asset ID ".$row->id;
    			break;
    		}
    	}
    }else{
    	$name = "Asset ID ".$row->id;
    }

    return $name;
}
//Get a specific field from form , Get id, return field
function GetSpecificFormField($id,$str_match = "ID",$Message=null){
    if (is_int($id) || is_string($id)) {
    	$row = Asset::where('id',$id)->first();
    }else{
    	$row =$id;
    }
    $pre_fields = json_decode($row->fields);
    $name = "";
    if (isset($pre_fields)) {
    	foreach ($pre_fields as $key => $value) {
            if(isset($value->answer)) {
                if ($value->answer != "") {
                        $str_match = preg_replace('/\s+/', '', strtolower($str_match));
                        $form_name = preg_replace('/\s+/', '', strtolower($value->form_name));

                    if (fnmatch($str_match, $form_name)) {
                        if (is_array($value->answer)) {
                            $name = $value->answer[0];
                            break;
                        } else {
                            $name = $value->answer;
                            break;
                        }
                    }else {
                        $name = "Not Found.";
                        continue;
                    }
                } else {
                    if ($Message) {
			    		$name = $Message;
			    	}else{
						$name = "  ";//.$row->id;
					}
                    continue;
                }
            }
    	}
    }else{
    	if ($Message) {
    		$name = $Message;
    	}else{
			$name = "  ";//.$row->id;
		}
    }

    return $name;
}



function checkAllScratch($id,$participant_id)
{
//    echo '>>>>>'.$id.'>>>>>';
//    echo $participant_id.'>>>>>';

    $classHorses    = ClassHorse::select("id", "horse_id", "horse_reg", "scratch", "horse_rider")->where("participant_id", $participant_id)->where("class_id", $id);
   $horseCount     = $classHorses->count();
    $scratchCount   = $classHorses->where('scratch',1)->count();
    if($horseCount!=0 && $scratchCount!=0 && $horseCount==$scratchCount)
    return true;
    else
    return false;
}


//Get name of the asset name.
function GetAssetNamefromId($id){
    $row = Asset::where('id',$id)->first();
    $number = '';
    $name = '';
    $str = '';
    if (isset($row->fields)) {
    	$pre_fields = json_decode($row->fields);
	    if ($pre_fields != null) {

		    foreach ($pre_fields as $key => $value) {
                if (isset($value->answer) && $value->answer != "") {

                    if ($value->form_name == "Class Title" || $value->form_name == "Class Name" || $value->form_name == "Class Number and Name") {
                        $name = $value->answer;
                    }
                    if ($value->form_name == "Class Number") {
                        $number = ' ' . $value->answer;
                    }

                    if ($name == '') {
                        if (is_array($value->answer)) {
                            $name = $value->answer[0];
                        } else {
                            $name = $value->answer;
                        }
                    }

                }
		    }
		}else{
			$name = "Asset ID ".$row->id;
		}
    }

    if($number!='')
        return $number.' '.$name;
    else
        return $name;
}
//get All the riders for the horse in a show

function getRidersForHorse($horse_id,$show_id,$status=UNPAID){
	$ch = ClassHorse::with("riders")->where("horse_id",$horse_id)->where("show_id",$show_id)->where("status",$status)->groupBy("horse_rider")->get();
	$html ="";
	if (count($ch)>0) {
		foreach ($ch as $classHorse) {
			if(isset($classHorse->riders)){
            	$html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"".url("master-template")."/". nxb_encode($classHorse->horse_rider) . "/horseProfile\" style='margin:0px !important; float:none !important;'>" . GetSpecificFormField($classHorse->riders,"Name") . "</a> ";
            	$html .= " USEF Number ".GetSpecificFormField($classHorse->riders,"USEF Number","N/A")."<br>";
			}
		}
	}
	return $html;
}

function getSponsorsCollection($show_id){
 return SponsorCategoryBilling::with('hasCategory','sponsor')
            ->whereHas('hasCategory', function ($query){
                $query->where('sponsor_on_invoice', SHOW_SPONSOR_SHOWONINVOICE);
            })->where('show_id',$show_id)
            ->orderBy('id','Desc')
            ->get();
}
//get All the riders for the horse in a show

function getTrainerForHorse($horse_id,$show_id,$status = UNPAID){
	$ch = ClassHorse::with(['MSR.trainer' => function($query){
        //return $query->groupBy('trainer_id');
    }])->where("horse_id",$horse_id)
    ->where("show_id",$show_id)
    ->where("status",$status)
    ->groupBy("msr_id")
    ->get();
	$trainerArray = array();
	$html ="";
	if (count($ch)>0) {
		foreach ($ch as $classHorse) {
			//dd($classHorse->MSR->trainer);
			if(isset($classHorse->MSR->trainer) && !in_array($classHorse->MSR->trainer_id, $trainerArray)){
				$trainerArray[] = $classHorse->MSR->trainer_id;
            	$html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"". url("shows/view-trainers")."/". nxb_encode($classHorse->MSR->trainer_id) . "/\" style='margin:0px !important; float:none !important;'>" . GetSpecificFormField($classHorse->MSR->trainer,"Name") . "</a> ";
            	$html .= " USEF# ".GetSpecificFormField($classHorse->MSR->trainer,"USEF Number","N/A")."<br>";
			}
		}
	}
	return $html;
}

//app owner invoice status
function appInvoiceStatus($show_id,$user_id)
{           

    $collection = ClassHorse::select("id")->with("horse")
            ->where("show_id",$show_id)
            ->where("user_id",$user_id)
            ->where("status",0)
            ->groupBy("horse_id")
            ->first();
    if(count($collection)>0){
    	$status = "Un-Paid";
    }else{
    	$status = "Paid";
    }
    return $status;
}
//get app owners for horses

function getRiderOwner($user_id,$template_id){
    $formids = Form::select('id')->where("form_type",RIDER_ASSETS)->where('template_id',$template_id)->get()->toArray();


    $html =[];
    if(!empty($formids)){
        $assets = Asset::where('user_id',$user_id)->whereIn('form_id',$formids)->get();

        foreach ($assets as $asset) {
            $riderName =  getRiderOwnerName($asset,"Relationship To Horse","Owner");
            if($riderName!='Not Found.') {
                $html[] = ['id' => $asset->id, 'owner' => getRiderOwnerName($asset, "Relationship To Horse", "Owner")];
            }
            }

    }
    return $html;
    //dd($formids);
}


function getRiderOwnerName($asset,$match,$optionFind){
    $pre_fields = json_decode($asset->fields);
    $name = "";
    if (isset($pre_fields)) {
        foreach ($pre_fields as $key => $value) {
            if($value->form_field_type == OPTION_DROPDOWN ||
                $value->form_field_type == OPTION_CHECKBOX ||
                $value->form_field_type == OPTION_AUTO_POPULATE) {
                if (fnmatch(strtolower($match), strtolower($value->form_name))) {
                    foreach ($value as $opKey => $options) {
                        if (is_numeric($opKey) && isset($options->answer)) {
                            if (fnmatch(strtolower($optionFind), strtolower($options->answer))) {
                                $name = GetSpecificFormField($asset,"Name");
                                if($name!='Not Found.') {
                                    return $name;
                                }
                            }
                            # code...
                        }
                    }

                }else {
                    $name = "";
                    continue;
                }

            }
        }

    }

    return $name;

}




function getOwnerForHorse($horse_id){
	$owner_id = HorseOwner::select('owner_id')->where('horse_id',$horse_id)->get();
	$html = "";
	if(count($owner_id)>0){
		foreach ($owner_id as $asset) {
			//$html .= FindFieldInform($asset,"Relationship To Horse","Owner");
			$html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"".url("master-template")."/". nxb_encode($asset->owner_id) . "/horseProfile\" style='margin:0px !important; float:none !important;'>"
						.GetAssetNamefromId($asset->owner_id)."</a> USEF# ".GetSpecificFormField($asset->owner_id,"USEF Number","N/A")."<br>";
                				
		}
	}
	return $html;
	//dd($formids);
}
function FindFieldInform($asset,$match,$optionFind){	
	$pre_fields = json_decode($asset->fields);
    $name = "";
    if (isset($pre_fields)) {
    	foreach ($pre_fields as $key => $value) {
            if($value->form_field_type == OPTION_DROPDOWN || 
            	$value->form_field_type == OPTION_CHECKBOX ||
            	$value->form_field_type == OPTION_AUTO_POPULATE) {
                if (fnmatch(strtolower($match), strtolower($value->form_name))) {
                	foreach ($value as $opKey => $options) {
                		if (is_numeric($opKey) && isset($options->answer)) {
                			if (fnmatch(strtolower($optionFind), strtolower($options->answer))) {
                				$name = "<a class=\"HorseAsset\" target=\"_blank\" href=\"".url("master-template")."/". nxb_encode($asset->id) . "/horseProfile\" style='margin:0px !important; float:none !important;'>" . GetSpecificFormField($asset,"Name")."</a> USEF# ".GetSpecificFormField($asset,"USEF Number","N/A")."<br>";
                				return $name;
                				break;
                			}
                			# code...
                		}
                	}
        
                }else {
                    $name = "";
                    continue;
                }
                
            }
    	}

    }
    return $name;

}

function getDivisionChampion($classHorses,$position=0){
	$chap = json_decode($classHorses->champions);
	$html = "";
	$html2= "";
	$number = 0;
	$number2=0;
	foreach ($chap as $key => $val) {
		if ($number ==0 || $val==$number) {
			$html .= getHorseNameAndUserfromCid($key)." - <small>Score </small><b> ".$val."</b><br>";
			$number= $val;
		}elseif($number2 ==0 || $val==$number2){
			$html2 .= getHorseNameAndUserfromCid($key)." - <small>Score </small><b> ".$val."</b><br>";
			$number2= $val;
		}else{
			//$html3 .= getHorseNameAndUserfromCid($key)." - <small>score </small><b> ".$val."</b><br>";
		}
	}
	if($position==2){
		return $html2;
	}else{	
		return $html;
	}
}
//Course Outline
function getCourseOutlineForm($template_id){
	$form = Form::where('template_id',$template_id)->where('form_type',F_COURSE_CONTENT)->first();
	if ($form) {
		return $form;
	}
	return false;
}
//--- Create Dropdown for font size
function CreatePermissionsDrp($name="defaults",$selected_v="",$starts=0,$ends=100,$custom_option=""){

    if ($custom_option != "" ) {
        $select = " <select class='custom-permission-admin' name='".$name."'>";
        //$select .= "<option value=''>Set Duplicate Permission if applicable</option>";
        if ($selected_v != "" ) {
            if ($selected_v == "00") {
                $select .= "<option value='00' selected='selected'>".$custom_option."</option>";
            }
        }else{
            $select .= "<option value='00'>".$custom_option."</option>";
        }
    }else{
        $select = " <select class='custom-permission' name='".$name."'>";
       // $select .= "<option value=''>Set Permission Time</option>";
    }
    for($i=$starts; $i<= $ends;$i++){
        if ($selected_v != "" ) {
            if ($selected_v == $i) {
                $selected_vals = "selected='selected'";
            }else{
                $selected_vals = "";
            }
        }else{
            $selected_vals = "";
        }
        $select .= "<option value='".$i."'".$selected_vals." >".$i." times</option>";
    }
    $select .="</select>";
    return $select;
}
//get User Name and email form id
function getUserNameEmailfromid($id)
{
    $collection = User::where('id',$id)->first();
    $nameEmail=$collection->name." (".$collection->email.")";
    return $nameEmail;
}

//get User Name and email form id
function getUserNameEmailfromEmail($email)
{
    $collection = User::where('email',$email)->first();
    $nameEmail=$collection->name." (".$collection->email.")";
    return $nameEmail;
}

//get User Name form id
function getUserNamefromid($id)
{
    $collection = User::where('id',$id)->first();
    if($collection)
        return $collection->name;
}
//get Business Name form id
function getShowOwnerInfo($show_id)
{
    $MS = ManageShows::find($show_id);
   return $collection = User::where('id',$MS->user_id)->first();
}



//get User Name form id
function getTrainerFromId($id)
{
    $collection = ManageShowTrainer::with("user")->where('id',$id)->first();
    if (!is_null($collection)) {
    	return $collection->user->name;
    }
    return "N/A";
}

//get User Name form id
function getUserEmailfromid($id)
{
    $collection = User::where('id',$id)->first();
    return $collection->email;
}
//get User Name form id
function getIdFromEmail($email)
{
    $collection = User::where('email',$email)->first();
    if ($collection) {
    	return $collection->id;
    }
    return 0;
}
//get User Name form email
function getUserNamefromEmail($email)
{
    $collection = User::where('email',$email)->first();
	if ($collection != null) {
		return $collection->name;
	}else{
		return "Not Registered yet";
	}
}
//get Form Name form id
function getFormNamefromid($id)
{
    $collection = Form::where('id',$id)->first();
    if (isset($collection->name)) {
        return $collection->name;
    }else{
        return false;
    }
}

function getModuleNameModuleId($id)
{
    $module = Module::where('id',$id)->first();
    if (isset($module->name)) {
        return $module->name;
    }else{
        return false;
    }
}
//Check if the participant has permission to access the module
function getModulePermission($key,$array){
	if(isset($array[$key])){
	    return $array[$key];
	}else{
		return false;
	}
}
//Get Module Name
function getFormsModuleFromId($form_id){
	$form = Form::where('id',$form_id)->first();
	$module = Module::where('id',$form->linkto)->first();
	if ($module != null) {
		$modulename = $module->name;
	}else{
		$modulename = "No Module Associated";
	}
	return $modulename;

}
//Get form Responses form ID
function getFormsResponsesfromNId($form_id){
    $user_id = \Auth::user()->id;
	return $forms = ParticipantResponse::select('form_id')->where("form_id",$form_id)->with("participant")->whereHas('participant', function ($query) use ($user_id) {
                                $query->where('invitee_id', $user_id);
                            })->orWhere(function ($query) use ($form_id, $user_id) {
        $query->where("form_id",$form_id)
        ->where('user_id', $user_id);
    })->count();
}
//Fet participants Count
function getParticipantCountFromInvitee($id){
	return $participants = Participant::select('id')->where('invitee_id',$id)->count();
}

//Get form Responses form ID
function getFormsResponsesfromId($form_id,$user_id=0){
	if ($user_id ==0) {
		return $count = ParticipantResponse::where('form_id',$form_id)->count();
	}else{
		return $count = ParticipantResponse::where('form_id',$form_id)->where('user_id',$user_id)->count();
	}
}
//Get Location for the assets from form
function GetAssetLocationfromId($asset_id,$Totalfields=0){


   // echo $asset_id;exit;

	$row = Asset::where('id',$asset_id)->first();
	 $pre_fields = json_decode($row->fields);

    $location = array();
    $answer = "";
    if ($pre_fields != null) {
	    foreach ($pre_fields as $key => $value) {
	    		if ($value->form_field_type == OPTION_ADDRESS_MAP) {

	    				$answer = $value->answer;
	    			if ($Totalfields == 5) {
	    				$location['location'] = $value->answer;
	    				$location["latitude"] = $value->latitude;
	    				$location["longitude"] = $value->longitude;
	    				$location["place_id"] = $value->place_id;
    					return $location;
	    			}
	    			break;
	    		}else{
	    				$answer = "No location field exist";
	    		}
	    }
	}else{
		$answer = "No data entered for location";
	}
    return $answer;
}
//Assign horse registration to the asset
function getHorseRegistrationId($show_id,$horse){
	$existing_horse = ClassHorse::where("show_id",$show_id)->where("horse_id",$horse)->first();
	$uniqueReg = 0;
	//Horse is already registered.
	if ($existing_horse) {
		$uniqueReg = $existing_horse->horse_reg;
	}else{
		$horses = ClassHorse::where("show_id",$show_id)->orderBy('horse_reg', 'desc')->first();
		//Horse is registring for first time
		if ($horses) {
			$horse_reg = $horses->horse_reg;
			$uniqueReg = $horse_reg+1;
		}else{
			$uniqueReg = 1;
		}
	}
	return $uniqueReg;
}
//Assign horse registration to the asset
function getHorseInvoice($show_id,$horse){
	$existing_horse = ClassHorse::where("show_id",$show_id)->where("horse_id",$horse)->where('status',0)->first();
	$uniqueReg = 0;
	//Horse is already registered.
	if ($existing_horse) {
		$uniqueReg = $existing_horse->invoice_no;
	}else{
		$horses = ClassHorse::where("show_id",$show_id)->orderBy('invoice_no', 'desc')->first();
		//Horse is registring for first time
		if ($horses) {
			$horse_reg = $horses->invoice_no;
			$uniqueReg = $horse_reg+1;
		}else{
			$uniqueReg = 1;
		}
	}
	//$uniqueReg = str_pad($uniqueReg, 4, '0', STR_PAD_LEFT);
	return $uniqueReg;
}
//Assign horse registration to the asset
function getInvoiceNumber($show_id,$horse,$MS=NULL){
	if(count($MS)>0){
		$existing_horse =$MS;
	}else{
		$existing_horse = ClassHorse::where("show_id",$show_id)->where("horse_id",$horse)->first();
	}
	$uniqueReg = 0000;
	if (count($existing_horse)>0) {
		//$horses = ClassHorse::where("show_id",$show_id);
		
		$uniqueReg = $existing_horse->show_id.'-'.$existing_horse->horse_reg.'-'.str_pad($existing_horse->invoice_no, 4, '0', STR_PAD_LEFT);
	}
	return $uniqueReg;
}
//Display horse names
function getHorseNames($participant_id ,$class_id,$scratch=0,$assetName="",$trainer_id=0){
	$horses = getHorsesForUser($participant_id ,$class_id );
	$html = "";
	if (sizeof($horses)>0) {
		foreach ($horses as $horse) {
			if ($scratch == 1) {
				if ($horse->scratch == HORSE_SCRATCHED) {
					$html .= "<div class='scratched-horses'>";
				}else{
					$html .= "<div class='not-scratched-horses'>";		
				}
				$html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_id) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_id) . "</a>"." [Entry# ".$horse->horse_reg."]";


				if ($horse->scratch == HORSE_SCRATCHED) {
					$html .= "</div>"; 
					$html .= "<a title='scratch' class=\"HorseScratch\" href=\"/shows/" . nxb_encode($horse->id) . "/horse/scratch/1\" onclick=\"return confirm('Are you sure you want to Un Scratch this horse?
						If it belongs to Must Division, Then it will scratch this horse from all other classes of division as well.')\" > Un-Scratch </a>";
				}else{
                    $html .= "<a class=\"HorseAsset HorseScratch\" title='scratch' href=\"/shows/" . nxb_encode($horse->id) . "/horse/scratch\" onclick=\"return confirm('Are you sure you want to scratch this class? If the class is part of a division that requires you to participate in all classes in the division it will scratch you from all classes in the division.')\" > Scratch </a>";
					$html .= "</div>";
				}
			}elseif($scratch == 2){
				if ($horse->scratch == HORSE_SCRATCHED) {
					$html .= "<div class='scratched-horses'>";
				}
				$html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_id) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_id) . "</a>"." [Entry# ".$horse->horse_reg."]";
				if ($horse->scratch == HORSE_SCRATCHED) { 
					$html .= "</div>"; 
				}
			}elseif ($scratch == 3) {
				if ($horse->scratch == HORSE_SCRATCHED) {
					$html .= "<div class='scratched-horses'>";
				}else{
					$html .= "<div class='not-scratched-horses'>";		
				}
					$html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_id) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_id) . "</a>"." [Entry# ".$horse->horse_reg."]";
					$html .= "<a class=\"HorseAsset HorseScratch\" title='scratch' href=\"/shows/" . nxb_encode($horse->id) . "/horse/scratch\" onclick=\"return confirm('Are you sure you want to scratch this class? If the class is part of a division that requires you to participate in all classes in the division it will scratch you from all classes in the division.')\" > Scratch </a>";
					$html .= "<input type='hidden' name='".$assetName."' value='".$horse->horse_id."'> ";
				
				if ($horse->scratch == HORSE_SCRATCHED) { 
					$html .= "</div>"; 
					$html .= "<a title='scratch' class=\"HorseScratch\" href=\"/shows/" . nxb_encode($horse->id) . "/horse/scratch/1\" onclick=\"return confirm('Are you sure you want to Un Scratch this horse?')\" > Un-Scratch </a>";
				}else{
					$html .= "</div>"; 
				}
			}elseif ($scratch == 4) {
				if ($horse->scratch == HORSE_SCRATCHED) {
					$html .= "<div class='scratched-horses'>";
				}else{
					$html .= "<div class='not-scratched-horses'>";		
				}
					$html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_id) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_id) . "</a>"." [Entry# ".$horse->horse_reg."]";
					$html .= "<a class=\"HorseAsset HorseScratch\" title='scratch' href=\"/trainer/" . nxb_encode($horse->id) . "/horse/scratch/trainer/".nxb_encode($trainer_id)."\" onclick=\"return confirm('Are you sure you want to scratch this class? If the class is part of a division that requires you to participate in all classes in the division it will scratch you from all classes in the division.')\" > Scratch </a>";
					$html .= "<input type='hidden' name='".$assetName."' value='".$horse->horse_id."'> ";
				
				if ($horse->scratch == HORSE_SCRATCHED) { 
					$html .= "</div>"; 
					$html .= "<a title='scratch' class=\"HorseScratch\" href=\"/trainer/" . nxb_encode($horse->id) . "/horse/scratch/trainer/".nxb_encode($trainer_id)."/1\" onclick=\"return confirm('Are you sure you want to Un Scratch this horse?')\" > Un-Scratch </a>";
				}else{
					$html .= "</div>"; 
				}
			}
            elseif ($scratch == 5) {
                if ($horse->scratch == HORSE_SCRATCHED) {
                    $html .= "<div class='scratched-horses'>";
                }else{
                    $html .= "<div class='not-scratched-horses'>";
                }
                $html .= "<a class=\"HorseAsset\" target=\"_blank\"   href=\"/master-template/" . nxb_encode($horse->horse_id) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_id) . "</a>"." [Entry# ".$horse->horse_reg."]";

                $html .= "<a class=\"HorseAsset\" title='scratch' href='javascript:' onclick=\"scratchHorse('".nxb_encode($horse->horse_id)."')\" > Scratch </a>";
                if ($horse->scratch == HORSE_SCRATCHED) {
                    $html .= "</div>";
                    $html .= "<a title='scratch' class=\"HorseScratch\" href=\"/shows/" . nxb_encode($horse->id) . "/horse/scratch/1\" onclick=\"return confirm('Are you sure you want to Un Scratch this horse?')\" > Un-Scratch </a>";
                }else{
                    $html .= "</div>";
                }
            }
			else{
				$html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_id) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_id) . "</a>"." [Entry# ".$horse->horse_reg."]";
            }
			$html .= "\n";
		}
	}else{
		$html = "No Horse Added";
	}

	return nl2br($html);
}

// get Rider name With Horse
function getRiderWithHorse($participant_id ,$class_id,$type)
    {

        $horses = getHorsesForUser($participant_id ,$class_id );
        $html = "";
        if (sizeof($horses)>0)
        {
            foreach ($horses as $horse)
            {
                if($horse->horse_rider!='') {
                    $html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_rider) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_rider) . "</a> [" . GetAssetNamefromId($horse->horse_id) . "]";
                   if($type==1) {
                       $html .= "\n";
                       $html .= "\n";
                   }elseif($type==2)
                   {
                       $html .= "\n";
                   }
                }else
                {
                    $html = "No Rider Added";
                }


                }
        }else
        {

            $html = "No Rider Added";

        }

        return nl2br($html);


    }

 //   get Rider name
function getRiderName($participant_id ,$class_id)
{

    $horses = getHorsesForUser($participant_id ,$class_id );
    $html = "";
    if (sizeof($horses)>0)
    {
        foreach ($horses as $horse)
        {
            if($horse->horse_rider!='') {
                $html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_rider) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_rider) . "</a>";
                $html .= "\n";
                $html .= "\n";
            }else
            {
                $html = "No Rider Added";
            }


        }
    }else
    {

        $html = "No Rider Added";

    }

    return nl2br($html);


}


function getTrainerQty($participant_id ,$class_id)
{

    $horses = getHorsesForUser($participant_id ,$class_id );
    $html = "";
    if (sizeof($horses)>0)
    {
        foreach ($horses as $horse)
        {
            if($horse->horse_rider!='') {
                $html .= "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($horse->horse_rider) . "/horseProfile\" >" . GetAssetNamefromId($horse->horse_rider) . "[".$horse->horse_quantity."]</a>";
                $html .= "\n";
                $html .= "\n";
            }else
            {
                $html = "No Rider Added";
            }


        }
    }else
    {

        $html = "No Rider Added";

    }

    return nl2br($html);


}




//Display horse names
function getHorseNamesfromID($manage_show_reg_id,$class_id,$assetName){
	$participant = Participant::select("id")->where("manage_show_reg_id",$manage_show_reg_id)->where('asset_id',$class_id)->first();
	return getHorseNames($participant->id,$class_id,3,$assetName);
}
//Trainer 
function getHorseNamesfromIDTrainer($manage_show_reg_id,$class_id,$assetName){
	$trainer_id = \Auth::user()->id;
    $participant = Participant::select("id")->where("manage_show_reg_id",$manage_show_reg_id)->where('asset_id',$class_id)->orderBy('id','desc')->first();
    if($participant)
	return getHorseNames($participant->id,$class_id,4,$assetName,$trainer_id);
}


function getRiderNamesfromIDTrainer($manage_show_reg_id,$class_id,$assetName=null){
    $trainer_id = \Auth::user()->id;
    $participant = Participant::select("id")->where("manage_show_reg_id",$manage_show_reg_id)->where('asset_id',$class_id)->first();
    if($participant)
    return getRiderWithHorse($participant->id,$class_id,1);

}

function getRiderQtyfromIDTrainer($manage_show_reg_id,$class_id,$assetName=null){
    $trainer_id = \Auth::user()->id;
    $participant = Participant::select("id")->where("manage_show_reg_id",$manage_show_reg_id)->where('asset_id',$class_id)->first();
    if($participant)
        return getTrainerQty($participant->id,$class_id,1);

}

//Show horses 
function modelShowHorse($horse_id,$assetId,$invite_asociated_key=0)
{
    return ClassHorse::with("horse","penalty","Joining_penalty")->where('horse_id', $horse_id)->where('class_id', $assetId)->where('invite_asociated_key',$invite_asociated_key)->first(); 
}
//Get Div penalty
function getDivJoiningDatePanlety($division_id,$horse_id){
	// $assets = Asset::whereHas('assetParent',function ($query) use ($division_id) {
	// 				$query->where('parent_id',$division_id);
	// 			})->with('ShowAssetInvoice','Joining_penalty')->get();
	// $penaltyObjs = ShowScratchPenalty::whereHas('assets.assetParent',function($query) use  ($division_id) {
	//  				$query->where('parent_id',$division_id);
	//  })->where('type',SCROPT_CLASS_JOINING_PENALITY)->get();
	$TotalPenalty = 0;
	$html = "";
	$classes = ClassHorse::with('Joining_penalty')->where('belong_to_div',$division_id)->where('horse_id',$horse_id)->get();
	
	if (count($classes)>0) {
		foreach ($classes as $class) {
			$penalty = getPenaltyImposed($class->Joining_penalty,$class->created_at,1);
			$TotalPenalty = $TotalPenalty+(float)$penalty['panelty'];
			$html .= $penalty['html'];
		}
	}
	return ['totalPenalty'=>$TotalPenalty,'html'=>$html];

}
//Get penalty added on the object
function getPenaltyImposed($penaltyObj,$date,$class=0){
	//$penaltyObj->penality;
	$arr = ['html'=>"",'panelty'=>""];
	foreach ($penaltyObj as $penality) {
		if ($class == 1) {
			$check = check_in_time_range($penality->date_from, $penality->date_to, $date);
		//dd("From :".$penaltyObj->date_from. "  UpTo: ".$penaltyObj->date_to."  User-scratched:".$date);

		}else{
			$check = check_in_time_range($penality->date_from, $penality->date_to, $date);		
		}
		
		if ($check) {
			if ($class == 1) {
				$html = " <div class='penalty-added'>Add Penalty:($)".$penality->penality."</div>";
				$prices = $penality->penality;

			}else{
				$html = " <div class='penalty-added'>Scratch Penalty:($)".$penality->penality."</div>";
				$prices = $penality->penality;	
			}
			$arr = ['html'=>$html,'panelty'=>$prices];
			return $arr;
		}else{
			$arr = ['html'=>"",'panelty'=>""];
		}
	}
	return $arr;
}

// Get riders for horses

//Check time range
function check_in_time_range($start_date, $end_date, $date_from_user)
{
  // Convert to timestamp
  $start_ts = strtotime($start_date);
  $end_ts = strtotime("+1 day",strtotime($end_date));
  $user_ts = strtotime($date_from_user);
  //dd('current - '. $user_ts, 'start - '. $start_ts, ' end - '.$end_ts);

  // Check that user date is between start & end
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}
//No Query Execution, Just formate the horse id
function FormateHorseRegisteration($horse_reg=0){
	if($horse_reg !=0){
		$html = "[Entry# ".$horse_reg."]";
	}else{
        $html = "Not Registered";
	}
	return $html;
}
//Get horse registration by horse and show id
function GetHorseRegisteration($horse_id,$show_id,$isScheudler=0){

    $horse = ClassHorse::select("horse_reg")->where("show_id",$show_id)->where("horse_id",$horse_id)->first();

  if($horse) {
          if ($isScheudler == 1)
              $html = "[" . $horse->horse_reg . "]";
          else
              $html = "[Entry# " . $horse->horse_reg . "]";
      } else {
          if ($isScheudler == 1)
              $html = "[N/A]";
          else
              $html = "Not Registered";
      }

	return $html;

}
//Get Horses for user
function getHorsesForUser($participant_id ,$class_id ){
	return ClassHorse::select("id","horse_id","horse_reg","scratch","horse_rider","horse_quantity")->where("participant_id",$participant_id)->where("class_id",$class_id)->get();
}
//Get Horses for user
function getParticipatingHorses($show_id){
	$user_id = \Auth::user()->id;
	return ClassHorse::with('horse')->select("id","horse_id","horse_reg")
	->where("show_id",$show_id)
	->where("user_id",$user_id)
	->groupBy('horse_id')
	->get();
}

function getUnPaidHorses($horse_id,$show_id,$divClass=null){
	if ($divClass != null) {
		$arrayInter= array();
		$arrayExcluedClasses= array();
		foreach ($divClass as $classesDiv) {
			$incClasses = AssetParent::with('assetsScheduler')->where('parent_id',$classesDiv['division_id'])
				->whereHas("assetsScheduler",function ($query) use ($show_id) {
					$query->where('show_id',$show_id);
				})
				->pluck('asset_id')->toArray();
			
			$haveClasses = ClassHorse::where("belong_to_div",$classesDiv['division_id'])->where("horse_id",$horse_id)
						->where("show_id",$show_id)->where('scratch',0)->pluck('class_id')->toArray();
			if ($classesDiv['primary_required'] == 1) {
				$arrayExcluedClasses[] = $haveClasses;
			}else{
				$arrayInter = array_diff($incClasses,$haveClasses);
				if(empty($arrayInter)){
					$arrayExcluedClasses[] =$haveClasses;
				}
			}

		}
		//dd($arrayExcluedClasses);
		$arrayExcluedClasses = array_flatten_div($arrayExcluedClasses);

		$CH = ClassHorse::with("pclass",'ShowClassPrice',"Joining_penalty",'combinedClass','splitclass.splitedclass.SchedulerRestriction')
				->where("status",0)
				->where("horse_id",$horse_id)
				->where("show_id",$show_id)
				->whereNotIn("class_id",$arrayExcluedClasses)
                ->groupBy("invite_asociated_key",'horse_id')

				//->where("division_id",NULL)
				//->whereNull("belong_to_div")
				// ->where(function ($query) use($divClass) {
				// 	$query->whereNotIn("belong_to_div",$divClass)
				// })
				->get();
				//->where("division_id",NULL)
				//->whereNotIn("belong_to_div",$divClass)


		$divClasses = ClassHorse::where("status",0)
				->where("horse_id",$horse_id)
				->where("show_id",$show_id)
				->whereIn("class_id",$arrayExcluedClasses)
                ->groupBy("invite_asociated_key",'horse_id')

            ->get();
				
	}else{
		$CH = ClassHorse::with("pclass",'ShowClassPrice',"Joining_penalty",'combinedClass','splitclass.splitedclass.SchedulerRestriction')
				->where("status",0)
				->where("horse_id",$horse_id)
				->where("show_id",$show_id)
                ->groupBy("invite_asociated_key",'horse_id')

				//->where("division_id",NULL)
				->get();
		$divClasses = null;
	}
	return ['collection'=>$CH,'divClasses'=>$divClasses];
			
}

function IsCSInShow($class_id,$show_id,$type=0){
		return SchedulerRestriction::where('asset_id',$class_id)->where('show_id',$show_id)->get()->count(); 
}
//Paid Horses
function getPaidHorses($horse_id,$show_id,$divClass=null,$paid_on=null){	
	if ($divClass != null) {
		$arrayInter= array();
		$arrayExcluedClasses= array();
		foreach ($divClass as $classesDiv) {
			$incClasses = AssetParent::with('assetsScheduler')->where('parent_id',$classesDiv['division_id'])
				->whereHas("assetsScheduler",function ($query) use ($show_id) {
					$query->where('show_id',$show_id);
				})
				->pluck('asset_id')->toArray();
			
			$haveClasses = ClassHorse::where("belong_to_div",$classesDiv['division_id'])->where("horse_id",$horse_id)
						->where("show_id",$show_id)->where('scratch',0)->pluck('class_id')->toArray();
			if ($classesDiv['primary_required'] == 1) {
				$arrayExcluedClasses[] = $haveClasses;
			}else{
				$arrayInter = array_diff($incClasses,$haveClasses);
				if(empty($arrayInter)){
					$arrayExcluedClasses[] =$haveClasses;
				}
			}

		}
		//dd($arrayExcluedClasses);
		$arrayExcluedClasses = array_flatten_div($arrayExcluedClasses);

		$CH = ClassHorse::with("pclass",'ShowClassPrice',"Joining_penalty",'combinedClass','splitclass.splitedclass.SchedulerRestriction')
				->where("status",1)
				->where("horse_id",$horse_id)
				->where("show_id",$show_id)
				->whereNotIn("class_id",$arrayExcluedClasses)
				->where("paid_on",$paid_on)
				//->where("division_id",NULL)
				//->whereNull("belong_to_div")
				// ->where(function ($query) use($divClass) {
				// 	$query->whereNotIn("belong_to_div",$divClass)
				// })
				->get();
				//->where("division_id",NULL)
				//->whereNotIn("belong_to_div",$divClass)


		$divClasses = ClassHorse::where("status",1)
				->where("horse_id",$horse_id)
				->where("show_id",$show_id)
				->whereIn("class_id",$arrayExcluedClasses)
				->get();
				
	}else{
		$CH = ClassHorse::with("pclass",'ShowClassPrice',"Joining_penalty",'combinedClass','splitclass.splitedclass.SchedulerRestriction')
				->where("status",1)
				->where("horse_id",$horse_id)
				->where("show_id",$show_id)
				->where("paid_on",$paid_on)
				//->where("division_id",NULL)
				->get();
		$divClasses = null;
	}

	return ['collection'=>$CH,'divClasses'=>$divClasses];

}
//
function getHorseQualifying($horse_id,$class_id){
	$CH = ClassHorse::select('qualifing_check')->where('horse_id',$horse_id)->where('class_id',$class_id)->first();
	if (count($CH)>0 && $CH->qualifing_check == 1) {
		return "Yes";
	}else{
		return "No";
	}
}

function array_flatten_div($array) { 
  if (!is_array($array)) { 
    return FALSE; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } 
    else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
}
function getEstimateStartTime($restrictions){
	$start = null;
	foreach ($restrictions as $time) {
		$pieces = explode(" - ", $time->restriction);
		if ($start==null)
			$start = $pieces[0];
		//echo strtotime($start). ' < '.strtotime($pieces[0]).'<br>';
		//echo $start. ' < '. $pieces[0].'<br>';
		if(strtotime($start) > strtotime($pieces[0])) {
			$start = $pieces[0];
		}
	}
	//dd($start);
	return $start;
}

function getEstimateRestrictionPrice($restrictions,$show_id){
	$start = 0;
	foreach ($restrictions as $restrict) {
		
		if ($show_id == $restrict->show_id) {
			# code...
		
			if ($restrict->qualifing_check == 1) {
				if ($start==null){
					$start = $restrict->qualifing_price;
				}

				if($start < $restrict->qualifing_price) {
					$start = $restrict->qualifing_price;
				}
			}
		}
	}

	return $start;
}

function getEstimateRestrictionif($restrictions){
	$start = null;
	foreach ($restrictions as $restrict) {
		if ($restrict->qualifing_check == 1) {
			return true;
		}
	}
	return false;
}
//Get Division
function getDivisionForHorse($horse_id,$show_id,$invoice_status,$paid_on=null){
	 if ($invoice_status == PAID) {
	 	$divisions =Division::with("pclass",'classhorses')
				->where("horse_id",$horse_id)
				->where("show_id",$show_id)
                ->where("invoice_status",$invoice_status)
                ->where("paid_on",$paid_on)
				->groupBy('horse_id','invite_key',"division_id")
				->get();
	 }else{
	 	$divisions =Division::with("pclass",'classhorses')
				->where("horse_id",$horse_id)
				->where("show_id",$show_id)
                ->where("invoice_status",$invoice_status)
				->groupBy('horse_id','invite_key',"division_id")
				->get();
	 }
	 
	
	if (count($divisions)>0) {
		foreach ($divisions as $key => $div) {
			//if any horse is scratched. Remove division
			$ch = ClassHorse::where('belong_to_div',$div->division_id)->where('show_id',$show_id)->where('horse_id',$horse_id)->where('scratch',1)->get();
			if (count($ch)>0) {
				$divisions->forget($key);
			}
			//if any horse is not selected for whole division. Remove division
			if ($div->primary_required == 0) {
				$ch_div = ClassHorse::where('belong_to_div',$div->division_id)->where('show_id',$show_id)->where('horse_id',$horse_id)->get();
				if($ch_div->count() != $div->total_classes){
					$divisions->forget($key);
				}
			}
			
		}
	}
	return $divisions;
	
}
//Get ordered supplies for horse
function getOrderedSupplies($horse_id,$show_id){
	return $MSOS = ManageShowOrderSupplies::with('orderSupplie')
			->where('show_id',$show_id)
			->where('status',1)
			->whereHas('orderSupplie', function ($query) use ($horse_id) {
                        $query->where('horse_id', $horse_id);
                	})
			->get();
}
//Get Stall Charges for horse
function getStallCharges($horse_id,$show_id,$invoice_status,$paid_on=NULL){
	return HorseRiderStall::with('stalls')->where('show_id',$show_id)
							->where("horse_id",$horse_id)
							->where("invoice_status",$invoice_status)
							->where("paid_on",$paid_on)
							->get();
}
//Get Utilit Stall for horse
function getUtilityStalls($horse_id,$show_id,$invoice_status,$paid_on=NULL){
	return ShowStallUtility::where('show_id',$show_id)
							->where("horse_id",$horse_id)
							->where("invoice_status",$invoice_status)
							->where("paid_on",$paid_on)
							->get();
}


//Get division count
function getDivisionClassCount($horse_id,$show_id,$division_id){
	$CH =Division::where("horse_id",$horse_id)
				->where("show_id",$show_id)
				->where("division_id",$division_id);

	$ids = $CH->pluck('id')->toArray();
	$counter = count($CH->whereHas('classhorses', function ($query) {
                        $query->where('scratch', HORSE_NOT_SCRATCHED);
                    })->get());
	return $returnElement = array('counter' => $counter, 'ids' => $ids);
}


function getDivisionClasses($horse_id,$show_id,$division_id){
	$ch = ClassHorse::where("belong_to_div",$division_id)->where("horse_id",$horse_id)
	->where("show_id",$show_id);
	$classes = $ch->pluck('class_id')->toArray();
	$HaveClasses = AssetParent::where('parent_id',$division_id)->pluck('asset_id')->toArray();
	
	$arrayInter = array_diff($HaveClasses,$classes);
	return $returnElement = array('difference' => $arrayInter);
}

function getClassParticipants($class_id,$show_id,$cc_id = null){	
	if ($cc_id==null) {
		return $CH = ClassHorse::where("class_id",$class_id)->where("show_id",$show_id)->where('scratch',0)->count();
	}else{
		$classIDs = CombinedClass::where('combined_class_id',$cc_id)->pluck('class_id')->toArray();
		return $CH = ClassHorse::whereIn("class_id",$classIDs)
						->where("show_id",$show_id)
						->where('scratch',0)
						->count();
	}
}
function  getInvoiceAddedComment($show_id,$horse_id, $paid=0){
	if ($paid == 0) {
		return $HIC = HorseInvoiceComment::where("show_id",$show_id)->where("horse_id",$horse_id)->where('paid_on',NULL)->first();
	}else{
		return $HIC = HorseInvoiceComment::where("show_id",$show_id)->where("horse_id",$horse_id)->where('paid_on',$paid)->first();
	}

}
function getInvoiceDividedUser($invite_asociated_key){
	return $ch= ClassHorse::where("invite_asociated_key",$invite_asociated_key)->groupBy("horse_id")->get()->count();
}

//Get permission
function getParticipantPermissions($participant_id){
	$participant = Participant::where('id',$participant_id)->first();
	return $participant->allowed_time;
}
//Allow form submit
function allowedToSubmitForm($participant_id,$form_id){
    $user_id = \Auth::user()->id;
	$participant = Participant::where('id',$participant_id)->first();
	$pResponse = ParticipantResponse::where('template_id',$participant->template_id)->where('participant_id',$participant_id)->where('form_id',$form_id)->count();

	if ($participant->allowed_time == "unlimited" ) {
		return true;
	}else{
		if ($pResponse < $participant->allowed_time){
			return true;
		}else{
			return false;
		}
		return false;
	}
}

//Allow form submit
function FormPermissionCheck($times_allowed,$participant_id,$forms){
    $user_id = \Auth::user()->id;
    $participantRCount = ParticipantResponse::where('user_id',$user_id)->where('participant_id',$participant_id)->get()->count();
	$count = $times_allowed;
	if($forms !=null){
		$count = $forms*$times_allowed;
	}
	if($count > $participantRCount){
		return "Pending";
	}else{
		return "Complete";
	}
}
//
function getRegisteredAssets($register,$geter=1){
	if ($geter == 2) {
		if ($register->additional_fields) {
			return json_decode($register->additional_fields,true);
		}
		
	}
	if ($register->assets_fields) {
		return json_decode($register->assets_fields,true);
	}
	return false;
}
//Sub Allow form submit
function allowedToSubmitSubPartForm($participant_id,$form_id){
    $user_id = \Auth::user()->id;
	$participant = subParticipants::where('id',$participant_id)->first();
	$pResponse = ParticipantResponse::where('template_id',$participant->template_id)->where('participant_id',$participant_id)->where('form_id',$form_id)->count();

	if ($participant->allowed_time == "unlimited" ) {
		return true;
	}else{
		if ($pResponse < $participant->allowed_time){
			return true;
		}else{
			return false;
		}
		return false;
	}
}
function submitFormFields($request){
	$disk = Storage::disk('s3');
	$user_id = \Auth::user()->id;

	 //Upload Image and Attachements to s3
		$fieldsarray = $request->fields;
        $template_id = $request->template_id;
        $form_id = $request->form_id;
        if (isset($request->fields)) {
           foreach ($request->fields as $key=>$field) {
           		if (isset($field["form_field_type"])) {
           			if(($field["form_field_type"] == OPTION_IMAGE) &&
                        isset($field["images_blob"]) ){
	                    foreach ($field["images_blob"] as $iIndex => $files) {
	                    	if (!filter_var($files, FILTER_VALIDATE_URL)) {
	                    		$ext = explode(',', $files ,2);
								$path = public_path('uploads/').$user_id.'file';
								$image = file_put_contents($path,base64_decode($ext[1]));
		                        //Resize
		                        imageResizeHelper($path);
		                        $save = $disk->putFile("formData/$form_id",new File($path),"public");
		                        $fieldsarray[$key]["images_blob"][$iIndex] = "";
		                        $fieldsarray[$key]["upload_files"][$iIndex] = $save;
		                        if(\File::exists($path)){
		                        	\File::delete($path);
		                        }
	                    	}
	                    }
	                }
	                if($field["form_field_type"] == OPTION_ATTACHMENT &&  isset($field["upload_files"])){
	            		 $count = 0;
	            		 //upload_filess are the old files uploaded
	            		 if (isset($field["upload_filess"])) {
	            		 	$count = count($field["upload_filess"]);
	            		 }
	            		 foreach ($field["upload_files"] as $iIndex => $file) {
	                		$extension = $file->getClientOriginalExtension();
	    					$nameoffile = $file->getClientOriginalName();
							$file->move(public_path('uploads'), $user_id.$nameoffile);
							$path = public_path('uploads/').$user_id.$nameoffile;
	 						$save = $disk->putFile("formData/$form_id",new File($path),"public");
	                        $fieldsarray[$key]["upload_filess"][$count]['path'] = $save;
	                        $fieldsarray[$key]["upload_filess"][$count]['name'] = $nameoffile;
	                        if(\File::exists($path)){
	                        	\File::delete($path);
	                        }
	                        $count = $count+1;
	                    }
	                }
	                 if($field["form_field_type"] == OPTION_VIDEO &&
	                        isset($field["upload_files"])){
	                 		//checking if old uploaded file exist
	                 		if ( isset($field["upload_filess"])) {
	                 			$delete = $disk->delete($field["upload_filess"]);
	                 		}
	                		$file = $field['upload_files'];
	                		$extension = $file->getClientOriginalExtension();
	    					$nameoffile = $file->getClientOriginalName();
							$file->move(public_path('uploads'), $user_id.$nameoffile);
							$path = public_path('uploads/').$user_id.$nameoffile;
	 						$save = $disk->putFile("formData/$form_id",new File($path),"public");
	                        //dd($save);
	                        $fieldsarray[$key]["upload_filess"] = $save;
	                        if(\File::exists($path)){
	                        	\File::delete($path);
	                        }
	                }
           		}

            }
        }

        if(is_array($fieldsarray)){
            $fields_inputs = json_encode(array_values($fieldsarray));
        }else{
            $fields_inputs = null;
        }
        return $fields_inputs;
}
function imageResizeHelper($path){
		$imgObj = Image::make($path);
	    if($imgObj->filesize() > 532446 )// Greater then 5KB
	    {
	    	$ImgJpg = $imgObj->resize(null, 1024, function ($constraint) {
					    $constraint->aspectRatio();
					    $constraint->upsize();
					})->encode('jpg', 100);
	    	// save the same file as jpg with default quality
			$ImgJpg->save($path);
	    }
}
//Decoded Post
function decoded_post($post,$Type=0){
	$spost = json_decode($post,true);
	//This is image
	if ($Type == 1) {
		return $Answer_array = $spost['images'];
	}
	//This is video.
	if ($Type == 2) {
		return $Answer_array = $spost['video'];
	}
	return $Answer_array = nl2br($spost['msg']);
}
//Carbon Now. Time for Post
function getTimeOfPost($time){
	return $time->diffForHumans(Carbon::now(),true)." ago";
}
//Check if has comment image
function doCommentHave($post,$type="Image"){
	if ($type=="Image") {
		$spost = json_decode($post,true);
		if (isset($spost['images'])) {
			return true;
		}
		return false;
	}
	if ($type=="Video") {
		$spost = json_decode($post,true);
		if (isset($spost['video'])) {
			if ($spost['video'] == "") {
				return false;
			}
			return true;
		}
		return false;
	}

}
function getYoutubeId($url){
	parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
	if (isset($my_array_of_vars['v'])) {
		return $my_array_of_vars['v'];
	}
}

function getAppFromInviteeAndTemplateIds($invitee_id,$template_id){
	
	return $app = InvitedUser::where("email",getUserEmailfromid($invitee_id))->where("template_id",$template_id)->first();
 	// return $html="<option value='$app->id'>".GetTemplateName($template_id,$invitee_id)."</option>";

}

//User have access to whcih templates
function user_template_accessable_appwise($user_id=0){

	//Getting app wise (22-02-2018)
	if ($user_id == 0) {
		$user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;
        $arrayInvit = array();
        $arrayInvited = InvitedUser::where('email',$useremail)
        					->whereHas('template',function($query){
        							$query->where('blog_type',TIMELINE_BLOG_APPWISE);
        					})->pluck("template_id")->toArray();


        $arrayParticipants = Participant::select("invitee_id","template_id")->where('email',$useremail)
        					->whereHas('hastemplate',function($query){
        							$query->where('blog_type',TIMELINE_BLOG_APPWISE);
        					})->groupBy('template_id', 'invitee_id')->get()->toArray();
		$finalArray = array();
		$templateWise = array();
        foreach ($arrayInvited as $index => $template_id) {
        	//$index = (int)$user_id."-".(int)$template_id;
        	if(find_key_value($arrayParticipants, "invitee_id", $user_id) && find_key_value($arrayParticipants, "template_id", $template_id)){
        		
        	}else{
        			$arrayInvit[$index]["invitee_id"]="$user_id";
        			$arrayInvit[$index]["template_id"]=$template_id;
        	}
        }
		$arrMerge = array_merge($arrayInvit, $arrayParticipants);

		//getting the trainers participation
		$mst = ManageShowTrainer::with('showtemp')->where('user_id',$user_id)->whereHas('showtemp.template',function($query){
        							$query->where('blog_type',TIMELINE_BLOG_APPWISE);
        					})->get();
		if(count($mst)>0){
			$counter = count($arrMerge);
			foreach ($mst as $loop => $shows) {
	        	$invitee_id = $shows->showtemp->user_id;
	        	$stemplate_id =$shows->showtemp->template_id;
				if(find_key_value($arrMerge, "invitee_id", $invitee_id) && find_key_value($arrMerge, "template_id", $stemplate_id)){
        		
	        	}else{
						$index = $counter+$loop;
	        			$arrMerge[$index]["invitee_id"]="$invitee_id";
						$arrMerge[$index]["template_id"]=$stemplate_id;
	        	}
				
			}
		}

		//dd($arrMerge);
		return $arrMerge;

	}


}
//User have access to whcih templates
function user_template_accessable($user_id=0){
	//If it is logged in user.
	
	// if ($user_id == 0) {
	// 	$user_id   = \Auth::user()->id;
 //        $useremail = \Auth::user()->email;
 //        $arrayInvited = InvitedUser::where('email',$useremail)->groupBy("template_id")->pluck('template_id')->toArray();
 //        $arrayParticipants = Participant::where('email',$useremail)->groupBy("template_id")->pluck('template_id')->toArray();
	// 	return $finalArray = array_unique(array_merge($arrayInvited, $arrayParticipants));
	// }

	//Getting app wise (22-02-2018)
	if ($user_id == 0) {
		$user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;
        $arrayInvit = array();
        $arrayInvited = InvitedUser::where('email',$useremail)
        					->whereHas('template',function($query){
        							$query->where('blog_type',TIMELINE_BLOG_TEMPLATEWISE);
        					})->pluck("template_id")->toArray();
        $arrayParticipants = Participant::select("invitee_id","template_id")->where('email',$useremail)
        					->whereHas('hastemplate',function($query){
        							$query->where('blog_type',TIMELINE_BLOG_TEMPLATEWISE);
        					})->groupBy('template_id', 'invitee_id')->get()->toArray();
		$finalArray = array();
		$templateWise = array();
        foreach ($arrayInvited as $index => $template_id) {
        	//$index = (int)$user_id."-".(int)$template_id;
        	if(find_key_value($arrayParticipants, "template_id", $template_id)){
        		
        	}else{
        		//if (getTemplateBlogType($template_id)) {
        			$arrayInvit[$index]["invitee_id"]="$user_id";
        			$arrayInvit[$index]["template_id"]=$template_id;
        		// }else{
        		// 	 if(!in_array($template_id, $templateWise)){
        		// 	 	$templateWise[]=$template_id;
        		// 	 }
        		// }
        		
        	}
        }
		$arrMerge = array_merge($arrayInvit, $arrayParticipants);
		//$arrMerge = array_merge($arrayInvit, $arrayParticipants,$templateWise);
        //dd($arrMerge);
        //getting the trainers participation
		$mst = ManageShowTrainer::with('showtemp')->where('user_id',$user_id)->whereHas('showtemp.template',function($query){
        							$query->where('blog_type',TIMELINE_BLOG_TEMPLATEWISE);
        					})->get();
		if(count($mst)>0){
			$counter = count($arrMerge);
			foreach ($mst as $loop => $shows) {
	        	$invitee_id = $shows->showtemp->user_id;
	        	$stemplate_id =$shows->showtemp->template_id;
				if(find_key_value($arrMerge, "invitee_id", $invitee_id) && find_key_value($arrMerge, "template_id", $stemplate_id)){
        		
	        	}else{
						$index = $counter+$loop;
	        			$arrMerge[$index]["invitee_id"]="$invitee_id";
						$arrMerge[$index]["template_id"]=$stemplate_id;
	        	}
				
			}
		}
		
		return $arrMerge;
		//$finalArray = array_combine($arrayInvited, $arrayParticipants);
		//$finalArray = array_unique(array_merge($arrayInvited, $arrayParticipants));
	}

	//Else not logged in. Have to check for the id supplied

}
function find_key_value($array, $key, $val)
{
    foreach ($array as $item)
    {
        if (is_array($item) && find_key_value($item, $key, $val)) return true;

        if (isset($item[$key]) && $item[$key] == $val) return true;
    }

    return false;
}
function getTemplateBlogType($template_id){
	$template = Template::find($template_id);
	if (isset($template) && $template->blog_type == 1) {
		return true;
	}else{
		return false;
	}
}
//this function tells that which user is related to other users.
function user_template_friends($user_id=0){
	//If it is logged in user.
	if ($user_id == 0) {
		$user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;

        //Get Invitee user who has invited current user on template
        $InviteeUsers =  Participant::select('invite_asociated_key')
        ->where('email',$useremail)
        ->groupBy("invitee_id")->get()
        ->toArray();
        //Invitee.
        $arrayInvited = InvitedUser::select('invited_by','email')
        ->where('email',$useremail)
        ->orWhere('invited_by',$user_id)
        ->groupBy("invited_by")->get()
        ->toArray();
        //Participants.
        //\DB::enableQueryLog();
        $arrayParticipants = Participant::select('invitee_id','email')
        ->where('status',1)
        ->where(function ($query) use ($useremail,$user_id){
    			$query->where('email',$useremail)
        		->orWhere('invitee_id',$user_id);
			})->orWhere(function ($query) use ($InviteeUsers){
    			$query->whereIn('invite_asociated_key',array_column($InviteeUsers, 'invite_asociated_key'));
			})->groupBy("email")->get()->toArray();

        //Find users relation with invitee or participants.
        $users = User::where('id',$user_id)
        ->orWhereIn('id',array_column($arrayParticipants, 'invitee_id'))
        ->orWhereIn('email',array_column($arrayParticipants, 'email'))
        ->orWhereIn('id',array_column($arrayInvited, 'invited_by'))
        ->orWhereIn('email',array_column($arrayInvited, 'email'))
        ->pluck('id')->toArray();
		//Return Users who are in relation with the current user.
		return $users;
	}
	//Else not logged in. Have to check for the id supplied

}
//this function tells that which user is related to other users.
function user_app_friends($user_id=0){
	//If it is logged in user.
	if ($user_id == 0) {
		$user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;

        //Get Invitee user who has invited current user on template
       $InviteeUsers =  Participant::select('invitee_id','template_id')
        ->where('email',$useremail)
        ->groupBy("invitee_id","template_id")->get()
        ->toArray();
      	$condition = "";
      	foreach($InviteeUsers as $index => $participant ){
			if ($index == 0) {
      			$condition .= "(invitee_id =".$participant["invitee_id"]." AND template_id =".$participant["template_id"].")";
      		}else{
      			$condition .= "OR (invitee_id =".$participant["invitee_id"]." AND template_id =".$participant["template_id"].")";
      		}
 
      	}
      	if ($condition == "") {
      		// $participants = Participant::select("email")
      		// 	->groupBy("email")
      		// 	->get()
      		// 	->toArray();
      		$participants = array();
      	}else{
      		$participants = Participant::select("email")
      			->whereRaw($condition)
      			->groupBy("email")
      			->get()
      			->toArray();
      	}

      	
      	// Commented on 13/11/2018 --> We are not considering invited users. 
      	
      	//Invited user
      	// $arrayInvited = InvitedUser::select('invited_by','email')
       //  ->where('email',$useremail)
       //  ->orWhere('invited_by',$user_id)
       //  ->groupBy("invited_by")->get()
       //  ->toArray();

        //Find users relation with invitee or participants.
        $users = User::where('id',$user_id)
        ->orWhereIn('email',$participants)
        // Commented on 13/11/2018 --> We are not considering invited users. 
        //->orWhereIn('id',array_column($arrayInvited, 'invited_by')) 
        //->orWhereIn('email',array_column($arrayInvited, 'email'))
        ->pluck('id')->toArray();
		//Return Users who are in relation with the current user.
		return $users;
	}
	//Else not logged in. Have to check for the id supplied

}
//Check if url Exists
function is_url_exist($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($code == 200){
       $status = true;
    }else{
      $status = false;
    }
    curl_close($ch);
   return $status;
}
//get Storage Disk
function getStorageDisk(){
	return Storage::disk('s3');
}
//Get Previous Images Array
function getPreviousArray($answer){
	$disk = getStorageDisk();
	$imageArray = "[";
if($answer) {
    foreach ($answer as $imageURL) {
        $imageArray .= "'" . $disk->url($imageURL) . "'" . ',';
    }
}
	$imageArray .="]";
	return $imageArray;
}
//get image path from s3
function getImageS3($image){
	$disk = getStorageDisk();
	if($disk->exists($image)) {
		return $imageArray = $disk->url($image);
	}else{
		return asset($image);
	}
}
//Get previous Video or attachment form S3
function getPreviousVidAta($answer){
	$disk = Storage::disk('s3');
	$imageArray = "[";
	$imageArray .= "'".$disk->url($answer)."'".',';
	$imageArray .="]";
	return $imageArray;
}
//Get the answer array
// function GetAnswersArray($answerfields,$question){
// 	$Answer_array = json_decode($answerfields->fields,true);
// 	  $indexer = array_search($question->unique_id, array_column($Answer_array, 'form_field_id'));
// 	  if(isset($Answer_array[$indexer]['answer'])){
// 	      $answer = $Answer_array[$indexer]['answer'];
// 	  }else{
// 	      $answer = "";
// 	  }
// 	  return $answer;
// }
function GetUserNameArray($user_id,$answerfields,$question){
	$Answer_array = json_decode($answerfields->fields,true);
	$duplicateArray = array_where($Answer_array, function ($value, $key) use ($question) {
	    if (isset($value['duplicated_from'])) {
	    	if ($question->unique_id == $value['duplicated_from']) {
	    		return $value['duplicated_from'];
	    	}
	    }
	});
	$answer = "'".getUserNamefromid($user_id)."',";
	if (!empty($duplicateArray)) {
	  	foreach ($duplicateArray as $duplicat_value) {
	  		$answer .= "' Duplicated ".getUserNamefromid($user_id)."',";
	  	}
	 }
	return $answer;

}
//Will return the index for the horse
function ExistingHorses($participatedHorses,$horse_id,$class_id){
	
	foreach ($participatedHorses as $key => $record) {
			if($record['horse_id']==$horse_id && $record['class_id']==$class_id){
			//If Horse already exist
				return true;
			}
	}
	return false;
}

function GetAnswersArray($answerfields,$question,$user_id){

	$Answer_array = json_decode($answerfields->fields,true);
	$userName = getUserNamefromid($user_id);
	  $indexer = array_search($question->unique_id, array_column($Answer_array, 'form_field_id'));
	  if(isset($Answer_array[$indexer]['answer'])){
	  	if($Answer_array[$indexer]['answer'] == "" || !is_numeric($Answer_array[$indexer]['answer'])){ $graph_Answer =0; }else{ $graph_Answer = $Answer_array[$indexer]['answer']; }
	      $answer = "['".$userName."', ".$graph_Answer."],";
	  }else{
	      $answer = "";
	  }
	  $duplicateArray = array_where($Answer_array, function ($value, $key) use ($question) {
		    if (isset($value['duplicated_from'])) {
		    	if ($question->unique_id == $value['duplicated_from']) {
		    		return $value['duplicated_from'];
		    	}
		    }
		});
	   if (!empty($duplicateArray)) {
	  	foreach ($duplicateArray as $duplicat_value) {

	  		$answer .= "['Duplicate ".$userName."', ".$duplicat_value["answer"]."],";
	  	}
	  }
	  return $answer;
}
//Get duplicate and ordinery names
function GetUserNameWithDuplicate($answerfields,$question,$user_id){

	$Answer_array = json_decode($answerfields->fields,true);
	$userName = getUserNamefromid($user_id);
	  $indexer = array_search($question->unique_id, array_column($Answer_array, 'form_field_id'));
	  if(isset($Answer_array[$indexer]['answer'])){
	  	 $answer = "'".$userName."',";
	  }else{
	      $answer = "";
	  }
	  $duplicateArray = array_where($Answer_array, function ($value, $key) use ($question) {
		    if (isset($value['duplicated_from'])) {
		    	if ($question->unique_id == $value['duplicated_from']) {
		    		return $value['duplicated_from'];
		    	}
		    }
		});
	   if (!empty($duplicateArray)) {
	  	foreach ($duplicateArray as $duplicat_value) {

	  		$answer .= " 'Duplicate ".$userName."', ";
	  	}
	  }
	  return $answer;
}
//
function getShowClassTypes($show_id){
	 $classt= ClassTypePoint::where("show_id",$show_id)->get();
	 if (count($classt) > 0) {
	 	$keys = array_pluck($classt, 'class_id');
	 	$values = array_pluck($classt, 'class_type');
	 	$classes = array_combine($keys, $values);
	 	return $classes;
	 }
	 return false;
	 
}

//Get the answer and duplicated answers needed
function GetSimpleAnswersArray($answerfields,$question){
	$Answer_array = json_decode($answerfields->fields,true);
	//dd($Answer_array);
	  $indexer = array_search($question->unique_id, array_column($Answer_array, 'form_field_id'));
	  if(isset($Answer_array[$indexer]['answer'])){
	      $answer = $Answer_array[$indexer]['answer'];
	  }else{
	      $answer = "";
	  }
	  $duplicateArray = array_where($Answer_array, function ($value, $key) use ($question) {
		    if (isset($value['duplicated_from'])) {
		    	if ($question->unique_id == $value['duplicated_from']) {
		    		return $value['duplicated_from'];
		    	}
		    }
		});
	  if (!empty($duplicateArray)) {
	  	foreach ($duplicateArray as $duplicat_value) {
	  		if (isset($duplicat_value["answer"])) {
	  			$answer .= "<br> <b>Duplicated Answer:</b>".$duplicat_value["answer"];
	  		}
	  	}
	  }
	  return $answer;
}
//Get the answer and duplicated answers needed
//function GetSimpleAnswersArray($answerfields,$question){
//	$Answer_array = json_decode($answerfields->fields,true);
//	//dd($Answer_array);
//	  $indexer = array_search($question->unique_id, array_column($Answer_array, 'form_field_id'));
//	  if(isset($Answer_array[$indexer]['answer'])){
//	      $answer = $Answer_array[$indexer]['answer'];
//	  }else{
//	      $answer = "";
//	  }
//	  $duplicateArray = array_where($Answer_array, function ($value, $key) use ($question) {
//		    if (isset($value['duplicated_from'])) {
//		    	if ($question->unique_id == $value['duplicated_from']) {
//		    		return $value['duplicated_from'];
//		    	}
//		    }
//		});
//	  if (!empty($duplicateArray)) {
//	  	foreach ($duplicateArray as $duplicat_value) {
//	  		$answer .= "<br> <b>Duplicated Answer:</b>".$duplicat_value["answer"];
//	  	}
//	  }
//	  return $answer;
//}
//getStoragePath
function getStoragePath($URL){
	$ext = explode('equetica/', $URL ,2);
	if (isset($ext[1])) {
		return $ext[1];
	}else{
		$ext = explode('equetica.s3.amazonaws.com/', $URL ,2);
		return $ext[1];
	}
}
//Get Array for x-axies for charts
function getXOptions($question){
	$arrayX=array();
	// if($question->form_field_type == OPTION_CHECKBOX){
		foreach ($question->form_field_options as $field) {
			$arrayX[] = $field->opt_name;
		}

	// }
	$arrayX = "'".implode("','", $arrayX)."'";
	return $arrayX;
}
//Get Array for x-axies for charts
function getXOptionsSingle($question){
	$arrayX=array();
	//dd($question);
	foreach ($question->form_field_options as $field) {
		$arrayX[] = $field->opt_name;
	}
	$arrayX = "'".implode("','", $arrayX)."'";
	return $arrayX;
}
//Get Data array ready for plot on chart
function getXYSeries($answerfields,$question){
	$arrayX=array();
	$Answer_array = json_decode($answerfields->fields,true);
	$Field_indexer = array_search($question->unique_id, array_column($Answer_array, 'form_field_id'));
	//Get All Options form Question and compairing the answers

	foreach ($question->form_field_options as $field) {
        if ($question->form_field_type == OPTION_DROPDOWN) {
			$indexer = 0;
			if (isset($Answer_array[$Field_indexer]['answer'])) {
				$answerExplod = explode("|||", $Answer_array[$Field_indexer]['answer']);
	        	if($field->opt_name == $answerExplod[0]){
	        		$indexer = "answer";
	        	}else{
	        		$indexer = 0;
	        	}
			}

        }elseif($question->form_field_type == OPTION_AUTO_POPULATE){
        	if ($Answer_array[$Field_indexer]["form_field_type"] == OPTION_AUTO_POPULATE && isset($Answer_array[$Field_indexer]["answer"])) {
        		//$matches = preg_grep("/".$field->opt_name."/", $Answer_array[$Field_indexer]['answer']);
	    	 	$matches =searchArrayForVal($field->opt_name, $Answer_array[$Field_indexer]['answer']);
	    	 	if($matches){
		        		$arrayX[] = 1;
			        }else{
			        	$arrayX[] = 0;
			       }
        	}
    		continue;
        }else{
        	$indexer = recursive_array_search($field->opt_name, $Answer_array[$Field_indexer]);
        }

        if(isset($Answer_array[$Field_indexer][$indexer])){
        	$arrayX[] = 1;
        }else{
        	$arrayX[] = 0;
        }
	}
	
	$arrayX = implode(",", $arrayX);
	return $arrayX;

}

function searchArrayForVal($option, $Answer_array) {
   foreach ($Answer_array as $key => $val) {
   	$newBlob = explode("|||", $val);
       if ($newBlob[0] === $option) {
           return $key;
       }
   }
   return null;
}
//function to check if duplicate fileds exists in this form
//function checkDuplicateArray($answerfields,$question){
//	$Answer_array = json_decode($answerfields->fields,true);
//	//duplication
//	  $duplicateArray = array_where($Answer_array, function ($value, $key) use ($question) {
//		    if (isset($value['duplicated_from'])) {
//		    	if ($question->unique_id == $value['duplicated_from']) {
//		    		return true;
//		    		break;
//		    	}
//		    }
//		});
//	  if ($duplicateArray) {
//	  	return true;
//	  }else{
//	  	return false;
//	  }
//}
//function to get graphical values
function getDuplicatedGraphicalVals($answerfields,$question){
	$Answer_array = json_decode($answerfields->fields,true);
	$retDuplicateArray = "";
	//duplication
	  $duplicateArray = array_where($Answer_array, function ($value, $key) use ($question) {
		    if (isset($value['duplicated_from'])) {
		    	if ($question->unique_id == $value['duplicated_from']) {
		    		return $value['duplicated_from'];
		    	}
		    }
		});
	  if (!empty($duplicateArray)) {
	  	foreach ($duplicateArray as $duplicat_value) {

	  		foreach ($question->form_field_options as $field) {
		        if ($question->form_field_type == OPTION_DROPDOWN) {
					$indexer = 0;
					if (isset($duplicat_value['answer'])) {
						$answerExplod = explode("|||",$duplicat_value['answer']);
			        	if($field->opt_name == $answerExplod[0]){
			        		$indexer = "answer";
			        	}else{
			        		$indexer = 0;
			        	}
					}

		        }elseif($question->form_field_type == OPTION_AUTO_POPULATE){
		        	if ($duplicat_value["form_field_type"] == OPTION_AUTO_POPULATE && isset($duplicat_value["answer"])) {
		        		 	//$matches = preg_grep("/".$field->opt_name."/", $duplicat_value["answer"]);
			    	 	$matches =searchArrayForVal($field->opt_name, $duplicat_value["answer"]);
			    	 	if($matches){
				        		$arrayX[] = 1;
					        }else{
					        	$arrayX[] = 0;
					       }
		        	}
		    		continue;
		        }else{
		        	$indexer = recursive_array_search($field->opt_name, $duplicat_value);
		        }

		        if(isset($duplicat_value[$indexer])){
		        	$arrayX[] = 1;
		        }else{
		        	$arrayX[] = 0;
		        }
			}//second foreach end
		        if (!isset($arrayX)) {
					$retDuplicateArray .= "{name:' Duplicate - ".getUserNamefromid($answerfields->user_id)."',"."data: []},";
		        }else{
		        	$clutch = implode(",", $arrayX);
					$retDuplicateArray .= "{name:' Duplicate - ".getUserNamefromid($answerfields->user_id)."',"."data: [".$clutch."]},";
			  		$arrayX = "";
		        }
			
	  	}//outer foreach
	  }
	  return $retDuplicateArray;
}

//Restricted dates
function restrictedScheduledDates($form_id,$show_id){


    //$user_id = \Auth::user()->id;
    $isEmail = \Session('isEmployee');
    $userEmail = \Auth::user()->email;


    if($isEmail==1) {

        $template_id = \App\ManageShows::where('id',$show_id)->pluck('template_id')->first();

        $user_id = getAppOwnerId($userEmail,$template_id);
        $employee_id = \Auth::user()->id;
    }
    else {
        $user_id = \Auth::user()->id;
        $employee_id = 0;
    }

	$scheduled = Schedual::where('form_id',$form_id)
        ->where('user_id',$user_id)
        ->where('show_id',$show_id);

	return $scheduled;
}
//Champion Division
function getAllRankResponseChampionDiv($asset_id, $template_id ,$show_id,$horse_id,$classes=0){
	 $TotalUserScore = 0;
	 //$participantResponse = ParticipantResponse::where("asset_id",$asset_id)->where('template_id',$template_id)->get();
	 // foreach ($participantResponse as $pResponse) {
	 //    $TotalUserScore= $TotalUserScore + getRankingOfResponse($pResponse);
	 // }
	 $TotalUserScore= getPointsOfParticipationClass($horse_id,$show_id,$classes);
	 return $TotalUserScore;
}
//Get all the ranking for the user form different forms
function getAllRankResponseCumulative($template_id,$asset_id,$form_id = null){
	$invitee_id = \Auth::user()->id;
    $TotalUserScore = 0;
	    if ($form_id != null) {
	    	$participantResponse = ParticipantResponse::where('template_id',$template_id)->where("asset_id",$asset_id)->where('form_id',$form_id)->get();
	    }else{
	    	if ($template_id == 0) {
	    		$participantResponse = ParticipantResponse::where("asset_id",$asset_id)->get();
	    	}else{
	    		$participantResponse = ParticipantResponse::where('template_id',$template_id)->where("asset_id",$asset_id)->get();
	    	}
	    }
	    foreach ($participantResponse as $pResponse) {
	    	$TotalUserScore= $TotalUserScore + getRankingOfResponse($pResponse);
	    }
	return $TotalUserScore;
}
//Participating price
function getParticipatingPrice($pResponse,$show){
	if ($show->show_type == 'Western') {
		return $pResponse->ShowClassPrice->price+$pResponse->ShowClassPrice->price_judges;
	}else{
		return $pResponse->ShowClassPrice->price;
	}
}
//Points for participating in show
function getPointsOfParticipation($horse_id,$show_id=0){
		//Getting points on participating in the show.
	    if ($show_id != 0) {
	    	$s_horses = ClassHorse::with("show.types")->where("show_id",$show_id)->where('horse_id',$horse_id)->groupBy('show_id')->get();
	    }else{
	    	$s_horses = ClassHorse::with("show.types")->where('horse_id',$horse_id)->groupBy('show_id')->get();
	    }
	    $TotalUserScore = 0;
	    foreach ($s_horses as $showPoints) {
	    	//Adding points to participate in show.
	    	if (isset($showPoints->show->types)) {
	    		$TotalUserScore = $TotalUserScore+$showPoints->show->types->points;
	    	}
	    }
	   	if ($show_id != 0) {
	   		$c_horses = ClassHorse::with("showsclass.type","positions")->where("show_id",$show_id)->where('horse_id',$horse_id)->groupBy('class_id')->get();
	    }else{
	    	$c_horses = ClassHorse::with("showsclass.type","positions")->where('horse_id',$horse_id)->groupBy('class_id')->get();
		}

	    //Adding class points
 		foreach ($c_horses as $classPoints) {
	    	if (isset($classPoints->positions)) {
	    		$positions = json_decode($classPoints->positions->position_fields,true);
	    			//If Show Class type is selected
	    		if(isset($classPoints->showsclass->type)){
	    			$TypePositionFields = json_decode($classPoints->showsclass->type->position_fields,true);
					foreach($positions as $key => $horse){
		    			if(isset($horse["horse_id"])){
		    				if($horse["horse_id"]==$horse_id){
		    					if (is_array($TypePositionFields)) {
		    					    if(isset($horse["position"])) {
                                        $key = recursive_array_search($horse["position"], $TypePositionFields);
                                        //$key = array_search($horse["position"], array_column($TypePositionFields, 'position'));
                                        if (isset($TypePositionFields[$key])) {
                                            $TotalUserScore = $TotalUserScore + $TypePositionFields[$key]["price"];
                                        }
                                    }
		    					    }
		    				}
		    			}
		    		}
	    		}

	    		//$TotalUserScore = $TotalUserScore+$showPoints->show->types->points;
	    	}
	    }
	   // dd($TypePositionFields);
	    return $TotalUserScore;
}
//Points for participating in show
function getPointsOfParticipationClass($horse_id,$show_id=0,$classes){
	//This code is still in BETA mode. Have to clean up all the un-used code once its shifted to real mode.
	   	$TotalUserScore=0;
	   	if ($show_id != 0) {
	   		$c_horses = ClassHorse::with("showsclass.type","positions")->where("show_id",$show_id)->whereIn('class_id',$classes)->where('horse_id',$horse_id)->groupBy('class_id')->get();
	    }else{
	    	$c_horses = ClassHorse::with("showsclass.type","positions")->where('horse_id',$horse_id)->whereIn('class_id',$classes)->groupBy('class_id')->get();
		}
	    //Adding class points
		
		foreach ($c_horses as $classPoints) {
	    		//If Show Class type is selected
	    		if(isset($classPoints->showsclass->type)){
	    			$TypePositionFields = json_decode($classPoints->showsclass->type->position_fields,true);
					if (isset($classPoints->positions)) {
						$positions = json_decode($classPoints->positions->position_fields,true);
						//echo GetAssetNamefromId($horse_id)." ------> ".$TotalUserScore;
						// echo "<pre>";
						// print_r($positions);
						// echo "</pre>";
						$setLoopVal = 0;
						foreach($positions as $kiy => $horse){
			    			if(isset($horse["horse_id"]) && $horse["horse_id"] !=''){
			    				
			    					if (is_array($TypePositionFields)) {
				    					//$keys =recursive_array_search($horse["position"], array_column($TypePositionFields,"position"));
				    					$keys = array_search($horse["position"], array_column($TypePositionFields, 'position'));
										// echo "<pre>";
				    		// 			echo $horse['horse_id'];
				    		// 			echo "<br>";
				    		// 			print_r($horse['position']);
				    		// 			print_r(array_column($TypePositionFields, 'position'));
										// echo "</pre>";
										// echo "--------------$keys-------------------------------------";
										
				    					if($horse["horse_id"]==$horse_id){
					    					 if (is_numeric($keys)) {
					    					 	$keys= $keys+1;
					    					 }
					    					 if (isset($TypePositionFields[$keys])) {
					    					 	$TotalUserScore = $TotalUserScore+$TypePositionFields[$keys]["price"];
			    								//echo "$TotalUserScore";
			    								$setLoopVal = 1;
			    								continue;
					    					 }
				    					}
				    					 if(!$keys){
				    						
				    					 	//dd($kiy);
				    					 	//$TotalUserScore = $TotalUserScore+$TypePositionFields["others"]["price"];
			    							$setLoopVal = 1;
				    					 }
				    				}

			    			}
			    		}
			    		//If No position is found for horse. 
			    		if($setLoopVal == 0){
			    			$TotalUserScore = $TotalUserScore+$TypePositionFields["others"]["price"];
			    		}
	    		}else{
			    		$TotalUserScore = $TotalUserScore+$TypePositionFields["others"]["price"];
	    		}
	    	}
	    }
	    return $TotalUserScore;
}
//Points for Trainers 
function getTrainerFeedbackPoints($horse_id){
		$invitee_id = \Auth::user()->id;
    	$TotalUserScore = 0;
	    
	    $participantResponse = SchedulerFeedBacks::where("horse_id",$horse_id)->get();
	    foreach ($participantResponse as $pResponse) {
	    	$TotalUserScore= $TotalUserScore + getRankingOfResponse($pResponse);
	    }
	return $TotalUserScore;
}


//Get all the ranking for the user form different forms
function getAllRankResponse($template_id,$user_id,$form_id = null){
	$invitee_id = \Auth::user()->id;
    $TotalUserScore = 0;
	    if ($form_id != null) {
	    	$participantResponse = ParticipantResponse::select("id","user_id")->where('template_id',$template_id)->where('form_id',$form_id)->where("user_id",$user_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id) {
                $query->where('invitee_id', $invitee_id);
            })->get();
	    }else{
	    	$participantResponse = ParticipantResponse::select("id","user_id")->where('template_id',$template_id)->where("user_id",$user_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id) {
	                $query->where('invitee_id', $invitee_id);
	            })->get();
	    }
	    foreach ($participantResponse as $pResponse) {
	    	$TotalUserScore= $TotalUserScore + getRankingOfResponse($pResponse->id);
	    }
	return $TotalUserScore;
}
//Get all the ranking for the participant form different forms
function getParticipantRankResponse($asset_id,$invitee_id,$template_id,$user_id,$form_id = null){
    $TotalUserScore = 0;
	    if ($form_id != null) {
	    	$participantResponse = ParticipantResponse::select("id","user_id")->where('template_id',$template_id)->where('form_id',$form_id)->where("user_id",$user_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id,$asset_id) {
                $query->where('invitee_id', $invitee_id);
                $query->where('asset_id', $asset_id);
            })->get();
	    }else{
	    	$participantResponse = ParticipantResponse::select("id","user_id")->where('template_id',$template_id)->where("user_id",$user_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id,$asset_id) {
	                $query->where('invitee_id', $invitee_id);
	                $query->where('asset_id', $asset_id);
	            })->get();
	    }
	    foreach ($participantResponse as $pResponse) {
	    	$TotalUserScore= $TotalUserScore + getRankingOfResponse($pResponse->id);
	    }
	return $TotalUserScore;
}
//Get Ranking of the form.
function getRankingOfResponse($response_id){
		if (is_object($response_id)) {
			$participantResponse = $response_id;
		}else{
			$participantResponse = ParticipantResponse::where("id",$response_id)->first();
		}
		
		$TotalRanking = 0;
		$fields = json_decode($participantResponse->fields,true);
		//dd($fields);
		if (isset($fields)) {
			foreach ($fields as $field) {
			if (isset($field["form_field_type"])) {
					if($field["form_field_type"] == OPTION_RADIOBUTTON){
						$found_key = WfindKey($field,"answer");
						if ($found_key) {
							$TotalRanking += (float)$field[$found_key]['opt_weightage'];
						}
					}
					if($field["form_field_type"] == OPTION_DROPDOWN){
						if (isset($field["answer"])) {
							$answerExplod = explode("|||", $field["answer"]);
							if (isset($answerExplod[1])) {
								if($answerExplod[1] != "");
								$TotalRanking += (float)$answerExplod[1];
							}
							
						}
					}
					if($field["form_field_type"] == OPTION_AUTO_POPULATE){
						if (isset($field["answer"])) {
							foreach ($field["answer"] as $option) {
								$answerExplod = explode("|||", $option);
								if (isset($answerExplod[1])) {
									if($answerExplod[1] != "");
									$TotalRanking = $TotalRanking+(float)$answerExplod[1];
								}
							}
						}
					}
					if($field["form_field_type"] == OPTION_CHECKBOX){
						$found_key = WsfindKey($field,"answer");
						if ($found_key) {
							foreach ($found_key as $keys) {
								$TotalRanking = $TotalRanking + (float)$field[$keys]['opt_weightage'];
							}
						}
					}
			}
		}
		}
		
		return $TotalRanking;
}
//Weight of array for radio button
function WfindKey($array, $keySearch)
{
    foreach ($array as $key => $item) {
        if ($key == $keySearch) {
            return $key;
        }
        else {
            if (is_array($item) && WfindKey($item, $keySearch)) {
               return $key;
            }
        }
    }
    return false;
}
//Weight of array for multiselect
function WsfindKey($array, $keySearch)
{
    foreach ($array as $key => $item) {
        if ($key == $keySearch) {
            $keys[]=$key;
        }
        else {
            if (is_array($item) && WfindKey($item, $keySearch)) {
               $keys[] = $key;
            }
        }
    }
    if (!empty($keys)) {
    	return $keys;
    }else{
    	return false;
    }
    return false;
}
//Get label for master template
function getButtonLabelFromTemplateId($template_id,$fieldtype=null){

	$TBlabel = TemplateButtonLabel::where('template_id',$template_id)->first();
        if ($fieldtype=='ya_fields' ||$fieldtype==null) {
	        $ya_fields = null;
	        if ($TBlabel != null) {
	            $ya_fields = json_decode($TBlabel->ya_fields, true);
	        }
	        return $ya_fields;
        }
        if ($fieldtype=='ia_fields') {
	        $ia_fields = null;
	        if ($TBlabel != null) {
	            $ia_fields = json_decode($TBlabel->ia_fields, true);
	        }
	        return $ia_fields;
        }
        if ($fieldtype=='s_fields') {
	        $s_fields = null;
	        if ($TBlabel != null) {
	            $s_fields = json_decode($TBlabel->s_fields, true);
	        }
	        return $s_fields;
        }

    if ($fieldtype=='m_s_fields') {
        $m_s_fields = null;
        if ($TBlabel != null) {
            $m_s_fields = json_decode($TBlabel->m_s_fields, true);
        }
        return $m_s_fields;
    }
    if ($fieldtype=='i_p_fields') {
        $i_p_fields = null;
        if ($TBlabel != null) {
            $i_p_fields = json_decode($TBlabel->i_p_fields, true);
        }
        return $i_p_fields;
    }


}

//Geting isset values for admin button labels
function post_value_or($fields, $key, $default = NULL) {
    return isset($fields[$key]) && !empty($fields[$key]) ? $fields[$key] : $default;
}
function post_description_or($fields, $key, $default = NULL) {
    return isset($fields[$key]) && !empty($fields[$key]) ? $fields[$key] : $default;
}

//Get all Templates for user
function getAllTemplatesNames($template_id){
	$user_id   = \Auth::user()->id;
    $useremail = \Auth::user()->email;
    $Names = "<br>";
	$participant_collection = Participant::where('email',$useremail)->where('template_id',$template_id)->where('status',1)->groupBy('invitee_id')->get();
	foreach ($participant_collection as $app) {
		$Names .= '<p>'.GetTemplateName($app->template_id,$app->invitee_id)." -- <span style='color:green'>".getUserNamefromid($app->invitee_id)."</span></p>";
	}
	return $Names;
}

//Get Profile Forms for Master template
function getFormsForProfile($template_id,$accessable_to=1,$invited_forms = null){


	if ($invited_forms == null) {
		$Form = Form::where('template_id',$template_id)->where('form_type',PROFILE_ASSETS)->where('accessable_to',$accessable_to)->get();
	}else{
		$Form = Form::where('template_id',$template_id)->where('form_type',PROFILE_ASSETS)->where('accessable_to',$accessable_to)->whereIn('id',$invited_forms)->get();
	}
	return $Form;
}


function getFeedBackForFacility($template_id){
    return $Form = Form::where('template_id',$template_id)->where('form_type',FEEDBACK)->get();
}


/**************calendar function updated by shakil***********************/





function getCalendarEvents($FormTemplate, $scheduals,$isMultpileClasses,$templateId,$dateFrom=null)
{

    $events = [];
    $date=[];


    $scrollTime = '';
    $alreadyExist = [];
    if(!is_null($dateFrom))
        $scrollTime = last(explode(' ',$dateFrom));


    if($isMultpileClasses > 1)
    {
        foreach ($FormTemplate as $row) {

            $restriction = $row->restriction;
            $slots_duration = $row->slots_duration;
            if($slots_duration==0 && $slots_duration=='')
                $slots_duration = 5;

            if ($restriction) {

                $var = explode('-', $restriction);

                if (count($var) > 0) {
                    $timeFrom[] = date('H:i', strtotime($var[0]));
                    $timeTo[] = date('H:i', strtotime($var[1]));
                    $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));
                    $dateTo = date('Y-m-d H:i:s', strtotime($var[1]));
                    $date[] =['dateFrom'=>date('Y-m-d H:i:s', strtotime($var[0])),'dateTo'=>date('Y-m-d H:i:s', strtotime($var[1]))];
                }
            }

        }

        if(count($date)>0) {

            $commonDates = checkIfOverlapped($date);

            $date[] =['dateFrom'=>date('Y-m-d H:i:s', strtotime($commonDates['dateFrom'])),'dateTo'=>date('Y-m-d H:i:s', strtotime($commonDates['dateTo']))];

           // ArrayPrint($commonDates[]);
            $events[] = \Calendar::event(
                'Multiple Scheduler',
                false,
                $commonDates['dateFrom'],
                $commonDates['dateTo'],
                '',
                [
                    'rendering' => 'background',
                    "slots_duration"=>$slots_duration,
                    "is_multiple_selection"=>$row->is_multiple_selection,
                    "restriction_id"=>$row->id,
                    "is_rider_restricted"=>$row->is_rider_restricted,
                    "class_group_key"=>$row->scheduler_key,
                    "is_group"=>$row->is_group,

                ]
            );

            if($row->block_time!='') {
                $dragVar = explode('-', $row->block_time);

                $dragDateFrom = date('Y-m-d H:i:s', strtotime($dragVar[0]));
                $dragDateTo = date('Y-m-d H:i:s', strtotime($dragVar[1]));

                if ($row->block_time_title != '')
                    $blockTile = $row->block_time_title;
                else
                    $blockTile = "Blocked Time";

                if (!in_array($dragDateFrom, $alreadyExist)) {
                    $events[] = \Calendar::event(
                        $blockTile,
                        false,
                        $dragDateFrom,
                        $dragDateTo,
                        $row->show_id,
                        [
                            'description' => '<h6 style="margin-top: 10px; color:#FFF; align-content: center;text-transform: capitalize">' . $row->block_time_title . '</h6>',
                            'backgroundColor' => '#000000',
                            'is_drag' => 1,
                            "eventOverlap" => true
                        ]
                    );
                    $alreadyExist[] = $dragDateFrom;
                }
            }



        }

    }else {
        foreach ($FormTemplate as $row) {
            $slots_duration = $row->slots_duration;

            if($slots_duration==0 && $slots_duration=='')
                $slots_duration = 5;

            $restriction = $row->restriction;
//exit;
            if ($restriction) {

                $var = explode('-', $restriction);

                if (count($var) > 0) {
                    $timeFrom[] = date('H:i', strtotime($var[0]));
                    $timeTo[] = date('H:i', strtotime($var[1]));
                    $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));
                    $dateTo = date('Y-m-d H:i:s', strtotime($var[1]));
                    $date[] = ['dateFrom' => date('Y-m-d H:i:s', strtotime($var[0])), 'dateTo' => date('Y-m-d H:i:s', strtotime($var[1]))];

                    $templateType = GetTemplateType($templateId);

                    $schedulerTitle = getSchedulerName($row->scheduler_id);

                    if($row->is_multiple_selection==1)
                    {

                        $events[] = \Calendar::event(
                            'Multiple Scheduler',
                            false,
                            $dateFrom,
                            $dateTo,
                            $row->scheduler_id,
                            [
                                'rendering' => 'background',
                                "slots_duration"=>$slots_duration,
                                "is_multiple_selection"=>$row->is_multiple_selection,
                                "restriction_id"=>$row->id,
                                "is_rider_restricted"=>$row->is_rider_restricted,
                                "class_group_key"=>$row->scheduler_key,
                                "is_group"=>$row->is_group,

                            ]
                        );


                    }else {
                        $events[] = \Calendar::event(
                            $schedulerTitle,
                            false,
                            $dateFrom,
                            $dateTo,
                            $row->scheduler_id,
                            [
                                'rendering' => 'background',
                                "slots_duration" => $slots_duration,
                                "is_multiple_selection" => $row->is_multiple_selection,
                                "restriction_id" => $row->id,
                                "is_rider_restricted"=>$row->is_rider_restricted,
                                "class_group_key"=>$row->scheduler_key,
                                "is_group"=>$row->is_group,



                            ]
                        );
                    }
                    if($row->block_time!='') {
                        $dragVar = explode('-', $row->block_time);

                        $dragDateFrom = date('Y-m-d H:i:s', strtotime($dragVar[0]));
                        $dragDateTo = date('Y-m-d H:i:s', strtotime($dragVar[1]));

                        if ($row->block_time_title != '')
                            $blockTile = $row->block_time_title;
                        else
                            $blockTile = "Blocked Time";

                        if (!in_array($dragDateFrom, $alreadyExist)) {
                            $events[] = \Calendar::event(
                                $blockTile,
                                false,
                                $dragDateFrom,
                                $dragDateTo,
                                $row->show_id,
                                [
                                    'description' => '<h6 style="margin-top: 10px; color:#FFF; align-content: center;text-transform: capitalize">' . $row->block_time_title . '</h6>',
                                    'backgroundColor' => '#000000',
                                    'is_drag' => 1,
                                    "eventOverlap" => true
                                ]
                            );
                            $alreadyExist[] = $dragDateFrom;
                        }
                    }
                }

            } else {

                $dateFrom = date('Y-m-d H:i:s');
                $events[] = \Calendar::event(
                    $row['name'],
                    false,
                    '2017-4-21T10:00:00',
                    '2030-4-21T9:00:00',
                    0,
                    [
                        'rendering' => 'background',
                        "restrictionType" => 1,
                        "slots_duration"=>$slots_duration,
                    ]
                );
            }

        }
    }


    $user_id = session('subParticipantInviteeId');

    if($user_id!='')
    {
        $user_id = $user_id;
    }else {
        $user_id = \Auth::user()->id;
    }


//    $user_id = \Auth::user()->id;

  //  dd($scheduals->toArray());
    $userContainsArray=[];

    foreach ($scheduals as $row1) {
        $pre_fields1 = json_decode($row1['time_slot'], true);



        $horse_rating_type = Asset::where('id',$row1['asset_id'])->pluck('horse_rating_type')->first();

        $isCombined = CombinedClass::where('class_id',$row1['asset_id'])->count();

       // $score = getScoreValues($row1['asset_id'],$row1['show_id'],$row1['horse_id']);


        $is_rider_restricted = getRiderRestrcition($row1['restriction_id']);


        if($row1['sub_participant_id']>0)
        {
             $userName =  getUserNamefromid($row1['sub_participant_id']);
             $userId =$row1['sub_participant_id'];
            $current_user_id =$row1['user_id'];

        }else
        {
            $userName =  getUserNamefromid($row1['user_id']);
            $current_user_id =$row1['user_id'];

            $userId =$row1['user_id'];
        }


        for ($j = 0; $j < count($pre_fields1); $j++) {
            $var1 = explode('-', $pre_fields1[$j]);

            if (count($var1) > 0) {
                $timeFrom[] = date('H:i', strtotime($var1[0]));
                $timeTo[] = date('H:i', strtotime($var1[1]));
                $dateFrom = date('Y-m-d H:i:s', strtotime($var1[0]));
                  $dateTo = date('Y-m-d H:i:s', strtotime($var1[1]));
                $date[] =['dateFrom'=>date('Y-m-d H:i:s', strtotime($var1[0])),'dateTo'=>date('Y-m-d H:i:s', strtotime($var1[1]))];

                if($row1['horse_id']!='')
                    $horseTitle =  GetAssetNamefromId($row1['horse_id']).'-'.GetHorseRegisteration($row1['horse_id'],$row1['show_id'],1);
                else
                    $horseTitle = '';
                $templateType = GetTemplateType($templateId);
//echo $row1['horse_id'].'>>>>'.$row1['asset_id'].'<br>';
              $horse_rider = getHorsesRiderForScheduler($row1['horse_id'] ,$row1['asset_id']);

               // dd($horse_rider);
                $description = '<span>' . $userName . '</span> 
                                <br /><a class="HorseAsset schedulerLink" target="_blank" style="position: relative; z-index: 99999999999999999999" href="/master-template/' . nxb_encode($row1["horse_id"]) . '/horse-view-profile">' . $horseTitle . '</a>';
//                if($row1['height']!='')
//                    $description .='<br /><strong>Height</strong> '.$row1['height'].'</a>';

                if($horse_rider!='')
                    $description .='<br /><a class="HorseAsset schedulerLink" target="_blank" style="position: relative; z-index: 99999999999999999999" href="/master-template/'.nxb_encode($horse_rider).'/horse-view-profile">'.GetAssetNamefromId($horse_rider).'</a>';


                if ($row1['is_multiple_selection'] == 1) {
                    $countUserInEvent = schedualNotes::where('asset_id', $row1['asset_id'])
                        ->where('show_id', $row1['show_id'])
                        ->where('form_id', $row1['form_id'])
                        ->where('multiple_scheduler_key', '=', $row1['multiple_scheduler_key'])
                        ->where('is_multiple_selection', 1)
                        ->where('user_id', $user_id)
                        ->where('horse_id', $row1['horse_id'])
                        ->count();

                    $participants = '<a style="width:100%;" href="javascript:" onclick="getEventsParticipants(\'' . $row1['show_id'] . '\',\'' . $row1['form_id'] . '\',\'' . $row1['asset_id'] . '\',\'' . $slots_duration . '\',\'' . $dateFrom . '\',\'' . $dateTo . '\',1,\'' . $row1['restriction_id'].'\')" class="viewBtn"   >View participants</a><br>';
                    //if ($countUserInEvent == 0)
                    if($is_rider_restricted==1){
                        $participants .= '<a href="javascript:"  class="viewBtn participantLink">Participate</a>';
                    }else {
                        $participants .= '<a href="javascript:"  onclick="participateInEvent(\'' . $row1['id'] . '\',\'' . $slots_duration . '\',0,1,\'' . $user_id . '\')" class="viewBtn participantLink">Participate</a>';
                    }
                    if($row1['is_mark']==1) {

                            if (!in_array($row1['multiple_scheduler_key'], $userContainsArray)) {

                                $events[] = \Calendar::event(
                                    "Multi Scheduler",
                                    false,
                                    $dateFrom,
                                    $dateTo,
                                    $row1['id'],
                                    [
                                        'description' => $participants,
                                        'notes' => $row1['notes'],
                                        'userId' => $userId,
                                        //'userType' => 'others',
                                        'backgroundColor' => 'green',
                                        'slots_duration' => $slots_duration,
                                        "reason" => $row1['reason'],
                                        "restriction_id" => $row1['restriction_id'],
                                        "is_multiple_selection" => $row1['is_multiple_selection'],
                                        "isMultiple" => 1,
                                        "is_rider_restricted"=>$is_rider_restricted,
                                        "height" => $row1['height'],
                                        "isCombined" => $row1['isCombined'],
                                        "horse_rating_type" => $horse_rating_type,

                                    ]
                                );

                                $userContainsArray[] = $row1['multiple_scheduler_key'];
                            }


                    }else
                    {

                        if (!in_array($row1['multiple_scheduler_key'], $userContainsArray)) {

                            $events[] = \Calendar::event(
                                "Multi Scheduler",
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $participants,
                                    'notes' => $row1['notes'],
                                    'userId' => $userId,
                                    //'userType' => 'others',
                                    'backgroundColor' => 'green',
                                    'slots_duration' => $slots_duration,
                                    "reason" => $row1['reason'],
                                    "restriction_id" => $row1['restriction_id'],
                                    "is_multiple_selection" => $row1['is_multiple_selection'],
                                    "isMultiple" => 1,
                                    "is_rider_restricted"=>$is_rider_restricted,
                                    "height" => $row1['height'],
                                    "isCombined" => $row1['isCombined'],
                                    "horse_rating_type" => $horse_rating_type,

                                ]
                            );

                            $userContainsArray[] = $row1['multiple_scheduler_key'];
                        }

                    }


                }
                elseif ($row1['other_group_Class'] == 1){

                    $events[] = \Calendar::event(
                        $row1['notes'],
                        false,
                        $dateFrom,
                        $dateTo,
                        $row1['id'],
                        [
                            'description' => $description,
                            'notes' => $row1['notes'],
                            'userId' => $userId,
                            "formId" => $row1['form_id'],
                            "scheduler_id" => $row1['schedual_id'],
                            "template_id" => $row1['template_id'],
                            "asset_id" => $row1['asset_id'],
                            "show_id" => $row1['show_id'],
                            "horse_id" => $row1['horse_id'],
                            "reason" => $row1['reason'],
                            'slots_duration' => $slots_duration,
                            "height" => $row1['height'],
                            "isCombined" => $row1['isCombined'],
                            "horse_rating_type"=>$horse_rating_type,
                            "restriction_id" => $row1['restriction_id'],
                            'userType' => 'others',
                            'backgroundColor' => '#000000',
                        ]
                    );
                }

                else{

                    if($row1['is_mark']==1)
                    {

                        if($user_id==$userId) {
                            $events[] = \Calendar::event(
                                $row1['notes'],
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'backgroundColor' => '#2ca02c',
                                    "isMark" => 1,
                                    'notes' => $row1['notes'],
                                    'description' =>$description,
                                    'userId' => $userId,
                                    'horse_id' => $row1['horse_id'],
                                    'asset_id' => $row1['asset_id'],
                                    'show_id' => $row1['show_id'],
                                    'slots_duration'=>$slots_duration,
                                    "reason" => $row1['reason'],
                                    "restriction_id"=>$row1['restriction_id'],
                                    "is_multiple_selection"=>$row1['is_multiple_selection'],
                                    "is_rider_restricted"=>$is_rider_restricted,
                                    "height" => $row1['height'],
                                    "isCombined" => $row1['isCombined'],
                                    "horse_rating_type" => $horse_rating_type,


                                ]
                            );
                        }else {
                            $events[] = \Calendar::event(
                                $row1['notes'],
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'backgroundColor' => '#2ca02c',
                                    "isMark" => 1,
                                    'notes' => $row1['notes'],
                                    'description' =>$description,
                                    'userId' => $userId,
                                    'userType' => 'others',
                                    'horse_id' => $row1['horse_id'],
                                    'asset_id' => $row1['asset_id'],
                                    'show_id' => $row1['show_id'],
                                    'slots_duration'=>$slots_duration,
                                    "reason" => $row1['reason'],
                                    "restriction_id"=>$row1['restriction_id'],
                                    "is_multiple_selection"=>$row1['is_multiple_selection'],
                                    "is_rider_restricted"=>$is_rider_restricted,
                                    "height" => $row1['height'],
                                    "isCombined" => $row1['isCombined'],
                                    "horse_rating_type" => $horse_rating_type,

                                ]
                            );
                        }
                    }
                    else
                    {


                        if($user_id==$current_user_id) {

                            if ($row1['horse_id'] != '')
                                $horseTitle = GetAssetNamefromId($row1['horse_id']);
                            else
                                $horseTitle = '';
                            $events[] = \Calendar::event(
                                $row1['notes'],
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $description,
                                    'notes' => $row1['notes'],
                                    'userId' => $userId,
                                    'userType' => 'participant',
                                    'horse_id' => $row1['horse_id'],
                                    'asset_id' => $row1['asset_id'],
                                    'show_id' => $row1['show_id'],
                                    'slots_duration' => $slots_duration,
                                    "reason" => $row1['reason'],
                                    "restriction_id" => $row1['restriction_id'],
                                    "is_multiple_selection" => $row1['is_multiple_selection'],
                                    "is_rider_restricted"=>$is_rider_restricted,
                                    "height" => $row1['height'],
                                    "isCombined" => $row1['isCombined'],
                                    "horse_rating_type" => $horse_rating_type,

                                ]
                            );
                        }else{
                            $events[] = \Calendar::event(
                                $row1['notes'],
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $description,
                                    'notes' => $row1['notes'],
                                    'userId' => $userId,
                                    'userType' => 'others',
                                    'backgroundColor' => '#000000',
                                    'slots_duration' => $slots_duration,
                                    "reason" => $row1['reason'],
                                    "restriction_id" => $row1['restriction_id'],
                                    "is_multiple_selection" => $row1['is_multiple_selection'],
                                    "is_rider_restricted"=>$is_rider_restricted,
                                    "height" => $row1['height'],
                                    "isCombined" => $row1['isCombined'],
                                    "horse_rating_type" => $horse_rating_type,


                                ]
                            );
                        }
                    }

                }
               }
        }
    }


    if(strpos($slots_duration, ':') !== false) {
        $slots_duration = "00:".$slots_duration;
    } else {
        $slots_duration ="00:".$slots_duration.":00";
    }

    $calendar = \Calendar::addEvents($events); //add an array with addEvents

    $clId= $calendar->getId();

    $calendar->setOptions([ //set fullcalendar options
        'firstDay' => 1,
        'slotDuration'=> $slots_duration,
        'defaultView'=>'agendaDay',
        'axisFormat'=> 'h:mm:ss a',
        'eventLimit' => true,
        'columnFormat'=>'dddd / D',
        'draggable'=>false,
        'editable'=> false,
        'clickable'=>true,
        'eventOverlap'=> false,
        'scrollTime' => $scrollTime,

    ]);
    $calendar->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
     "viewRender"=>"function(view, element) {
     if (view.name === 'month') {
     var arrayFromPHP = ".json_encode($date,true).";

      var formattedEventData = [] ;
            for (var k = 0; k < arrayFromPHP.length; k ++) {
                formattedEventData.push({
                    title: '',
                    start: arrayFromPHP[k][\"dateFrom\"],
                    end:arrayFromPHP[k][\"dateTo\"],
                    rendering:'background',
                    allDay:true,
                  'background' : '#8fdf82'
                });
            }
            
     $('#calendar-".$clId."').fullCalendar('addEventSource',formattedEventData);
  	$('#calendar-".$clId."').fullCalendar('refetchEvents');

   
   }
  
    }",
    'eventClick' => 'function(calEvent, jsEvent, view) {
    if(calEvent.isMultiple==1 || calEvent.isCombined>0 || calEvent.userType=="others")
    {
        return false;
    }
    
        
     if(calEvent.userType==\'others\' )
    {
     $("#eventContent").modal("hide"); // in order to click on horse profile to prevent modal show up
    }
    
     if(calEvent.is_rider_restricted==1)
    {
     alertBox("Show Owner can edit this booking for this Class","TYPE_INFORMATION");   
     return false;
    }
    
   
    
    var dateSelected=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bg td:eq(\'+$(this).closest(\'td\').index()+\')\').data(\'date\');
    
    var backgroundId=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bgevent-skeleton td:eq(\'+$(this).closest(\'td\').index()+\')\').children().children().children().attr(\'id\');
    
    $("#myDiv").html(\'\');
    
    $(".schedule_id").val("");
    $(".notes").val("");
                
    if(calEvent.isMark==1)   
    {
    markDisabled();
    }
    else
    {
    markEnable();
    }

    if(calEvent.endDaterestriction)
    {
        endTime = moment(calEvent.endDaterestriction).format("HH:mm:ss");
        endDate = moment(calEvent.endDaterestriction).format("YYYY/MM/DD");
    }
    else
    {
        if(calEvent.source.origArray)
        {
            var currentSelected=calEvent.source.origArray[0].start;
            var currentTime=parseTimestamp(currentSelected);
            var endTime="23:45";
            var endDate=  moment(calEvent.end).format("YYYY/MM/DD");
        }
        else
        {
            var endDateTime=parseTimestamp(calEvent.end);
            endTime = moment(calEvent.end).format("HH:mm:ss");      
            endDate = moment(calEvent.end).format("YYYY/MM/DD") ; 
        }
    }  
    var startDate=moment(calEvent.start).format("YYYY/MM/DD");
    var startTime=moment(calEvent.start).format("HH:mm:ss");
        
       if(calEvent.reason == null)
        {
        $(".ReasonCon").addClass(\'hide\');
        $(".reason").attr(\'required\',false);
        }
       else
       {
        $(".ReasonCon").removeClass(\'hide\');
         $(".reason").val(calEvent.reason)
        $(".reason").attr(\'required\',false);
       }
   
       $(document).on("click",".HorseAsset", function () {
     $("#eventContent").modal("hide"); // in order to click on horse profile to prevent modal show up
    });
   
      
    $("#eventContent").modal("show");
    $("#eventContent").addClass("show");
    $(".notes").val(calEvent.notes);
    $("#height").val(calEvent.height);

    $(".schedule_id").val(calEvent.id);    
   
        if(calEvent.horse_rating_type==1)
        {
        $(".scoreCon").hide();
        $(".score").html(" ");
        }else
        {
          getScoreForScheduler(calEvent.asset_id,calEvent.show_id,calEvent.horse_id,calEvent.restriction_id,calEvent.form_id);
        }
   
       $("#restriction_id").val(calEvent.restriction_id);    
    $("#is_multiple_selection").val(calEvent.is_multiple_selection);    
   
    $(".backgrounbdSlotId").val(backgroundId);
    
    $(".startTime").val(moment(calEvent.start).format("YYYY/MM/DD HH:mm:ss"));    
    $(".endTime").val(endDate+" "+endTime);   
    $(".markDone").show();
     
    // getHorseAssets(calEvent.asset_id,calEvent.show_id,calEvent.userId);
     getHorseName(calEvent.horse_id);
       var slots_Time = calEvent.slots_duration;

       if(Number.isInteger(slots_Time))
       {
         var slots_duration = parseInt(slots_Time*60);
       }else
       {
          if (slots_Time.indexOf(\':\') > -1)
        {
            var segments =  slots_Time.split(\':\');
            var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])
        }else
        {
            return false;
        }
       
       }
         
    setTimeout(function () {
        $("select[name=ClassHorse]").val(calEvent.horse_id);
        $(".ClassHorse").selectpicker("refresh")
    },700)

    populate2(startTime,endTime,startDate,endDate,moment(calEvent.end).format("HH:mm:ss"),0,slots_duration);
    }',
    "dayClick"=>" function(date, allDay, jsEvent, view) 
    {

    if(jsEvent.name == 'month') {
    $('#calendar-".$clId."').fullCalendar('changeView', 'agendaDay');
    $('#calendar-".$clId."').fullCalendar('gotoDate', date);
    return false;
    }
    if (!allDay.target.classList.contains('fc-bgevent')) {
        	alert(\"Please select the date which is available.\");
        }
        $('.schedule_id').val('');
        $('.notes').val('');
        window.selectedTime=date.format(\"YYYY/MM/DD HH:mm:ss a\");    
    }",
    "eventRender"=>" function (calEvent, element)
    {
   
    
        if (calEvent.rendering == 'background')
        {
         if(calEvent.is_multiple_selection=='1'){
                element.addClass('multipleSelection');
            }
            element.append('<h6 id=\"'+calEvent.id+'\">'+calEvent.title+'</h6>');
            $(element).data(calEvent);
        }
        else
        {
        
         
          eventsdate = moment(calEvent.start).format('hh:mm:ss');
          eventedate = moment(calEvent.end).format('hh:mm:ss');
          element.find('.fc-time').html(eventsdate + \" - \" + eventedate + \"<br>\");
        
          
            if(calEvent.userType=='Multiple')
            {
           // element.append('<button title=\"Add another book for this time\" onclick=\"addMoreUsers(event,this)\" class=\"btn addMultiple\"  data-id=\"'+calEvent.id+'\" type=\"button\" id=\"plus\"> + </button>');
            }
          
            if(calEvent.userType!='others' && calEvent.isMark!=1  && calEvent.is_multiple_selection!='1')
            {
                if(calEvent.is_drag!=1)
                     element.append('<span onclick=\"delEvent(event,this)\" data-id=\"'+calEvent.id+'\" class=\"closeon\">X</span>' );
            }

        if(calEvent.description!='' && calEvent.description!='undefined')
        {       
            element.append('<p>'+calEvent.description+'</p>' );
        }
         if(calEvent.isMark==1)
         {
            element.append('<img src=\"/img/check_mark.png\">' );
        }
            element.find('closeon').click(function() {
            $('#calendar').fullCalendar('removeEvents',event._id);
            });
        }
    
    }",
    "eventOverlap"=>" function(stillEvent, movingEvent) 
    {
        return stillEvent.allDay && movingEvent.allDay;
    }",
    "eventMouseover"=>'function(calEvent, jsEvent) 
    {
        $(this).css(\'z-index\', \'10\');
     if(calEvent.title!="")
    {
        var tooltip = \'<div class="tooltipevent" style="width:200px; padding:10px; border:solid 1px #000; border-radius: 5px; color:#000;height:Auto;background:#ccc;position:absolute;z-index:10001;">\' + calEvent.title + \'</div>\';
     //   var $tooltip = $(tooltip).appendTo("body");   
      }
        $(this).mouseover(function(e) {
        $(this).css("z-index", 10000);
       // $tooltip.fadeIn("500");
      //  $tooltip.fadeTo("10", 1.9);
        }).mousemove(function(e) {
      //  $tooltip.css("top", e.pageY + 10);
       // $tooltip.css("left", e.pageX + 20);
        });
       
    }',
    'eventMouseout'=>"function(calEvent, jsEvent) 
    {        
        $(this).css('z-index', 8);
        $('.tooltipevent').remove();
    }",

    ]);

    $arr=[];
    $arr['calendar']=$calendar;
    $arr['clId']=$clId;

    return $arr;

}

function getMasterSchedulerEvents($FormTemplate, $scheduals,$assetId,$template_id,$dateFrom=null)
{
    $events = [];
    $date = [];
    $alreadyExist = [];
    $scrollTime = '';

    if(!is_null($dateFrom))
        $scrollTime = last(explode(' ',$dateFrom));

    $horse_rating_type = Asset::where('id',$assetId)->pluck('horse_rating_type')->first();

    $slots_duration = 5;

    foreach ($FormTemplate as $row) {

        $restriction = $row->restriction;
        $slots_duration = $row->slots_duration;

        $schedulerTitle = getSchedulerName($row->scheduler_id);

        if($slots_duration==0 && $slots_duration=='')
            $slots_duration = 5;
        if ($restriction) {

                $var = explode('-', $restriction);

                if (count($var) > 0) {
                    $timeFrom[] = date('H:i', strtotime($var[0]));
                    $timeTo[] = date('H:i', strtotime($var[1]));
                    $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));
                    $dateTo = date('Y-m-d H:i:s', strtotime($var[1]));
                    $date[] =['dateFrom'=>date('Y-m-d H:i:s', strtotime($var[0])),'dateTo'=>date('Y-m-d H:i:s', strtotime($var[1]))];

                    if($row->is_multiple_selection==1)
                    {
                        $events[] = \Calendar::event(
                            'Multiple Scheduler',
                            false,
                            $dateFrom,
                            $dateTo,
                            $row->scheduler_id,
                            [
                                'rendering' => 'background',
                                "slots_duration"=>$slots_duration,
                                'show_id' => $row->show_id,
                                'form_id' => $row->form_id,
                                "is_multiple_selection"=>$row->is_multiple_selection,
                                "restriction_id"=>$row->id,
                                "class_group_key"=>$row->scheduler_key,
                                "is_group"=>$row->is_group,

                            ]
                        );

                    }else {
                        $events[] = \Calendar::event(
                            $schedulerTitle,
                            false,
                            $dateFrom,
                            $dateTo,
                            $row->scheduler_id,
                            [
                                'rendering' => 'background',
                                'scheduler_id' => $row->scheduler_id,
                                'show_id' => $row->show_id,
                                'form_id' => $row->form_id,
                                "is_multiple_selection" => $row->is_multiple_selection,
                                "restriction_id"=>$row->id,
                                "class_group_key"=>$row->scheduler_key,
                                "is_group"=>$row->is_group,

                            ]
                        );
                    }
                    if($row->block_time!='') {
                        $dragVar = explode('-', $row->block_time);

                        $dragDateFrom = date('Y-m-d H:i:s', strtotime($dragVar[0]));
                        $dragDateTo = date('Y-m-d H:i:s', strtotime($dragVar[1]));

                        if ($row->block_time_title != '')
                            $blockTile = $row->block_time_title;
                        else
                            $blockTile = "Blocked Time";

                        if (!in_array($dragDateFrom, $alreadyExist)) {
                            $events[] = \Calendar::event(

                                '<h6 style="margin-top: 10px; align-content: center;text-transform: capitalize">' . $blockTile . '</h6>',
                                false,
                                $dragDateFrom,
                                $dragDateTo,
                                $row->show_id,
                                [
                                    'description' => '<h6 style=" margin-top: 10px; color:#FFF; align-content: center; text-transform: capitalize;">' . $row->block_time_title . '</h6>',
                                    'backgroundColor' => '#000000',
                                    'is_drag' => 1,
                                    "eventOverlap" => true
                                ]
                            );
                            $alreadyExist[] = $dragDateFrom;
                        }
                    }


                }

        } else {

             $dateFrom = date('Y-m-d H:i:s');
             $events[] = \Calendar::event(
                 $schedulerTitle,
                false,
                '2017-4-21T10:00:00',
                '2030-4-21T9:00:00',
                0,
                [
                    'rendering' => 'background',
                    "restrictionType"=>1

                ]
            );
        }

    };
    if($scheduals) {

     //  dd($scheduals->toArray());
        $userContainsArray = [];

        foreach ($scheduals as $row1) {

            $faults_option = json_decode($row1['faults_option'], true);

            $isCombined = CombinedClass::where('class_id',$row1['asset_id'])->count();

            $pre_fields1 = json_decode($row1['time_slot'], true);
            for ($j = 0; $j < count($pre_fields1); $j++) {
                $var1 = explode('-', $pre_fields1[$j]);

                if (count($var1) > 0) {
                    $timeFrom[] = date('H:i:s', strtotime($var1[0]));
                    $timeTo[] = date('H:i:s', strtotime($var1[1]));
                    $dateFrom = date('Y-m-d H:i:s', strtotime($var1[0]));
                    $dateTo = date('Y-m-d H:i:s', strtotime($var1[1]));
                    $date[] = ['dateFrom' => date('Y-m-d H:i:s', strtotime($var1[0])), 'dateTo' => date('Y-m-d H:i:s', strtotime($var1[1]))];

                    if($row1['horse_id']!='')
                        $horseTitle =  GetAssetNamefromId($row1['horse_id']).'-'.GetHorseRegisteration($row1['horse_id'],$row1['show_id'],1);
                    else
                        $horseTitle = '';

                   $horse_rider = getHorsesRiderForScheduler($row1['horse_id'] ,$row1['asset_id'] );


                    $description = '<span>'.getUserNamefromid($row1['user_id']).'</span>
                                <br /><a class="HorseAsset" target="_blank" style="position: relative; font-size: 12px; z-index: 99999999999999999999" href="/master-template/'.nxb_encode($row1["horse_id"]).'/horseProfile">'.$horseTitle.'</a>';
                   if($horse_rider!='')
                     $description .='<br /><a class="HorseAsset" target="_blank" style="position: relative; font-size: 12px; z-index: 99999999999999999999" href="/master-template/'.nxb_encode($horse_rider).'/horseProfile">'.GetAssetNamefromId($horse_rider).'</a>';


                   //'userType' => 'others',
                   // 'backgroundColor' => '#000000',

                    if ($row1['is_multiple_selection'] == 1)
                   {

                            $countUserInEvent = schedualNotes::where('asset_id', $row1['asset_id'])
                                ->where('show_id', $row1['show_id'])
                                ->where('form_id', $row1['form_id'])
                                ->where('timeFrom', '=', $row1['timeFrom'])
                                ->where('timeTo', '=', $row1['timeTo'])
                                ->where('is_multiple_selection', 1)
                                ->count();

                            $participants = '<a href="javascript:" onclick="getEventsParticipants(\'' . $row1['show_id'] . '\',\'' . $row1['form_id'] . '\',\'' . $row1['asset_id'] . '\',\'' . $slots_duration . '\',\'' . $dateFrom . '\',\'' . $dateTo . '\',2,\'' . $row1['restriction_id'] . '\')" class="viewBtn"   >View participants</a><br>';

                            $participants .= '<a href="javascript:"  onclick="InviteInEvent(\'' . $row1['id'] . '\',\'' . $slots_duration . '\')" class="viewBtn participantLink">Participate</a>';


                            if (!in_array($row1['multiple_scheduler_key'], $userContainsArray)) {
                                $events[] = \Calendar::event(
                                    $participants,
                                    false,
                                    $dateFrom,
                                    $dateTo,
                                    $row1['id'],
                                    [
                                        'description' => "Multi Scheduler",
                                        'notes' => $row1['notes'],
                                        "userId" => $row1['user_id'],
                                        "formId" => $row1['form_id'],
                                        "scheduler_id" => $row1['schedual_id'],
                                        "template_id" => $row1['template_id'],
                                        "asset_id" => $row1['asset_id'],
                                        'backgroundColor' => 'green',
                                        "isMultiple" => 1,
                                        "show_id" => $row1['show_id'],
                                        "horse_id" => $row1['horse_id'],
                                        "reason" => $row1['reason'],
                                        "height" => $row1['height'],
                                        "isCombined" => $row1['isCombined'],
                                        'slots_duration' => $slots_duration,
                                        "is_multiple_selection" => $row1['is_multiple_selection'],
                                        "horse_rating_type"=>$horse_rating_type,
                                        "restriction_id" => $row1['restriction_id'],
                                        "multiple_scheduler_key" => $row1['multiple_scheduler_key'],
                                        "faults_option"=>$faults_option

                                    ]
                                );

                            }
                            $userContainsArray[] = $row1['multiple_scheduler_key'];
                        }
                   elseif ($row1['other_group_Class'] == 1){

                           $events[] = \Calendar::event(
                               $description,
                               false,
                               $dateFrom,
                               $dateTo,
                               $row1['id'],
                               [
                                   'description' => $row1['notes'],
                                   'notes' => $row1['notes'],
                                   "userId" => $row1['user_id'],
                                   "formId" => $row1['form_id'],
                                   "scheduler_id" => $row1['schedual_id'],
                                   "template_id" => $row1['template_id'],
                                   "asset_id" => $row1['asset_id'],
                                   "show_id" => $row1['show_id'],
                                   "horse_id" => $row1['horse_id'],
                                   "reason" => $row1['reason'],
                                   'slots_duration' => $slots_duration,
                                   "height" => $row1['height'],
                                   "isCombined" => $row1['isCombined'],
                                   "horse_rating_type"=>$horse_rating_type,
                                   "restriction_id" => $row1['restriction_id'],
                                   'userType' => 'others',
                                    'backgroundColor' => '#000000',
                                   "faults_option"=>$faults_option
                               ]
                           );
                   }
                    else {
                        if ($row1['is_mark'] == 1) {
                            $events[] = \Calendar::event(
                                $description,
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $description,
                                    'notes' => $row1['notes'],
                                    'backgroundColor' => '#2ca02c',
                                    "isMark" => 1,
                                    "userId" => $row1['user_id'],
                                    "formId" => $row1['form_id'],
                                    "scheduler_id" => $row1['schedual_id'],
                                    "template_id" => $row1['template_id'],
                                    "asset_id" => $row1['asset_id'],
                                    "show_id" => $row1['show_id'],
                                    "horse_id" => $row1['horse_id'],
                                    "reason" => $row1['reason'],
                                    'slots_duration' => $slots_duration,
                                    "height" => $row1['height'],
                                    "isCombined" => $row1['isCombined'],
                                    "is_multiple_selection" => $row1['is_multiple_selection'],
                                    "horse_rating_type"=>$horse_rating_type,
                                    "restriction_id" => $row1['restriction_id'],
                                    "multiple_scheduler_key" => $row1['multiple_scheduler_key'],
                                    "faults_option"=>$faults_option
                                ]
                            );
                        } else {
                            $events[] = \Calendar::event(
                                $description,
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $row1['notes'],
                                    'notes' => $row1['notes'],
                                    "userId" => $row1['user_id'],
                                    "formId" => $row1['form_id'],
                                    "scheduler_id" => $row1['schedual_id'],
                                    "template_id" => $row1['template_id'],
                                    "asset_id" => $row1['asset_id'],
                                    "show_id" => $row1['show_id'],
                                    "horse_id" => $row1['horse_id'],
                                    "reason" => $row1['reason'],
                                    'slots_duration' => $slots_duration,
                                    "height" => $row1['height'],
                                    "isCombined" => $row1['isCombined'],
                                    "is_multiple_selection" => $row1['is_multiple_selection'],
                                    "horse_rating_type"=>$horse_rating_type,
                                    "restriction_id" => $row1['restriction_id'],
                                    "multiple_scheduler_key" => $row1['multiple_scheduler_key'],
                                    "faults_option"=>$faults_option
                                ]
                            );
                        }
                    }
                }
            }
        }
    }

    //        'slotDuration'=> '00:'.$slots_duration.':00',


    if(strpos($slots_duration, ':') !== false) {
        $slots_duration = "00:".$slots_duration;
    } else {
        $slots_duration ="00:".$slots_duration.":00";
    }

    $calendar = \Calendar::addEvents($events); //add an array with addEvents

    $clId= $calendar->getId();

    $isSpectator = session('isSpectator');

    if($isSpectator=='')
        $agendaWeek='agendaWeek';
    else
        $agendaWeek='agendaDay';



    $calendar->setOptions([ //set fullcalendar options
        'firstDay' => 1,
        'slotDuration'=> $slots_duration,
        'defaultView'=>$agendaWeek,
        'axisFormat'=> 'h:mm:ss a',
        'columnFormat'=>'dddd  / D',
        'clickable'=>true,
        'scrollTime' => $scrollTime,
    ]);
    $calendar->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)

    "viewRender"=>"function(view, element) {
                
   if(view.name === 'month') {
     var arrayFromPHP = ".json_encode($date,true).";
     var formattedEventData = [] ;
            for (var k = 0; k < arrayFromPHP.length; k ++) {
                formattedEventData.push({
                    title: '',
                    start: arrayFromPHP[k][\"dateFrom\"],
                    end:arrayFromPHP[k][\"dateTo\"],
                    rendering:'background',
                    allDay:true,
                  'background' : '#8fdf82'
                });
            }
     $('#calendar-".$clId."').fullCalendar('addEventSource',formattedEventData);
  	 $('#calendar-".$clId."').fullCalendar('refetchEvents');
   }
  
    }",
    'eventClick' => 'function(calEvent, jsEvent, view) 
    {
    
        var dateSelected=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bg td:eq(\'+$(this).closest(\'td\').index()+\')\').data(\'date\');
        var backgroundId=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bgevent-skeleton td:eq(\'+$(this).closest(\'td\').index()+\')\').children().children().children().attr(\'id\');
        $("#myDiv").html(\'\');
        $("#schedule_id").val("");
        $("#notes").val("");
        
        if(calEvent.isMultiple==1 || isCombined > 0 || calEvent.is_drag==1)
         return false;

        if(calEvent.isMark==1)   
            markDisabled();
        else
         markEnable();
        
          $(".markSave").attr("disabled",false);

        if(calEvent.endDaterestriction)
        {
        endTime = moment(calEvent.endDaterestriction).format("HH:mm:ss");
        endDate = moment(calEvent.endDaterestriction).format("YYYY/MM/DD");
        
        }
        else
        {
            if(calEvent.source.origArray)
            {
                var currentSelected=calEvent.source.origArray[0].start;
                
                var currentTime=parseTimestamp(currentSelected);
                var endTime="23:45";
                var endDate=  moment(calEvent.end).format("YYYY/MM/DD");
            }
            else
            {
                var endDateTime=parseTimestamp(calEvent.end);
                endTime = moment(calEvent.end).format("HH:mm:ss");      
                endDate = moment(calEvent.end).format("YYYY/MM/DD") ; 
            }
        }  
        var startDate=moment(calEvent.start).format("YYYY/MM/DD");
        var startTime=moment(calEvent.start).format("HH:mm:ss");
        var spectatorsId= $("#spectatorsId").val();
        $(".faults_option").selectpicker("val","");
        $(".faults_option").selectpicker("val",calEvent.faults_option);        
        if(spectatorsId > 0)
        {
        
        $(".scoreContainer").hide();
         hideButtonSpectator();
         $(".participantLink").hide();
         $(".score").attr("disabled",true);
        }        
        //$("#notes").attr("disabled",true);
               
        if(calEvent.reason == null)
        {
        $(".ReasonCon").addClass(\'hide\');
        $(".reason").attr(\'required\',false);
        }
       else
       {
        $(".ReasonCon").removeClass(\'hide\');
         $(".reason").val(calEvent.reason)
        $(".reason").attr(\'required\',false);
       }
              
       if(calEvent.horse_rating_type==1)
       {       
        $(".scoreContainer").hide();
        $(".score").attr("disabled","disabled");
       }
       else{
        if(spectatorsId == 0)
        {
        $(".scoreContainer").show();
        }
        $(".score").attr("disabled",false);
       }
       
         if(calEvent.userType=="others")
        {
        return false;
        }
       
      // alert(calEvent.restriction_id);
       
       
      var score = getScoreForScheduler(calEvent.asset_id,calEvent.show_id,calEvent.horse_id,calEvent.restriction_id,calEvent.formId);
       
        
        $(document).on(\'click\', \'.HorseAsset\', function () {
        $("#eventContent").modal("hide"); // in order to click on horse profile to prevent modal show up
        });
       
       
       $("#eventContent").addClass("show");
        $("#eventContent").modal("show");
        $(".notes").val(calEvent.notes);
        $(".reason").val(calEvent.reason);
        $(".schedule_id").val(calEvent.id);
       // $(".score").val(score);
        $(".templateId").val(calEvent.template_id);
        $(".assetId").val(calEvent.asset_id);
        $(".event_asset_id").val(calEvent.asset_id);
        $(".restriction_id").val(calEvent.restriction_id);
        $(".is_multiple_selection").val(calEvent.is_multiple_selection);
        $(".multiple_scheduler_key").val(calEvent.multiple_scheduler_key);

        $(".userId").val(calEvent.userId);
        //var el = document.querySelector(".schedulerProfileView");
        $(".schedulerProfileView").attr(\'data-id\',calEvent.userId);
        //el.setAttribute(\'data-id\',calEvent.userId);
        
        $(".form_id").val(calEvent.formId);
        $(".masterScheduler").val(1);
        $(".backgrounbdSlotId").val(calEvent.scheduler_id);
        
        $(".startTime").val(moment(calEvent.start).format("YYYY/MM/DD HH:mm:ss"));    
        $(".endTime").val(endDate+" "+endTime);   
        $(".markDone").show();
        
        getHorseName(calEvent.horse_id);
               
        if(calEvent.height!=null)
            getHorseHeight(calEvent.id);
        else
            $("#ClassHeight").hide();
        
        setTimeout(function () {
            $("select[name=ClassHorse]").val(calEvent.horse_id);
            $(".ClassHorse").selectpicker("refresh")
        },700);
        
       var slots_Time = calEvent.slots_duration;
   
       
         if (String(slots_Time).indexOf(\':\') > -1)
        {
            var segments =  slots_Time.split(\':\');

            var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

        }else
        {
            var slots_duration = parseInt(slots_Time*60);

        }
    
        populate2(startTime,endTime,startDate,endDate,moment(calEvent.end).format("HH:mm:ss"),0,slots_duration);
    }',

    "eventRender"=>" function (calEvent, element) 
     {

         var spectatorsId= $(\"#spectatorsId\").val();
    
          eventsdate = moment(calEvent.start).format('hh:mm:ss');
          eventedate = moment(calEvent.end).format('hh:mm:ss');
          element.find('.fc-time').html(eventsdate + \" - \" + eventedate + \"<br>\");
          
          element.find('div.fc-title').html(element.find('div.fc-title').text());                   
       
        if (calEvent.rendering == 'background') 
        {
       
        if(calEvent.is_multiple_selection=='1'){
                element.addClass('multipleSelection');
            }
       
       element.append('<h6  id=\"'+calEvent.id+'\">'+calEvent.title+'</h6>');
        $(element).data(calEvent);
        }
        else
        {
        if(calEvent.isMark==1)
            element.append('<img src=\"/img/check_mark.png\">' );
       
        if(calEvent.is_drag!=1 && calEvent.isMark!=1 && calEvent.is_multiple_selection!='1' && spectatorsId=='' )
        {
        element.append('<span onclick=\"delEvent(event,this)\" data-id=\"'+calEvent.id+'\" class=\"closeon\">X</span>' );
        }
  
        if(calEvent.is_multiple_selection=='1' && spectatorsId==''  )
        {
        element.append('<span onclick=\"delMultiEvent(event,this,'+calEvent.id+')\" data-id=\"'+calEvent.multiple_scheduler_key+'\" class=\"closeon\">X</span>' );
        }
        if(spectatorsId > 0)
          element.find('a.participantLink').hide();

        
        }

    }",
    "dayClick"=>" function(date, allDay, jsEvent, view) 
    {
        if(jsEvent.name == 'month') {
        $('#calendar-".$clId."').fullCalendar('changeView', 'agendaDay');
        $('#calendar-".$clId."').fullCalendar('gotoDate', date);
        return false;
        }
        if (!allDay.target.classList.contains('fc-bgevent')) 
        {
            alert(\"Please select the date which is available.\");
        }
        $('.schedule_id').val('');
        $('.notes').val('');
        window.selectedTime=date.format(\"YYYY/MM/DD HH:mm:ss a\");    
    }",
    "eventMouseover"=>'function(calEvent, jsEvent) 
    {
        if(calEvent.description!="")
        {
           if(calEvent.isMultiple==1)
           {
            var tooltip = \'<div class="tooltipevent" style="width:200px; padding:10px; border:solid 1px #000; border-radius: 5px; color:#000;height:Auto;background:#ccc;position:absolute;z-index:10001;">\' + calEvent.description + \'</div>\';
           }else{
            var tooltip = \'<div class="tooltipevent" style="width:200px; padding:10px; border:solid 1px #000; border-radius: 5px; color:#000;height:Auto;background:#ccc;position:absolute;z-index:10001;">\' + calEvent.title + \'</div>\';
}           
           
          //  var $tooltip = $(tooltip).appendTo("body");
            
//            $(this).mouseover(function(e) {
//            $(this).css("z-index", 10000);
//            $tooltip.fadeIn("500");
//            $tooltip.fadeTo("10", 1.9);
//            }).mousemove(function(e) {
//            $tooltip.css("top", e.pageY + 10);
//            $tooltip.css("left", e.pageX + 20);
//            });
        }
    }',
    'eventMouseout'=>"function(calEvent, jsEvent) {
    $(this).css('z-index', 8);
    $('.tooltipevent').remove();
    }",
    ]);

    $arr=[];
    $arr['calendar']=$calendar;

    $arr['clId']=$clId;

    return $arr;
}

function schedulerReminder($request,$id)
    {


        $SchedulerReminder = SchedulerReminder::where('notes_id',$id);


        if($SchedulerReminder)
             $SchedulerReminder->delete();

       $scheduleId=$request->schedual_id;

        if($scheduleId) {
            $Schedual = Schedual::findOrFail($scheduleId);

            $fromDate = $request->timeFrom;

            $arr = [];
            $data = [];



            if ($Schedual->reminderHours != '') {
                $hours = date('Y-m-d H:i:s', strtotime($fromDate) - 60 * 60 * $Schedual->reminderHours);
                $data[] = array('remind_date' => $hours, 'notes_id' => $id, 'scheduler_id' => $scheduleId);
            }
            if ($Schedual->reminderMinutes != '') {
                $minutes = date('Y-m-d H:i:s', strtotime($fromDate) - 60 * $Schedual->reminderMinutes);
                $data[] = array('remind_date' => $minutes, 'notes_id' => $id, 'scheduler_id' => $scheduleId);
            }
            if ($Schedual->reminderDays != '') {
                $days = date('Y-m-d H:i:s', strtotime($fromDate) - 24 * 60 * 60 * $Schedual->reminderDays);
                $data[] = array('remind_date' => $days, 'notes_id' => $id, 'scheduler_id' => $scheduleId);
            }


            //dd($data);

            SchedulerReminder::insert($data);
        }
    }

function getRemindersEmails()
    {
        $reminders = DB::table('scheduals')
            ->join('scheduals_notes', 'scheduals_notes.schedual_id', '=', 'scheduals.id')
            ->join('users', 'scheduals_notes.user_id', '=', 'users.id')
            ->join('scheduler_reminders', 'scheduler_reminders.notes_id', '=', 'scheduals_notes.id')
            ->select('remind_date','scheduler_reminders.id', 'users.email', 'users.name as userName','scheduals_notes.notes','scheduals_notes.time_slot','scheduals_notes.asset_id','scheduals_notes.show_id','scheduals.name')
            ->where('is_sent',0)
            ->groupBy('remind_date','scheduals.user_id')
            ->get();

        foreach($reminders as $reminder) {

           $time_zone = \App\ManageShows::where('id',$reminder->show_id)->pluck('time_zone')->first();

            if($time_zone!='') {
                if($time_zone=='CT')
                    $time_zone = CT;
                elseif($time_zone=='PT')
                    $time_zone = PT;
                elseif($time_zone=='MT')
                    $time_zone = MT;
                elseif($time_zone=='ET')
                    $time_zone = ET;
                date_default_timezone_set($time_zone);
            }


//            echo '>>><br>'.$time_zone;
//            echo '>>>>'.$datetime1 = $reminder->remind_date;
//            echo '>>>>>'.$datetime2 = date('Y-m-d H:i:s');
//            echo '>>>>'.$reminder->email;

            $datetime1 = strtotime($reminder->remind_date);
            $datetime2 = strtotime(date('Y-m-d H:i:s'));
            $interval  = abs($datetime2 - $datetime1);
            $minutes   = round($interval / 60);

           //echo $minutes.'>>>>>>>'.$reminder->email;

            $timeSlot = json_decode($reminder->time_slot,true);

            $fromDate = explode('-', $timeSlot[0]);
            $reminder->assetName =GetAssetNamefromId($reminder->asset_id);

            $reminder->timeSlot = date('d-m-Y H:i:s',strtotime($fromDate[0]));

          //  \Mail::to('riders@mailinator.com')->send(new ReminderEmail($reminder));


            if($minutes<=3)
            {
               \Mail::to($reminder->email)->send(new ReminderEmail($reminder));
                $remind = SchedulerReminder::findOrFail($reminder->id);
                $remind->is_sent=1;
                $remind->update();
           }

        }

    }

function bulkUpdateTimeSlots($changeClasses,$isTimeChange,$reminderMinutes,$asset_id,$reason,$show_id){


      foreach ($changeClasses as $c)
        $changeClasses[]=  nxb_decode($c);

    $results = SchedualNotes::whereIn('asset_id',$changeClasses)->where('show_id',$show_id)->get();
    $asset_title = GetAssetNamefromId($asset_id);


   if(!is_null($isTimeChange)) {
       $restriction = SchedulerRestriction::whereIn('asset_id', $changeClasses)->where('show_id', $show_id)->get();


       foreach ($restriction as $row) {

           $schedulerRes = SchedulerRestriction::findOrFail($row->id);
           list($timeFrom,$timeTo) = explode('-', $row->restriction);


           $timeFromStr = Carbon::parse($timeFrom);
           $timeFrom = $timeFromStr->addMinutes($reminderMinutes);
           $timeFrom = $timeFrom->format('m/d/Y h:i A');

           $timeToStr = Carbon::parse($timeTo);
           $timeTo = $timeToStr->addMinutes($reminderMinutes);
           $timeTo = $timeTo->format('m/d/Y h:i:s A');

           $restrictionTime = $timeFrom . ' - ' . $timeTo;

           $schedulerRes->restriction = $restrictionTime;

           $time_slot = [];

           if($row->block_time!='') {
               $time_slot = explode('-', $row->block_time);

               if (count($time_slot) > 0) {
                   $blockTimeFromStr = Carbon::parse($time_slot[0]);
                   $blockTimeFrom = $blockTimeFromStr->addMinutes($reminderMinutes);
                   $blockTimeFrom = $blockTimeFrom->format('Y/m/d H:i:s');

                   $blockTimeToStr = Carbon::parse($time_slot[1]);
                   $blockTimeTo = $blockTimeToStr->addMinutes($reminderMinutes);
                   $blockTimeTo = $blockTimeTo->format('Y/m/d H:i:s');

                   $blockTimeSlot = $blockTimeFrom . '-' . $blockTimeTo;

                   $schedulerRes->date_from = $blockTimeFrom;
                   $schedulerRes->date_to = $blockTimeTo;
                   $schedulerRes->block_time = $blockTimeSlot;
               }
           }
           $schedulerRes->update();

       }
   }


if($results) {


    foreach ($results as $row) {
        $timeSlot = json_decode($row->time_slot);
        $time_slot = explode('-', $timeSlot[0]);

        $timeFromStr = Carbon::parse($time_slot[0]);
        $timeFrom = $timeFromStr->addMinutes($reminderMinutes);
        $timeFrom = $timeFrom->format('Y/m/d H:i:s');

        $timeToStr = Carbon::parse($time_slot[1]);
        $timeTo = $timeToStr->addMinutes($reminderMinutes);
        $timeTo = $timeTo->format('Y/m/d H:i:s');

        $timeSlot = json_encode($timeFrom . '-' . $timeTo);

        $schedualNotes = SchedualNotes::findOrFail($row->id);
        $schedualNotes->timeFrom = $timeFrom;
        $schedualNotes->timeTo = $timeTo;
        $schedualNotes->reason = $reason;
        $schedualNotes->time_slot = '[' . $timeSlot . ']';
        $schedualNotes->update();

        $user = User::where('id', $row->user_id)->first();

        $trainers = ManageShowsRegister::with('trainerEamil')
            ->where('user_id', $row->user_id)
            ->where('manage_show_id', $show_id)
            ->where('trainer_id', '!=', 0)
            ->first();
        $showTitle = getShowName($show_id);

        $horseTitle = GetAssetNamefromId($row->horse_id);

        if(isset($trainers->trainerEamil)) {
            $email =$trainers->trainerEamil->email;
            \Mail::to($email)->send(new TimeUpdateEmailTrainer($showTitle, $trainers->trainerEamil->name, $time_slot, $timeFrom, $timeTo, $reminderMinutes, $reason, $asset_title,$horseTitle));
        }
         \Mail::to($user->email)->send(new TimeUpdateEmail($time_slot,$timeFrom,$timeTo,$reminderMinutes,$reason,$user,$asset_title,$horseTitle));
    }
}
    return true;
    }

function createOrUpdate($data, $keys) {
    $record = Spectators::where($keys)->first();
    if (is_null($record)) {
        return Spectators::create($data);
    } else {
        return $record->update($data);
    }
}

function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

function participantInvoiceForms($id,$assetId)
 {

     $user_id = \Auth::user()->id;
     $email = \Auth::user()->email;

     $module =Participant::select("modules_permission")->where('email','=',$email)->where('asset_id',$assetId)->first();

         $modules_permission = json_decode($module->modules_permission, true);

         $filteredArray = [];

         if(count($modules_permission) > 0) {
             $filteredArray = array_filter($modules_permission, 'filterModulePermissionArray');

             $filteredArray = array_keys($filteredArray);
         }
         $invoiceForms = Form::where('invoice', '!=', 0)->whereIn('linkto', $filteredArray);

         return $invoiceForms;

 }

function filterModulePermissionArray($value){
        return ($value == 2);
    }

function filterReadOnlyArray($value){
    return ($value == 1);
}


function getAcc()
{
    $headers[] = 'Content-Type: application/json';
    $params = array(
        'client_id' => '592d2329bdc6a401d71d810c',
        'secret' => 'c0c8673c4fc5206f191c7417eb6c96',
        'public_token' => 'public-sandbox-2b153076-c38d-48c1-8f3c-20741ceb3623',
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://sandbox.plaid.com/item/public_token/exchange");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);
    if(!$result = curl_exec($ch)) {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);

    $jsonParsed = json_decode($result);


    //print_r($jsonParsed);exit;

    $btok_params = array(
        'client_id' => '592d2329bdc6a401d71d810c',
        'secret' => 'c0c8673c4fc5206f191c7417eb6c96',
        'access_token' => "$jsonParsed->access_token",
        'account_id' => 'kPLkqXgWg4SmZzLAQvRAUabLNvadjjIw5Weea'
    );

    //print_r($btok_params);exit;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://sandbox.plaid.com/processor/stripe/bank_account_token/create");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($btok_params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);



    if(!$result = curl_exec($ch)) {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);

    $btok_parsed = json_decode($result);




}

function getStates()
{

return  array(
        'AL'=>'Alabama',
        'AK'=>'Alaska',
        'AZ'=>'Arizona',
        'AR'=>'Arkansas',
        'CA'=>'California',
        'CO'=>'Colorado',
        'CT'=>'Connecticut',
        'DE'=>'Delaware',
        'DC'=>'District of Columbia',
        'FL'=>'Florida',
        'GA'=>'Georgia',
        'HI'=>'Hawaii',
        'ID'=>'Idaho',
        'IL'=>'Illinois',
        'IN'=>'Indiana',
        'IA'=>'Iowa',
        'KS'=>'Kansas',
        'KY'=>'Kentucky',
        'LA'=>'Louisiana',
        'ME'=>'Maine',
        'MD'=>'Maryland',
        'MA'=>'Massachusetts',
        'MI'=>'Michigan',
        'MN'=>'Minnesota',
        'MS'=>'Mississippi',
        'MO'=>'Missouri',
        'MT'=>'Montana',
        'NE'=>'Nebraska',
        'NV'=>'Nevada',
        'NH'=>'New Hampshire',
        'NJ'=>'New Jersey',
        'NM'=>'New Mexico',
        'NY'=>'New York',
        'NC'=>'North Carolina',
        'ND'=>'North Dakota',
        'OH'=>'Ohio',
        'OK'=>'Oklahoma',
        'OR'=>'Oregon',
        'PA'=>'Pennsylvania',
        'RI'=>'Rhode Island',
        'SC'=>'South Carolina',
        'SD'=>'South Dakota',
        'TN'=>'Tennessee',
        'TX'=>'Texas',
        'UT'=>'Utah',
        'VT'=>'Vermont',
        'VA'=>'Virginia',
        'WA'=>'Washington',
        'WV'=>'West Virginia',
        'WI'=>'Wisconsin',
        'WY'=>'Wyoming');

}

function invoiceInvitation($array_invoiceAttach,$asset,$data,$email)
{

    $user_id = \Auth::user()->id;
    $participantResponseKey = \Session('participantResponseKey');

    if(isset($array_invoiceAttach) && $array_invoiceAttach!=null)
    {

        foreach ($array_invoiceAttach as $key => $value)
        {

            $model = new InviteInvoices();

            $model->template_id = $data->template_id;
            $model->invitee_id = $user_id;
            $model->asset_id = implode(',', $asset);
            $model->participant_email = $email;
            $model->module_id = $key;
            $model->invoiceFormKey = $participantResponseKey;

            $model->save();
        }


    }



}

function getFormIdFromModuleId($module_id){
        $form = Form::where('linkto',$module_id)->first();
        if ($form != null) {
            $formId = $form->id;
        }else{
            $formId = "";
        }
        return $formId;

    }

function getPenaltyInvoice($assetId,$key,$template_id)
{

    $user_id = \Auth::user()->id;
    $user_email = \Auth::user()->email;



    $module = inviteParticipantinvoice::where('invoiceFormKey',trim($key))
            ->where('template_id',$template_id)
            ->where('is_penalty',1)
            ->first();

    if($module) {
        $invoice = new Invoice();
        $invoice->template_id = $template_id;
        $invoice->asset_id = $assetId;
        $invoice->form_id = $module->form_id;
        $invoice->invoice_form_id = $module->form_id;
        $invoice->fields = $module->fields;
        $invoice->invitee_id = $module->invitee_id;
        $invoice->invoice_email = $user_email;
        $invoice->payer_id = $user_id;
        $invoice->invite_asociated_key = trim($key);
        $invoice->amount = getAmount($module->fields);

        $invoice->is_draft = 2;

        $invoice->save();




    }
}

function pullInvoice($id,$assetId,$formId,$template_id,$invite_asociated_key,$user_id=null,$sub_participant_id=0)
    {


         if ($user_id=='')
        {
            $user_id = \Auth::user()->id;
        }


        $user_email = \Auth::user()->email;

        $form_id = nxb_decode($formId);


        $linkTo = Form::select('linkto')->where('id',$form_id)->first();

        $module = inviteParticipantinvoice::where('asset_id',$assetId)
            ->where('module_id',$linkTo->linkto)
            ->where('template_id',$template_id)
            ->first();


        if($module) {

            $invoice = new Invoice();
            $invoice->template_id = $template_id;
            $invoice->asset_id = $assetId;
            $invoice->form_id = $form_id;
            $invoice->invoice_form_id = $id;
            $invoice->fields = $module->fields;
            $invoice->invitee_id = $module->invitee_id;
            $invoice->invoice_email = $user_email;
            $invoice->payer_id = $user_id;
            $invoice->amount = getAmount($module->fields);
            $invoice->invite_asociated_key = trim($invite_asociated_key);
            $invoice->is_draft = 2;
            $invoice->sub_participant_id = $sub_participant_id;

            $invoice->save();
        }
    }

function checkInvboice($associatedKey)
{
    $user_id = \Auth::user()->id;

    $invoice = inviteParticipantinvoice::where('invoiceFormKey',$associatedKey)->get()->toArray();

    return $invoice;
}

function userImage($user_id)
{
    $data =  User::select('cropped_profile_picture')->where('id',$user_id)->first();

    if($data->cropped_profile_picture!='')
        return $url = getImageS3($data->cropped_profile_picture);
    else
        return $url = '/adminstyle/images/img-admin.png';
}

function getAssetModules($assets,$uniqueId)
{

    $assetModules = AssetModules::whereIn('asset_id',$assets)->get();
    $arr = [];
    foreach ($assetModules as $row)
    {
        $modules_permission = json_decode($row->modules_permission, true);
			if(count($modules_permission) > 0) {
			    foreach ($modules_permission as $key => $value) {

			        if ($value == 1)
			            $arr[] = array('access' => "Read Only", 'module' => $key,
			                'invoice' => CheckAssociatedInvoice($key, $row->asset_id, $uniqueId),
			                'asset' => $row->asset_id);
			        if ($value == 2)
			            $arr[] = array('access' => "Read & Write", 'module' => $key,
			                'invoice' => CheckAssociatedInvoice($key, $row->asset_id, $uniqueId), 'asset' => $row->asset_id);
			    }
			}
       //   arrayPrint(array_values($arr));
    }

      //arrayPrint(array_values($arr));

    return array_values($arr);
}


function getSubParticipantAssetModules($asset_id,$modules_permission,$uniqueId)
{

    $arr = [];
        foreach ($asset_id as $asset) {
            if (count($modules_permission) > 0) {
                foreach ($modules_permission as $key => $value) {
                    if ($value == 1)
                        $arr[] = array('access' => "Read Only", 'module' => $key,
                            'invoice' => CheckAssociatedInvoice($key, $asset, $uniqueId),
                            'asset' => $asset);
                    if ($value == 2)
                        $arr[] = array('access' => "Read & Write", 'module' => $key,
                            'invoice' => CheckAssociatedInvoice($key, $asset, $uniqueId), 'asset' => $asset);
                }
            }
        }
        //   arrayPrint(array_values($arr));


  //  arrayPrint(array_values($arr));

    return array_values($arr);
}


function CheckAssociatedInvoice($id,$asst_id,$uniqueId=null)
{
   $model = inviteParticipantinvoice::where('module_id',$id)
        ->where('asset_id',$asst_id);

    if($model->count() > 0)
    {
        $invoiceModel = $model->first();

        $invoiceModel->invoiceFormKey = $uniqueId;

        $invoiceModel->update();

    }

    return   $model->count();


}

function getAmount($field)
{
    $pre_fields = json_decode($field);

    $amount = 0;
    if(isset($pre_fields)) {
        foreach ($pre_fields as $key => $value) {
            if (is_numeric($value->answer)) {
                $amount = $value->answer;
            }

        }
    }
    return $amount;

}

function getschedulerForms($id,$associatedId,$userEmail)
{

    $forms_collection =  Participant::where('template_id', $id)
        ->where('invite_asociated_key', $associatedId)
        ->where('email', $userEmail)
        ->first();

    $modules_permission = json_decode($forms_collection->modules_permission, true);

    $filteredArray = [];

    if(count($modules_permission) > 0)
        {
            $filteredArray = array_filter($modules_permission, 'filterModulePermissionArray');

            $filteredArray = array_keys($filteredArray);
        }



    $forms_collection =  Form::select('id')->whereIn('linkTo', $filteredArray)->where('scheduler',1);

    return $forms_collection;

}

function subParticipantSchedulerForms($id)
    {
        $forms_collection =  subParticipants::where('id', $id)
            ->first();
       // echo '????'.$forms_collection->modules_permission.'>>>>>>';exit;
        if($forms_collection->modules_permission!='false') {
            $forms_collection = getFormsFromModules($forms_collection->modules_permission, 1);
            return $forms_collection;
        }
    }

function subParticipantInvoiceForms($id,$associatedId,$userEmail)
    {
        $forms_collection =  subParticipants::where('template_id', $id)
            ->where('invite_asociated_key', $associatedId)
            ->where('email', $userEmail)
            ->first();

        $forms_collection = getFormsFromModules($forms_collection->modules_permission);

        return $forms_collection;

    }

function  getFormsFromModules($arr,$scheduler=0)
    {

    $modules_permission = json_decode($arr, true);

        $filteredArray = [];
        if(isset($modules_permission)) {
            if (count($modules_permission) > 0) {
                $filteredArray = array_filter($modules_permission, 'filterModulePermissionArray');

                $filteredArray = array_keys($filteredArray);
            }
        }
//print_r($filteredArray);exit;

     if($scheduler==1)
        $forms_collection =  Form::select('id')->whereIn('linkTo', $filteredArray)->where('scheduler',1);
     else
         $forms_collection =  Form::select('id')->whereIn('linkTo', $filteredArray);



        return $forms_collection;

    }

//get give additional chagrges
function AdditionalCharge($id,$field=0){
	if ($field == 1) {
		$addit = AdditionalCharges::where("id",$id)->first();
		return $addit->description;
	}elseif($field == 2){
		$addit = AdditionalCharges::where("id",$id)->first();
		return getDates($addit->created_at);
	}
	else
    {
        $addit = AdditionalCharges::where("id",$id)->first();
        return $addit->title;
     }
}
//Get unique batch users
function getShowInvoiceUsers($unique=null){
	if ($unique != null) {
		return $shows = ManageShowTrainerSplit::with("ClassHorse.horse","ClassHorse.user")->where("unique_batch",$unique)->get();
	}
}
function getSplitCharges($CHids,$invoice_status,$paid_on=NULL){
	// if ($invoice_status == 0) {
	// 	$invoice_status = NULL;
	// }
	// dd($CHids,$invoice_status,$paid_on);
	return $split = ManageShowTrainerSplit::with("TrainerUser")
         ->whereIn("class_horses_id",$CHids)
         ->where('invoice_status',$invoice_status)
         ->where('paid_on',$paid_on)
         ->get();
}
function twodecimalformate($number){
	if ($number == 0 || $number === 0) {
		return 0;
	}else{
		return floor($number * 100) / 100;
	}
}
//get mulltiple asset names at once
function getPrentAssets($id){
    $res = AssetParent::where('asset_id',$id)->get();
    $name = [];

    if($res) {
        foreach ($res as $r) {

            $row = Asset::where('id', $r->parent_id)->first();


            $pre_fields = json_decode($row->fields);

            if ($pre_fields != null) {
                foreach ($pre_fields as $key => $value) {
                    if ($value->answer != "") {
                        $name[] = "<a href='".URL::to('/master-template/').'/'.nxb_encode($row->id)."/edit/assets"."' data-toggle='tooltip' data-placement='top'
	 data-original-title='View primary Asset'>".$value->answer."</a><br>";
                        break;
                    } else {
                        $name[] = "Asset ID " . $row->id;
                    }
                    break;
                    // if (strtolower($value->form_name) == strtolower(Assets_Name)) {
                    // 	if ($value->answer != "") {
                    // 		$name = $value->answer;
                    // 		break;
                    // 	}else{
                    // 		$name = "Asset ID ".$row->id;
                    // 	}
                    // }else{
                    // 	$name = "Asset ID ".$row->id;
                    // }
                }
            } else {
                $name[] = "Asset ID " . $row->id;
            }

        }

        return implode($name,'');

    }
    else
    {

     return false;
    }

    }

    function bankAccountExist($id)
    {

        return  \App\AppownerBankAccountInformation::where('owner_id', $id)->count();

    }

 function setAlert($type, $title, $message)
{
    $alerts = array();
    if (Session::has('alerts')) {
        $alerts = Session::get('alerts');
    }
    $alert = array(
        'type' => $type,
        'title' => $title,
        'message' => $message
    );
    array_push($alerts, $alert);

    Session::put('alerts', $alerts);
}


 function getAlert()
{


    $arr = [];
    $alert_html = '';
    if (Session::has('alerts')) {
        $alerts = Session::get('alerts');
        foreach ($alerts as $key => $alert) {
            $type    = $alert['type'];
            $title   = stripslashes($alert['title']);
            $message = stripslashes($alert['message']);
            if ($type == 'error') {
                $type = 'danger';
            }

            if(!in_array($title,$arr)) {
                $alert_html .= '
                    <div data-alert class="alert alert-' . $type . '">
                        <strong>' . $title . '&nbsp;</strong>&nbsp;&nbsp;' . $message . '
                    </div>';
                $arr[] = $title;
            }

        }

        echo $alert_html;

        Session::forget('alerts');
    }

}

function getuserSearchRecords($template_id,$show_id,$input)
{
    $user_id = \Auth::user()->id;
    $templateType = GetTemplateType($template_id);


    $model = DB::table('scheduals_notes')
        ->leftJoin('manage_shows', 'scheduals_notes.show_id', '=', 'manage_shows.id')
        ->leftJoin('scheduals', 'scheduals_notes.schedual_id', '=', 'scheduals.id')
        ->leftJoin('users', 'scheduals_notes.user_id', '=', 'users.id')
        ->select('users.name as userName','scheduals_notes.asset_id','scheduals_notes.time_slot','manage_shows.title','scheduals_notes.form_id','is_mark','scheduals_notes.id','horse_id','scheduals.name as SchedualName','scheduals_notes.template_id','scheduals_notes.show_id')
        ->where(function ($qry) use ($input) {
            return $qry->where('users.name', 'like', "%$input%")
                ->orWhere('scheduals.name', 'like', "%$input%");
        })
      //  ->where('scheduals_notes.user_id', $user_id)
        ->where('scheduals_notes.template_id', $template_id)
        ->where('manage_shows.id', $show_id);

//    if($templateType==FACILTY) {
//        $model->where('scheduals_notes.asset_id', $show_id);
//    }else{
//        $model->where('manage_shows.id', $show_id);
//    }

    return $model;


    }
/**
* This will check how many users the invoice will be split to
 * @param $Split class horses id array
 */
function splitBetweenUsers($idsArray){
	$users = ClassHorse::select("msr_id")->whereIn('id', $idsArray)->groupBy("msr_id")->get();
	return $users->count();
}

/**
 * @param $template_id
 * @param $formId
 */
function checkSchedulerTime($template_id, $formId)
{

   $isEmail = \Session('isEmployee');
    $userEmail = \Auth::user()->email;


    if($isEmail==1) {

        $user_id = getAppOwnerId($userEmail,$template_id);
        $employee_id = \Auth::user()->id;
    }
    else {
        $user_id = \Auth::user()->id;
        $employee_id = 0;
    }

    $assetName =[];
    $assets = Asset::select('assets.id','asset_modules.modules_permission')->where('assets.template_id',$template_id)->where('assets.user_id',$user_id)
        ->join('asset_modules', function ($join) {
            $join->on('assets.id', '=', 'asset_modules.asset_id');
        })
        ->get();

    $array = [];
    $ar = [];
    $j = [];
    foreach ($assets as $asset)
    {
        if($asset->modules_permission!='')
        {
           $ar = getFormsFromModules($asset->modules_permission)->pluck('id');
           for($i=0;$i<count($ar);$i++)
           {
              $j[$ar[$i]][]=$asset->id;
           }
        }
    }

    if (array_key_exists($formId, $j)) {

        for($k=0;$k<count($j[$formId]);$k++)
        {

            $assetName[] =  array('name'=>GetAssetNamefromId($j[$formId][$k]),'id'=>$j[$formId][$k]);
        }
    }

   // ArrayPrint($assetName);

    return $assetName;

}


function ScoringClasses($scoringFrom,$class_id,$show_id,$form_id){
    foreach ($scoringFrom as $score)
    {
     $model = new ScoreFromClass();
     $model->class_id = $class_id;
     $model->score_from_class = $score;
     $model->show_id = $show_id;
     $model->form_id = $form_id;
     $model->save();
    }

}

function updateRestrcitionTime($arr)
{
    if(isset($arr['slotsMinutes'])){
    foreach ($arr['slotsMinutes'] as $key=>$value) {
        foreach ($value as $k => $minutes) {

            $ArrSlotsTime = '';

            $seconds = $arr['slotsSeconds'][$key][$k];

            if ($minutes != '' && $seconds != '')
                $ArrSlotsTime = $minutes . ':' . $seconds;
            elseif ($minutes != '' && $seconds == '')
                $ArrSlotsTime = $minutes . ':00';

            $model = SchedulerRestriction::where('asset_id', $k)->where('form_id', $key)->where('show_id', $arr['show_id'])->update(['slots_duration' => $ArrSlotsTime]);
        }
    }

    }

}


function getShows($template_id)
{

    $isEmail = \Session('isEmployee');
    $userEmail = \Auth::user()->email;


    if($isEmail==1) {

        $user_id = getAppOwnerId($userEmail,$template_id);
        $employee_id = \Auth::user()->id;
    }
    else {
        $user_id = \Auth::user()->id;
        $employee_id = 0;
    }

   return $getShow = Schedual::select('show_id')->where('template_id',$template_id)->where('user_id',$user_id)->orderBy('show_id','Desc')->first();

}

function getShowName($show_id)
{
    $getShow = \App\ManageShows::select('title')->where('id',$show_id)->first();
    if(isset($getShow))
    return $getShow->title;
    else
        return false;
}

function getShowNameContactAddress($show_id)
{
    $getShow = \App\ManageShows::select('title','contact_information','location')->where('id',$show_id)->first();
    if(isset($getShow))
    return ['title'=>$getShow->title,'contact_information'=>$getShow->contact_information,'location'=>$getShow->location];
    else
        return false;
}

function getSchedulerName($id)
{

    $getSheduler = Schedual::select('name')->where('id',$id)->first();
    if($getSheduler)
    return $getSheduler->name;

}

function getrestrictionTime($form_id,$show_id,$assetId){

    $schedulerRestrcition = SchedulerRestriction::select('restriction')->where('show_id',$show_id)
        ->where('form_id',$form_id)
        ->where('asset_id',$assetId)
        ->get()
        ->toArray();
}



function getFormIdOfAsset($asset_id,$show_id)
{


 $model =  SchedulerRestriction::select('form_id')->where('asset_id',$asset_id)
        ->where('show_id',$show_id)
       ->first();
   return $model->form_id;

}

function ArrayPrint($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
        exit;
}







function checkIfOverlapped($ranges)
{
    $res = $ranges[0];

    $countRanges = count($ranges);

    for ($i = 1; $i < $countRanges; $i++) {

        $r1s = $res['dateFrom'];
        $r1e = $res['dateTo'];

        $r2s = $ranges[$i]['dateFrom'];
        $r2e = $ranges[$i]['dateTo'];

        if ($r1s >= $r2s && $r1s <= $r2e || $r1e >= $r2s && $r1e <= $r2e || $r2s >= $r1s && $r2s <= $r1e || $r2e >= $r1s && $r2e <= $r1e) {

            $res = array(
                'dateFrom' => $r1s > $r2s ? $r1s : $r2s,
                'dateTo' => $r1e < $r2e ? $r1e : $r2e
            );

        } else return false;

    }

    return $res;
}


function checkEncodeDecode($var)
{
    if (is_numeric($var))
        $variable = $var;
    else
        $variable = nxb_decode($var);

    return $variable;

}


function createInvoiceForShow($data){

    //ArrayPrint($data,1);

    $user_id = \Auth::user()->id;
    $useremail = \Auth::user()->email;
    $participant =Participant::where('email',$useremail)->where('invite_asociated_key','=',trim($data->invite_asociated_key));


    $assetDataResult = $participant->get()->pluck('asset_id');

    $assetData  = json_encode($assetDataResult);

    $assetInvoice = ShowAssetInvoice::whereIn('asset_id',$assetDataResult)->get();
    $total = 0;
    $i=1;
    $arr = [];
    foreach ($assetInvoice as $a)
    {
    $amount = getAmount($a->fields);
    $total =$total + $amount;
    $arr[$i] = ['price'=>$amount,'id'=>$a->asset_id];
    $i ++;
    }

    $invoice =new Invoice();
    $invoice->template_id = $data->template_id;

    $invoice->show_id = $data->show_id;
    //$show_id = $request->assets;

    $invoice->fields = json_encode($arr);
    $invoice->payer_id = $user_id;
    $invoice->form_id = 92; //we have to change it in future just to avoid errors

    $invoice->invitee_id =$data->invitee_id;
    $invoice->show_owner_id =$data->invitee_id;
    $invoice->invitee_id =$data->invitee_id;

    $invoice->invite_asociated_key = $data->invite_asociated_key;
    $invoice->amount = $total;
    $invoice->is_draft = 2;
    $invoice->save();

    // this is for show participant user

 $templateType = GetTemplateType($data->template_id);

 if($templateType!=FACILTY) {

     $additional_price = AdditionalCharges::where("template_id", $data->template_id)
         ->where('required', 1)->get();

     $addArr = [];
     $j = 1;

     foreach ($additional_price as $add) {
         $addArr[$j] = ['price' => $add->amount, "qty" => "1", 'id' => $add->id];
         $j++;
     }

     $model = new ManageShowsRegister();
     $model->total_price = $total;
     $model->additional_fields = json_encode($addArr);
     $model->status = 1;
     $model->user_id = $user_id;
     $model->manage_show_id = $data->show_id;
     $model->assets_fields = json_encode($arr);
     $model->save();

     // $participant->manage_show_reg_id = $model->id;
     $participant->update(['manage_show_reg_id' => $model->id]);

 }

}

function sendTimeUpdateEmail($asset_id,$timeSlots,$show_id)
{

    $userEmails = Participant::where('asset_id',$asset_id)->where('show_id',$show_id)
        ->groupBy('email')
        ->get();

    foreach ($userEmails as $email) {
        $data['asset_name']=GetAssetNamefromId($asset_id);
        $data['show_name']=getShowName($show_id);
        $data['userName']=$email->name;
        $data['timeSlots']=$timeSlots;

        \Mail::to($email->email)->send(new SchedulerTimeUpdate($data));
    }

}


function getRegistration($SRID){

 $mod =  ManageShowsRegister::select('show_reg_number')->where('id',$SRID)->first();
    if($mod)
        return $mod->show_reg_number;

}


//get user profile with horse name and registration number

function getUserHorseNamefromid($user_id,$asset_id,$show_id,$page)
{

    $model = ClassHorse::select('horse_id','horse_reg')->where('user_id',$user_id)->where('class_id',$asset_id)->where('show_id',$show_id)->first();

    $userNamer = getUserNamefromid($user_id);

    $horseTitle = '';

    if($model) {

        if ($page == 'participant')
            {
                $HorseTitle = "(<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($model->horse_id) . "/horseProfile\" >" . GetAssetNamefromId($model->horse_id). " - ".$model->horse_reg . "</a>)";
                $horseTitle = $userNamer . $HorseTitle;
            }
            else
            {
                $horseTitle = " (" . GetAssetNamefromId($model->horse_id) . " - ".$model->horse_reg.")";
            }
    }
    elseif($page == 'participant'){
        $horseTitle = $userNamer;
    }
    return $horseTitle;
   // exit;
}

//get user profile with horse name and registration number

function getHorseNameAndUserfromid($horse_id,$class_id,$show_id,$page=null)
{

    $iscombinedClass = CombinedClass::where('combined_class_id',$class_id)->pluck('combined_class_id');

    if(count($iscombinedClass)>0) {
        $SchedulerCombined = SchedulerRestriction::whereIn('asset_id', $iscombinedClass)->where('show_id', $show_id)->first();
        if ($SchedulerCombined) {
            $combinedClass = CombinedClass::where('combined_class_id', $SchedulerCombined->asset_id);
            $combinedClasses = $combinedClass->pluck('class_id');
            $classId = SchedulerRestriction::whereIn('asset_id', $combinedClasses)->where('show_id', $show_id)->pluck('asset_id');
        }
    }else{
        $classId[] = $class_id;

    }

    $splitId = ShowClassSplit::where('split_class_id',$class_id)->pluck('orignal_class_id')->first();
    if(isset($splitId)&& count($splitId)>0) {
        $classId[] = $splitId;
    }
   // echo $horse_id.'>>>>'.$class_id.'>>>>'.$show_id;exit;
	$classHosre = ClassHorse::with("horse","user")->where('horse_id',$horse_id)->whereIn('class_id',$classId)->where('show_id',$show_id)->first();



	$horseTitle = '';
    if($classHosre) {
        $userNamer = '(' . $classHosre->user->name . ')';
        if ($classHosre) {
            if (isset($classHosre->horse)) {
                $HorseTitle = "<a class=\"HorseAsset\" target=\"_blank\" href=\"" . URL::to('/') . "/master-template/" . nxb_encode($classHosre->horse_id) . "/horse-view-profile\" >" . GetAssetName($classHosre->horse) . " - " . $classHosre->horse_reg . "</a> ";
                $horseTitle = $HorseTitle . $userNamer;
            }
        } elseif ($page == 'participant') {
            $horseTitle = $userNamer;
        }
    }
    return $horseTitle;
   // exit;
}
//get user profile with horse name and registration number

function getHorseNameAndUserfromCid($CH_id)
{
	$classHosre = ClassHorse::with("horse","user")->where('id',$CH_id)->first();
   	$horseTitle = '';
    $userNamer = '';
    if($classHosre) {
        $userNamer = '('.$classHosre->user->name.')';
            $HorseTitle = "<a class=\"HorseAsset\" target=\"_blank\" href=\"/master-template/" . nxb_encode($classHosre->horse_id) . "/horse-view-profile\" >" .GetAssetName($classHosre->horse). " - ".$classHosre->horse_reg . "</a> ";
            $horseTitle = $HorseTitle.$userNamer;
    }
//    elseif($page == 'participant'){
//        $horseTitle = $userNamer;
//    }
    return $horseTitle;
   // exit;
}
// Horse and show id. Get link of horse

function getHorseNameFromHorseShowId($horse_id,$show_id){
	$CH = ClassHorse::with("horse")->where('horse_id',$horse_id)->where('show_id',$show_id)->first();
	return getHorseNameAsLink($CH->horse);
}
//Make horse name linkable from object
function getHorseNameAsLink($CH)
{
	return $HorseTitle = "<a class=\"HorseAsset\" target=\"_blank\" href=\"". URL::to('/master-template/'). nxb_encode($CH->id) . "/horseProfile\" style='margin:0px !important; float:none !important;' >" .GetAssetName($CH). "</a>";
}
//Make horse name linkable from horse id
function getHorseNameAsLinkFromId($horse_id)
{
	return $HorseTitle = "<a class=\"HorseAsset\" target=\"_blank\" href=\"" . URL::to('/master-template/'). nxb_encode($horse_id) . "/horseProfile\" style='margin:0px !important; float:none !important;' >" .GetAssetNamefromId($horse_id). "</a>";
}

function getAppOwnerId($userEmail,$template_id){

   return $app_owner_id = \App\Employee::where('email',$userEmail)->where('template_id',$template_id)->pluck('show_owner_id')->first();


}

//Get Limited words
function limit_words($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ", array_splice($words, 0, $word_limit));
}


function GetTemplateType($template_id)
{
 return Template::where('id',$template_id)->pluck('category')->first();
}

function array_Check($array) {

    global $array_Check_result;

    foreach ($array as $key=>$value)
    {
        if(is_array($value))
        {
            foreach ($value as $k=>$v)
            {
                if(is_array($v))
                {
                    array_Check($v);
                    $array_Check_result[$k] = 2;
                }
                 else
                 {
                     $array_Check_result[$key] = $v;
                     $array_Check_result[$k] = $v;
                 }
            }
        }

        else
        {
            $array_Check_result[$key] = $value;

        }
    }

        return $array_Check_result;

}


function getModuels($modules)
{

    $arr = [];
    if(count($modules)>0) {
        foreach ($modules as $key => $value) {

            if ($value == 2) {
                if (strpos($key, ',') !== false) {
                    $keyArr = explode(',', $key);

                    for ($i = 0; $i < count($keyArr); $i++) {
                        $arr[$keyArr[$i]] = $value;
                    }

                } else {
                    $arr[$key] = $value;

                }

            } else {
                if (strpos($key, ',') === false) {
                    $arr[$key] = $value;
                }else{
                	$keyArr = explode(',', $key);

                    for ($i = 0; $i < count($keyArr); $i++) {
                        $arr[$keyArr[$i]] = $value;
                    }
                }
            }

        }
    }else
    {
        return false;
    }
return $arr;

}


function parentModule($module_id)
{

    $account = Module::with(['allChildrenAccounts'=>function($query)
    { $query->select('id','linkto')->get(); }])->where('id',$module_id)->get();


   // ArrayPrint($account->toArray());

    $arr = [];
    foreach ($account as $row)
    {
        if(!is_null($row->allChildrenAccounts));
        {
            if(!is_null($row->allChildrenAccounts)) {
                $arrCon = $row->allChildrenAccounts->toArray();
                if (count($arrCon) > 0) {
                    foreach ($arrCon as $r) {
                        if (!is_array($r) && $r!=0)
                            $arr[] = $r;
                    }
                }
            }
        }
        $arr[]=$row->id;
    }

   // ArrayPrint($arr);

    if(count($arr)> 0)
    return implode(',',$arr);
    else
    return false;

}


function childModule($module_id)
{
    return Module::where('linkto',$module_id)->count();
}

function getMinuteSelect()
{
    $html = '';
    for($i=1;$i<60;$i++)
    {
        if($i<10)
            $i="0".$i;
        $html.="<option value='".$i."'>$i</option>";
    }
return $html;
}

function getSecondSelect()
{
    $html = '';
    for($i=0;$i<60;$i+=5)
    {
        if($i<10)
            $i="0".$i;

        $html.="<option value='".$i."'>$i</option>";
    }
    return $html;
}

function GetAssetNameWithType($row){
    $pre_fields = json_decode($row->fields);
    $name = "";

   $formName = Form::where('id',$row->form_id)->pluck('name')->first();


    if (isset($pre_fields)) {
        foreach ($pre_fields as $key => $value) {
            // if (strtolower($value->form_name) == strtolower(Assets_Name)) {
            // 	if ($value->answer != "") {
            // 		$name = $value->answer;
            // 		break;
            // 	}else{
            // 		$name = "Asset ID ".$row->id;
            // 	}
            // }else{
            // 	$name = "Asset ID ".$row->id;
            // }
            if ($value->answer != "") {
                if (is_array($value->answer)) {
                    $name = $value->answer[0];
                    break;
                }else{
                    $name = $value->answer;
                    break;
                }
            }else{
                $name = "Asset ID ".$row->id;
                break;
            }
        }
    }else{
        $name = "Asset ID ".$row->id;
    }

    return $name." ( ".$formName." ) ";
}

function secondaryEvents($FormTemplate, $scheduals,$templateId,$dateFrom)
{

    $events = [];
    $date=[];

    $scrollTime = '';
    $slots_duration = 5;


    if(!is_null($dateFrom))
        $scrollTime = last(explode(' ',$dateFrom));

 //  dd($FormTemplate->toArray());

    // ArrayPrint($scheduals->toArray());
    $assets = [];
    foreach ($FormTemplate as $row) {
        $restriction = $row->restriction;
        $assets[$restriction][] = $row->asset_id;
    }

    foreach ($FormTemplate as $row) {
            $slots_duration = $row->slots_duration;

                $slots_duration = 30;

            $restriction = $row->restriction;

            //exit;
            if ($restriction) {
                $var = explode('-', $restriction);

                if (count($var) > 0) {
                    $timeFrom[] = date('H:i', strtotime($var[0]));
                    $timeTo[] = date('H:i', strtotime($var[1]));
                    $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));
                    $dateTo = date('Y-m-d H:i:s', strtotime($var[1]));
                    $date[] = ['dateFrom' => date('Y-m-d H:i:s', strtotime($var[0])), 'dateTo' => date('Y-m-d H:i:s', strtotime($var[1]))];

//                    echo '<pre>';
//                    print_r($date);
//                    echo '</pre><hr>';

                    $templateType = GetTemplateType($templateId);


                    if($templateType==TRAINER)
                    $schedulerTitle = GetAssetNamefromId($row->asset_id);
                    else
                    $schedulerTitle = GetAssetNamefromId($row->show_id);

                    $events[] = \Calendar::event(
                        $schedulerTitle,
                        false,
                        $dateFrom,
                        $dateTo,
                        $row->scheduler_id,
                        [
                            'rendering' => 'background',
                            "slots_duration"=>$slots_duration,
                            "assets"=>implode(',',$assets[$restriction]),
                            'scheduler_id' => $row->scheduler_id,
                            'restriction_id' => $row->id,
                            "is_multiple_selection" => $row->is_multiple_selection,
                            'show_id' => $row->show_id,
                            'form_id' => $row->form_id,
                            'template_id' => $templateId,
                            'restriction_id' => $row->id,

                        ]
                    );
                }

            } else {

                $dateFrom = date('Y-m-d H:i:s');
                $events[] = \Calendar::event(
                    $row['name'],
                    false,
                    '2017-4-21T10:00:00',
                    '2030-4-21T9:00:00',
                    0,
                    [
                        'rendering' => 'background',
                        "restrictionType" => 1,
                        "slots_duration"=>$slots_duration,
                        'scheduler_id' => $row->scheduler_id,
                        "is_multiple_selection" => $row->is_multiple_selection,
                        'show_id' => $row->show_id,
                        'restriction_id' => $row->id,
                        'form_id' => $row->form_id,
                        'template_id' => $templateId,
                        'restriction_id' => $row->id,

                    ]
                );
            }

        };
    //dd($assets);

    $user_id = \Auth::user()->id;
    $userContainsArray = [];

    //dd($scheduals->toArray());

    foreach ($scheduals as $row1) {
        $pre_fields1 = json_decode($row1['time_slot'], true);

        if($row1['sub_participant_id']>0)
        {
            $userName =  getUserNamefromid($row1['sub_participant_id']);
            $userId =$row1['sub_participant_id'];

        }else
        {
            $userName =  getUserNamefromid($row1['user_id']);

            $userId =$row1['user_id'];
        }


        for ($j = 0; $j < count($pre_fields1); $j++) {
            $var1 = explode('-', $pre_fields1[$j]);

            if (count($var1) > 0) {
                $timeFrom[] = date('H:i', strtotime($var1[0]));
                $timeTo[] = date('H:i', strtotime($var1[1]));
                $dateFrom = date('Y-m-d H:i:s', strtotime($var1[0]));
                $dateTo = date('Y-m-d H:i:s', strtotime($var1[1]));
                $date[] =['dateFrom'=>date('Y-m-d H:i:s', strtotime($var1[0])),'dateTo'=>date('Y-m-d H:i:s', strtotime($var1[1]))];
                $description = '<span>'.$userName.'</span> <br />
                                <span class="assetClass">'.GetAssetNamefromId($row1['asset_id']).'</span> <br />
                                <span class="assetClass">'.GetAssetNamefromId($row1['horse_id']).'</span>';
                $otherUsers = '<span>Time is not available</span> <br />';

                if ($row1['is_multiple_selection'] == 1) {


                    $participants = '<a style="width:100%;" href="javascript:" onclick="getGroupsParticipants(\'' . $row1['show_id'] . '\',\'' . $row1['schedual_id'] . '\',\'' . $slots_duration . '\',\'' . $dateFrom . '\',\'' . $dateTo . '\',2,\'' . $row1['restriction_id'].'\')" class="viewBtn"   >View participants</a><br>';
                    $participants .= '<a href="javascript:"  onclick="participateInGroupRider(\'' . $row1['id'] . '\',\'' . $slots_duration . '\',0,1,\'' . $row1['user_id'] . '\')" class="viewBtn participantLink">Participate</a>';

                    if($row1['is_mark']==1) {

                        if (!in_array($row1['multiple_scheduler_key'], $userContainsArray)) {

                            $events[] = \Calendar::event(
                                "Multi Scheduler",
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $participants,
                                    'notes' => $row1['notes'],
                                    'userId' => $userId,
                                    //'userType' => 'others',
                                    'backgroundColor' => 'green',
                                    'slots_duration' => $slots_duration,
                                    "reason" => $row1['reason'],
                                    "restriction_id" => $row1['restriction_id'],
                                    "is_multiple_selection" => $row1['is_multiple_selection'],
                                    "isMultiple" => 1,
                                    'horse_id' => $row1['horse_id'],
                                    "template_id" => $row1['template_id']

                                ]
                            );

                            $userContainsArray[] = $row1['multiple_scheduler_key'];
                        }


                    }else
                    {

                        if (!in_array($row1['multiple_scheduler_key'], $userContainsArray)) {


                            $events[] = \Calendar::event(
                                "Multi Scheduler",
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $participants,
                                    'notes' => $row1['notes'],
                                    'userId' => $userId,
                                    //'userType' => 'others',
                                    'backgroundColor' => 'green',
                                    'slots_duration' => $slots_duration,
                                    "reason" => $row1['reason'],
                                    "restriction_id" => $row1['restriction_id'],
                                    "is_multiple_selection" => $row1['is_multiple_selection'],
                                    "isMultiple" => 1,
                                    'horse_id' => $row1['horse_id'],
                                    "template_id" => $row1['template_id']

                                ]
                            );

                            $userContainsArray[] = $row1['multiple_scheduler_key'];
                        }

                    }


                }
                else {
                    if ($row1['is_mark'] == 1) {
                        if ($user_id == $userId) {
                            $events[] = \Calendar::event(
                                $row1['notes'],
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [

                                    'backgroundColor' => '#2ca02c',
                                    "isMark" => 1,
                                    'notes' => $row1['notes'],
                                    'description' => $description,
                                    'userId' => $userId,
                                    'horse_id' => $row1['horse_id'],
                                    'asset_id' => $row1['asset_id'],
                                    "asset_name" => GetAssetNamefromId($row1['asset_id']),
                                    "asset_user" => getUserNamefromid($row1['user_id']),
                                    'show_id' => $row1['show_id'],
                                    'slots_duration' => $slots_duration,
                                    "reason" => $row1['reason'],
                                    "assets" => implode(',', $assets[$restriction]),
                                    "restriction_id" => $row1['restriction_id'],
                                    "template_id" => $row1['template_id']
                                ]
                            );
                        } else {
                            $events[] = \Calendar::event(
                                $row1['notes'],
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'backgroundColor' => '#2ca02c',
                                    "isMark" => 1,
                                    'notes' => $row1['notes'],
                                    'description' => $description,
                                    'userId' => $userId,
                                    'userType' => 'others',
                                    'horse_id' => $row1['horse_id'],
                                    'asset_id' => $row1['asset_id'],
                                    "asset_name" => GetAssetNamefromId($row1['asset_id']),
                                    "asset_user" => getUserNamefromid($row1['user_id']),
                                    'show_id' => $row1['show_id'],
                                    'slots_duration' => $slots_duration,
                                    "reason" => $row1['reason'],
                                    "assets" => implode(',', $assets[$restriction]),
                                    "restriction_id" => $row1['restriction_id'],
                                    "template_id" => $row1['template_id']

                                ]
                            );
                        }
                    } else {
                        if ($user_id == $userId) {

                            $events[] = \Calendar::event(
                                $row1['notes'],
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $description,
                                    'notes' => $row1['notes'],
                                    'userId' => $userId,
                                    'userType' => 'participant',
                                    'horse_id' => $row1['horse_id'],
                                    'asset_id' => $row1['asset_id'],
                                    "asset_name" => GetAssetNamefromId($row1['asset_id']),
                                    "asset_user" => getUserNamefromid($row1['user_id']),
                                    'show_id' => $row1['show_id'],
                                    'slots_duration' => $slots_duration,
                                    "reason" => $row1['reason'],
                                    "assets" => implode(',', $assets[$restriction]),
                                    "restriction_id" => $row1['restriction_id'],
                                    "template_id" => $row1['template_id']


                                ]
                            );

                        } else {
                            $events[] = \Calendar::event(
                                $row1['notes'],
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $otherUsers,
                                    'notes' => $row1['notes'],
                                    'horse_id' => $row1['horse_id'],

                                    'userId' => $userId,
                                    'userType' => 'others',
                                    'backgroundColor' => '#000000',
                                    'slots_duration' => $slots_duration,
                                    "reason" => $row1['reason'],
                                    "assets" => implode(',', $assets[$restriction]),
                                    "restriction_id" => $row1['restriction_id'],
                                    "template_id" => $row1['template_id']

                                ]
                            );
                        }
                    }
                }
            }
        }
    }

    if(strpos($slots_duration, ':') !== false) {
        $slots_duration = "00:".$slots_duration;
    } else {
        $slots_duration ="00:".$slots_duration.":00";
    }

    $calendar = \Calendar::addEvents($events); //add an array with addEvents

    $clId= $calendar->getId();

    $calendar->setOptions([ //set fullcalendar options
        'firstDay' => 1,
        'slotDuration'=> "00:30:00",
        'defaultView'=>'agendaWeek',
        'axisFormat'=> 'h:mm:ss a',
//        'eventLimit' => true,
        'columnFormat'=>'dddd / D',
        'draggable'=>false,
        'editable'=> false,
        'clickable'=>true,
        'scrollTime' => $scrollTime,

    ]);
    $calendar->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
        "viewRender"=>"function(view, element) {
     if (view.name === 'month') {
     var arrayFromPHP = ".json_encode($date,true).";

      var formattedEventData = [] ;
            for (var k = 0; k < arrayFromPHP.length; k ++) {
                formattedEventData.push({
                    title: '',
                    start: arrayFromPHP[k][\"dateFrom\"],
                    end:arrayFromPHP[k][\"dateTo\"],
                    rendering:'background',
                    allDay:true,
                  'background' : '#8fdf82'
                });
            }
            
     $('#calendar-".$clId."').fullCalendar('addEventSource',formattedEventData);
  	$('#calendar-".$clId."').fullCalendar('refetchEvents');

   
   }
  
    }",
        'eventClick' => 'function(calEvent, jsEvent, view) {
    
    if(calEvent.userType==\'others\' || calEvent.isMultiple==1)
            {
            return false;
            }
    
    $(".courseContainer").hide();
     $(".ClassHorse").hide();
 $(".ClassHorse select").attr("required",false);
 $(".courseContainer select").attr("required",false);
    var dateSelected=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bg td:eq(\'+$(this).closest(\'td\').index()+\')\').data(\'date\');
    
    var backgroundId=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bgevent-skeleton td:eq(\'+$(this).closest(\'td\').index()+\')\').children().children().children().attr(\'id\');
    
    $("#myDiv").html(\'\');
    
    $(".schedule_id").val("");
    $(".notes").val("");
                
    if(calEvent.isMark==1)   
    {
    markDisabled();
    }
    else
    {
    markEnable();
    }

    if(calEvent.endDaterestriction)
    {
        endTime = moment(calEvent.endDaterestriction).format("HH:mm:ss");
        endDate = moment(calEvent.endDaterestriction).format("YYYY/MM/DD");
    }
    else
    {
        if(calEvent.source.origArray)
        {
            var currentSelected=calEvent.source.origArray[0].start;            
            var currentTime=parseTimestamp(currentSelected);
            var endTime="23:45";
            var endDate=  moment(calEvent.end).format("YYYY/MM/DD");
        }
        else
        {
            var endDateTime=parseTimestamp(calEvent.end);
            endTime = moment(calEvent.end).format("HH:mm:ss");      
            endDate = moment(calEvent.end).format("YYYY/MM/DD") ; 
        }
    }  
    var startDate=moment(calEvent.start).format("YYYY/MM/DD");
    var startTime=moment(calEvent.start).format("HH:mm:ss");
        
       if(calEvent.reason == null)
        {
        $(".ReasonCon").addClass(\'hide\');
        $(".reason").attr(\'required\',false);
        }
       else
       {
        $(".ReasonCon").removeClass(\'hide\');
         $(".reason").val(calEvent.reason)
        $(".reason").attr(\'required\',false);
       }
      
    $("#eventContent").addClass("show");
    $("#eventContent").modal("show");
    $(".notes").val(calEvent.notes);


      $("#Hrs").hide();  
      $("#StrtTime").removeClass("col-sm-3").addClass("col-sm-6");      
      $("#eTime").removeClass("col-sm-3").addClass("col-sm-6");
        $(".restriction_id").val(calEvent.restriction_id);

        getfeedBackLinks(calEvent.id,calEvent.template_id);


        $("#exampleModalLabel2").show();
        $("#exampleModalLabel1").hide();

    $(".assetsTitle").html(calEvent.asset_name);
    $(".userTitle").html(calEvent.asset_user);
    $(".horse_id").html(calEvent.horse_id);

    $(".schedule_id").val(calEvent.id);    
    $(".event_id").val(calEvent.id);    

 
    $(".backgrounbdSlotId").val(backgroundId);
    
    $(".startTime").val(moment(calEvent.start).format("YYYY/MM/DD HH:mm:ss"));    
    $(".endTime").val(endDate+" "+endTime);   
    $(".markDone").show();
    
   $(".assetId").val(calEvent.asset_id);

    
     
       var slots_Time = calEvent.slots_duration;
       
       if(Number.isInteger(slots_Time))
       {
         var slots_duration = parseInt(slots_Time*60);
       }else
       {
          if (slots_Time.indexOf(\':\') > -1)
        {
            var segments =  slots_Time.split(\':\');

            var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

        }else
        {
            return false;

        }
       
       }
           
          
    setTimeout(function () {
        $("select[name=ClassHorse]").val(calEvent.horse_id);
        $(".ClassHorse").selectpicker("refresh")
    },700)

    populate(startTime,endTime,startDate,endDate,moment(calEvent.end).format("HH:mm:ss"),0,slots_duration);
    }',
    "dayClick"=>" function(date, allDay, jsEvent, view) 
    {

   
    if(jsEvent.name == 'month') {
    $('#calendar-".$clId."').fullCalendar('changeView', 'agendaDay');
    $('#calendar-".$clId."').fullCalendar('gotoDate', date);
    return false;
    }
    if (!allDay.target.classList.contains('fc-bgevent')) {
        	alert(\"Please select the date which is available.\");
        }
        $('.schedule_id').val('');
        $('.notes').val('');
       
       
        window.selectedTime=date.format(\"YYYY/MM/DD HH:mm:ss a\");    
    }",
        "eventRender"=>" function (calEvent, element)
    {
        
        eventsdate = moment(calEvent.start).format('hh:mm:ss');
        eventedate = moment(calEvent.end).format('hh:mm:ss');
        element.find('.fc-time').html(eventsdate + \" - \" + eventedate + \"<br>\");
        
        element.find('div.fc-title').html(element.find('div.fc-title').text());                   

        
        if (calEvent.rendering == 'background')
        {
        
        //console.log(calEvent.assets);
        
            element.append('<h6 data-id=\"'+calEvent.assets+'\" id=\"'+calEvent.id+'\">'+calEvent.title+'</h6>');
            $(element).data(calEvent);
        }
        else
        {
        
         
          eventsdate = moment(calEvent.start).format('hh:mm:ss');
          eventedate = moment(calEvent.end).format('hh:mm:ss');
          element.find('.fc-time').html(eventsdate + \" - \" + eventedate + \"<br>\");
        
        
           if(calEvent.userType!='others' && calEvent.isMultiple!=1)
            {
                element.append('<span onclick=\"delEvent(event,this)\" data-id=\"'+calEvent.id+'\" class=\"closeon\">X</span>' );
            }

        if(calEvent.description!='' && calEvent.description!='undefined')
        {       
          element.find('div.fc-title').html('');
            element.append('<p>'+calEvent.description+'</p>' );
        }
         if(calEvent.isMark==1)
         {
            element.append('<img class=\"tickImage\" src=\"/img/check_mark.png\">' );
        }
            element.find('closeon').click(function() {
            $('#calendar').fullCalendar('removeEvents',event._id);
            });
        }
    
    }",
        "eventOverlap"=>" function(stillEvent, movingEvent) 
    {
        return stillEvent.allDay && movingEvent.allDay;
    }",
        "eventMouseover"=>'function(calEvent, jsEvent) 
    {
     if(calEvent.title!="")
    {
        var tooltip = \'<div class="tooltipevent" style="width:200px; padding:10px; border:solid 1px #000; border-radius: 5px; color:#000;height:Auto;background:#ccc;position:absolute;z-index:10001;">\' + calEvent.title + \'</div>\';
       // var $tooltip = $(tooltip).appendTo("body");   
        $(this).mouseover(function(e) {
        $(this).css("z-index", 10000);
      //  $tooltip.fadeIn("500");
      //  $tooltip.fadeTo("10", 1.9);
        }).mousemove(function(e) {
      //  $tooltip.css("top", e.pageY + 10);
     //   $tooltip.css("left", e.pageX + 20);
        });
     }   
    }',
        'eventMouseout'=>"function(calEvent, jsEvent) 
    {
        $(this).css('z-index', 8);
        $('.tooltipevent').remove();
    }",

    ]);

    $arr=[];
    $arr['calendar']=$calendar;
    $arr['clId']=$clId;

    return $arr;

}


function primaryEvents($FormTemplate, $scheduals,$template_id,$dateFrom)
{

    $scrollTime = '';

    if(!is_null($dateFrom))
        $scrollTime = last(explode(' ',$dateFrom));

    $events = [];
    $date = [];

    $alreadyExist = [];
    //dd($FormTemplate->toArray());
    foreach ($FormTemplate as $row) {

        $restriction = $row->restriction;
        $slots_duration = $row->slots_duration;

        if($slots_duration==0 && $slots_duration=='')
            $slots_duration =30;

        $templateType = GetTemplateType($template_id);


        if($templateType==TRAINER)
            $schedulerTitle = GetAssetNamefromId($row->asset_id);
        else
            $schedulerTitle = GetAssetNamefromId($row->show_id);


        if ($restriction) {

            $var = explode('-', $restriction);

            if (count($var) > 0) {
                $timeFrom[] = date('H:i', strtotime($var[0]));
                $timeTo[] = date('H:i', strtotime($var[1]));
                $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));
                $dateTo = date('Y-m-d H:i:s', strtotime($var[1]));
                $date[] =['dateFrom'=>date('Y-m-d H:i:s', strtotime($var[0])),'dateTo'=>date('Y-m-d H:i:s', strtotime($var[1]))];

                if($row->is_multiple_selection==1) {

                    $events[] = \Calendar::event(
                        'Multiple Scheduler',
                        false,
                        $dateFrom,
                        $dateTo,
                        $row->scheduler_id,
                        [
                            'rendering' => 'background',
                            'scheduler_id' => $row->scheduler_id,
                            'restriction_id' => $row->id,
                            "is_multiple_selection" => $row->is_multiple_selection,
                            'show_id' => $row->show_id,
                            'form_id' => $row->form_id,
                            "restriction_id" => $row->id,
                            'template_id' => $template_id,

                        ]
                    );
                }else {

                    $events[] = \Calendar::event(
                        $schedulerTitle,
                        false,
                        $dateFrom,
                        $dateTo,
                        $row->scheduler_id,
                        [
                            'rendering' => 'background',
                            'scheduler_id' => $row->scheduler_id,
                            'show_id' => $row->show_id,
                            'form_id' => $row->form_id,
                            'restriction_id' => $row->id,
                            "is_multiple_selection" => $row->is_multiple_selection,
                            'template_id' => $template_id,

                        ]
                    );
                }
            }

        } else {

            $dateFrom = date('Y-m-d H:i:s');
            $events[] = \Calendar::event(
                $schedulerTitle,
                false,
                '2017-4-21T10:00:00',
                '2030-4-21T9:00:00',
                0,
                [
                    'rendering' => 'background',
                    "restrictionType"=>1
                ]
            );
        }

    };

    if($scheduals) {

        $userContainsArray = [];

        // dd($scheduals->toArray());
        foreach ($scheduals as $row1) {
            $pre_fields1 = json_decode($row1['time_slot'], true);
            for ($j = 0; $j < count($pre_fields1); $j++) {
                $var1 = explode('-', $pre_fields1[$j]);

                if (count($var1) > 0) {
                    $timeFrom[] = date('H:i:s', strtotime($var1[0]));
                    $timeTo[] = date('H:i:s', strtotime($var1[1]));
                    $dateFrom = date('Y-m-d H:i:s', strtotime($var1[0]));
                    $dateTo = date('Y-m-d H:i:s', strtotime($var1[1]));
                    $date[] = ['dateFrom' => date('Y-m-d H:i:s', strtotime($var1[0])), 'dateTo' => date('Y-m-d H:i:s', strtotime($var1[1]))];

                    $description = '<span>'.getUserNamefromid($row1['user_id']).'</span> <br />
                                    <span class="assetClass">'.GetAssetNamefromId($row1['asset_id']).'</span> <br />
                                    <span class="assetClass">'.GetAssetNamefromId($row1['horse_id']).'</span>';

                    if ($row1['is_multiple_selection'] == 1) {
                        $participants = '<a style="width:100%;" href="javascript:" onclick="getGroupsParticipants(\'' . $row1['show_id'] . '\',\'' . $row1['schedual_id'] . '\',\'' . $slots_duration . '\',\'' . $dateFrom . '\',\'' . $dateTo . '\',2,\'' . $row1['restriction_id'].'\')" class="viewBtn">View participants</a><br>';
                        $participants .= '<a href="javascript:"  onclick="participateInGroup(\'' . $row1['id'] . '\',\'' . $slots_duration . '\',0,2,\'' . $row1['user_id'] . '\')" class="viewBtn participantLink">Participate</a>';

                        if (!in_array($row1['multiple_scheduler_key'], $userContainsArray)) {
                            $events[] = \Calendar::event(
                                $participants,
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $participants,
                                    'notes' => $row1['notes'],
                                    "userId" => $row1['user_id'],
                                    "formId" => $row1['form_id'],
                                    "scheduler_id" => $row1['schedual_id'],
                                    "template_id" => $row1['template_id'],
                                    "asset_id" => $row1['asset_id'],
                                    'backgroundColor' => 'green',
                                    "isMultiple" => 1,
                                    "show_id" => $row1['show_id'],
                                    "horse_id" => $row1['horse_id'],
                                    "reason" => $row1['reason'],
                                    'slots_duration' => $slots_duration,
                                    "is_multiple_selection" => $row1['is_multiple_selection'],
                                    "restriction_id" => $row1['restriction_id'],
                                    "multiple_scheduler_key" => $row1['multiple_scheduler_key'],

                                ]
                            );
                            $userContainsArray[] = $row1['multiple_scheduler_key'];
                        }

                    }else {

                        if ($row1['is_mark'] == 1) {
                            $events[] = \Calendar::event(
                                $description,
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $description,
                                    'notes' => $row1['notes'],
                                    'backgroundColor' => '#2ca02c',
                                    "isMark" => 1,
                                    "userId" => $row1['user_id'],
                                    "formId" => $row1['form_id'],
                                    "scheduler_id" => $row1['schedual_id'],
                                    "template_id" => $row1['template_id'],
                                    "asset_id" => $row1['asset_id'],
                                    "show_id" => $row1['show_id'],
                                    "reason" => $row1['reason'],
                                    "asset_name" => GetAssetNamefromId($row1['asset_id']),
                                    "asset_user" => getUserNamefromid($row1['user_id']),
                                    "restriction_id" => $row1['restriction_id'],
                                    'slots_duration' => 30,
                                    'horse_id' => $row1['horse_id']


                                ]
                            );
                        } else {
                            $events[] = \Calendar::event(
                                $description,
                                false,
                                $dateFrom,
                                $dateTo,
                                $row1['id'],
                                [
                                    'description' => $row1['notes'],
                                    'notes' => $row1['notes'],
                                    "userId" => $row1['user_id'],
                                    "formId" => $row1['form_id'],
                                    "scheduler_id" => $row1['schedual_id'],
                                    "template_id" => $row1['template_id'],
                                    "asset_id" => $row1['asset_id'],
                                    "asset_name" => GetAssetNamefromId($row1['asset_id']),
                                    "asset_user" => getUserNamefromid($row1['user_id']),
                                    "show_id" => $row1['show_id'],
                                    "horse_id" => $row1['horse_id'],
                                    "is_multiple_selection" => $row1['is_multiple_selection'],
                                    "reason" => $row1['reason'],
                                    'slots_duration' => 30,
                                    "restriction_id" => $row1['restriction_id']


                                ]
                            );
                        }
                    }
                }
            }
        }
    }

    $slots_duration ="00:30:00";

    $calendar = \Calendar::addEvents($events); //add an array with addEvents

    $clId= $calendar->getId();

    $calendar->setOptions([ //set fullcalendar options
        'firstDay' => 1,
        'defaultView'=>'agendaWeek',
        'axisFormat'=> 'h:mm:ss a',
        'columnFormat'=>'dddd  / D',
        'clickable'=>true,
        'scrollTime' => $scrollTime,
        'slotDuration'=> $slots_duration

    ]);



    $calendar->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)

        "viewRender"=>"function(view, element) {
                
   if(view.name === 'month') {
     var arrayFromPHP = ".json_encode($date,true).";
     var formattedEventData = [] ;
            for (var k = 0; k < arrayFromPHP.length; k ++) {
                formattedEventData.push({
                    title: '',
                    start: arrayFromPHP[k][\"dateFrom\"],
                    end:arrayFromPHP[k][\"dateTo\"],
                    rendering:'background',
                    allDay:true,
                  'background' : '#8fdf82'
                });
            }
     $('#calendar-".$clId."').fullCalendar('addEventSource',formattedEventData);
  	 $('#calendar-".$clId."').fullCalendar('refetchEvents');
   }
  
    }",
        'eventClick' => 'function(calEvent, jsEvent, view) 
        {
        var dateSelected=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bg td:eq(\'+$(this).closest(\'td\').index()+\')\').data(\'date\');
        var backgroundId=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bgevent-skeleton td:eq(\'+$(this).closest(\'td\').index()+\')\').children().children().children().attr(\'id\');
        $("#myDiv").html(\'\');
        $("#schedule_id").val("");
        $("#notes").val("");
     $(".courseContainer").hide();
     $(".ClassHorse").hide();

       
     if(calEvent.is_drag==1 || calEvent.isMultiple==1)            
    {
        return false;
    }
    

                    
        if(calEvent.isMark==1)   
        {
            markDisabled();
        }
        else
        {
            markEnable();
        }

        if(calEvent.endDaterestriction)
        {
             endTime = moment(calEvent.endDaterestriction).format("HH:mm:ss");
             endDate = moment(calEvent.endDaterestriction).format("YYYY/MM/DD");
        }
        else
        {
            if(calEvent.source.origArray)
            {
                var currentSelected=calEvent.source.origArray[0].start;
                var currentTime=parseTimestamp(currentSelected);
                var endTime="23:45";
                var endDate=  moment(calEvent.end).format("YYYY/MM/DD");
            }
            else
            {
                var endDateTime=parseTimestamp(calEvent.end);
                endTime = moment(calEvent.end).format("HH:mm:ss");      
                endDate = moment(calEvent.end).format("YYYY/MM/DD") ; 
            }
        }  
        
        
        
        var startDate=moment(calEvent.start).format("YYYY/MM/DD");
        var startTime=moment(calEvent.start).format("HH:mm:ss");
        var spectatorsId= $("#spectatorsId").val();
       $("#is_multiple_selection").val(calEvent.is_multiple_selection);    

       
       
        if(spectatorsId > 0)
        {
            hideButtonSpectator();
        }        
                       
        if(calEvent.reason == null)
        {
            $(".ReasonCon").addClass(\'hide\');
            $(".reason").attr(\'required\',false);
        }
       else
        {
            $(".ReasonCon").removeClass(\'hide\');
            $(".reason").val(calEvent.reason)
            $(".reason").attr(\'required\',false);
        }
        
        getfeedBackLinks(calEvent.id,calEvent.template_id);
        
        
        $("#eventContent").addClass("show");
        $("#eventContent").modal("show");
        $(".notes").val(calEvent.notes);
        $(".reason").val(calEvent.reason);
        $(".schedule_id").val(calEvent.id);
        $(".event_id").val(calEvent.id);

        
        
        $(".templateId").val(calEvent.template_id);
        $(".assetId").val(calEvent.asset_id);
        $(".event_asset_id").val(calEvent.asset_id);
        $(".restriction_id").val(calEvent.restriction_id);
        $(".assetsTitle").html(calEvent.asset_name);
        $(".userTitle").html(calEvent.asset_user);
       $(".horse_id").val(calEvent.horse_id);
 
        $(".userId").val(calEvent.userId);
        var el = document.querySelector(".schedulerProfileView");
        el.setAttribute(\'data-id\',calEvent.userId);
    
        $(".form_id").val(calEvent.formId);
        $(".masterScheduler").val(1);
        $(".backgrounbdSlotId").val(calEvent.scheduler_id);
        
        $(".startTime").val(moment(calEvent.start).format("YYYY/MM/DD HH:mm:ss"));    
        $(".endTime").val(endDate+" "+endTime);   
        $(".markDone").show();
        
        var h =$(".feeds").attr("href");
        h = h.replace("{sheduleId}",calEvent.id);
        $(".feeds").attr("href",h);
        
        setTimeout(function () {
            $("select[name=ClassHorse]").val(calEvent.horse_id);
            $(".ClassHorse").selectpicker("refresh");
            $("#selectUser").val(calEvent.userId);     
        },1000);
                
       var slots_Time = calEvent.slots_duration;
       
         if (String(slots_Time).indexOf(\':\') > -1)
        {
            var segments =  slots_Time.split(\':\');
            var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])
        }
        else
        {
            var slots_duration = parseInt(slots_Time*60);
        }
    
        populate(startTime,endTime,startDate,endDate,moment(calEvent.end).format("HH:mm:ss"),0,slots_duration);
    }',

        "eventRender"=>" function (calEvent, element) 
     {
     
         eventsdate = moment(calEvent.start).format('hh:mm:ss');
          eventedate = moment(calEvent.end).format('hh:mm:ss');
          element.find('.fc-time').html(eventsdate + \" - \" + eventedate + \"<br>\");          
          element.find('div.fc-title').html(element.find('div.fc-title').text());                   
       
        if (calEvent.rendering == 'background') 
        {
       
        if(calEvent.is_multiple_selection=='1'){
                element.addClass('multipleSelection');
            }
            
        element.append('<h6  id=\"'+calEvent.id+'\">'+calEvent.title+'</h6>');
        $(element).data(calEvent);
        }
        else
        {
       
       
        if(calEvent.description!='' && calEvent.description!='undefined')
        {       
           // element.append('<p>'+calEvent.description+'</p>' );
        }  
        if(calEvent.isMark==1)
            element.append('<img class=\"tickImage\" src=\"/img/check_mark.png\">' );
        
         if(calEvent.is_multiple_selection!='1')
        {
        element.append('<span onclick=\"delEvent(event,this)\" data-id=\"'+calEvent.id+'\" class=\"closeon\">X</span>' );
        }else
          {
        element.append('<span onclick=\"delGroupMultiEvent(event,this,'+calEvent.id+')\" data-id=\"'+calEvent.multiple_scheduler_key+'\" class=\"closeon\">X</span>' );
        }
                     
        }
    }",
        "dayClick"=>" function(date, allDay, jsEvent, view) 
    {
        if(jsEvent.name == 'month') {
        $('#calendar-".$clId."').fullCalendar('changeView', 'agendaDay');
        $('#calendar-".$clId."').fullCalendar('gotoDate', date);
        return false;
        }
        if (!allDay.target.classList.contains('fc-bgevent')) 
        {
            alert(\"Please select the date which is available.\");
        }
        $('.schedule_id').val('');
        $('.notes').val('');
        window.selectedTime=date.format(\"YYYY/MM/DD HH:mm:ss a\");    
    }",
        "eventMouseover"=>'function(calEvent, jsEvent) 
    {
        if(calEvent.description!="")
        {
          
           if(calEvent.isMultiple==1)
           {
            var tooltip = \'<div class="tooltipevent" style="width:200px; padding:10px; border:solid 1px #000; border-radius: 5px; color:#000;height:Auto;background:#ccc;position:absolute;z-index:10001;">\' + calEvent.description + \'</div>\';
           }else{
            var tooltip = \'<div class="tooltipevent" style="width:200px; padding:10px; border:solid 1px #000; border-radius: 5px; color:#000;height:Auto;background:#ccc;position:absolute;z-index:10001;">\' + calEvent.title + \'</div>\';
            }          
          
         //   var tooltip = \'<div class="tooltipevent" style="width:200px; padding:10px; border:solid 1px #000; border-radius: 5px; color:#000;height:Auto;background:#ccc;position:absolute;z-index:10001;">\' + calEvent.title + \'</div>\';
           // var $tooltip = $(tooltip).appendTo("body");
            
            $(this).mouseover(function(e) {
            $(this).css("z-index", 10000);
          //  $tooltip.fadeIn("500");
          //  $tooltip.fadeTo("10", 1.9);
            }).mousemove(function(e) {
         //   $tooltip.css("top", e.pageY + 10);
         //   $tooltip.css("left", e.pageX + 20);
            });
        }
    }',
        'eventMouseout'=>"function(calEvent, jsEvent) {
    $(this).css('z-index', 8);
    $('.tooltipevent').remove();
    }",
    ]);

    $arr=[];
    $arr['calendar']=$calendar;

    $arr['clId']=$clId;

    return $arr;
}

function getHorsesForScheduler($horse_id ,$class_id ){
    return ClassHorse::select("id","horse_id","horse_reg","scratch")->where("horse_id",$horse_id)->where("class_id",$class_id)->first();
}


function getHorsesRiderForScheduler($horse_id ,$class_id ){
    return ClassHorse::select("id","horse_id","horse_reg","horse_rider")->where("horse_id",$horse_id)->where("class_id",$class_id)->orderBy('id','desc')->pluck('horse_rider')->first();

}
function updateInvoiceDetail($total,$id){

    $model = Invoice::findOrFail($id);
    $model->amount = $total;
    $model->update();
}
function getClassCount($show_id,$class_id){
	return $CH = classHorse::where('show_id',$show_id)->where('class_id',$class_id)
					->where('scratch',HORSE_NOT_SCRATCHED)
					->groupBy('horse_id')
					->get()->count();
}

function getFromAccessRights($form_id)
{
   return Form::where('id',$form_id)->pluck('accessable_to')->first();
}
function removeScratchScheduler($show_id,$user_id,$class_id,$horse_id){

  $scheduler = SchedualNotes::where('user_id',$user_id)
        ->where('show_id',$show_id)
        ->where('asset_id',$class_id)
        ->where('horse_id',$horse_id)->first();
 //  echo $scheduler->id;exit;
  if($scheduler) {
    $feedBack = SchedulerFeedBacks::where('schedule_id',$scheduler->id);
      $feedBack->delete();
      $reminder = SchedulerReminder::where('scheduler_id',$scheduler->id);
      $reminder->delete();
      $scheduler->delete();
  }

}


function prizeClaimCount($horse_id,$show_id){

  return  PrizeClaimForm::where('horse_id',$horse_id)->where('show_id',$show_id)->count();

}

function getScratchHorseCount($show_id){

     $arr['unScratch'] = ClassHorse::where('show_id',$show_id)->where('scratch',0)->groupBy('horse_id')->get()->count();
     $arr['scratch'] = ClassHorse::where('show_id',$show_id)->where('scratch',1)->groupBy('horse_id')->get()->count();

    return $arr;

}

function getSchedulerTime($show_id,$participant_id,$class_id,$horse_id)
{

        $html ='';



            $scheduler = SchedualNotes::where('show_id',$show_id)->where('asset_id',$class_id)->where('horse_id',$horse_id)->first();
            if($scheduler)
            {
                if($scheduler->timeFrom!='')
                $html =date('m-d-Y g:i A', strtotime($scheduler->timeFrom));
            }



    if($html=='')
        $html .="No Scheduler Exist";

    return $html;

    }


function getCategories($categoryArr)
{
    $html ='';

   $cateTitle = $categoryArr->toArray();

    foreach ($cateTitle as $cat)
    {
    $html .='<strong>'.$cat['category_title'].'</strong>';
    $html .="<br>";
    }
    return $html;
}

function getCategoriesAmount($categoryArr)
{
    $total =0;

    $cateTitle = $categoryArr->toArray();

    foreach ($cateTitle as $cat)
    {
        $total = $total+$cat['category_price'];
    }
    return $total;

}


function getSposnorCategories($categoryId)
{
    $catArr = explode(',',$categoryId);

    return SponsorCategories::whereIn('id',$catArr)->get();

}

function GetSponsorName($showFormId,$user_id){

    $fields = ShowSponsors::where('id',$showFormId)->pluck('fields')->first();

    $pre_fields = json_decode($fields);
    $name = "";
    if (isset($pre_fields)) {
        foreach ($pre_fields as $key => $value) {
            if ($value->answer != "") {
                if (is_array($value->answer)) {
                    $name = $value->answer[0];
                    break;
                }else{
                    $name = $value->answer;
                    break;
                }
            }else{
                $name = getUserNamefromid($user_id);
                break;
            }
        }
    }

    return $name;
}

function getFirstFieldAnswer($fieldObj){
	$fields = $fieldObj->fields;
	$pre_fields = json_decode($fields);
    $name = "";
    if (isset($pre_fields)) {
        foreach ($pre_fields as $key => $value) {
            if ($value->answer != "") {
                if (is_array($value->answer)) {
                    $name = $value->answer[0];
                    break;
                }else{
                    $name = $value->answer;
                    break;
                }
            }else{
                $name = getUserNamefromid($user_id);
                break;
            }
        }
    }

    return $name;
}

function getStallTypeById($id)
{

   return StallTypes::where('id',$id)->pluck('stall_type')->first();

}
function getStallTypes($str){
    $arr = json_decode($str);
    $html ='';
    if (!empty($arr)) { 	
    	foreach ($arr as $k=>$v)
	    {
	        if($v!='')
	        $html .="<strong>". getStallTypeById($k)."</strong> = ".$v.'</br>';
	    }
    }
    
    return $html;
 }

function getRemainingStallTypes($id,$str){
    $arr = json_decode($str);
    $html ='';
    if (!empty($arr)) { 	
	    foreach ($arr as $k=>$v)
	    {
	        if($v!='') {
	            $collection = ShowStallRequest::where('approve_stable_id',$id)->where('stall_type_id',$k)->where('status',1)->sum('quantity');
	            $remainingQ= $v-$collection;
	            $html .= "<strong>" . getStallTypeById($k) . "</strong> = " .$remainingQ. '</br>';
	        }
	    }
    }
    return $html;
}



    function getstallSavedValues($id,$userArr=null,$stallHorse=null){

        $html = '';
        $stallArr = [];
        $select ='';

        $alreadyAdded = [];

        $assign = ShowStallRequest::where('id',$id)->first();

        if ($assign->stall_number != '')
            $stallNoArr = explode(',', $assign->stall_number);

        if(isset($stallHorse)) {

          $remainingStalls = $stallHorse->where('stall_no','!=','')->count();

            if ($stallHorse->count() > 0) {

                foreach ($stallHorse as $st) {
                    if ($st->stall_no != '') {
                        $html .= '<div class="col-sm-12" style="padding-left: 0px;"><label><strong>Rider/Horse/Stall : </strong></label> ' . getUserNamefromid($st->rider_id) . '/' . getHorseNameAsLinkFromId($st->horse_id) . '/' . $st->stall_no . '</div>';
                        $stallArr[] = $st->stall_no;
                    }else{
                        $pselect='';
                        if(count($stallNoArr)>0 && $st->horse_id!=0)
                        {

                            $alreadyAdded[]=$st->id;

                            $pselect = "<div class=\"col-sm-4\"><select class='form-control' name='stallNumber[".$st->id."]'><option value=''>Select Stall No</option> ";
                            $stalls = array_diff_assoc($stallNoArr, $stallArr);

                            foreach ($stalls as $stall)
                                $pselect .= "<option value='" . $stall . "'>" . $stall . "</option>";
                            $pselect .="</select>";
                        }

                        $count = 0;

                            $horse_id = $st->horse_id;
                            if($horse_id)
                            $horseName = '['.getHorseNameAsLinkFromId($horse_id).']';
                            else
                            $horseName = '';

                            $html .= '<div class="col-sm-12"  style="padding: 5px 0px;"><div class="row ml-0"> <div class="col-sm-5" style="padding:5px 0px;"><strong>'.getUserNamefromid($st->rider_id).'</strong>'.$horseName.'</div>'.$pselect.'</div></div>';
                            if($pselect!='')
                                $html .= '<input   value="'.$st->id.'" type="hidden" name="showStallHorses[]"/></div>';
                            $count ++;

                        $html .= '<input class="cls-'.$st->id.'"  value="'.$count.'" type="hidden" name="counter"/>';

                    }
                }

               // echo $stallHorse->where('horse_id','!=',0)->count().'>>>>';

                     $quantity = $assign->quantity - $stallHorse->where('horse_id','!=',0)->count();

                    if ($quantity > 0) {
                        $stalls = array_diff_assoc($stallNoArr, $stallArr);
                        $html .= '<div class="fieldsContainer col-md-12 mt-10" style="padding: 0px;"><div class="row recordCon  ml-0"> <div class="col-md-4" style="padding: 0px;">
                     <select required  style="padding: 0px; width: 80%" name="riders[' . $id . '][]" class="form-control assign" onchange="getHorses($(this),' . $assign->show_id . ')">
                     <option value="">Rider</option>';
                        foreach ($userArr as $k => $v)
                            $html .= '<option value="' . $k . '">' . $v . '</option>';
                        $html .= '</select>';
                        $html .= '</div><div class="col-md-4" style="padding: 0px;">
                     <select required  style="padding: 0px; width: 80%" name="horses[' . $id . '][]" class="form-control assign horseContainer">
                     <option value="">Horse</option>
                     </select>
                     </div>';
                        $html .= '<div class="col-md-3" style="padding: 0px;">
                     <select required  style="padding: 0px; width: 80%" name="stalls[' . $id . '][]" class="form-control assign">
                     <option value="">Stalls</option>';
                        foreach ($stalls as $st) {
                            $html .= '<option value="' . $st . '">' . $st . '</option>';
                        }

                        $html .= '</select>';
                        $html .= '</div>
                       <div class="col-md-1" style="padding: 0px;">
                           <button type="button" style="padding:0px 5px; margin-top: 6px; margin-left: 8px;" class="btn btn-default addUtility" data-total-quantity="' . $assign->quantity.'" data-id="' . $id . '" data-quantity="' . $quantity . '">
                               <i class="fa fa-plus"></i></button>
                           <button type="button" style="padding:0px 5px; margin-top: 6px; margin-left: 8px;" onclick="removeCurrent($(this))" class="btn hide btn-default removeButton">
                               <i class="fa fa-minus"></i></button>
                       </div>
                       </div>
                   </div> ';


                    }

                 if(count($stallNoArr) > $remainingStalls)
                $html .='<div class="col-sm-12" style="margin-top: 5px; padding-left: 0px;"><button style="border-radius: 5px;" class="btn btn-success">Save</button></div>';

            }
        }

        return $html;
    }

    function getStallAssignRiders($str,$id,$stalls)
    {


      $stQ = ShowStallRequest::where('id',$id)->first();

      $quantity = $stQ->quantity;

      $strArr = json_decode($str,true);

      $html = '';
      $select = '';
        if(count($stalls)>0)
        {
        foreach ($stalls as $stall)
        $select .="<option value='".$stall."'>".$stall."</option>";
        $select .="</select></div>";
        }

        $count = 0;
        foreach ($str as $row)
        {
            $horse_id = $row->horse_id;

            if($select!='') {
                $pselect = "<div class=\"col-sm-3\"><select class='form-control' name='stallNumber[".$row->id."]'><option value=''>Select Stall No</option> ";
                $pselect .=$select;
            }else{
                $pselect = '';
            }

            $html .= '<div class="col-sm-12"  style="padding: 5px 0px;"><div class="col-sm-6" style="padding:5px 0px;"><strong>'.getUserNamefromid($row->rider_id).'</strong>['.GetAssetNamefromId($horse_id).']</div>'.$pselect;
            if($pselect!='')
            $html .= '<input   value="'.$row->id.'" type="hidden" name="showStallHorses[]"/></div>';
            $count ++;
        }
        $html .= '<input class="cls-'.$id.'"  value="'.$count.'" type="hidden" name="counter"/>';

        return $html;
    }


    function getViewResponseData($id){

        $pResponse = ShowStallRequest::where('id',$id)->first();

        $html = '';

        if($pResponse->status==1) {
            $html .= '<div class="col-sm-12 row"><div class="col-sm-3" style="padding-right: 0px;"> <strong>Status :</strong> </div>';
            $html .= '<div class="col-sm-8" style="padding-left: 0px;"> Approved</div></div>';
            $html .= '<div class="col-sm-12 row"><div class="col-sm-3" style="padding-right: 0px;"> <strong>Stable :</strong> </div>';
            if(isset($pResponse->stable))
                $html .= '<div class="col-sm-8" style="padding-left: 0px;"> '.$pResponse->stable->name.'</div></div>';
            $html .= '<div class="col-sm-12 row"><div class="col-sm-3" style="padding-right: 0px;"> <strong>Stall# :</strong></div>';

            if ($pResponse->stall_number != "") {
                $stalls = explode(",", $pResponse->stall_number);
                $c = 0;
                $offset ='';
                foreach ($stalls as $s) {
                    $c++;
                    if ($c > 1) {
                        $offset = "offset-md-3";
                    }
                    $html .= '<div class="col-sm-7 ' . $offset . '" style="padding: 4px 0px 4px 0px; border-bottom: solid 1px #cdcdcd">';
                    $html .= $s . '<br></div>';
                }
            }
            $html .= '</div>';
        }elseif($pResponse->status==2) {
            $html .= '<div class="col-sm-12 row"><div class="col-sm-3" style="padding-right: 0px;"> <strong>Status :</strong> </div>';
            $html .= '<div class="col-sm-9" style="padding-left: 0px;"> Rejected</div></div>';
            $html .= '<div class="col-sm-12 row"><div class="col-sm-4" style="padding-right: 0px;"> <strong>Comments :</strong> </div>';
            $html .= '<div class="col-sm-8" style="padding-left: 0px;"> '.$pResponse->comments.'</div></div>';
        }
        return $html;

    }


    function getTotalStallTypeOfStable($stable_id,$type_id)
    {

        $stall_types = ShowStables::where('id',$stable_id)->pluck('stall_types')->first();
        $arr = json_decode($stall_types);
        $html ='';
        $totalType = '';
        foreach ($arr as $k=>$v)
        {
            if($v!='' && $k== $type_id)
            {
                $totalType = $v;

            }
        }
        return $totalType;



    }

   function getUserOccupiedStalls($stabel_id,$id){

       $collection = DB::table('show_stall_requests')
           ->join('users', 'users.id', '=', 'show_stall_requests.user_id')
           ->select('users.name', DB::raw('SUM(quantity) as total'))
           ->where('approve_stable_id',$stabel_id)
           ->where('status',1)
           ->where('stall_type_id',$id)

           ->groupBy('user_id')
           ->get();

       $html ='';


       if($collection->count()>0) {
           foreach ($collection as $col) {
               $html .= '<div style="padding-left: 0px;" class="col-sm-12"><strong>' . $col->name . '</strong> = <strong>' . $col->total . '</strong></div>';
           }
       }else
       {
           $html .="<strong>All Availables</strong>";
       }


return $html;

    }

function horsesLinkedStallType($stabel_id,$id){

    $collection = ShowStallRequest::where('stall_type_id',$id)
        ->where('approve_stable_id',$stabel_id)
        ->where('status',1)
        ->select('stall_type_id','show_id')->groupBy('stall_type_id')->get();

    $html ='';
    if($collection->count()>0) {
        foreach ($collection as $row) {

         $stallsAssined =  HorseRiderStall::where('stall_type_id',$row->stall_type_id)->get();

            if ($stallsAssined->count() > 0) {

                foreach ($stallsAssined as $r) {

                    $stallno = '[Stall#' . $r->stall_no . ']';

                    $existing_horse = ClassHorse::where("show_id", $r->show_id)->where("horse_id", $r->horse_id)->first();

                    if ($existing_horse)
                        $html .= '<div style="padding-left: 0px;" class="col-sm-12"><a  target="_blank" href="/master-template/' . nxb_encode($r->horse_id) . '/horseProfile" ><strong>' . GetAssetNamefromId($r->horse_id) . '</strong>[Entry#' . $existing_horse->horse_reg . ']' . $stallno . '</a></div>';
                    else
                        $html .= '<div style="padding-left: 0px;" class="col-sm-12"><a  target="_blank" href="/master-template/' . nxb_encode($r->horse_id) . '/horseProfile" ><strong>' . GetAssetNamefromId($r->horse_id) . '</strong></a></div>';

                }
            }else{

                $html = "<strong>No Horse Linked</strong>";
            }
            }
        }
    else{
        $html = "<strong>No Horse Linked</strong>";
    }
    return $html;

}


function getRiderRestrcition($id){

  return  SchedulerRestriction::where('id',$id)->pluck('is_rider_restricted')->first();

}

function getRemainingStalls($collection,$type){
    $html ='';
    if($type=='action')
    {

        $html .="<div class='col-sm-10' style='margin-bottom: 20px;padding-left: 0px;'>";
        $html .="<select required class='form-control selectpicker' multiple data-width=\"100%\" title='Select Stalls' name='stallNos[]'>";


    foreach ($collection as $k=>$v) {
        if (isset($v)) {
            foreach ($v as $s)
                $html .= "<option value='" . $s.'|||'.$k . "'>#" . $s . " [".getStallTypeById($k)."]</option>";
        }
    }
       $html .="</select></div>";
    }else {
        foreach ($collection as $k => $v) {
            if(count($v)>0) {
                if (isset($v)) {

                    if ($type == 'stalls' and count($v) > 0)
                        $stall = '#' . implode(' ,&nbsp;  #', $v);
                    else
                        $stall = count($v);
                    $html .= "<strong>" . getStallTypeById($k) . "</strong> = " . $stall . '</br>';
                }
            }
        }
    }

    return $html;
}

function array_non_empty_items($input) {
    // If it is an element, then just return it
    if (!is_array($input)) {
        return $input;
    }

    $non_empty_items = array();

    foreach ($input as $key => $value) {
        // Ignore empty cells
        if($value) {
            // Use recursion to evaluate cells
            $non_empty_items[$key] = array_non_empty_items($value);
        }
    }

    // Finally return the array without empty items
    return $non_empty_items;
}
    function assignPriceToPositions($position,$asset_id)
    {
        $positions = ShowPrizing::where("asset_id", $asset_id)->first();
        $placing = null;
        if ($positions) {
            $placing = json_decode($positions->fields,true);

            foreach ($placing['place'] as $k=>$v){
                if($position==$k)
                {
                    return $v['price'];
                }
            }
        }

    }

function record_sort_price_position($records, $field, $reverse=false,$asset_id)
{
    $hash = array();
    if(isset($records)) {
        foreach ($records as $record) {
            if (isset($record[$field]))
                $hash[$record[$field]] = $record;
        }

        ($reverse) ? krsort($hash) : ksort($hash);

        $records = array();
        $position = 1;

        foreach ($hash as $k=>$v)
        {

            $price = assignPriceToPositions($position,$asset_id);

            $hash[$k]['position']=$position;
            $hash[$k]['price']=$price;
            $position = $position +1;


        }

        foreach ($hash as $record) {
            $records [] = $record;
        }

        return $records;
    }else
        return false;
}


function record_sort($records, $field, $reverse=false)
{
    $hash = array();
    if(isset($records)) {
        foreach ($records as $record) {
            if (isset($record[$field]))
                $hash[$record[$field]] = $record;
        }
        ($reverse) ? krsort($hash) : ksort($hash);

        $records = array();

        foreach ($hash as $record) {
            $records [] = $record;
        }

        return $records;
    }else
        return false;
    }


function getScoreValues($asset_id,$show_id,$horse_id,$restriction_id,$form_id){

  $res = ShowPrizingListing::where('asset_id',$asset_id)->where('show_id',$show_id)->where('form_id',$form_id)->first();
  if (isset($res)) {
	  $positions =  json_decode($res->position_fields,true);

	  //echo $restriction_id;

	  foreach ($positions as $k => $v){

	      if (isset($v['horse_id'])) {
	          if ($v['horse_id'] == $horse_id) {
	              if (isset($v['rounds'][$restriction_id])) {
	                  return $v['rounds'][$restriction_id];
                  }
	              else
	                  return null;
	          }
	      }
	  }
  }
  return null;
}

    function checkHorseExist($pre,$horse_id){

    foreach ($pre as $pr)
    {
    if(isset($pr['horse_id']) && $pr['horse_id']==$horse_id)
    return true;
    }
    return false;
    }



    function isScoringClasses($asset_id){

        $scoreArr = [];
        $scoreFroms =  SchedulerRestriction::where('asset_id',$asset_id)->where('score_from','!=',null)->get();

        foreach ($scoreFroms as $score){
            $scoreArr[] =explode(',',$score->score_from);
        }

        return $scoreArr;

    }


    function getScoreFromClasses($arr,$index){

        $html = '';
        if(isset($arr[$index]['scoreFrom']))
        {
           foreach ($arr[$index]['scoreFrom'] as $sc){
               //dd($sc['class_id']);
               $html .='<tr><td style="padding: 0px 5px 10px 5px;"><span style="width: 140px; float:left; overflow: inherit"> <strong>'.GetAssetNamefromId($sc["class_id"]).'  </strong></span><span style="font-weight: bold; float: right; color: #00C851">'.$sc["ClassScore"].'</span></td></tr>';
           }

        }

        return $html;

    }


    function checkHorseExistInClassic($horse_id,$key,$participantsArr)
    {

        foreach ($participantsArr as $k=>$record)
        {
            if(isset($record['horse_id'])) {

            if($horse_id==$record['horse_id'])
            {
                if (isset($record['rounds']))
                 return  $score = array_sum($record['rounds']);
            }

            }

            }
    }
    function getClassNames($classStr){

     $classArr = explode(',',$classStr);

     $classes = Asset::whereIn('id',$classArr)->get();
     $asset = [];

    // dd($classes->toArray());
     $html = '<div class="form-inline">';
     foreach ($classes as $cls)
         $html  .= '<div class="bg-secondary text-white p-1 m-1">'.GetAssetName($cls).'</div>';

     $html  .= '</div>';

    return $html;


    }


    function getShowTypeRestrictions($horseAsset,$show_type){

        $pre_fields = json_decode($horseAsset->fields);
        if($show_type=='Dressage')
            $checkData = ['Previous Name','USDF Number','For Sale','Height','Color','Vaccinated for Equine EHV-1, Equine Influenza Virus, Rabies','Coggins date','Sire','Dam',"Dam's Sire","Sires's Sire",'Country of Birth','Breeder Name','Groom'];
        elseIf($show_type=='Hunter')
            $checkData = ['Height','Color','Must have Health Certificate if from out of state of where show is located','Vaccinated for Equine EHV-1, Equine Influenza Virus, Rabies'];
        elseIf($show_type=='Eventing')
            $checkData = ['USEA Number','Country of residence','For Sale','Stud Book Number (if applicable)','Height','Color', 'Vaccinated for Equine EHV-1, Equine Influenza Virus, Rabies','Coggins date','Sire','Dam',"Dam's Sire","Sires's Sire",'Breeder Name','Groom','1(a) Breed/Affiliate Association','2(a) Breed/Affiliate Association','3(a) Breed/Affiliate Association','4(a) Breed/Affiliate Association','1(b) Breed/Affiliate Number','2(b) Breed/Affiliate Number','3(b) Breed/Affiliate Number','4(b) Breed/Affiliate Number',"Breeder's Address",'Previous Experience','TIP (Thoroughbred Incentive Program) Number (if applicable)','Team Name and Division (if applicable)'];
        elseIf($show_type=='Western')
            $checkData = ['Coggins date'];
        elseIf($show_type=='Breeding')
            $checkData = ['Height','Color','Must have Health Certificate if from out of state of where show is located','Vaccinated for Equine EHV-1, Equine Influenza Virus, Rabies','Coggins date','Section'];


       // ArrayPrint($pre_fields);

        $name = "";
        $data = [];
        if(isset($checkData)) {
            if (isset($pre_fields)) {
                foreach ($pre_fields as $key => $value) {
                    if (in_array($value->form_name, $checkData)) {
                        if($value->form_name=='Vaccinated for Equine EHV-1, Equine Influenza Virus, Rabies' || $value->form_name=='Coggins Accession Number' || $value->form_name=='Must have Health Certificate if from out of state of where show is located')
                        {
                            if (isset($value->upload_filess) && count($value->upload_filess)==0)
                                $data[] = $value->form_name;
                        }else {
                            if (isset($value->answer) && $value->answer == '')
                                $data[] = $value->form_name;
                            elseif (!isset($value->answer))
                                $data[] = $value->form_name;
                        }
                    }
                }

            }
            return $data;
        }

    }
    function getRiderRestrictions($riderAsset,$show_type){

    $pre_fields = json_decode($riderAsset->fields);
    if($show_type=='Dressage')
        $checkData = ['1(a) Affiliate Association','Local Number','Citizenship','Rider/Handler Signature','Parent/Guardian Name','Parent/Guardian Signature (Required if rider, driver, handler is a minor)','Emergency Contact Phone Number'];
    elseIf($show_type=='Hunter')
        $checkData = ['Citizenship','Rider/Handler Signature','Parent/Guardian Signature (Required if rider, driver, handler is a minor)','Emergency Contact Phone Number',''];
    elseIf($show_type=='Eventing')
        $checkData = ['3(a) Affiliate Association','Fax','FEI Nationality','Rider/Handler Signature','Parent/Guardian Name','Parent/Guardian Signature (Required if rider, driver, handler is a minor)', 'Emergency Contact Phone Number','Do you plan on attending this event alone? If yes, please state the name and contact number of the person wo will represent you in the event of an injury',"If riding more then one horse,state horse's names and divisions"];
    elseIf($show_type=='Western')
        $checkData = [];
    elseIf($show_type=='Breeding')
        $checkData = ['1(a) Affiliate Association','2(a) Affiliate Association','3(a) Affiliate Association','4(a) Affiliate Association','Rider/Handler Signature','Parent/Guardian Name','Parent/Guardian Signature (Required if rider, driver, handler is a minor)','Emergency Contact Phone Number','Section (Welsh Pony and Cob Breed Registries)'];

    $name = "";
    $data = [];
    if(isset($checkData)) {
        if (isset($pre_fields)) {
            foreach ($pre_fields as $key => $value) {
                if (in_array($value->form_name, $checkData)) {
                    if (isset($value->answer) && $value->answer == '')
                        $data[] = $value->form_name;
                    elseif (!isset($value->answer))
                        $data[] = $value->form_name;
                }
            }

        }
        return $data;
    }

}


function getShowDivision($show_id){

      return ShowDivision::where('show_id',$show_id)->pluck('division_id')->toArray();
}


function GetRiderOwnerStatus($id,$str_match = "ID"){
    if (is_int($id) || is_string($id)) {
        $row = Asset::where('id',$id)->first();
    }else{
        $row =$id;
    }
    $pre_fields = json_decode($row->fields);
    $riderCon = [];
   // ArrayPrint($pre_fields);

    if (isset($pre_fields)) {
        foreach ($pre_fields as $key => $value)
        {
            if (fnmatch(strtolower($str_match), strtolower($value->form_name)))
            {
                foreach ($value as $opKey => $options)
                {
                    if (is_numeric($opKey) && isset($options->answer))
                    $riderCon[]=$options->answer;
                }
            }

        }
    }
    return $riderCon;
}
function checkUserAlreadyExist($email)
{
    return $collection = User::where('email',$email)->count();

}
function checkCombined($asset_id)
{
   return $isCombined = CombinedClass::where('class_id',$asset_id)->count();
}


    function invitedBySelf($template_id)
    {

        $user_email = \Auth::user()->email;
        $user_name = \Auth::user()->name;
        $template_id = nxb_decode($template_id);

        $invited_user = new InvitedUser();
        $invited_user->name = $user_name;
        //$invited_user->royalty = $user["royalty"];
        $invited_user->email = $user_email;
        $invited_user->invited_by = 1;
        $invited_user->email_confirmation = GENERAL_ACTIVE;
        $invited_user->status = GENERAL_ACTIVE;
        $invited_user->template_id = $template_id;
        $invited_user->save();

    }
    function getPayInOfficeStatus($show_id,$horse_id,$paid_on=null){
        if ($paid_on == null) {
        	$model = ShowPayInOffice::where('show_id',$show_id)->where('horse_id',$horse_id)->where('invoice_status',0)->first();
        }else{
        	$model = HorseInvoices::with('bill')->where('show_id',$show_id)->where('horse_id',$horse_id)->where('invoice_status',1)->where('paid_on',$paid_on)->first();
        	if(isset($model->bill) && $model->bill->type == "pay in office"){
        		return $model->bill;
        	}else{
        		return false;
        	}
        }
        if (count($model)>0) {
        	return true;
        }else{
        	return false;
        }
        
    }

    function updatePaidInvoices($horse_id,$show_id,$HIid,$user_id=0)
    {
        $time_now = Carbon::now()->format('Y-m-d H:i:s');

        if ($user_id ==0) {
        	$user_id = \Auth::user()->id;
        }

        $stalls = HorseRiderStall::where('show_id',$show_id)->where("horse_id",$horse_id)->where("invoice_status",0)->get();
        foreach($stalls as $st)
        {
            $stl = HorseRiderStall::findOrFail($st->id);
            $stl->invoice_status=1;
            $stl->paid_on=$time_now;

            $stl->update();
        }

        $showStall = ShowStallUtility::where('show_id',$show_id)->where("horse_id",$horse_id)->where("invoice_status",0)->get();
        foreach($showStall as $sh_st)
        {
            $shStall = ShowStallUtility::findOrFail($sh_st->id);
            $shStall->invoice_status=1;
            $shStall->paid_on=$time_now;
            $shStall->update();
        }

        $HorseInvoices = HorseInvoices::findOrFail($HIid);
            $HorseInvoices->invoice_status = 1;
            $HorseInvoices->paid_on = $time_now;
            $HorseInvoices->update();


        $splitInvoices =  ManageShowTrainerSplit::whereHas('ClassHorse',function($q) use ($horse_id){
            $q->where('horse_id',$horse_id);
        })->where('show_id',$show_id)->where('invoice_status',0)->get();
        foreach($splitInvoices as $split)
        {
            $splitInv = ManageShowTrainerSplit::findOrFail($split->id);
            $splitInv->invoice_status=1;
            $splitInv->paid_on=$time_now;
            $splitInv->update();
        }

        $divisions =  Division::where('show_id',$show_id)->where('invoice_status',0)->where('horse_id',$horse_id)->where('user_id',$user_id)->get();
        foreach($divisions as $d)
        {
            $division = Division::findOrFail($d->id);
            $division->invoice_status=1;
            $division->paid_on=$time_now;
            $division->save();
        }

        $classHorse =  ClassHorse::where('show_id',$show_id)->where('status',0)->where('horse_id',$horse_id)->where('user_id',$user_id)->get();

        foreach($classHorse as $h)
        {
            $horse = ClassHorse::findOrFail($h->id);
            $horse->status=1;
            $horse->paid_on=$time_now;
            $horse->update();
        }
        $prize = ShowPrizingListing::with("shows")->where("show_id",$show_id)->get();
        foreach($prize as $p)
        {
            $prize = ShowPrizingListing::findOrFail($p->id);
            $dec_prize = json_decode($prize->position_fields);
            if (count($dec_prize)>0) {
                foreach ($dec_prize as $positions) {
                    if ( isset($positions->horse_id) && $positions->horse_id == $horse_id) {
                        if (!isset($positions->paid_on)) {
                            $positions->paid_on = $time_now;
                        }
                    }
                }
                $prize->position_fields = json_encode($dec_prize);
                $prize->update();
            }
        }

    return $time_now;


    }

    function formatDate($date)
    {
        return $date->format('m-d-Y');

    }

function getSchedulers($parent_id,$asset_id){

    return $model =  SchedulerRestriction::where('scheduler_id',$parent_id)->where('asset_id',$asset_id)->groupBy('show_id','asset_id')->get();

}
    function getScheulderTime($asset_id,$show_id){

    $model =  SchedulerRestriction::where('asset_id',$asset_id)->where('show_id',$show_id)->get();
    $timeFrom = '';
    $timeTo = '';

    foreach ($model as $m) {
        $restrcition = $m->restriction;
        $times = explode(' - ', $restrcition);
        if (count($times) > 1) {
            $timeFrom .= $times[0] . '<br>';
            $timeTo .= $times[1] . '<br/>';
        }
    }
        $data['timeFrom'] = $timeFrom;
        $data['timeTo'] = $timeTo;

    return $data;

    }


function getFieldsLabel($jsonString,$str_match = "ID"){

    $pre_fields = json_decode($jsonString);
    $name = "";

    if (isset($pre_fields)) {
        foreach ($pre_fields as $key => $value) {
            if(isset($value->answer)) {
                if ($value->answer != "") {
                    $str_match = preg_replace('/\s+/', '', strtolower($str_match));
                    $form_name = preg_replace('/\s+/', '', strtolower($value->form_name));

                    if (fnmatch($str_match, $form_name)) {
                        if (is_array($value->answer)) {
                            $name = $value->answer[0];
                            break;
                        } else {
                            $name = $value->answer;
                            break;
                        }
                    }else {
                        $name = "";
                        continue;
                    }
                }
            }
        }
    }

    return $name;
}


    function getClassPrizeMoney($asset_id)
    {
        $total = 0;

        $positions = ShowPrizing::where("asset_id", $asset_id)->first();


        if ($positions) {
            $placing = json_decode($positions->fields, true);
            if(isset($placing['place'])) {

                foreach ($placing['place'] as $k => $v) {

                    if (isset($v['price']))
                        $total = (float)$total + (float)$v['price'];
                }
            }
        }
        return $total;
    }


    function getPrizeMoneyAwarded($asset_id,$show_id,$horse_id)
    {
        $faults_option ='';
        $prizeAwarded = [];
        $faults_option= SchedualNotes::where('asset_id', $asset_id)->where('show_id',$show_id)->where('horse_id',$horse_id)->pluck('faults_option')->first();

        $prize = ShowPrizingListing::where('asset_id', $asset_id)->where('show_id',$show_id)->first();
        if ($prize) {
            $prizes = json_decode($prize->position_fields, true);
            foreach ($prizes as $key => $val) {
            if($val['horse_id']==$horse_id)
                if(isset($val['price']))
                $prizeAwarded['price'] = $val['price'];
            if(isset($val['position']))
                $prizeAwarded['placing'] = $val['position'].$faults_option;
            }
        return $prizeAwarded;
        }

    }


    function breedsWithPercentage($breeds,$percentage)
    {
        $breedStr = '';
        $breedArr = [];
        $percentage = str_replace('Pure Breed|||','',$percentage);
        $percentage = str_replace('Half|||','',$percentage);

        foreach ($breeds as $breed)
        {
           $breedArr[] = $percentage.' '.$breed;
        }


        if(!empty($breedArr))
           $breedStr = implode(', ',$breedArr);

        return $breedStr;
    }



function getJudgesData($asset_id,$show_id,$horse_id)
{
    $collection = SchedulerFeedBacks::where('asset_id', $asset_id)->where('show_id',$show_id)->orderBy('created_at','ASC')->get();
    $data = [];
    if ($collection) {
        foreach ($collection as $row) {
        $data[$row->horse_id][]=[
            'firstName'=>getFieldsLabel($row->fields,"Judge First Name"),
            'lastName'=>getFieldsLabel($row->fields,"Judge Last Name"),
            'judgePercentage'=>getFieldsLabel($row->fields,"Judge Percentage"),
            'judgeScore'=>getFieldsLabel($row->fields,"Judge Score")
            ];

        }
    }
    return $data;

}