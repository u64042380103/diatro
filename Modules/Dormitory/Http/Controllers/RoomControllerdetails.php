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
use Modules\Dormitory\Entities\DormitoryLease;
use Modules\Dormitory\Entities\DormitoryMonthly_rent;
use Modules\Dormitory\Entities\DormitoryDetails;
use Modules\Dormitory\Entities\DormitoryRepair;



class RoomControllerdetails extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $Url_details = session('details');
        $data_room = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $data = DormitoryDetails::where('room', $data_room->id)->where('status_delete', '!=', 'Disable')->get();
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
        return view('dormitory::rooms_details.index', compact('dormitory', 'data', 'data_room', 'Url_details'));
    }
    
    
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {        
        $data_room = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();

        return view('dormitory::rooms_details.create',compact('dormitory','data_room'));
    }

    // web.php
    public function insert(Request $request, $id)
{        
    $data_room = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
    $dormitory = Dormitory::where('id', $data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
    $request->validate([
        'name' => 'required',
        'details' => 'required',
    ],
    [
        'name.required' => 'กรุณาป้อนหมายเลขห้อง',
        'details.required' => 'กรุณาเลือกรายละเอียด',
    ]);
    $imgdetailsPath = $request->file('img_details')->store('details_images', 'public');

        $data =  new DormitoryDetails;
        $data->room = $id;
        $data->name = $request->name;
        $data->date_buy = $request->date_buy;
        $data->price = $request->price;
        $data->details = $request->details;
        $data->imgdetailsPath = $request->imgdetailsPath;
        $data->save();

    return redirect()->route('dormitorys.rooms_details.index', $data_room->id);
}

    public function create_repair($id)
{        
    // dd($id);
    $data_Details = DormitoryDetails::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
    // dd($data_Details);
    $data_room = DormitoryRoom::where('id', $data_Details->room)->where('status_delete', '!=', 'Disable')->first();
    $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();

    return view('dormitory::rooms_details.create_repair',compact('dormitory','data_room','data_Details'));
}
    public function insert_repair(Request $request, $id)
{        
    $data_Details = DormitoryDetails::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
    $data_room = DormitoryRoom::where('id', $data_Details->room)->where('status_delete', '!=', 'Disable')->first();

    $data = new DormitoryRepair;
    $data->tool_id = $data_Details->id;
    $data->date = $request->date;
    $data->price = $request->price;
    $data->details = $request->details;
    $data->save();

    return redirect()->route('dormitorys.rooms_details.index', $data_room->id);
}


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
{
    $data = DormitoryRoom::where('name', $id)->where('status_delete', '!=', 'Disable')->first();
    $dormitory = Dormitory::where('id', $data->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
    $data_lease = DormitoryLease::where('rooms_id', $data->id)->where('status_delete', '!=', 'Disable')->first();

    $previousUrl = session('room');

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

    $details = DormitoryDetails::where('room',$data->id)->where('status_delete', '!=', 'Disable')->get();;

    return view('dormitory::rooms.show', compact('dormitory', 'data', 'data_lease', 'latestWaterMeter', 'latestElectricMeter','previousUrl','details'));
}


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
{
    $details = DormitoryDetails::where('id',$id)->where('status_delete', '!=', 'Disable')->first();
    $data = DormitoryRoom::where('id', $details->room)->where('status_delete', '!=', 'Disable')->first();
    $dormitory = Dormitory::where('id', $data->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();

    return view('dormitory::rooms_details.edit', compact('dormitory', 'data','details'));
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
        $details = DormitoryDetails::where('id',$id)->where('status_delete', '!=', 'Disable')->first();

        $data_room = DormitoryRoom::where('id', $details->room)->where('status_delete', '!=', 'Disable')->first();
        // dd($data);
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')
        ->first();
    
            $data = DormitoryDetails::where('id',$id)->first();
            $data->name = $request->name;
            $data->date_buy = $request->date_buy;
            $data->price = $request->price;
            $data->details = $request->details;
            if ($request->hasFile('img_details')) {
                $imgdetailsPath = $request->file('img_details')->store('details_images', 'public');
                $data['img_details'] = $imgdetailsPath;
            }
            $data->save();
            
        return redirect()->route('dormitorys.rooms_details.index', ['id' => $data_room->id]);
    }

    public function edit_repair($id)
    {
        $data_Repair = DormitoryRepair::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $data_Details = DormitoryDetails::where('id', $data_Repair->tool_id)->where('status_delete', '!=', 'Disable')->first();
        $data_room = DormitoryRoom::where('id', $data_Details->room)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
    
        return view('dormitory::rooms_details.edit_repair', compact('dormitory', 'data_room','data_Details','data_Repair'));
    }

    public function update_repair(Request $request, $id)
    {        
        $data_Repair = DormitoryRepair::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $data_Details = DormitoryDetails::where('id', $data_Repair->tool_id)->where('status_delete', '!=', 'Disable')->first();
        $data_room = DormitoryRoom::where('id', $data_Details->room)->where('status_delete', '!=', 'Disable')->first();
    
        // dd($request->details);
        $data = DormitoryRepair::where('id',$id)->first();
        $data->date = $request->date;
        $data->price = $request->price;
        $data->details = $request->details;
        $data->save();
    
        return redirect()->route('dormitorys.rooms_details.index', $data_room->id);
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
    public function delete($id)
{
    $details = DormitoryDetails::find($id);
    if ($details) {
        $details->status_delete = 'Disable';
        $details->save();
    }

    $data_room = DormitoryRoom::find($details->room);
    return redirect()->route('dormitorys.rooms_details.index', $data_room->name)
                        ->with('success', 'อุปกรณ์ถูกลบเรียบร้อยแล้ว');
}


    public function massDelete(Request $request,$id)
{
    $data_room = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();

    $ids = $request->input('ids');
    
    if (!empty($ids)) {
        DB::beginTransaction(); // Start transaction
        
        foreach ($ids as $id) {
            $Details = DormitoryDetails::find($id);
            if ($Details) {
                $Details->status_delete = 'Disable';
                $Details->save();
            }
        }
        
        DB::commit(); // Commit transaction
        return redirect()->route('dormitorys.rooms_details.index', $data_room->name)->with('success', 'ลบอุปกรณ์ที่เลือกเรียบร้อยแล้ว');
    } else {
        return redirect()->route('dormitorys.rooms_details.index', $data_room->name)->withErrors(['error' => 'ไม่พบข้อมูลที่เลือก']);
    }
}


}
