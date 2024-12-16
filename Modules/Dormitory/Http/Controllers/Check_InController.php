<?php

namespace Modules\Dormitory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Modules\Dormitory\Entities\Dormitory;
use Modules\Dormitory\Entities\DormitoryMeter;
use Modules\Dormitory\Entities\DormitoryBillings;
use Modules\Dormitory\Entities\DormitoryBillings_month;
use Modules\Dormitory\Entities\DormitoryLease;

use Modules\Dormitory\Entities\DormitoryUser;
use Modules\Dormitory\Entities\DormitoryRoom;
use Modules\Dormitory\Entities\DormitoryMonthly_rent;
use Modules\Dormitory\Entities\Dormitory_Check_In;
use Modules\Dormitory\Entities\Dormitory_Check_Out;

class Check_InController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    
    public function index($code) {
        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();
        $waiting_count = Dormitory_Check_In::where('user_type', 'Waiting_In')->where('dormitorys_id', $dormitory->id)->where('status_delete', '!=', 'Disable')->count();
        $In_count = Dormitory_Check_In::where('user_type', 'In')->where('dormitorys_id', $dormitory->id)->where('status_delete', '!=', 'Disable')->count();
        return view('dormitory::check_in.index', compact('dormitory', 'waiting_count','In_count'));
    }
    public function Waiting($code){
        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();
        $data = Dormitory_Check_In::where('user_type', 'Waiting_In')->where('status_delete', '!=', 'Disable')->get();
        session(['ooo' => url()->current()]);
        
        return view('dormitory::check_in.Waiting',compact('dormitory','data'));
    }

    public function change($id){
        $data_user = Dormitory_Check_In::where('id',$id)->first();
        $data_user->user_type = 'In' ;
        $data_user->save();

        $data = DormitoryUser::where('users_id',$data_user->users_id)->first();
        if($data){
        $data->staying = 'live' ;
        $data->save();}

        $data_room = DormitoryRoom::where('id',$data->room_id)->first();
        $data_room->status = 'Active';
        $data_room->save();

        return  redirect()->back();
    }


    public function in($code){
        session(['ooo' => url()->current()]);

        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();
        $data = Dormitory_Check_In::where('user_type', 'In')->where('status_delete', '!=', 'Disable')->get();

        return view('dormitory::check_in.in',compact('dormitory','data'));
    }

    public function out($id){
        $data_user = Dormitory_Check_In::where('id',$id)->first();
        $data_user->status_delete = 'Disable' ;
        $data_user->save();

        $data = DormitoryUser::where('users_id',$data_user->users_id)->first();
        if($data){
        $data->staying = 'waiting_out' ;
        $data->save();
        }
        $data_out = new Dormitory_Check_Out;
        $data_out->users_id  = $data_user->users_id ;
        $data_out->dormitorys_id  = $data_user->dormitorys_id;
        $data_out->room_id   = $data_user->room_id;
        $data_out->save();

        return  redirect()->back();
    }
}