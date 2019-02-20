<?php
    /**
     * This is user modle to control all the model of Modules.
     *
     * @author Faran Ahmed (Vteams)
     */


namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    
    /**
     * The attributes that are mass assignable.
     * Also table name can be defined if not used the same way as laravel
     * @var array
     */

    //protected $table = 'template_associate';
    protected $fillable = [
         'name', 'general', 'value', 'linkto', 'logo', 'template_id'
    ];

    public function parent()
    {
        return $this->hasOne(static::class, 'id','linkto');
    }
    
    /**
     * @return array
     */
    public function linkedInvoice($id)
    {
        return  \App\Form::select('invoice')->where('linkto', $id)->first();
    }
    
    /**
     * @return array
     */
    public function ChecklinkedInvoice($id,$asst_id)
    {
        return \App\inviteParticipantinvoice::where('module_id',$id)
            ->where('asset_id',$asst_id)
            ->count();
    }

    public function childModule()
    {

        return $this->hasMany("App\Module", 'linkto','id');

    }

    public function parentModule()
    {

        return $this->belongsTo("App\Module", 'linkto','id');

    }

    public function moduleLogo()
    {
        return $this->hasOne("App\AppModules", 'module_id','id');
    }
    public function form_module()
    {
        return $this->hasOne("App\Form", 'linkto','id');
    }


    public function forms()
    {
        return $this->hasOne("App\Form", 'linkto','id');
    }


    public function allChildrenAccounts()
    {
        return $this->parentModule()->with('allChildrenAccounts');
    }


}
