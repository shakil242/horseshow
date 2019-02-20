<?php
    /**
     * This is user modle to control all the model of Modules.
     *
     * @author Faran Ahmed (Vteams)
     */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
	/**
     * The attributes that are mass assignable.
     * Also table name can be defined if not used the same way as laravel
     * @var array
    */

    protected $fillable = [
         'name', 'linkto', 'form_type', 'invoice', 'scheduler'
    ];
    /**
     * Get Form Types for forms
     *
     * @return form types
     */
    public function formtypes() {
        return $this->hasOne('App\FormType', 'id', 'form_type');
    }
    /**
     * Get Module Attached to
     *
     * @return modules attached
     */
    public function moduleAttached() {
        return $this->hasOne('App\Module', 'id', 'linkto');
    }
     /**
     * Get Invoice title Attached to form
     *
     * @return modules attached
     */
    public function invoiceTitle()
    {
        return $this->hasOne("App\Form", 'id','invoice');
    }
      /**
     * Get Invoice User Attached to form
     *
     * @return Invoices attached
     */
    public function invoiceUser($id,$assetId)
    {
    
        $userId = \Auth::user()->id;
    
        return  \App\Invoice::where('form_id', $id)
            ->where('user_id', $userId)
            ->where('asset_id', $assetId);

    }
    
      /**
     * Get status Attached to form
     *
     * @return modules attached
     */

    public function invoiceStatus($id,$assetId,$fieldName,$value)
    {
        return $this->invoiceUser($id,$assetId)->where($fieldName,$value)->count();
    }
      /**
     * Get response form
     *
     * @return modules attached
     */
    public function responseForm($id,$templateId)
    {
        
        $userId = \Auth::user()->id;
    
        return  \App\Invoice::where('form_id', $id)
              ->where('payer_id', $userId)
              ->where('template_id', $templateId)
              ->where('response_id','!=',0);
    }
      /**
     * Get all assets
     *
     * @return array assets
     */
    public function assets()
    {
        return $this->hasMany('App\Asset','form_id');
    }
      /**
     * Get template
     *
     * @return modules attached
     */
    public function template()
    {
        return $this->hasOne("App\Template", 'id','template_id');
    }
    
    
}
