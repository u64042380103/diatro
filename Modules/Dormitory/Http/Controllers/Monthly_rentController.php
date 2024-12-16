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
use Modules\Dormitory\Entities\DormitoryMonthly_rent;
use Modules\Dormitory\Entities\DormitoryBillings_month;


class Monthly_rentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id){
        $data_room = DormitoryRoom::where('id',$id)->first();
        $data_month = DormitoryMonthly_rent::where('status_delete', '!=', 'Disable')->where('room_id',$data_room->id)->get();
        // dd($data_room);
        $data_Lease = DormitoryLease::where('status_delete', '!=', 'Disable')->where('rooms_id',$data_room->id)->orderby('id','asc')->first();
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')->orderby('id','asc')->first();
        $dormitory->load([
            'rooms.metersWater', 
            'rooms.metersElectric',
            'rooms.latestLease',        
        ]);
        $user = request('user');
        $month_id = request('month_id');
        $room_id = request('room_id');
        if($month_id){
            $previousUrl = session('month');
            if($user){
                $previousUrl = route('users_review.index', ['id' => $user, 'dormitory_user' => true,'room_id' => $data_room->id]);
            }
            if($room_id){
                $previousUrl = route('dormitorys.rooms.show', $data_room->id );
            }
        }
        else{
        $previousUrl = session('latestLease');
        }

        return view('dormitory::monthly_rent.index', compact('data_Lease','previousUrl','dormitory','data_room','data_month','month_id'));
    }
    
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id){
        // dd($id);
        $data_room = DormitoryRoom::where('id',$id)->first();
        // dd($data_room);
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->first();
        // dd($dormitory);
        return view('dormitory::monthly_rent.create',compact('dormitory','data_room'));
    }

    public function insert(Request $request, $id){

        $data_room = DormitoryRoom::where('id',$id)->first();
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->first();

        $request->validate([
            'monthly_rent' => 'required|numeric',
        ], [
            'monthly_rent.required' => 'กรุณาใส่ราคาค่าเช่า',
        ]);
        // dd($request->rooms_id);

        $insert = new DormitoryMonthly_rent();
        $insert->room_id = $id;
        $insert->monthly_rent = $request->monthly_rent;
        $insert->payment_status = $request->payment_status;
        $insert->save();
        return redirect()->route('dormitorys.monthly_rent.index', ['id' => $id]);
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
    public function show($id){
        return view('dormitory::lease_agreements.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function edit($id){
        // dd($id);
        $data = DormitoryMonthly_rent::where('id',$id)->orderby('id','asc')->first();
        // dd($data);
        $data_room = DormitoryRoom::where('id',$data->room_id)->first();
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->first();
        return view('dormitory::monthly_rent.edit',compact('dormitory','data_room','data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id){
        // dd($id);
        $data = DormitoryMonthly_rent::where('id',$id)->orderby('id','asc')->first();
        $data_room = DormitoryRoom::where('id',$data->room_id)->first();
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->first();
        $data_month = DormitoryBillings_month::where('data_id',$data->id)->first();
        // dd($data_month);
        $request->validate([
            'monthly_rent' => 'required|numeric',
        ], [
            'monthly_rent.required' => 'กรุณาใส่ราคาค่าเช่า',
        ]);

        $data = DormitoryMonthly_rent::where('id',$id)->first();
        $data->room_id = $request->room_id;
        $data->monthly_rent = $request->monthly_rent;
        $data->payment_status = $request->payment_status;
        $data->save();

        DormitoryBillings_month::where('data_id',$data->id)->update(['payment_status' => $request->payment_status]);

        return redirect()->route('dormitorys.monthly_rent.index', ['id' => $data_room->id]);
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
        // dd($id);        
        $data = DormitoryMonthly_rent::where('id',$id)->first();
        // dd($data);
        $data_room = DormitoryRoom::where('id',$data->room_id)->first();
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->first();

        if ($data) {
            $data->status_delete = 'Disable';
            $data->save();
        }        
        return redirect()->route('dormitorys.monthly_rent.index', ['id' => $data_room->id]);
    }

    public function massDelete(Request $request){

        $ids = $request->input('ids');
        // dd($ids);
        if (!empty($ids)) {
        foreach($ids as $itm){
            $data = DormitoryMonthly_rent::where('id',$itm)->first();
            if ($data) {
                $data->status_delete = 'Disable';
                $data->save();
                }            
            }
        }
        return back();
    }
}
