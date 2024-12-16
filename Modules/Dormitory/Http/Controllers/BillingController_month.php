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

use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController_month extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    
    public function index($id) {
        // Retrieve the relevant data
        $data = DormitoryBillings_month::where('billings_id', $id)
            ->where('status_delete', '!=', 'Disable')
            ->where('status_use', 'yes')
            ->get();
    
        $data_billings = DormitoryBillings::where('id', $id)->first();
        $data_room = DormitoryRoom::where('id', $data_billings->room_id)
            ->where('status_delete', '!=', 'Disable')
            ->firstOrFail();
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)
            ->where('status_delete', '!=', 'Disable')
            ->firstOrFail();
    
        $data_meter = [];
        $data_month = [];
    
        foreach ($data as $index => $item) {
            if ($item->data_type == 'meter') {
                $meter = DormitoryMeter::where('id', $item->data_id)
                    ->where('status_delete', '!=', 'Disable')
                    ->first();
                if ($meter) {
                    $data_meter[$index] = $meter;
                }
            } elseif ($item->data_type == 'month') {
                $month = DormitoryMonthly_rent::where('id', $item->data_id)
                    ->where('status_delete', '!=', 'Disable')
                    ->first();
                if ($month) {
                    $data_month[$index] = $month;
                }
            }
        }
    
        $previousUrl = session('billings');
        session(['month' => url()->current()]);
    
        return view('dormitory::billings_month.index', compact('data_month', 'data_meter',
        'previousUrl', 'dormitory', 'data', 'data_room', 'data_billings'));
    }
    
    public function add($id) {
        // dd($id);
        session(['add' => url()->current()]);
        $previousUrls = session('month');
        $data = DormitoryBillings_month::where('billings_id', $id)->where('status_delete', '!=', 'Disable')->where('status_use','!=','yes')->get();   
        $data_billings =  DormitoryBillings::where('id',$id)->first();
        $data_room = DormitoryRoom::where('id', $data_billings->room_id)->where('status_delete', '!=', 'Disable')->firstOrFail();
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)->where('status_delete', '!=', 'Disable')->firstOrFail();

        return view('dormitory::billings_month.add', compact('previousUrls','dormitory', 'data','data_room','data_billings'));
    }

    public function before($id) {
        session(['before' => url()->current()]);
        $previousUrl = session('add');
    
        $data = DormitoryBillings_month::where('billings_id', $id)
                ->where('status_delete', '!=', 'Disable')
                ->get();   
        $data_billings = DormitoryBillings::where('id', $id)->first();
        $data_room = DormitoryRoom::where('id', $data_billings->room_id)
                ->where('status_delete', '!=', 'Disable')
                ->firstOrFail();
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)
                ->where('status_delete', '!=', 'Disable')
                ->firstOrFail();
        $data_meter = DormitoryMeter::where('rooms_id', $data_room->id)
                ->where('status_delete', '!=', 'Disable')
                ->where('payment_status', 'Unpaid')
                ->get(['id', 'type', 'Total','created_at','payment_status']);  // Include units and price in the data
        $data_month = DormitoryMonthly_rent::where('room_id', $data_room->id)
        ->where('status_delete', '!=', 'Disable')
        ->where('payment_status', 'Unpaid')
        ->get(['id', 'monthly_rent','created_at','payment_status']);
        $data_Lease = DormitoryLease::where('rooms_id', $data_room->id)
        ->where('status_delete', '!=', 'Disable')
        ->get(['id', 'deposit', 'created_at', 'payment_status']);
    
        $data_other = DormitoryBillings_month::where('billings_id', $id)
        ->where('status_delete', '!=', 'Disable')->where('data_type','other')
        ->first(); 

        return view('dormitory::billings_month.before', compact('data_Lease','data_month','data_meter', 'previousUrl', 'dormitory', 'data', 'data_room', 'data_billings'));
    }
    
    

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */

    public function insert_before(Request $request, $id) {
        $data_billings = DormitoryBillings::findOrFail($id);
        $data_room = DormitoryRoom::where('id', $data_billings->room_id)
            ->where('status_delete', '!=', 'Disable')
            ->firstOrFail();
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)
            ->where('status_delete', '!=', 'Disable')
            ->firstOrFail();
    
        $request->validate([
            'data_id.*' => 'required',
            'amount.*' => 'required',
        ], [
            'data_id.*.required' => 'กรุณาใส่รหัส',
            'amount.*.required' => 'กรุณาใส่จำนวนเงิน',
        ]);
        // dd($request->data_type);
        foreach ($request->data_id as $index => $data_id) {
            $data = new DormitoryBillings_month();
            $data->billings_id = $id;
            $data->data_id = $data_id;
            $data->data_type = $request->data_type[$index];  // Fix this line
            $data->amount = $request->amount[$index];
            $data->payment_status = $request->payment_status[$index];
            $data->status_use = 'no';
            $data->save();
        }

        return redirect()->route('dormitorys.billings_month.add', ['id' => $id]);
    }
    
    public function insert(Request $request, $id){
        // Retrieve the specific billing record
        $data_billings = DormitoryBillings::where('id', $id)->first();
    
        // Retrieve the related room record
        $data_room = DormitoryRoom::where('id', $data_billings->room_id)
                                    ->where('status_delete', '!=', 'Disable')
                                    ->firstOrFail();
    
        // Retrieve the related dormitory record
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)
                              ->where('status_delete', '!=', 'Disable')
                              ->firstOrFail();
        // Update all related records to set status_use to 'yes'
        DormitoryBillings_month::where('billings_id', $id)->update(['status_use' => 'yes']);
    
        // Redirect to the index page
        return redirect()->route('dormitorys.billings_month.index', ['id' => $id]);
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
        
        return view('dormitory::billings.show');
    }
    

    public function generatePDF($id)
    {
        $data = DormitoryBillings_month::where('billings_id', $id)
            ->where('status_delete', '!=', 'Disable')
            ->where('payment_status','Unpaid')
            ->where('status_use', 'yes')
            ->get();
        $data_billings = DormitoryBillings::where('id', $id)->first();
        $data_room = DormitoryRoom::where('id', $data_billings->room_id)
            ->where('status_delete', '!=', 'Disable')
            ->firstOrFail();
        $data_user = DormitoryUser::where('room_id', $data_room->id)
            ->where('status_delete', '!=', 'Disable')
            ->firstOrFail();
        $dormitory = Dormitory::where('id', $data_room->dormitorys_id)
            ->where('status_delete', '!=', 'Disable')
            ->firstOrFail();
            
        $pdf = App::make('dompdf.wrapper');
        $html = view('dormitory::billings_month.pdf',
        compact('data','data_user','data_billings','data_room','dormitory'))->render();
        $pdf->loadHTML($html);
    
        return $pdf->stream();
    }
    
    


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

        $data = DormitoryBillings_month::where('id', $id)
                ->where('status_delete', '!=', 'Disable')
                ->first(); 
        // dd($data);
        $data_Billings = DormitoryBillings::where('id', $data->billings_id)->first();
        $dataroom = DormitoryRoom::where('id', $data_Billings->room_id)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id', $dataroom->dormitorys_id)->where('status_delete', '!=', 'Disable')->firstOrFail();
    
        return view('dormitory::billings_month.edit', compact('data', 'dataroom','dormitory'));
    }
    

    

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id){
        // dd($id);
        $data = DormitoryBillings_month::where('id', $id)
                ->where('status_delete', '!=', 'Disable')
                ->first(); 
        
        $data_Billings = DormitoryBillings::where('id',$data->billings_id)->first();
        $dataroom = DormitoryRoom::where('id',$data_Billings->room_id)->where('status_delete', '!=', 'Disable')->first();
        // dd($dataroom);
        $dormitory = Dormitory::where('id',$dataroom->dormitorys_id)->first();

        $request->validate([
            'payment_status' => 'required',
        ], [

            'payment_status.required' => 'กรุณาเลือกสถานะ',
        ]);

        $data = DormitoryBillings_month::where('id',$data->id)->first();
        $data->payment_status = $request->payment_status;
        $data->save();

        return redirect()->route('dormitorys.billings_month.index', ['id' => $data_Billings->id]);
    }

    function delete($id){
        // dd($id);        
        $data = DormitoryBillings_month::where('id',$id)->where('status_delete', '!=', 'Disable')->first();
        $data_Billings = DormitoryBillings::where('id',$data->billings_id)->first();

        // dd($data);
        if ($data) {
            $data->status_delete = 'Disable';
            $data->save();
            } 
            return redirect()->route('dormitorys.billings_month.index', ['id' => $data_Billings->id]);
    }

    public function massDelete(Request $request){

        $ids = $request->input('ids');
        // dd($ids);
        if (!empty($ids)) {
        foreach($ids as $itm){
            $data = DormitoryBillings_month::where('id',$itm)->first();
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
