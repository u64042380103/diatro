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

class BillingController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    
    public function index($code) {
        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->firstOrFail();
        $data = DormitoryBillings::where('status_delete', '!=', 'Disable')->get();
        $billingAmounts = [];
        // foreach($data as $data_bill){
            foreach ($data as $billing) {
                $billingData = DormitoryBillings_month::where('billings_id', $billing->id)
                    ->where('payment_status', 'Unpaid')
                    ->where('status_delete', '!=', 'Disable')
                    ->get();
                
                $amount = $billingData->sum('amount');
                $billingAmounts[$billing->id] = $amount;
            }
    // }        
    $rooms = DormitoryRoom::where('dormitorys_id', $dormitory->id)->where('status_delete', '!=', 'Disable')->orderby('name','asc')->get(['id', 'name']);

        // dd($amount);
        session(['billings' => url()->current()]);

        return view('dormitory::billings.index', compact('dormitory','rooms','data','billingAmounts'));
    }
    
    
    

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */

    public function create($id)
    {        

        $data_room = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id',$data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();

        return view('dormitory::billings.create',compact('dormitory','data_room'));
    }
    // public function create(Request $request, $code){


    //     return redirect()->route('dormitorys.billings.create', ['code' => $code]);
    // }

    public function insert(Request $request, $id){
    // dd($request->room_id);
        $dormitory = Dormitory::where('code',$id)->first();

        $data = new DormitoryBillings();
        $data->room_id = $request->room_id;
        $data->save();

        return redirect()->route('dormitorys.billings.index', ['code' => $dormitory->code]);
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
    public function show($id) {
        // Retrieve the dormitory room by ID and ensure it's not disabled

        $data_Billings = DormitoryBillings::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        // dd($data_Billings);
        $dataroom = DormitoryRoom::where('id', $data_Billings->room_id)
        ->where('status_delete', '!=', 'Disable')
        ->firstOrFail();
    
        $dormitory = Dormitory::findOrFail($dataroom->dormitorys_id);

        $billingAmounts = [];
        
        foreach ($data_Billings as $billing) {
            $billingData = DormitoryBillings_month::where('billings_id', $data_Billings->id)
                ->where('payment_status', 'Unpaid')
                ->where('status_delete', '!=', 'Disable')
                ->get();
            
            $amount = $billingData->sum('amount');
            $billingAmounts[$billing->id] = $amount;
        }
    // dd($billingData);
        // Load related data for the dormitory
        $dormitory->load([
            'rooms.metersWater', 
            'rooms.metersElectric',
        ]);
    
        // Retrieve the previous URL from session and store the current one
        $previousUrl = session('billings');
        session(['billings_show' => url()->current()]);
    
        // Return the view with the necessary data
        return view('dormitory::billings.show', compact('data_Billings','billingData','billingAmounts', 'previousUrl', 'dormitory', 'dataroom'));
    }
    
    
    function change_billings($id){
        $dataroom = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id', $dataroom->dormitorys_id)->first();
    
        $dormitory->load([
            'rooms.metersWater', 
            'rooms.metersElectric',
        ]);
        $previousUrl = session('billings');

        // Pass all meter data to the view
        $metersWater = DormitoryMeter::where('rooms_id', $dataroom->id)->where('type', 'water')->where('status_delete', '!=', 'Disable')->orderby('created_at','desc')->get();
        $metersElectric = DormitoryMeter::where('rooms_id', $dataroom->id)->where('type', 'electric')->where('status_delete', '!=', 'Disable')->orderby('created_at','desc')->get();
        $Monthly_rent = DormitoryMonthly_rent::where('room_id', $dataroom->id)->where('status_delete', '!=', 'Disable')->orderby('created_at','desc')->get();
        
        $Total = 0;

        return view('dormitory::billings.show', compact( 'Total','previousUrl','dormitory', 'dataroom', 'metersWater', 'metersElectric','Monthly_rent'));
    }
    

    public function settings(Request $request, $id)
    {
    // dd($id);
    $dataroom = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
    // dd($dataroom);
    $dormitory = Dormitory::where('id', $dataroom->dormitorys_id)->first();
    $ids = $request->input('ids');

    if (empty($ids)) {
        return redirect()->route('dormitorys.billings.show', $id)->withErrors(['error' => 'ไม่พบข้อมูลที่เลือก']);
    }

        $selectedWaterMeters = DormitoryMeter::whereIn('id', $ids)->where('type', 'water')->get();
        $selectedElectricMeters = DormitoryMeter::whereIn('id', $ids)->where('type', 'electric')->get();
        $Lease = DormitoryMonthly_rent::whereIn('id', $ids)->get();


    $totalWater = 0;
    foreach ($selectedWaterMeters as $water) {
        $useunitWater = $water->unit ?? 0;

        if ($useunitWater <= 10) {
            $unitWater = 16.00;
        } elseif ($useunitWater <= 20) {
            $unitWater = 19;
        } elseif ($useunitWater <= 30) {
            $unitWater = 20;
        } elseif ($useunitWater <= 50) {
            $unitWater = 21.50;
        } elseif ($useunitWater <= 80) {
            $unitWater = 21.60;
        } elseif ($useunitWater <= 100) {
            $unitWater = 21.65;
        } elseif ($useunitWater <= 300) {
            $unitWater = 21.70;
        } elseif ($useunitWater <= 1000) {
            $unitWater = 21.75;
        } elseif ($useunitWater <= 2000) {
            $unitWater = 21.80;
        } elseif ($useunitWater <= 3000) {
            $unitWater = 21.85;
        } else {
            $unitWater = 21.90;
        }
        $totalWater += $useunitWater * $unitWater;
    }
    $totalElectric = 0;
    foreach ($selectedElectricMeters as $electric) {
        $useunitElectric = $electric->unit ?? 0;

        if ($useunitElectric <= 15) {
            $unitElectric = 2.3488;
        } elseif ($useunitElectric <= 25) {
            $unitElectric = 2.9882;
        } elseif ($useunitElectric <= 35) {
            $unitElectric = 3.2405;
        } elseif ($useunitElectric <= 100) {
            $unitElectric = 3.6237;
        } elseif ($useunitElectric <= 150) {
            $unitElectric = 3.7171;
        } elseif ($useunitElectric <= 400) {
            $unitElectric = 4.2218;
        } else {
            $unitElectric = 4.4217;
        }
        $totalElectric += $useunitElectric * $unitElectric;
    }
    // $totalMonthly_rent = 0;
    // foreach ($Lease as $Monthly) {
    //     $item=$Monthly->monthly_rent;
    //     $totalMonthly_rent += $item;
    // }
    //     $Total = $totalWater+$totalElectric+$totalMonthly_rent;
    // dd($totalElectric);

    return view('dormitory::billings.settings', compact('Lease','selectedWaterMeters', 'selectedElectricMeters', 'dormitory', 'dataroom'));
    }
    
    public function settings_update(Request $request, $id)
    {
    // dd($id);
    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return redirect()->route('dormitorys.billings.show', $id)->withErrors(['error' => 'ไม่พบข้อมูลที่เลือก']);
    }

    foreach ($ids as $idd) {
        $paymentStatus = $request->input('payment_status_' . $idd);
        
        // Determine whether the id belongs to water, electric, or lease
        $water = DormitoryMeter::find($idd);
        $electric = DormitoryMeter::find($idd);
        $lease = DormitoryMonthly_rent::find($idd);

        if ($water) {
            $water->payment_status = $paymentStatus;
            $water->save();
        } elseif ($electric) {
            $electric->payment_status = $paymentStatus;
            $electric->save();
        } elseif ($lease) {
            $lease->payment_status = $paymentStatus;
            $lease->save();
        }
    }

    return redirect()->route('dormitorys.billings.show', $id)->with('success', 'Billing updated successfully');
    } 

    


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // dd($id);
        $dataroom = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();

        $data = DormitoryBillings::where('room_id', $dataroom->id)->first();
    
        // Calculate total meter charges for the room
        $dormitory = Dormitory::where('id', $dataroom->dormitorys_id)->where('status_delete', '!=', 'Disable')->firstOrFail();
    
        $filter = request('filter', 'electric'); // Default to 'electric' if no filter is provided
    
        $dormitory->load([
            'rooms.metersWater' => function($query) {
                $query->where('status_delete', '!=', 'Disable')->where('payment_status', 'Unpaid');
            },
            'rooms.metersElectric' => function($query) {
                $query->where('status_delete', '!=', 'Disable')->where('payment_status', 'Unpaid');
            },
            'rooms.latestLease',
            'rooms.billings' // Ensure you load the billings relation
        ]);
    
        return view('dormitory::billings.edit', compact('data', 'dataroom','dormitory'));
    }
    

    

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id){
        // dd($id);
        $dataroom = DormitoryRoom::where('id',$id)->where('status_delete', '!=', 'Disable')->first();
        $data = DormitoryBillings::where('id',$dataroom->id)->first();
        // dd($dataroom);
        $dormitory = Dormitory::where('id',$dataroom->dormitorys_id)->first();

        $request->validate([
            'monthly_rent' => 'required|numeric',
            // 'amount' => 'required|numeric',
            'payment_status' => 'required',
        ], [
            'monthly_rent.required' => 'กรุณาใส่ราคาค่าเช่า',
            // 'amount.required' => 'กรุณาใส่จำนวนเงินที่จ่ายแล้ว',
            'payment_status.required' => 'กรุณาเลือกสถานะ',
        ]);

        // dd($request->amount);

        $data = DormitoryBillings::where('id',$dataroom->id)->first();
        $data->room_id = $dataroom->id;
        $data->save();

        return redirect()->route('dormitorys.billings.index', ['code' => $dormitory->code]);
    }

    function delete($id){
        // dd($id);        
        $data = DormitoryBillings::where('id',$id)->where('status_delete', '!=', 'Disable')->first();
        // dd($data);
        $datauser = DormitoryUser::where('id',$data->user_id)->first();
        $dataroom = DormitoryRoom::where('id',$datauser->room_id)->first();
        $dormitory = Dormitory::where('id',$dataroom->dormitorys_id)->first();

        if ($data) {
            $data->status_delete = 'Disable';
            $data->save();
            } 
        return redirect()->route('dormitorys.billings.index', ['code' => $dormitory->code]);
    }
    public function massDelete(Request $request)
    {
        $ids = $request->input('ids');
        
        if (!empty($ids)) {
            DormitoryBillings::whereIn('id', $ids)->update(['status_delete' => 'Disable']);
            foreach($ids as $itm){
                $data = DormitoryBillings_month::where('billings_id',$itm)->first();
                if ($data) {
                    $data->status_delete = 'Disable';
                    $data->save();
                    }            
                }
        }
        
        return back();
    }
    
    


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id){
        //
    }
}
