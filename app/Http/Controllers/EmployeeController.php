<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    //manage employee listing

    public function index($template_id,$app_id)
    {

        $user_id = \Auth::user()->id;

        $template_id = nxb_decode($template_id);
        $app_id = nxb_decode($app_id);

        $collection = Employee::where('template_id',$template_id)
            ->where('app_id',$app_id)
            ->where('show_owner_id',$user_id)
            ->get();

        return view('employees.index')->with(compact('collection','template_id','app_id'));

    }


    public function addEmployee(Request $request)
    {
        $user_id = \Auth::user()->id;



        if($request->employee_id=='') {


            $this->validate($request,[
                'email' => 'required|unique:employees,email,NULL,id,template_id,'.$request->template_id.',app_id,'.$request->app_id
            ]);

            $model = new Employee();

            $model->name = $request->name;
            $model->email = $request->email;
            $model->template_id = $request->template_id;
            $model->designation = $request->designation;
            $model->show_owner_id = $user_id;
            $model->app_id = $request->app_id;
            $model->permissions = json_encode($request->permissions);
            $model->save();
        }
        else
        {
           $model = Employee::findOrFail($request->employee_id);
            $model->name = $request->name;
            $model->email = $request->email;
            $model->template_id = $request->template_id;
            $model->designation = $request->designation;
            $model->app_id = $request->app_id;
            $model->show_owner_id = $user_id;
            $model->permissions = json_encode($request->permissions);
            $model->update();
        }

        \Session::flash('message', 'Employee data has been updated successfully');
        return redirect()->route('EmployeeController-index', ['id' => nxb_encode($request->template_id),'app_id'=>nxb_encode($request->app_id)]);

    }


    public function view($id)
    {
        $collection = Employee::where('id',$id)->first();
        return $Response = array(
            'results' => json_encode($collection->toArray()),
        );
    }


    public function delete($id)
    {

        $id = nxb_decode($id);
        $model = Employee::findOrFail($id);
        $model->delete();

        \Session::flash('message', 'Employee records has been deleted successfully');

        return redirect()->back();
    }

    public function updateStatus($id,$status)
    {
        $id = nxb_decode($id);

        $model = Employee::findOrFail($id);
        $model->status = $status;
        $model->update();

        \Session::flash('message', 'Employee status has been updated successfully');

        return redirect()->back();
    }


    public function isEmployee($tabSelected)
    {

    if($tabSelected==5)
    {
        \Session::put('isEmployee', 1);
    }
    else
    {
        \Session::put('isEmployee', 0);
    }

        echo $isEmail = \Session('isEmployee');



    }


}
