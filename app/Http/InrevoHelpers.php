<?php
/**
 * Handle All Constant Variable in constants.php and
 * Inrevo function to be used through out the application
 *
 * @author Faran Ahmed Khan
 * @date 18-Jun-2018
 */

use App\Asset;
use Illuminate\Http\File;
use Carbon\Carbon;

// This will get dynamic data for project overview in the list.

function parseGridRowProject(&$row, $key, $params =[]){

      $res = $params["parent"]->count();
      $sub = $params["sub"]->count();
      $last = $params["lengths"];
  	  $showExist = $params["showExist"];
      $asset_type = 1;
      $template_id = $params["template_id"];
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
    	}

      $newRow1 ="<div class='TD-left'>
  	<a href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/edit/project-overview-assets"."' data-toggle='tooltip' data-placement='top'
  	 data-original-title='Edit'><i class='fa fa-pencil' aria-hidden='true'></i></a>
      <a href='#' class='more' type='button' id='dropdownMenuButton' data-toggle='dropdown'><i data-toggle='tooltip' title='More Action' class='fa fa-list-ul'></i></a>
        <div class='dropdown-menu dropdown-menu-custom' aria-labelledby='dropdownMenuButton'>";
      if ($params["class_type"] == 1) {
        $newRow1 .= "<a class='dropdown-item' href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/change-status/project-overview-assets".'/'.nxb_encode(2)."' data-toggle='tooltip'
        data-placement='top' data-original-title='currently completed'>Unmark Completed</a>";
      }else{
        $newRow1 .= "<a class='dropdown-item' href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/change-status/project-overview-assets".'/'.nxb_encode(1)."' data-toggle='tooltip'
        data-placement='top' data-original-title='currently in-complete'>Mark as Completed</a>";
      }
    

    $newRow1 .= "<a class='dropdown-item' href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"])."/block/project-overview-assets"."' data-toggle='tooltip'
    data-placement='top' onclick='return confirm(".'"'."Are you sure?".'"'.");'  data-original-title='Delete'>
    Delete</a>

     <a class='dropdown-item' href='".URL::to('/master-template/').'/'.nxb_encode($params["assetid"]).'/'.nxb_encode($params["template_id"])."/project-overview/submissions"."' data-toggle='tooltip' data-placement='top'
      data-original-title='View Submissions'>View</a>   ";
      if($asset_type!=1) {
      	 $newRow1 .= "<a class='dropdown-item' href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/history/assets" . "' data-toggle='tooltip' data-placement='top'
  	 	data-original-title='View Feedback'>Feedback</a>";


         $newRow1 .="<a class='dropdown-item' href='" . URL::to('/master-template/') . '/' . nxb_encode($params["assetid"]) . "/associate/modules" . "' data-toggle='tooltip' data-placement='top'
  	 data-original-title='Manage Modules'>Manage Modules</a>";
          $newRow1 .= "<a class='dropdown-item' onclick='getQrCode(\"".$params["assetid"]."\")' href='javascript:' data-toggle='tooltip' data-placement='top'
  			 data-original-title='View QR Code'>View QR Code</a>";



      }
       $newRow1 .= "</div>";
       if ($params["class_type"] == 1) {
          $newRow[$last-1] = "Completed";
      }else{
          $newRow[$last-1] = "In Complete";
      }
      $newRow[$last] = $newRow1;
      return $row = $newRow;

}

//SetUploadFiles to s3
function UploadFileToS3($attachedFiles){
  $disk = Storage::disk('s3');
  $user_id = \Auth::user()->id;
  $timenow = Carbon::now();
  $arrayValues = [];
  if (isset($attachedFiles)) {
    foreach ($attachedFiles as $iIndex => $file) {
         $extension = $file->getClientOriginalExtension();
         $nameoffile = $file->getClientOriginalName();
         $file->move(public_path('uploads'), $timenow.$nameoffile);
         $path = public_path('uploads/').$timenow.$nameoffile;
         $save = $disk->putFileAs("email/submissions",new File($path),$timenow.$nameoffile,'public');
         $arrayValues[$iIndex]['path'] = $save;
         $arrayValues[$iIndex]['name'] = $nameoffile;
         if(\File::exists($path)){
           \File::delete($path);
         }
       }
  }
  return $arrayValues;

}

// Search
function arrays_filter_recursive($array)
{
    if(is_array($array))
    {
        $array = array_map('array_filter', $array);
        return array_filter($array);
    }
}
