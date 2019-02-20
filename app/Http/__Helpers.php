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
use App\Spectators;
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
use App\ParticipantResponse;
use App\Module;
use App\Schedual;
use App\InvitedUser;
use App\inviteTemplatename;
use App\SchedulerReminder;
use App\Mail\ReminderEmail;
use App\participantAsset;
use App\SchedualNotes;
use App\TemplateButtonLabel;
use App\Mail\TimeUpdateEmail;
use App\subParticipants;


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
	return date("Y-m-d", strtotime($date));
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
function AddOptionsFrontend($fieldoptions,$field,$answer=null){
	
	$html = "";
	$index = 1;
	if($field['form_field_type'] == OPTION_DROPDOWN){ 
		 $html .= '<div class="col-sm-8">
			<select class="form-control" name="fields['.$field["unique_id"].'][answer]">';
	}
	if($field['form_field_type'] == OPTION_AUTO_POPULATE){ 
		 $html .= '<div class="col-sm-12">
		 	<select class="form-control autopopulate-basic-multiple" multiple="multiple" name="fields['.$field["unique_id"].'][answer][]">';
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
		if($field['form_field_type'] ==OPTION_AUTO_POPULATE){ 
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
		          <label><input data-attr="'.$field["unique_id"].'" name="fields['.$field["unique_id"].']['.$index.'][answer]" value="'.$option["opt_name"].'" type="radio" '.$selec.' />'.$option["opt_name"].'</label>
		          <input name="fields['.$field["unique_id"].']['.$index.'][opt_weightage]" type="hidden" value="'.$option["opt_weightage"].'" />
		        </div>';
		}
		if($field['form_field_type'] == OPTION_CHECKBOX){ 
			 $html .= '<div class="checkbox">
		          <label><input data-attr="'.$field["unique_id"].'" name="fields['.$field["unique_id"].']['.$index.'][answer]" value="'.$option["opt_name"].'" type="checkbox" '.$selec.' />'.$option["opt_name"].'</label>
		          <input name="fields['.$field["unique_id"].']['.$index.'][opt_weightage]" type="hidden" value="'.$option["opt_weightage"].'" />
		        </div>';
		}
		if($field['form_field_type'] == OPTION_LABEL){ 
			 $html .= '<span style="padding-top: 7px;">'.$option["opt_label"].'</span>';
		}
		if($field['form_field_type'] == OPTION_HYPERLINK){ 
			 $html .= '<a target="_blank" href="http://'.$option["opt_hyperlink"].'" style="padding-top: 7px;position: absolute;">'.$option["opt_hyperlink"].'</a>';
		}
		
      $index = $index+1;
    }
    	//OutSide Loop end:
    	if($field['form_field_type'] == OPTION_DROPDOWN || $field['form_field_type'] == OPTION_AUTO_POPULATE ){ 
		 $html .= '</select>
			</div>';
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
            $html .= '<div class="checkbox">
		          <label><input data-attr="'.$field["unique_id"].'" name="fields['.$field["unique_id"].']['.$index.'][answer]" value="'.$option["opt_name"].'" type="radio" '.$selec.' />'.$option["opt_name"].'</label>
		          <input name="fields['.$field["unique_id"].']['.$index.'][opt_weightage]" type="hidden" value="'.$option["opt_weightage"].'" />
		        </div>';
        }
        if($field['form_field_type'] == OPTION_CHECKBOX){
            $html .= '<div class="checkbox">
		          <label><input data-attr="'.$field["unique_id"].'" name="fields['.$field["unique_id"].']['.$index.'][answer]" value="'.$option["opt_name"].'" type="checkbox" '.$selec.' />'.$option["opt_name"].'</label>
		          <input name="fields['.$field["unique_id"].']['.$index.'][opt_weightage]" type="hidden" value="'.$option["opt_weightage"].'" />
		        </div>';
        }
        if($field['form_field_type'] == OPTION_LABEL){
            $html .= '<span style="padding-top: 7px;">'.$option["opt_label"].'</span>';
        }
        if($field['form_field_type'] == OPTION_HYPERLINK){
            $html .= '<a target="_blank" href="http://'.$option["opt_hyperlink"].'" style="padding-top: 7px;position: absolute;">'.$option["opt_hyperlink"].'</a>';
        }

        $index = $index+1;
    }
    //OutSide Loop end:
    if($field['form_field_type'] == OPTION_DROPDOWN || $field['form_field_type'] == OPTION_AUTO_POPULATE ){
        $html .= '</select>
			</div>';
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
	if($field == OPTION_LABEL || $field == OPTION_IMAGE || $field == OPTION_VIDEO || $field == OPTION_ATTACHMENT){
		return false;
	}else{
		return true;
	}
}
//Array finding
function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

function parseGridRow(&$row, $key, $params =[]){


    $res = $params["parent"]->count();
    $sub = $params["sub"]->count();

    $asset_type = $params["asset_type"];


    $newRow = [];
	$id = 0;
	$key = 0;
	foreach($row as $data){
		if (exclueded_fields_datatable($data["form_field_type"])) {
			$newRow[$key] = isset($data["answer"])?$data["answer"]:"-";
			$key = $key+1;
		}
		//$id = $data["id"];
	}

    if($asset_type==1)
    {
        if ($sub > 0)
            $newRow[] = "<div class='TD-left'><a href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/sub/assets"."' data-toggle='tooltip' data-placement='top'
	 data-original-title='View History'>View Seconday</a></div> ";
        else
            $newRow[] = "<div class='TD-left'><h6>No Seconday</h6></div> ";

    }
    else {
        if ($res > 0)
            $newRow[] = "<div class='TD-left'>" . getPrentAssets($params['assetid']) . "</div> ";
        else
            $newRow[] = "<div class='TD-left'><h6>No Primary</h6></div> ";
    }

    $newRow1 ="<div class='TD-left'><a href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/remove/assets"."' data-toggle='tooltip' 
	data-placement='top' onclick='return confirm(Are you sure?);' data-original-title='Delete Template'>
	<i class='fa fa-trash-o' aria-hidden='true'></i></a>
	<a href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/edit/assets"."' data-toggle='tooltip' data-placement='top'
	 data-original-title='Edit Template'><i class='fa fa-pencil' aria-hidden='true'></i></a>";
    if($asset_type!=1) {
        $newRow1 .= "<a href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/history/assets" . "' data-toggle='tooltip' data-placement='top'
	 data-original-title='View History'><i class='fa fa-eye' aria-hidden='true'></i></a>
		<a href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/associate/modules" . "' data-toggle='tooltip' data-placement='top'
	 data-original-title='>Manage Modules'>Manage Modules</a>";
    }
        $newRow1 .="</div>";
    $newRow[] = $newRow1;

    return $row = $newRow;
}
//Get name of the asset in dropdown.
function GetAssetName($row){
    $pre_fields = json_decode($row->fields);
    $name = "";
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
    
    return $name;
}
//Get name of the asset name.
function GetAssetNamefromId($id){
    $row = Asset::where('id',$id)->first();
    
    $pre_fields = json_decode($row->fields);


    $name = "";
    if ($pre_fields != null) {
	    foreach ($pre_fields as $key => $value) {
	    		if ($value->answer != "") {
	    			$name = $value->answer;
	    			break;
	    		}else{
	    			$name = "Asset ID ".$row->id;
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
	}else{
		$name = "Asset ID ".$row->id;
	}
    return $name;
}
//--- Create Dropdown for font size
function CreatePermissionsDrp($name="defaults",$selected_v="",$starts=0,$ends=100,$custom_option=""){
	
	if ($custom_option != "" ) {
		$select = " <select class='custom-permission-admin' name='".$name."'>";
		$select .= "<option value=''>Set Duplicate Permission if applicable</option>";
		$select .= "<option value='00'>".$custom_option."</option>";
	}else{
		$select = " <select class='custom-permission' name='".$name."'>";
		$select .= "<option value=''>Set Permission Time</option>";
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

//get User Name form id
function getUserNamefromid($id)
{
    $collection = User::where('id',$id)->first();
    return $collection->name;
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
    return $collection->name;
}

function getModuleNameModuleId($id)
{
    $module = Module::where('id',$id)->first();
    return $module->name;
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
//User have access to whcih templates
function user_template_accessable($user_id=0){
	//If it is logged in user.
	if ($user_id == 0) {
		$user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;
        $arrayInvited = InvitedUser::where('email',$useremail)->groupBy("template_id")->pluck('template_id')->toArray();
        $arrayParticipants = Participant::where('email',$useremail)->groupBy("template_id")->pluck('template_id')->toArray();
		return $finalArray = array_unique(array_merge($arrayInvited, $arrayParticipants));
	}
	//Else not logged in. Have to check for the id supplied

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
        \DB::enableQueryLog();
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
		return $image;
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
function GetAnswersArray($answerfields,$question){
	$Answer_array = json_decode($answerfields->fields,true); 
	  $indexer = array_search($question->unique_id, array_column($Answer_array, 'form_field_id'));
	  if(isset($Answer_array[$indexer]['answer'])){ 
	      $answer = $Answer_array[$indexer]['answer'];
	  }else{
	      $answer = "";
	  } 
	  return $answer;
}
//getStoragePath
function getStoragePath($URL){
	$ext = explode('equetica/', $URL ,2);
	return $ext[1];
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
	dd($question);
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
        	//dd($Answer_array[$Field_indexer]['answer']);
        	$matches = preg_grep("/".$field->opt_name."/", $Answer_array[$Field_indexer]['answer']);
    	 	if($matches){
        		$arrayX[] = 1;
	        }else{
	        	$arrayX[] = 0;
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

function my_search($needle,$haystack) {
    return(strpos($haystack, $needle)); // or stripos() if you want case-insensitive searching.
}
//Restricted dates
function restrictedScheduledDates($form_id){
	$user_id = \Auth::user()->id;
	$scheduled = Schedual::where('form_id',$form_id)->where('user_id',$user_id)->first();
	if ($scheduled != null) {
		$scheduled->restriction = json_decode($scheduled->restriction,true);
	}else{
		$scheduled = "";
	}
	return $scheduled;
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
		$participantResponse = ParticipantResponse::where("id",$response_id)->first();
		$TotalRanking = 0;
		$fields = json_decode($participantResponse->fields,true);
		//dd($fields);
		foreach ($fields as $field) {
			if($field["form_field_type"] == OPTION_RADIOBUTTON){
				$found_key = WfindKey($field,"answer");
				if ($found_key) {
					$TotalRanking += (float)$field[$found_key]['opt_weightage'];
				}
			}
			if($field["form_field_type"] == OPTION_DROPDOWN){
				if (isset($field["answer"])) {
					$answerExplod = explode("|||", $field["answer"]);
					if($answerExplod[1] != "");
					$TotalRanking += (float)$answerExplod[1];
				}
			}
			if($field["form_field_type"] == OPTION_AUTO_POPULATE){
				if (isset($field["answer"])) {
					foreach ($field["answer"] as $option) {
						$answerExplod = explode("|||", $option);
						if($answerExplod[1] != "");
						$TotalRanking = $TotalRanking+(float)$answerExplod[1];
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
        
}

//Geting isset values for admin button labels
function post_value_or($fields, $key, $default = NULL) {
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
function getFormsForProfile($template_id,$accessable_to=1){
	$user_id   = \Auth::user()->id;
	$Form = Form::where('template_id',$template_id)->where('form_type',PROFILE_ASSETS)->where('accessable_to',$accessable_to)->get();
	return $Form;
}

/**************calendar function updated by shakil***********************/

function getCalendarEvents($FormTemplate, $scheduals)
{

    $events = [];
    $date=[];
 
    foreach ($FormTemplate as $row) {

        $pre_fields = json_decode($row['restriction']);
        
        if (count($pre_fields)>0) {
    
            $pre_fields=array_filter($pre_fields);
    
            for ($i = 0; $i < count($pre_fields); $i++) {
                $var = explode('-', $pre_fields[$i]);

                if (count($var) > 0) {
                    $timeFrom[] = date('H:i', strtotime($var[0]));
                    $timeTo[] = date('H:i', strtotime($var[1]));
                    $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));
                    $dateTo = date('Y-m-d H:i:s', strtotime($var[1]));

                    $date[] =['dateFrom'=>date('Y-m-d H:i:s', strtotime($var[0])),'dateTo'=>date('Y-m-d H:i:s', strtotime($var[1]))];
                    
                    $events[] = \Calendar::event(
                        '',
                        false,
                        $dateFrom,
                        $dateTo,
                        $row['id'],
                        [
                            'rendering' => 'background',
                            "scheduleId"=>$row['schedual_id'],
                        ]
                );
                }
            }
        } else {

             $dateFrom = date('Y-m-d H:i:s');

            $events[] = \Calendar::event(
                '',
                false,
                '2017-4-21T10:00:00',
                '2020-4-21T10:00:00',
                $row['id'],
                [
                    'rendering' => 'background',
                    "restrictionType"=>1,
                ]
            );
        }
    }
    
    
//    for (var i = 0; i < arrayLength; i++) {
//
//
//    var newEvent = {
//        title: '',
//         start: '".arrayFromPHP['dateFrom'][i]."',
//         end: '".arrayFromPHP['dateTo'][i]."',
//         rendering:'background'
//    };
    foreach ($scheduals as $row1) {
        $pre_fields1 = json_decode($row1['time_slot'], true);
        
        
        
        if($row1['sub_participant_id']>0)
        {
            $user_id = \Auth::user()->id;
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
    
                if($row1['is_mark']==1) {
                   $events[] = \Calendar::event(
                       $row1['notes'],
                       false,
                       $dateFrom,
                       $dateTo,
                       $row1['id'],
                       [
                           'backgroundColor' => '#2ca02c',
                           "isMark" => 1,
                           'description'=>$userName,
                           'userId'=>$userId


                       ]
                   );
               }else
               {
                   $events[] = \Calendar::event(
                       $row1['notes'],
                       false,
                       $dateFrom,
                       $dateTo,
                       $row1['id'],
                       [
                           'description'=>$userName,
                           'userId'=>$userId

                       ]
                   );

               }

               }
        }
    }
    
    
    $calendar = \Calendar::addEvents($events); //add an array with addEvents

    $clId= $calendar->getId();

    $calendar->setOptions([ //set fullcalendar options
        'firstDay' => 1,
        'slotDuration'=> '00:10:00',
        'defaultView'=>'agendaWeek',
        'axisFormat'=> 'h:mm a',
        'eventLimit' => true,
        'columnFormat'=>'dddd / D',
        'draggable'=>false,
        'editable'=> false,
        'clickable'=>true,
        'eventOverlap'=> false,
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
    
    var dateSelected=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bg td:eq(\'+$(this).closest(\'td\').index()+\')\').data(\'date\');
    
    var backgroundId=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bgevent-skeleton td:eq(\'+$(this).closest(\'td\').index()+\')\').children().children().children().attr(\'id\');
    
    $("#myDiv").html(\'\');
    
    $("#schedule_id").val("");
    $("#notes").val("");
                
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
        endTime = moment(calEvent.endDaterestriction).format("HH:mm");
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
            endTime = moment(calEvent.end).format("HH:mm");      
            endDate = moment(calEvent.end).format("YYYY/MM/DD") ; 
        }
    }  
    var startDate=moment(calEvent.start).format("YYYY/MM/DD");
    var startTime=moment(calEvent.start).format("HH:mm");
    
    $("#eventContent").modal("show");
    $("#eventContent").addClass("show");

    $("#notes").val(calEvent.title);
    $("#schedule_id").val(calEvent.id);    
    
    $("#backgrounbdSlotId").val(backgroundId);
    
    $("#startTime").val(moment(calEvent.start).format("YYYY/MM/DD HH:mm"));    
    $("#endTime").val(endDate+" "+endTime);   
    $(".markDone").show();
     
    populate(startTime,endTime,startDate,endDate,moment(calEvent.end).format("HH:mm"));
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
        $('#schedule_id').val('');
        $('#notes').val('');
        window.selectedTime=date.format(\"YYYY/MM/DD HH:mm a\");    
    }",
    "eventRender"=>" function (calEvent, element)
    {
        if (calEvent.rendering == 'background')
        {
            element.append('<h6 id=\"'+calEvent.id+'\">'+calEvent.title+'</h6>');
            $(element).data(calEvent);
        }
        else
        {
            
            element.append('<span onclick=\"delEvent(event,this)\" data-id=\"'+calEvent.id+'\" class=\"closeon\">X</span>' );


        if(calEvent.description!='' && calEvent.description!='undefined')
        {       
            element.append('<h6>'+calEvent.description+'</h6>' );
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
     if(calEvent.title!="")
    {
        var tooltip = \'<div class="tooltipevent" style="width:200px; padding:10px; border:solid 1px #000; border-radius: 5px; color:#000;height:Auto;background:#ccc;position:absolute;z-index:10001;">\' + calEvent.title + \'</div>\';
        var $tooltip = $(tooltip).appendTo("body");   
        $(this).mouseover(function(e) {
        $(this).css("z-index", 10000);
        $tooltip.fadeIn("500");
        $tooltip.fadeTo("10", 1.9);
        }).mousemove(function(e) {
        $tooltip.css("top", e.pageY + 10);
        $tooltip.css("left", e.pageX + 20);
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

function getMasterSchedulerEvents($FormTemplate, $scheduals)
{

    $events = [];
    $date = [];
    foreach ($FormTemplate as $row) {

        $pre_fields = json_decode($row['restriction'], true);
        
        if ($pre_fields) {
            $pre_fields=array_filter($pre_fields);
    
            for ($i = 0; $i < count($pre_fields); $i++) {
                $var = explode('-', $pre_fields[$i]);

                if (count($var) > 0) {
                    $timeFrom[] = date('H:i', strtotime($var[0]));
                    $timeTo[] = date('H:i', strtotime($var[1]));
                    $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));
                    $dateTo = date('Y-m-d H:i:s', strtotime($var[1]));
                    $date[] =['dateFrom'=>date('Y-m-d H:i:s', strtotime($var[0])),'dateTo'=>date('Y-m-d H:i:s', strtotime($var[1]))];
    
    
    
                    $events[] = \Calendar::event(
                        $row['name'],
                        false,
                        $dateFrom,
                        $dateTo,
                        $row['id'],
                        [
                            'rendering' => 'background'
                        ]
                    );
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
                    "restrictionType"=>1

                ]
            );
        }
    
    }

    foreach ($scheduals as $row1) {
        $pre_fields1 = json_decode($row1['time_slot'], true);
        for ($j = 0; $j < count($pre_fields1); $j++) {
            $var1 = explode('-', $pre_fields1[$j]);

            if (count($var1) > 0) {
                $timeFrom[] = date('H:i', strtotime($var1[0]));
                $timeTo[] = date('H:i', strtotime($var1[1]));
                $dateFrom = date('Y-m-d H:i:s', strtotime($var1[0]));
                $dateTo = date('Y-m-d H:i:s', strtotime($var1[1]));
                $date[] =['dateFrom'=>date('Y-m-d H:i:s', strtotime($var1[0])),'dateTo'=>date('Y-m-d H:i:s', strtotime($var1[1]))];
    
                if($row1['is_mark']==1) {
                    $events[] = \Calendar::event(
                        getUserNamefromid($row1['user_id']),
                        false,
                        $dateFrom,
                        $dateTo,
                        $row1['id'],
                        [
                        'description' => $row1['notes'],
                        'backgroundColor' => '#2ca02c',
                        "isMark" => 1,
                        "userId"=>$row1['user_id'],
                        "formId"=>$row1['form_id']

                        ]
                    );
                }
                else
                {
                    $events[] = \Calendar::event(
                        getUserNamefromid($row1['user_id']),
                        false,
                        $dateFrom,
                        $dateTo,
                        $row1['id'],
                        [
                        'description' => $row1['notes'],
                        "userId"=>$row1['user_id'],
                        "formId"=>$row1['form_id']
                        ]
                    );
                }

            }
        }
    }
//exit;
    
    
    
    $calendar = \Calendar::addEvents($events); //add an array with addEvents

    $clId= $calendar->getId();

    $calendar->setOptions([ //set fullcalendar options
        'firstDay' => 1,
        'slotDuration'=> '00:10:00',
        'defaultView'=>'agendaWeek',
        'axisFormat'=> 'h:mm a',
        'columnFormat'=>'dddd  / D',
        'clickable'=>true,

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
    
       var dateSelected=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bg td:eq(\'+$(this).closest(\'td\').index()+\')\').data(\'date\');
    
    var backgroundId=$(this).closest(\'.\'+(view.type==\'week\'?\'fc-row\':\'fc-time-grid\')).find(\'.fc-bgevent-skeleton td:eq(\'+$(this).closest(\'td\').index()+\')\').children().children().children().attr(\'id\');

    $("#myDiv").html(\'\');
    
    $("#schedule_id").val("");
    $("#notes").val("");
                
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
        endTime = moment(calEvent.endDaterestriction).format("HH:mm");
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
            endTime = moment(calEvent.end).format("HH:mm");      
            endDate = moment(calEvent.end).format("YYYY/MM/DD") ; 
        }
    }  
    var startDate=moment(calEvent.start).format("YYYY/MM/DD");
    var startTime=moment(calEvent.start).format("HH:mm");
    
    var spectatorsId= $("#spectatorsId").val();
    
    if(spectatorsId > 0)
    {
     hideButtonSpectator();
    }
    
   // console.log(calEvent);
   
    
    //$("#notes").attr("disabled",true);
    $("#eventContent").modal("show");
    $("#eventContent").addClass("show");

    $("#notes").val(calEvent.description);
    $("#schedule_id").val(calEvent.id);
    $("#userId").val(calEvent.userId);
    var el = document.querySelector(".schedulerProfileView");
     el.setAttribute(\'data-id\',calEvent.userId);
    
    $("#form_id").val(calEvent.formId);
    $("#masterScheduler").val(1);
    $("#backgrounbdSlotId").val(backgroundId);
    
    $("#startTime").val(moment(calEvent.start).format("YYYY/MM/DD HH:mm"));    
    $("#endTime").val(endDate+" "+endTime);   
    $(".markDone").show();

    
    populate(startTime,endTime,startDate,endDate,moment(calEvent.end).format("HH:mm"));
    }',

        "eventRender"=>" function (calEvent, element) 
    {
        if (calEvent.rendering == 'background') 
        {
            element.append('<h6 id=\"'+calEvent.id+'\">'+calEvent.title+'</h6>');
            $(element).data(calEvent);
        }
        else
        {
         if(calEvent.isMark==1)
            element.append('<img src=\"/img/check_mark.png\">' );
        }
    
    }",

        "eventMouseover"=>'function(calEvent, jsEvent) {

            if(calEvent.description!="")
            {
            var tooltip = \'<div class="tooltipevent" style="width:200px; padding:10px; border:solid 1px #000; border-radius: 5px; color:#000;height:Auto;background:#ccc;position:absolute;z-index:10001;">\' + calEvent.description + \'</div>\';
            var $tooltip = $(tooltip).appendTo("body");
            
            $(this).mouseover(function(e) {
            $(this).css("z-index", 10000);
            $tooltip.fadeIn("500");
            $tooltip.fadeTo("10", 1.9);
            }).mousemove(function(e) {
            $tooltip.css("top", e.pageY + 10);
            $tooltip.css("left", e.pageX + 20);
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

function schedulerReminder($request,$id)
    {

        $SchedulerReminder = SchedulerReminder::where('notes_id',$id);

        if($SchedulerReminder)
             $SchedulerReminder->delete();

         $scheduleId=$request->get("backgrounbdSlotId");

        if($scheduleId) {
            $Schedual = Schedual::findOrFail($scheduleId);

            $timeSlot = json_decode($request->timeSlot);

            $arr = [];
            $data = [];

            $fromDate = explode('-', $timeSlot);

            if ($Schedual->reminderHours != '') {
                $hours = date('Y-m-d H:i:s', strtotime($fromDate[0]) - 60 * 60 * $Schedual->reminderHours);
                $data[] = array('remind_date' => $hours, 'notes_id' => $id, 'scheduler_id' => $scheduleId);
            }
            if ($Schedual->reminderMinutes != '') {
                $minutes = date('Y-m-d H:i:s', strtotime($fromDate[0]) - 60 * $Schedual->reminderMinutes);
                $data[] = array('remind_date' => $minutes, 'notes_id' => $id, 'scheduler_id' => $scheduleId);
            }
            if ($Schedual->reminderDays != '') {
                $days = date('Y-m-d H:i:s', strtotime($fromDate[0]) - 24 * 60 * 60 * $Schedual->reminderDays);
                $data[] = array('remind_date' => $days, 'notes_id' => $id, 'scheduler_id' => $scheduleId);
            }

            SchedulerReminder::insert($data);
        }
    }
    
function getRemindersEmails()
    {

        $reminders = DB::table('scheduals')
            ->join('scheduals_notes', 'scheduals_notes.schedual_id', '=', 'scheduals.id')
            ->join('users', 'scheduals_notes.user_id', '=', 'users.id')
            ->join('scheduler_reminders', 'scheduler_reminders.notes_id', '=', 'scheduals_notes.id')
            ->select('remind_date','scheduler_reminders.id', 'users.email', 'users.name as userName','scheduals_notes.notes','scheduals_notes.time_slot','scheduals.name')
            ->where('is_sent',0)
            ->groupBy('remind_date','scheduals.user_id')
            ->get();

        foreach($reminders as $reminder) {

            
            date_default_timezone_set('Asia/Karachi');
            $reminder->remind_date;
            $datetime1 = strtotime($reminder->remind_date);
            $datetime2 = strtotime(date('Y-m-d H:i:s'));
            $interval  = abs($datetime2 - $datetime1);
            $minutes   = round($interval / 60);

            $timeSlot = json_decode($reminder->time_slot,true);

            $fromDate = explode('-', $timeSlot[0]);

            $reminder->timeSlot = date('Y-m-d H:i:s',strtotime($fromDate[0]));


            if($minutes<=3)
            {
               \Mail::to($reminder->email)->send(new ReminderEmail($reminder));
                $remind = SchedulerReminder::findOrFail($reminder->id);
                $remind->is_sent=1;
                $remind->update();
           }

        }
        
    }
    
function bulkUpdateTimeSlots($dateFrom,$dateTo,$reminderMinutes,$asset_id){

        $results = DB::select( DB::raw("SELECT * FROM scheduals_notes where `timeFrom` between 
        STR_TO_DATE(\"$dateFrom\",'%Y/%m/%d %H:%i')
        and STR_TO_DATE(\"$dateTo\",'%Y/%m/%d %H:%i') and asset_id=".$asset_id) );

        foreach ($results as $row)
        {
            $timeSlot = json_decode($row->time_slot);
            $time_slot = explode('-', $timeSlot[0]);
            $timeFrom = date('Y/m/d H:i',strtotime($time_slot[0].' + '. $reminderMinutes .' minute'));
            $timeTo= date('Y/m/d H:i',strtotime($time_slot[1].' + '. $reminderMinutes .' minute'));

            $timeSlot=json_encode($timeFrom.'-'.$timeTo);

            $schedualNotes = SchedualNotes::findOrFail($row->id);
            $schedualNotes->timeFrom = $timeFrom;
            $schedualNotes->timeTo = $timeTo;
            $schedualNotes->time_slot ='['.$timeSlot.']';
            $schedualNotes->update();

            $user = User::where('id',$row->user_id)->first();

           // \Mail::to($user->email)->send(new TimeUpdateEmail($time_slot,$timeFrom,$timeTo,$reminderMinutes,$user));

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
    
         $filteredArray = array_filter($modules_permission, 'filterModulePermissionArray');
    
         $filteredArray = array_keys($filteredArray);
    
         $invoiceForms = Form::where('invoice', '!=', 0)->whereIn('linkto', $filteredArray);
    
         return $invoiceForms;
     
 }
    
function filterModulePermissionArray($value){
        return ($value == 2);
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
        $invoice->user_id = $user_id;
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
            $invoice->user_id = $user_id;
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
        
        foreach ($modules_permission as $key=>$value)
        {
            
            if($value==1)
                $arr[] =array('access'=>"Read Only",'module'=>$key,
                    'invoice'=>CheckAssociatedInvoice($key,$row->asset_id,$uniqueId),
                    'asset'=>$row->asset_id);
            if($value==2)
                $arr[]=array('access'=>"Read & Write",'module'=>$key,
                    'invoice'=>CheckAssociatedInvoice($key,$row->asset_id,$uniqueId),'asset'=>$row->asset_id);
        }
       //   arrayPrint(array_values($arr));
    }
    
      //arrayPrint(array_values($arr));
    
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
    
    $filteredArray = array_filter($modules_permission, 'filterModulePermissionArray');
    
    $filteredArray = array_keys($filteredArray);
    
    $forms_collection =  Form::select('id')->whereIn('linkTo', $filteredArray)->where('scheduler',1);
    
    return $forms_collection;
    
}
    
function subParticipantSchedulerForms($id)
    {
        $forms_collection =  subParticipants::where('id', $id)
            ->first();
        
        $forms_collection =  getFormsFromModules($forms_collection->modules_permission,1);
        return $forms_collection;
        
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
    
    // ArrayPrint($modules_permission);
        
    $filteredArray = array_filter($modules_permission, 'filterModulePermissionArray');
    
    $filteredArray = array_keys($filteredArray);
    
     if($scheduler==1)
        $forms_collection =  Form::select('id')->whereIn('linkTo', $filteredArray)->where('scheduler',1);
     else
         $forms_collection =  Form::select('id')->whereIn('linkTo', $filteredArray);
    
        return $forms_collection;
    
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



function ArrayPrint($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
    exit;
}
