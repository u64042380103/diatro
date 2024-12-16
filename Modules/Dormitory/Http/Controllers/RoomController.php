<?php

namespace Modules\Dormitory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Modules\Dormitory\Entities\Dormitory;
use Modules\Dormitory\Entities\DormitoryUser;
use Modules\Dormitory\Entities\DormitoryRoom;
use Modules\Dormitory\Entities\DormitoryMeter;
use Modules\Dormitory\Entities\DormitoryBillings;
use Modules\Dormitory\Entities\DormitoryBillings_month;
use Modules\Dormitory\Entities\DormitoryLease;
use Modules\Dormitory\Entities\DormitoryMonthly_rent;
use Modules\Dormitory\Entities\DormitoryDetails;
use Modules\Dormitory\Entities\DormitoryRoom_User;

use Illuminate\Support\Str;

class RoomController extends Controller
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
    $search = request()->query('search');
    if (in_array(auth()->user()->user_type, [4, 5])) {
        $roomsQuery = $dormitory->rooms()->withCount('residents')->where(function($query) {
            $query->where('status', 'free')
                    ->orWhereIn('id', function($subquery) {
                        $subquery->select('room_id')->from('dormitory_users')
                                ->where('users_id', auth()->user()->id)
                                ->where('status_delete', '!=', 'Disable');
                    });
                });
            } else {
                $roomsQuery = $dormitory->rooms()->withCount('residents');
            }

    if ($search) {
        $roomsQuery->where('name', 'like', '%' . $search . '%')->where('status_delete', '!=', 'Disable');
    }

    $rooms = $roomsQuery->get();
    session(['room' => url()->current()]);
    $data_room = DormitoryRoom::where('dormitorys_id', $dormitory->id)
    ->where('status_delete', '!=', 'Disable')
    ->orderby('name','asc')->get();
        $total_rooms = $data_room->count();
    return view('dormitory::rooms.index', compact('dormitory', 'rooms','total_rooms'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($code)
    {
        $dormitory = Dormitory::where('code',$code)->where('status_delete', '!=', 'Disable')->first();

        return view('dormitory::rooms.create',compact('dormitory'));
    }

    // web.php
    public function insert(Request $request, $code)
{
    $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();
    $request->validate([
        'floor' => 'required',
        'name' => 'required',
        'status' => 'required',
    ],
    [
        'floor.required' => 'กรุณาป้อนชั้น',
        'name.required' => 'กรุณาป้อนหมายเลขห้อง',
        'status.required' => 'กรุณาเลือกสถานะ',
    ]);

    // Check if the room name already exists in the dormitory
    $existingRoom = DormitoryRoom::where('name', $request->name)
                                ->where('status_delete', '!=', 'Disable')
                                ->where('dormitorys_id', $dormitory->id)
                                ->first();
    if ($existingRoom) {
        return redirect()->back()->withErrors(['name' => 'หมายเลขห้องนี้มีอยู่แล้ว'])->withInput();
    }
    
    // $imgroomPath = $request->file('img_room')->store('room_images', 'public');

        $data =  new DormitoryRoom;
        $data->dormitorys_id = $dormitory->id;
        $data->name = $request->name;
        $data->floor = $request->floor;
        $data->resident = "0";
        $data->residents_additional = "0";
        $data->status = $request->status;
        $data->room_type = $request->room_type;
        $data->area  = $request->area ;        
        $data->extension = $request->extension;

        $data->monthly_rent = $request->monthly_rent;
        $data->deposit = $request->deposit;

        if ($request->hasFile('img_room')) {
            $images = [];
            foreach ($request->file('img_room') as $file) {
                $randomString = Str::random(4);
                $filename = time() . '-' . $randomString  . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/room_images'), $filename);
                $images[] = $filename;
            }
            $data->img_room = json_encode($images);
        }
        $data->save();

    $room = DormitoryRoom::orderBy('id', 'desc')->first();

    $electricMeter = new DormitoryMeter;
    $electricMeter->meter = 0;
    $electricMeter->unit = 0;
    $electricMeter->type = 'electric';
    $electricMeter->Total = 0;
    $electricMeter->rooms_id = $room->id;
    $electricMeter->payment_status= 'Paid';
    $electricMeter->save();

    $waterMeter = new DormitoryMeter;
    $waterMeter->meter = 0;
    $waterMeter->unit = 0;
    $waterMeter->type = 'water';
    $waterMeter->Total = 0;
    $waterMeter->rooms_id = $room->id;
    $waterMeter->payment_status= 'Paid';
    $waterMeter->save();

    $billing = new DormitoryBillings;
    $billing->room_id = $room->id;
    $billing->sum = 0;
    $billing->amount = 0;
    $billing->payment_status = 'Unpaid';
    $billing->save();

    // $billing = new DormitoryLease;
    // $billing->room_id = $room->id;
    // $billing->monthly_rent = 0 ;
    // $billing->deposit = 0 ;
    // $billing->deposit_amount = 0 ;
    // $billing->payment_status = 'Unpaid';
    // $billing->save();

    // $data_lease = DormitoryLease::where('rooms_id', $room->id)->where('status_delete', '!=', 'Disable')->first();

    // $billing = new DormitoryMonthly_rent;
    // $billing->room_id = $room->id;
    // $billing->monthly_rent = $data_lease->monthly_rent;
    // $billing->payment_status = 'Unpaid';
    // $billing->save();

    return redirect()->route('dormitorys.rooms.index', ['code' => $code]);
}

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function show($id)
{
    $data = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
    // dd($data);
    $dormitory = Dormitory::where('id', $data->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
    $data_lease = DormitoryLease::where('rooms_id', $data->id)->where('status_delete', '!=', 'Disable')->first();
    $data_month = DormitoryMonthly_rent::where('room_id',$data->id)->orderby('payment_status', "desc")
    ->where('status_delete', '!=', 'Disable')->orderby('created_at', "desc")->get();

    $previousUrl = session('room');
    session(['billings' => url()->current()]);
    session(['latestLease' => url()->current()]);
    session(['Control' => url()->current()]);

    $dormitory->load([
        'rooms.metersWater' => function($query) {
            $query->where('status_delete', '!=', 'Disable')->where('payment_status', 'Unpaid');
        },
        'rooms.metersElectric' => function($query) {
            $query->where('status_delete', '!=', 'Disable')->where('payment_status', 'Unpaid');
        },
        'rooms.latestLease',
        'rooms.billings'
    ]);

    // Get the latest meter readings
    $latestWaterMeter = $data->metersWater()->latest()->first();
    $latestElectricMeter = $data->metersElectric()->latest()->first();

    $details = DormitoryDetails::where('room',$data->id)->where('status_delete', '!=', 'Disable')->get();
    session(['details' => url()->current()]);
    $data_water = DormitoryMeter::where('rooms_id', $data->id)
    ->where('payment_status', 'Unpaid')
    ->where('type', 'water')
    ->where('status_delete', '!=', 'Disable')
    ->get();
        $total_water = $data_water->sum('Total');
    $data_water_latest = DormitoryMeter::where('rooms_id', $data->id)
        ->where('type', 'water')
        ->where('status_delete', '!=', 'Disable')
        ->orderBy('id', 'desc')
        ->first();    
// dd($data_water_latest);
    $data_electric = DormitoryMeter::where('rooms_id', $data->id)
    ->where('payment_status', 'Unpaid')
    ->where('type', 'electric')
    ->orderby('payment_status', "desc")
    ->where('status_delete', '!=', 'Disable')
    ->orderby('created_at', "desc")
    ->get();
    $data_electric_latest = DormitoryMeter::where('rooms_id', $data->id)
    ->where('type', 'electric')
    ->where('status_delete', '!=', 'Disable')
    ->orderBy('id', 'desc')
    ->first();    

    $meter_electric = DormitoryMeter::where('rooms_id', $data->id)
    ->where('type', 'electric')
    ->where('status_delete', '!=', 'Disable')
    ->orderby('payment_status', "desc")
    ->orderby('created_at', "desc")
    ->get();
    $meter_water = DormitoryMeter::where('rooms_id', $data->id)
    ->where('type', 'water')
    ->where('status_delete', '!=', 'Disable')
    ->orderby('created_at', "desc")
    ->get();
    // dd($electric);
        $total_electric = $data_electric->sum('Total');
    
    $arrears = DormitoryMonthly_rent::where('room_id',$data->id)
    ->where('payment_status', 'Unpaid')
    ->where('status_delete', '!=', 'Disable')
    ->orderby('created_at', "desc")->get();
    $total_arrears = $arrears->sum('monthly_rent');
    $data_user = DormitoryUser::where('room_id', $data->id)->where('status_delete', '!=', 'Disable')->where('staying','live')->get();
    $total_user = $data_user->count();

    $data_usered = DormitoryRoom_User::where('room_id', $data->id)->where('status_delete', '!=', 'Disable')->get();

    // $data_Billings = DormitoryBillings::where('room_id', $data->id)->where('status_delete', '!=', 'Disable')->first();
    // dd($data_Billings);
    // $data_Billings_month = DormitoryBillings_month::where('billings_id', $data_Billings->id)->where('status_delete', '!=', 'Disable')->get();

    $data_Billings = DormitoryBillings::where('room_id', $data->id)->where('status_delete', '!=', 'Disable')->get();
    // dd($data_Billings);
    $data_Billings_month = [];
    // foreach($data as $data_bill){
        foreach ($data_Billings as $billing) {
            $billingData = DormitoryBillings_month::where('billings_id', $billing->id)
                ->where('payment_status', 'Unpaid')
                ->where('status_delete', '!=', 'Disable')
                ->get();

            $amount = $billingData->sum('amount');
            $data_Billings_month[$billing->id] = $amount;
        }
// }
$rooms = DormitoryRoom::where('dormitorys_id', $dormitory->id)->where('status_delete', '!=', 'Disable')->orderby('name','asc')->get(['id', 'name']);

        // dd($data_Billings_month);
    return view('dormitory::rooms.show', compact('dormitory','data_Billings','billingData','data_Billings_month','data_user', 'data_usered','total_user','data', 'total_arrears','meter_electric','meter_water','data_lease',
    'data_water','data_electric', 'latestWaterMeter', 'latestElectricMeter','previousUrl','details','total_water','total_electric','data_month','data_water_latest','data_electric_latest'));
}


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
{
    $data = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
    $dormitory = Dormitory::where('id', $data->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
    $data_lease = DormitoryLease::where('rooms_id', $data->id)->where('status_delete', '!=', 'Disable')->first();
    $data_user = DormitoryUser::where('room_id', $data->id)->where('status_delete', '!=', 'Disable')->get();
    $total_user = $data_user->count();
        
    // dd($data_user);
    // dd($data_lease);
    $dormitory->load([
        'rooms.metersWater' => function($query) {
            $query->where('status_delete', '!=', 'Disable')->where('payment_status', 'Unpaid');
        },
        'rooms.metersElectric' => function($query) {
            $query->where('status_delete', '!=', 'Disable')->where('payment_status', 'Unpaid');
        },
        'rooms.latestLease',
        'rooms.billings'
    ]);
    
    $latestWaterMeter = $data->metersWater()->latest()->first();
    $latestElectricMeter = $data->metersElectric()->latest()->first();

    return view('dormitory::rooms.edit', compact('dormitory', 'data','data_lease','total_user','latestWaterMeter', 'latestElectricMeter'));
}
    
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // dd($id);
        $data_room = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        // dd($data);
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')
        ->first();
        
        $request->validate([
            'floor' => 'required',
            'name' => 'required',
            'status' => 'required',],
            ['floor.required' => 'กรุณาป้อนชั้น',
            'name.required' => 'กรุณาป้อนหมายเลขห้อง',
            'status.required' => 'กรุณาเลือกสถานะ',
        ]);
        
        $existingRoom = DormitoryRoom::where('dormitorys_id', $dormitory->id)
                                    ->where('name', $request->name)
                                    ->where('status_delete', '!=', 'Disable')
                                    ->where('id', '!=', $id)
                                    ->first();
        if ($existingRoom) {
            return redirect()->back()->withErrors(['name' => 'หมายเลขห้องนี้มีอยู่แล้ว'])->withInput();
        }
        
        $data_user = DormitoryUser::where('room_id', $data_room->id)->where('status_delete', '!=', 'Disable')->where('staying','live')->get();
        $total_user = $data_user->count();

        $data = DormitoryRoom::where('id',$id)->first();
        $data->dormitorys_id = $dormitory->id;
        $data->name = $request->name;
        $data->floor = $request->floor;
        $data->resident = $total_user;
        $data->residents_additional = $request->residents_additional;
        $data->extension = $request->extension;
        $data->status = $request->status;

        if ($request->hasFile('img_room')) {
            $images = [];
            foreach ($request->file('img_room') as $file) {
                $randomString = Str::random(4);
                $filename = time() . '-' . $randomString  . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/room_images'), $filename);
                $images[] = $filename;
            }
            $data->img_room = json_encode($images);
        }
        $data->monthly_rent = $request->monthly_rent;
        $data->deposit = $request->deposit;
        $data->room_type = $request->room_type;
        $data->area  = $request->area ;
        $data->water  = $request->water ;
        $data->check_water  = $request->check_water ;
        $data->person  = $request->person ;
        $data->save();

        // $data_lease = DormitoryLease::where('rooms_id', $id)->where('status_delete', '!=', 'Disable')->first();
    
        // if ($data_lease) {
        //     $data_lease->monthly_rent = $request->monthly_rent;
        //     $data_lease->deposit = $request->deposit;
        //     $data_lease->save();
        // }
        
        return redirect()->route('dormitorys.rooms.show', ['id' => $data_room->id]);
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

    function delete($id){
        $data = DormitoryRoom::where('id', $id)->first();
        $dormitory = Dormitory::where('id', $data->dormitorys_id)->first();
        $datameter = DormitoryMeter::where('rooms_id', $id)
            ->orderby('meter', "desc")
            ->get();
        $databilling = DormitoryBillings::where('room_id',$id)->first();
        if ($databilling) {
            $databilling->status_delete = 'Disable';
            $databilling->save();
            }
        $datalease = DormitoryLease::where('rooms_id',$id)->first();
        if ($datalease) {
            $datalease->status_delete = 'Disable';
            $datalease->save();
            }
        foreach ($datameter as $meter) {
            $meter = DormitoryMeter::where('rooms_id',$id)->first();
            if ($meter) {
                $meter->status_delete = 'Disable';
                $meter->save();
                }
            // DB::table('dormitory_meters')->where('id', $meter->id)->delete();
        }
        if ($data) {
            $data->status_delete = 'Disable';
            $data->save();
            }
        return redirect()->route('dormitorys.rooms.index', ['code' => $dormitory->code]);
    }

}
