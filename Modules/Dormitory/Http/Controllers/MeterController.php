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


class MeterController extends Controller
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
        $filter = request('filter');
        // dd($filter);
        $dormitory->load([
            'rooms.metersLatest' => function($query) use ($filter) {
            $query->where('type', $filter);
        }, 'rooms.previousMonthMeter'=> function($query) use ($filter) {
            $query->where('type', $filter);
        },]);

        session(['meter' => url()->current()]);


        return view('dormitory::meters.index', compact('dormitory', 'filter'));
    }
    
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(){
        return view('dormitory::create');
    }

    public function insert(Request $request, $id_room) {
        $room = DormitoryRoom::where('id', $id_room)->where('status_delete', '!=', 'Disable')->first();
        $filter = request('filter');
        $dormitory = Dormitory::where('id', $room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
    
        $dormitory->load(['rooms.previousMonthMeter' => function($query) use ($filter) {
            $query->where('type', $filter);
        }, 'rooms.metersLatest' => function($query) use ($filter) {
            $query->where('type', $filter);
        }]);
    
        // Get the last meter record for the room
        $lastMeter = DormitoryMeter::where('rooms_id', $room->id)
            ->where('status_delete', '!=', 'Disable')
            ->where('type', $filter)
            ->orderBy('created_at', 'desc')
            ->first();
        // dd($lastMeter);
        $currentMonth = now()->format('Y-m');
        if ($lastMeter && $lastMeter->created_at->format('Y-m') == $currentMonth) {
            return redirect()->back()->withErrors(['meter' => 'ข้อมูลมิเตอร์ในเดือนนี้ถูกเพิ่มไปแล้ว']);
        }
    
        $previous_meter = $request->previous_meter;
        $meter = $request->meter;
        if ($meter <= $previous_meter) {
            return redirect()->back()->withErrors(['meter' => 'เลขมิเตอร์น้อยกว่ามิเตอร์ครั่งก่อน']);
        }
    
        if ($filter == 'electric') {
            if ($request->unit >= 0) {
                if ($request->unit <= 15) {
                    $Total = 2.3488 * $request->unit;
                } elseif ($request->unit <= 25) {
                    $Total = 2.9882 * $request->unit;
                } elseif ($request->unit <= 35) {
                    $Total = 3.2405 * $request->unit;
                } elseif ($request->unit <= 100) {
                    $Total = 3.6237 * $request->unit;
                } elseif ($request->unit <= 150) {
                    $Total = 3.7171 * $request->unit;
                } elseif ($request->unit <= 400) {
                    $Total = 4.2218 * $request->unit;
                } else {
                    $Total = 4.4217 * $request->unit;
                }
            }
        } elseif ($filter == 'water') {
            if ($request->unit >= 0) {
                if ($request->unit <= 10) {
                    $Total = 16.00 * $request->unit;
                } elseif ($request->unit <= 20) {
                    $Total = 19 * $request->unit;
                } elseif ($request->unit <= 30) {
                    $Total = 20 * $request->unit;
                } elseif ($request->unit <= 50) {
                    $Total = 21.50 * $request->unit;
                } elseif ($request->unit <= 80) {
                    $Total = 21.60 * $request->unit;
                } elseif ($request->unit <= 100) {
                    $Total = 21.65 * $request->unit;
                } elseif ($request->unit <= 300) {
                    $Total = 21.70 * $request->unit;
                } elseif ($request->unit <= 1000) {
                    $Total = 21.75 * $request->unit;
                } elseif ($request->unit <= 2000) {
                    $Total = 21.80 * $request->unit;
                } elseif ($request->unit <= 3000) {
                    $Total = 21.85 * $request->unit;
                } else {
                    $Total = 21.90 * $request->unit;
                }
            }
        }
        
        $Total = round($Total, 2);
        $insert = new DormitoryMeter;
        $insert->meter = $request->meter;
        $insert->unit = $request->unit;
        $insert->type = $request->type;
        $insert->Total = $Total;
        $insert->rooms_id = $room->id;
        $insert->month_latest = $lastMeter ? $lastMeter->id : null;
        $insert->payment_status = 'Unpaid'; // Corrected assignment
        $insert->save();
    
        // Insert DormitoryMonthly_rent record only if the type is 'water'
        // if ($request->type == 'electric') {
        //     $data_lease = DormitoryLease::where('rooms_id', $room->id)->where('status_delete', '!=', 'Disable')->first();
    
        //     $monthlyRent = new DormitoryMonthly_rent();
        //     $monthlyRent->room_id = $room->id;
        //     $monthlyRent->monthly_rent = $data_lease->monthly_rent;
        //     $monthlyRent->payment_status = 'Unpaid';
        //     $monthlyRent->save();
        // }
    
        return redirect()->route('dormitorys.meters.show', ['id_room' => $id_room, 'filter' => $filter]);
    }
    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request){
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id_room){

        // dd($id_room);
        $room = DormitoryRoom::where('id', $id_room)->where('status_delete', '!=', 'Disable')->first();
        // dd($room);
        $dormitory = Dormitory::where('id', $room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
        $filter = request('filter');
        $id_meter = request('id_meter');
        $room_id = request('room_id');

        $room->load(['previousMonthMeter' => function($query) use ($filter) {
            $query->where('type', $filter);
        },'metersLatest' => function($query) use ($filter) {
            $query->where('type', $filter); }]);

        $datameter = DormitoryMeter::where('rooms_id', $room->id)
            ->where('type', $filter)
            ->where('status_delete', '!=', 'Disable')
            ->orderby('meter', "desc")
            ->get();
            if ($id_meter) {
                $previousUrl = session('month');
                if($room_id){
                    $previousUrl = route('dormitorys.rooms.show', $room->id);
                }
            }else{
                $previousUrl = route('dormitorys.meters.index', ['code' => $dormitory->code, 'filter' => 'electric']);
            }
            // dd($previousUrl);
            return view('dormitory::meters.show', compact('previousUrl', 'dormitory', 'room', 'filter', 'datameter', 'id_meter'));
        }
    

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $datameter = DormitoryMeter::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
    
        $dataPrevious = DormitoryMeter::where('rooms_id', $datameter->rooms_id)
                                        ->where('status_delete', '!=', 'Disable')
                                        ->where('type', $datameter->type)
                                        ->where('id', '<', $datameter->id)
                                        ->orderBy('meter', 'desc')
                                        ->orderBy('created_at', 'desc')
                                        ->first();
        // dd($dataPrevious);
        $dataroom = DormitoryRoom::where('id', $datameter->rooms_id)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id', $dataroom->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
    
        $filter = request('filter');
        // dd($filter);
        return view('dormitory::meters.edit', compact('dormitory', 'dataroom', 'datameter', 'dataPrevious', 'filter'));
    }
    
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id) {
        $datameter = DormitoryMeter::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $room = DormitoryRoom::where('id', $datameter->rooms_id)->where('status_delete', '!=', 'Disable')->first();
        $filter = $request->filter; // Get the filter value from the request
        $data_month = DormitoryBillings_month::where('data_id',$datameter->id)->first();
        // dd($data_month);
    // dd($filter);
        // Calculate the total based on the filter type
        $previous_meter = $request->previous_meter;
        $meter = $request->meter;
        // dd($previous_meter);
        if ($meter <= $previous_meter) {
            return redirect()->back()->withErrors(['meter' => 'เลขมิเตอร์น้อยกว่ามิเตอร์ครั่งก่อน']);
        }
        if ($filter == 'electric') {
            if ($request->unit >= 0) {
                if ($request->unit <= 15) {
                    $Total = 2.3488 * $request->unit;
                } elseif ($request->unit <= 25) {
                    $Total = 2.9882 * $request->unit;
                } elseif ($request->unit <= 35) {
                    $Total = 3.2405 * $request->unit;
                } elseif ($request->unit <= 100) {
                    $Total = 3.6237 * $request->unit;
                } elseif ($request->unit <= 150) {
                    $Total = 3.7171 * $request->unit;
                } elseif ($request->unit <= 400) {
                    $Total = 4.2218 * $request->unit;
                } else {
                    $Total = 4.4217 * $request->unit;
                }
            }
        } elseif ($filter == 'water') {
            if ($request->unit >= 0) {
                if ($request->unit <= 10) {
                    $Total = 16.00 * $request->unit;
                } elseif ($request->unit <= 20) {
                    $Total = 19 * $request->unit;
                } elseif ($request->unit <= 30) {
                    $Total = 20 * $request->unit;
                } elseif ($request->unit <= 50) {
                    $Total = 21.50 * $request->unit;
                } elseif ($request->unit <= 80) {
                    $Total = 21.60 * $request->unit;
                } elseif ($request->unit <= 100) {
                    $Total = 21.65 * $request->unit;
                } elseif ($request->unit <= 300) {
                    $Total = 21.70 * $request->unit;
                } elseif ($request->unit <= 1000) {
                    $Total = 21.75 * $request->unit;
                } elseif ($request->unit <= 2000) {
                    $Total = 21.80 * $request->unit;
                } elseif ($request->unit <= 3000) {
                    $Total = 21.85 * $request->unit;
                } else {
                    $Total = 21.90 * $request->unit;
                }
            }
        }

        $Total = round($Total, 2);
        $datameter->meter = $request->meter;
        $datameter->unit = $request->unit;
        $datameter->Total = $Total;
        $datameter->payment_status = $request->payment_status;
        $datameter->save();

        DormitoryBillings_month::where('data_id',$datameter->id)->update(['payment_status' => $request->payment_status]);

        return redirect()->route('dormitorys.meters.show', ['id_room' => $room->id, 'filter' => $filter]);
    }
    
    public function edit_payment($id)
    {
        
        $datameter = DormitoryMeter::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        // dd($datameter);
        $dataroom = DormitoryRoom::where('id', $datameter->rooms_id)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id', $dataroom->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
    
        $filter = request('filter');
        // dd($filter);

        return view('dormitory::meters.edit_payment', compact('dormitory', 'dataroom', 'datameter', 'filter'));
    }

    public function update_payment(Request $request, $id) {
        $datameter = DormitoryMeter::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $room = DormitoryRoom::where('id', $datameter->rooms_id)->where('status_delete', '!=', 'Disable')->first();
        $filter = $request->filter; // Get the filter value from the request
        $data_month = DormitoryBillings_month::where('data_id',$datameter->id)->first();
        // dd($data_month);

        $datameter->payment_status = $request->payment_status;
        $datameter->save();

        DormitoryBillings_month::where('data_id',$datameter->id)->update(['payment_status' => $request->payment_status]);
        return redirect()->route('dormitorys.meters.show', ['id_room' => $room->id, 'filter' => $filter]);
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
