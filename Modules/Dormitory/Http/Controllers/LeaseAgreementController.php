<?php

namespace Modules\Dormitory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Modules\Dormitory\Entities\Dormitory;
use Modules\Dormitory\Entities\DormitoryUser;
use Modules\Dormitory\Entities\DormitoryRoom;
use Modules\Dormitory\Entities\DormitoryLease;
use Modules\Dormitory\Entities\DormitoryRoom_User;
use Modules\Dormitory\Entities\DormitoryMonthly_rent;
use Modules\Dormitory\Entities\Dormitory_Check_In;
use Modules\User\Entities\Modules_User_Lavel;

use App\Models\User;


class LeaseAgreementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($code)
    {
        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();

        // $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->orderby('id','asc')->first();
        session(['latestLease' => url()->current()]);

        $dormitory->load([
            'rooms.metersWater', 
            'rooms.metersElectric',
            'rooms.latestLease',        
        ]);
        return view('dormitory::lease_agreements.index', compact('dormitory'));
    }
    
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        // dd($id);
        $data_room = DormitoryRoom::where('id',$id)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->first();
        // $data = DormitoryMonthly_rent::where('room_id',$data_room->id)->where('status_delete', '!=', 'Disable')->first();

        
        // dd($data);
        return view('dormitory::lease_agreements.create',compact('dormitory','data_room'));
    }
    public function store(Request $request)
    {
        //
    }

    public function insert(Request $request, $code){
        // dd($request->user_id);
        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();

        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ], [
            'startDate.required' => 'กรุณาเลือกวันเริ่มเช่า',
            'endDate.required' => 'กรุณาเลือกวันหมดสัญญา',
        ]);
        // dd($request->imgo);
        $imgPath = $request->file('img')->store('contract_images', 'public');
        // dd($imgPath);

        $data = new DormitoryLease();
        $data->rooms_id = $request->rooms_id;
        $data->startDate = $request->startDate;
        $data->endDate = $request->endDate;
        $data->monthly_rent = $request->monthly_rent;
        $data->img = $imgPath;
        $data->deposit = $request->deposit;
        $data->user_id = $request->user_id;
        $data->payment_status = $request->payment_status;
        $data->save();

            $data_lease = DormitoryLease::where('rooms_id', $request->rooms_id)->where('status_delete', '!=', 'Disable')->first();

            $billing = new DormitoryMonthly_rent;
            $billing->room_id = $request->rooms_id;
            $billing->monthly_rent = $data_lease->monthly_rent;
            $billing->payment_status = 'Unpaid';
            $billing->save();

            $check_in = new Dormitory_Check_In;
            $check_in->room_id = $request->rooms_id;
            $check_in->users_id  = $request->user_id;
            $check_in->dormitorys_id  = $dormitory->id ;
            $check_in->save();

            $user = DormitoryUser::where('room_id',$request->rooms_id)->where('status_delete', '!=', 'Disable')->where('staying','live')->get();
            $total_user = $user->count();

            $data_room = DormitoryRoom::where('id',$request->rooms_id)->first();
            $data_room->monthly_rent = $request->monthly_rent;
            $data_room->deposit = $request->deposit;
            $data_room->resident = $request->total_user;
            
            $data_room->status = 'Active';
            $data_room->save();

            $data_user = User::where('status_delete', '!=', 'Disable')->find($request->user_id);
    
        $insert = new DormitoryUser;
        $insert->users_id = $request->user_id;
        $insert->room_id = $request->rooms_id;
        $insert->dormitorys_id = $dormitory->id;
        $insert->save();

        $item = new DormitoryRoom_User;
        $item->room_id = $request->rooms_id;
        $item->username = $request->username;
        $item->name = $data_user->name;
        $item->phone = $data_user->phone;
        $item->save();

        $user_lavels = Modules_User_Lavel::where('user_id', $data_user->id)->where('group_id', "4")->first();
        if (!$user_lavels) {
            $data_lavel = new Modules_User_Lavel;
            $data_lavel->user_id = $data_user->id;
            $data_lavel->group_id = '1';
            $data_lavel->save();
        }
        elseif($user_lavels->status_delete == "Disable"){
        $user_lavels->status_delete = 'Enable';
        $user_lavels->save();
        }

        return redirect()->route('dormitorys.lease_agreements.index', ['code' => $code]);
    }

    public function fetchUser(Request $request){
        $username = $request->username;
        $user = User::where('username', $username)->where('status_delete', '!=', 'Disable')->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'username' => $user->username,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบผู้ใช้',
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
 

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data_room = DormitoryRoom::where('id',$id)->where('status_delete', '!=', 'Disable')->first();

        $data = DormitoryLease::where('status_delete', '!=', 'Disable')->where('rooms_id',$data_room->id)->orderby('id','asc')->first();
        $data_month = DormitoryMonthly_rent::where('status_delete', '!=', 'Disable')->where('room_id',$data_room->id)->get();
        
        // dd($data_room);
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')->orderby('id','asc')->first();
        // $data = DormitoryLease::where('', )->orderBy('created_at', 'desc')->get();
        $dormitory->load([
            'rooms.metersWater', 
            'rooms.metersElectric',
            'rooms.latestLease',        
        ]);

        $previousUrl = session('latestLease');

        return view('dormitory::lease_agreements.show', compact('previousUrl','dormitory','data','data_room','data_month'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function edit($id)
    {
        // dd($id);
        $data = DormitoryLease::where('id',$id)->orderby('id','asc')->first();
        // dd($data);
        $data_room = DormitoryRoom::where('id',$data->rooms_id)->first();
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->first();
        return view('dormitory::lease_agreements.edit',compact('dormitory','data_room','data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // dd($request->username);
        $data = DormitoryLease::where('id',$id)->orderby('id','asc')->first();
        $data_room = DormitoryRoom::where('id',$data->rooms_id)->first();
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->first();

        $request->validate([
            'rooms_id' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'monthly_rent' => 'required|numeric',

        ], [
            'rooms_id.required' => 'กรุณาใส่หมายเลขห้อง',
            'startDate.required' => 'กรุณาเลือกวันเริ่มเช่า',
            'endDate.required' => 'กรุณาเลือกวันหมดสัญญา',
            'monthly_rent.required' => 'กรุณาใส่ราคาค่าเช่า',

        ]);

        $data = DormitoryLease::where('id',$id)->first();
        $data->rooms_id = $request->rooms_id;
        $data->startDate = $request->startDate;
        $data->endDate = $request->endDate;
        $data->monthly_rent = $request->monthly_rent;
        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('contract_images', 'public');
            $data['img'] = $imgPath;
        }
        $data->deposit = $request->deposit;
        $data->user_id = $request->user_id;
        $data->payment_status = $request->payment_status;
        $data->save();

        $data_lease = DormitoryLease::where('rooms_id', $data_room->id)->where('status_delete', '!=', 'Disable')->first();

            $billing = $data_lease;
            $billing->monthly_rent = $request->monthly_rent;
            $billing->save();
        
        $data_room = DormitoryRoom::where('id',$data->rooms_id)->first();
        $data_room->monthly_rent = $request->monthly_rent;
        $data_room->deposit = $request->deposit;
        $data_room->save();

        return redirect()->route('dormitorys.lease_agreements.index', ['code' => $dormitory->code]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
