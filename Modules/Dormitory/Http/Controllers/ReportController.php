<?php

namespace Modules\Dormitory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dormitory\Entities\Dormitory;
use Modules\Dormitory\Entities\DormitoryUser;
use Modules\Dormitory\Entities\DormitoryRoom;
use Modules\Dormitory\Entities\DormitoryLease;
use Modules\Dormitory\Entities\Monthly_rent;
use Modules\Dormitory\Entities\DormitoryMeter;
use Modules\Dormitory\Entities\DormitoryMonthly_rent;

class ReportController extends Controller
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
    if (!$dormitory) {
        return redirect()->back()->with('error', 'Dormitory not found.');
    }

    $rooms = DormitoryRoom::where('dormitorys_id', $dormitory->id)->where('status_delete', '!=', 'Disable')->get();
    $monthlyRents = collect();
    $dormitory_meters = collect();

    foreach ($rooms as $room) {
        $rents = DormitoryMonthly_rent::where('room_id', $room->id)->where('status_delete', '!=', 'Disable')->where('payment_status','Paid')->orderby('created_at','asc')->get();
        $monthlyRents = $monthlyRents->merge($rents);
        $meter = DormitoryMeter::where('rooms_id', $room->id)->where('status_delete', '!=', 'Disable')->where('payment_status','Paid')->orderby('created_at','asc')->get();
        $dormitory_meters = $dormitory_meters->merge($meter);
    }

    $chartData = [];
    foreach ($monthlyRents as $rent) {
        $month = $rent->created_at->format('Y-m');
        if (!isset($chartData[$month])) {
            $chartData[$month] = [
                'Total' => 0,
                'monthly_rent' => 0,
            ];
        }
        $chartData[$month]['monthly_rent'] += $rent->monthly_rent;
    }

    foreach ($dormitory_meters as $meter) {
        $month = $meter->created_at->format('Y-m');
        if (!isset($chartData[$month])) {
            $chartData[$month] = [
                'Total' => 0,
                'monthly_rent' => 0,
            ];
        }
        $chartData[$month]['Total'] += $meter->Total;
    }

    return view('dormitory::reports.index', compact('dormitory', 'monthlyRents', 'dormitory_meters', 'chartData'));
    }



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('dormitory::create');
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
        return view('dormitory::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('dormitory::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
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
