<?php

namespace Modules\Dormitory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Modules\Dormitory\Entities\Dormitory;
use Modules\Dormitory\Entities\DormitoryRoom;
use Modules\Dormitory\Entities\DormitoryLease;
use Modules\Dormitory\Entities\DormitoryUser;
use Modules\Dormitory\Entities\DormitoryComment;
use Modules\Dormitory\Entities\Dormitory_Facilitate;

use Illuminate\Support\Str;


class DormitoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $id = auth()->user()->id;
        $user = DormitoryUser::where('users_id', $id)->where('status_delete', '!=', 'Disable')->first();
        
        if (in_array(auth()->user()->user_type, [2, 3])) {
            $lists = Dormitory::where('id', $user->dormitorys_id)
                                ->where('status_delete', '!=', 'Disable')
                                ->orderBy('id')
                                ->paginate(20);
        } else {
            $lists = Dormitory::where('status_delete', '!=', 'Disable')
                                ->orderBy('id')
                                ->paginate(20);
        }
        return view('dormitory::index', compact('lists'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(){
        return view('dormitory::create');
    }

    public function insert(Request $request){
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'status' => 'required',
            'img' => 'required', // ตรวจสอบว่ามีการอัปโหลดรูปภาพ
        ],
        [
            'code.required' => 'กรุณาป้อนรหัส',
            'name.required' => 'กรุณาป้อนชื่อหอพัก',
            'address.required' => 'กรุณาป้อนที่อยู่',
            'status.required' => 'กรุณาเลือกสถานะ',
            'img.required' => 'กรุณาอัปโหลดรูปภาพ',
        ]);
        
        $codeExists = DB::table('dormitorys')->where('code', $request->code)->exists();
        if ($codeExists) {
            return redirect()->back()->withErrors(['code' => 'รหัสนี้มีอยู่ในระบบแล้ว'])->withInput();
        }
        
        $data = new Dormitory;
        $data->code = $request->code;
        $data->name = $request->name;
        $data->address = $request->address;
        $data->web = $request->web;
        
        if ($request->hasFile('img')) {
            $images = [];
            foreach ($request->file('img') as $file) {
                $randomString = Str::random(4);
                $filename = time() . '-' . $randomString  . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/Dormitory_images'), $filename);
                $images[] = $filename;
            }
            $dormitory->img = json_encode($images);
        }
        
        $data->Rent_min = $request->Rent_min;
        $data->Rent_max = $request->Rent_max;
        $data->phone = $request->phone;
        $data->description = $request->description;
        $data->Nearby = $request->Nearby;
        $data->animal = $request->animal;
        $data->room = $request->room;
        $data->status = $request->status;
        $data->save();
    
        $lists = Dormitory::orderBy('id')->paginate(20);
        return view('dormitory::index', compact('lists'));
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
        $dormitory = Dormitory::where('code', $id)->where('status_delete', '!=', 'Disable')->first();
        // dd($dormitory);
        $data_room = DormitoryRoom::where('dormitorys_id', $dormitory->id)
                                    ->where('status_delete', '!=', 'Disable')
                                    ->orderby('name','asc')->get();
        $total_rooms = $data_room->count();
        $total_fan = $data_room->where('room_type', 'fan')->count();
        $total_air = $data_room->where('room_type', 'air')->count();
        $free_fan = $data_room->where('room_type', 'fan')->where('status', 'Free')->all();
        $fan = count($free_fan);
        $free_air = $data_room->where('room_type', 'air')->where('status', 'Free')->all();
        $air = count($free_air);
        $Lease = DormitoryLease::whereIn('rooms_id', $data_room->pluck('id'))->get();
        $Lease_count = $Lease->sum('monthly_rent');
        $Lease_net = DormitoryLease::whereIn('rooms_id', $data_room->pluck('id'))->where('payment_status','Paid')->get();
        $Lease_count_net  = $Lease_net->sum('monthly_rent');
        
        $data_room_fan = DormitoryRoom::where('dormitorys_id', $dormitory->id)
        ->where('status_delete', '!=', 'Disable')->where('room_type', 'fan')->orderby('name','asc')->get();
        $Lease_fan = DormitoryLease::whereIn('rooms_id', $data_room_fan->pluck('id'))->get();
        $Lease_count_fan = $Lease_fan->sum('monthly_rent');
        $Lease_fan_net = DormitoryLease::whereIn('rooms_id', $data_room_fan->pluck('id'))->where('payment_status','Paid')->get();
        $Lease_count_fan_net  = $Lease_fan_net->sum('monthly_rent');
        
        $data_room_air = DormitoryRoom::where('dormitorys_id', $dormitory->id)
        ->where('status_delete', '!=', 'Disable')->where('room_type', 'air')->orderby('name','asc')->get();
        $Lease_air = DormitoryLease::whereIn('rooms_id', $data_room_air->pluck('id'))->get();
        $Lease_count_air = $Lease_air->sum('monthly_rent');
        $Lease_air_net = DormitoryLease::whereIn('rooms_id', $data_room_air->pluck('id'))->where('payment_status','Paid')->get();
        $Lease_count_air_net  = $Lease_air_net->sum('monthly_rent');
        
        $residents_count = 0;
        foreach ($data_room as $room) {
            $room_residents_count = $room->resident;
            $residents_count += $room_residents_count;
        }

        $data_Facilitate = Dormitory_Facilitate::where('dormitory_id', $dormitory->id)
        ->where('status_delete', '!=', 'Disable')->get();

        $comments = DormitoryComment::where('dormitorys_id', $dormitory->id)
        ->where('status_delete', '!=', 'Disable')->get();
        // $data = Dormitory::where('code',$id)->where('status_delete', '!=', 'Disable')->get();
        // dd($data);
        session(['room' => url()->current()]);
        return view('dormitory::show', compact('Lease_count_net','dormitory', 'data_room', 'total_rooms', 
        'total_fan', 'total_air', 'free_fan', 'free_air', 'fan', 'air', 'residents_count', 'Lease_count',
        'Lease_count_fan','Lease_count_fan_net','Lease_count_air','Lease_count_air_net','comments','data_Facilitate'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($code){
        $dormitory = Dormitory::where('code',$code)->first();
        $data = Dormitory::where('code',$code)->get();
        return view('dormitory::edit',compact('dormitory','data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'code' => 'required|unique:dormitorys,code,' . $id,
        'address' => 'required',
        'status' => 'required',
        'img.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validate each image
    ], [
        'name.required' => 'กรุณาป้อนชื่อ',
        'code.required' => 'กรุณาป้อนรหัสผู้ใช้',
        'code.unique' => 'รหัสนี้มีอยู่ในระบบแล้ว',
        'address.required' => 'กรุณาป้อนที่อยู่',
        'status.required' => 'กรุณาเลือกสถานะ',
        'img.*.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
        'img.*.max' => 'ไฟล์รูปภาพต้องไม่เกิน 2MB'
    ]);
    $dormitory = Dormitory::find($id);
    $dormitory->code = $request->code;
    $dormitory->name = $request->name;
    $dormitory->address = $request->address;
    $dormitory->web = $request->web;
    $dormitory->Rent_min = $request->Rent_min;
    $dormitory->Rent_max = $request->Rent_max;
    $dormitory->phone = $request->phone;
    $dormitory->description = $request->description;
    $dormitory->Nearby = $request->Nearby;
    $dormitory->animal = $request->animal;
    $dormitory->room = $request->room;
    $dormitory->status = $request->status;

    if ($request->hasFile('img')) {
        $images = [];
        foreach ($request->file('img') as $file) {
            $randomString = Str::random(4);
            $filename = time() . '-' . $randomString  . '.' . $file->getClientOriginalExtension(); // Fixed file extension
            $file->move(public_path('storage/Dormitory_images'), $filename); // Changed to 'storage/Dormitory_images'
            $images[] = $filename;
        }
        $dormitory->img = json_encode($images);
    }
    
    // dd(public_path('Dormitory_images'), $filename);
    $dormitory->save();

    return redirect()->route('dormitorys.show', ['code' => $dormitory->code])->with('success', 'Dormitory updated successfully!');
}

    
    public function comment($id){
        $data = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id', $data->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
        return view('dormitory::report',compact('dormitory','data'));
    }
    
    public function insert_comment(Request $request, $id)
    {
        $data = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id', $data->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
        // dd($request->comment);
        $data = new DormitoryComment;
        $data->dormitorys_id = $dormitory->id;
        $data->comment = $request->comment;
        $data->star = $request->star;
        $data->recorder_id = auth()->user()->id; // Save the current user's ID
        $data->save();
    
        return redirect()->route('dormitorys.rooms.show', ['id' => $data->id]);
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id){
        //
    }

    public function delete($id){
        $hasRooms = DormitoryRoom::where('dormitorys_id', $id)->exists();
        $errors = [];
        if ($hasRooms) {
            $dormitory = Dormitory::find($id);
            $errors[] = "ไม่สามารถลบหอพัก '{$dormitory->name}' ได้เนื่องจากยังมีห้องอยู่ในหอพัก";
        } else {
            $data = Dormitory::where('id',$id)->first();
            if ($data) {
                $data->status_delete = 'Disable';
                $data->save();
                }         
            }
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
        return redirect()->route('dormitorys.index');
    }

    public function massDelete(Request $request){
        $ids = $request->input('ids');
        $errors = [];
        if (!empty($ids)) {
        foreach ($ids as $id) {
            // Check if there are rooms associated with the dormitory
            $hasRooms = DormitoryRoom::where('dormitorys_id', $id)->exists();
            if ($hasRooms) {
                $dormitory = Dormitory::find($id);
                $errors[] = "ไม่สามารถลบหอพัก '{$dormitory->name}' ได้เนื่องจากยังมีห้องอยู่ในหอพัก";
                continue;
                }
                $data = Dormitory::where('id',$id)->first();
                if ($data) {
                    $data->status_delete = 'Disable';
                    $data->save();
                    }
            }
        }
            if (!empty($errors)) {
                return back()->withErrors($errors);
            }
        return back();
}

}    
