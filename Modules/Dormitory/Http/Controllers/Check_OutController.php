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
use Modules\Dormitory\Entities\Dormitory_Check_Out;

class Check_OutController extends Controller
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
        $waiting_count = Dormitory_Check_Out::where('user_type', 'Waiting')->where('dormitorys_id', $dormitory->id)->where('status_delete', '!=', 'Disable')->count();
        $out_count = Dormitory_Check_Out::where('user_type', 'out')->where('dormitorys_id', $dormitory->id)->where('status_delete', '!=', 'Disable')->count();
        return view('dormitory::check_out.index', compact('dormitory', 'waiting_count','out_count'));
    }
    

    public function Waiting($code){
        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();
        $data = Dormitory_Check_Out::where('user_type', 'Waiting')->where('status_delete', '!=', 'Disable')->get();
        session(['ooo' => url()->current()]);
        
        return view('dormitory::check_out.Waiting',compact('dormitory','data'));
    }

    public function change($id){
        $data_user = Dormitory_Check_Out::where('id',$id)->first();
        $data_user->user_type = 'Out' ;
        $data_user->save();

        $data = DormitoryUser::where('users_id',$data_user->users_id)->first();
        $data->staying = 'out' ;
        $data->save();

        $data_room = DormitoryRoom::where('id',$data->room_id)->first();
        $data_room->status = 'Free';
        $data_room->save();

        return  redirect()->back();
    }


    public function out($code){
        session(['ooo' => url()->current()]);

        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();
        $data = Dormitory_Check_Out::where('user_type', 'out')->where('status_delete', '!=', 'Disable')->get();

        return view('dormitory::check_out.out',compact('dormitory','data'));
    }
}