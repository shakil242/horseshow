<?php

namespace App\Http\Controllers;

use App\ManageShows;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $collection = ManageShows::with("template","appowner");
        $trainerCollection = ManageShows::with("template","appowner");
        if($request->ajax()) {
            $query =  $request->get('query');
            $collection = $collection->where(function($q) use ($query) {
                $q->where('title', 'LIKE', '%'.$query.'%')
                    ->orWhere('location', 'LIKE', '%'.$query.'%')
                    ->orWhere('show_type', 'LIKE', '%'.$query.'%')
                    ->orWhere('governing_body', 'LIKE', '%'.$query.'%');
            });
            $trainerCollection = $trainerCollection->where(function($q) use ($query) {
                $q->where('title', 'LIKE', '%'.$query.'%')
                    ->orWhere('location', 'LIKE', '%'.$query.'%')
                    ->orWhere('show_type', 'LIKE', '%'.$query.'%')
                    ->orWhere('governing_body', 'LIKE', '%'.$query.'%');
            });
        }

        $collection =    $collection->where("type",SHOW_TYPE_SHOWS)->whereHas("template",function ($query){
        $query->where('category', CONST_SHOW);
    })->whereHas("appowner",function ($query){
        $query->where('status',1)->where('block',0);
    })->orderBy('date_from','desc')->get();


        $trainerCollection =  $trainerCollection->where("type",SHOW_TYPE_TRAINER)
            ->whereHas("template",function ($query){
            $query->where('category', TRAINER);
        })->whereHas("appowner",function ($query){
            $query->where('status',1)->where('block',0);
        })->orderBy('date_from','desc')->get();

        if($request->ajax()) {
            $type =  $request->get('type');
            if($type=='show')
            return view('show_search_view')->with(compact("collection"));
            else
           return view('trainer_search_view')->with(compact("trainerCollection"));
        }

        return view('index',compact(['collection','trainerCollection']));
    }
}
