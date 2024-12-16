<?php

namespace Modules\ControlSystem\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Dormitory\Entities\Dormitory;
use Modules\ControlSystem\Entities\ModelControlSystem;
use Modules\ControlSystem\Entities\Modelset_time;
use Modules\Dormitory\Entities\DormitoryRoom;

class ControlSystemController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index($id)
{
    session(['ControlSystem' => url()->current()]);
    $previousUrl = session('Control');

    $room = DormitoryRoom::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
    $dormitory = Dormitory ::where('id', $room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();

    if (!$room) {
        abort(404, 'Room not found');
    }

    $data = ModelControlSystem::where('room_id', $room->id)->where('status_delete', '!=', 'Disable')->get();
    $deviceIds = $data->pluck('id')->toArray();
    $setTimes = Modelset_time::whereIn('id', $deviceIds)->get()->keyBy('id');
    $data = $data->map(function ($item) use ($setTimes) {
        $item->set_time = $setTimes->get($item->id);
        return $item;
    });

    return view('controlsystem::index', compact('previousUrl','data','room','dormitory'));
}


    public function set_time($id)
    {
        $previousUrl = session('ControlSystem');

        $device = ModelControlSystem::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        if (!$device) {
            return redirect()->route('controlsystem.index')->withErrors(['error' => 'Device not found or disabled']);
        }
        $setTime = Modelset_time::where('device_key', $device->id)->where('status_delete', '!=', 'Disable')->orderBy('time', 'asc')->get();

        return view('controlsystem::set_time', compact('previousUrl','device', 'setTime'));
    }

    public function create($id){
        $previousUrl = session('ControlSystem');
        // dd('cdsdfd');
        return view('controlsystem::create', compact('previousUrl'));
    }

    public function create_time($id){
        $device = ModelControlSystem::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        return view('controlsystem::create_time', compact("device"));
    }

    public function insert(Request $request){
        $previousUrl = session('ControlSystem');

        $request->validate([
            'name' => 'required|max:50',
            'device_id' => 'required',
            'port' => 'required',
            'status' => 'required',
        ], [
            'name.required' => 'กรุณาป้อนชื่อกลุ่ม',
            'name.max' => 'ชื่อกลุ่มไม่ควรเกิน 100 ตัวอักษร',
            'device_id.required' => 'กรุณาป้อนหมายเหตุ',
            'port.required' => 'กรุณาเลือกสถานะ',
            'status.required' => 'กรุณาเลือกสถานะ',
        ]);

        $data = new ModelControlSystem;
        $data->name = $request->name;
        $data->device_id = $request->device_id;
        $data->port = $request->port;
        $data->status = $request->status;
        $data->set_times = $request->set_times;
        $data->save();

        return redirect($previousUrl);
    }

    public function insert_time(Request $request, $id) {
        
        $existingTime = DB::table('set_time')->where('status_delete', '!=', 'Disable')
            ->where('device_key', $id)
            ->where('time', $request->time)
            ->exists();
    
        if ($existingTime) {
            return back()->withErrors(['time' => 'เวลาที่เลือกมีอยู่แล้ว กรุณาเลือกเวลาใหม่']);
        }

        $loop = empty($request->loop) ? "0" : implode(',', $request->loop);
        if ($loop == "1,2,3,4,5,6,7") {
            $loop = "8";
        }

        $controlsystem = ModelControlSystem::where('id', $id)->first();

        $data = new Modelset_time;
        $data->device_key = $controlsystem->id;
        $data->time = $request->time;
        $data->on_off = $request->on_off;
        $data->set_times = $request->set_times;
        $data->loop = $loop;
        $data->save();

        return redirect()->route('device.set_time', ['id' => $id]);
    }

    public function store(Request $request){}

    public function show($id){
        return view('controlsystem::show');
    }

    public function edit($id){
        return view('controlsystem::edit');
    }

    public function update(Request $request, $id){
        $previousUrl = session('ControlSystem');

        $request->validate([
            'device_id' => 'required',
            'name' => 'required|max:50',
            'port' => 'required',
            'status' => 'required',
        ], [
            'device_id.required' => 'กรุณาป้อนชื่อกลุ่ม',
            'name.required' => 'กรุณาป้อนชื่อกลุ่ม',
            'name.max' => 'ชื่อกลุ่มไม่ควรเกิน 100 ตัวอักษร',
            'port.required' => 'กรุณาเลือกสถานะ',
            'status.required' => 'กรุณาเลือกสถานะ',
        ]);

        $data = ModelControlSystem::where('id', $id)->first();
        $data->name = $request->name;
        $data->device_id = $request->device_id;
        $data->port = $request->port;
        $data->status = $request->status;
        $data->set_times = $request->set_times;
        $data->save();

        return redirect($previousUrl);
    }

    public function update_time(Request $request, $id) {
        $existingTime = DB::table('set_time')->where('status_delete', '!=', 'Disable')
            ->where('device_key', $request->device_key)
            ->where('time', $request->time)
            ->where('id', '!=', $id)
            ->exists();
    
        if ($existingTime) {
            return back()->withErrors(['time' => 'เวลาที่เลือกมีอยู่แล้ว กรุณาเลือกเวลาใหม่']);
        }

        $loop = empty($request->loop) ? "0" : implode(',', $request->loop);
        if ($loop == "1,2,3,4,5,6,7") {
            $loop = "8";
        }

        $data = Modelset_time::where('id', $id)->first();
        $data->time = $request->time;
        $data->on_off = $request->on_off;
        $data->set_times = $request->set_times;
        $data->loop = $loop;
        $data->save();

        $setTime = Modelset_time::where('id', $id)->first();
    
        return redirect()->route('device.set_time', ['id' => $setTime->device_key]);
    }

    public function destroy($id){}

    public function settings($id)
    {
        $previousUrl = session('ControlSystem');

        $device = ModelControlSystem::where('id', $id)->where('status_delete', '!=', 'Disable')->first();

        if (!$device) {
            return redirect()->route('controlsystem.index')->withErrors(['error' => 'Device not found or disabled']);
        }

        return view('controlsystem::settings', compact('device','previousUrl'));
    }

    public function settings_time(Request $request, $id)
    {
        $setTime = Modelset_time::where('id', $id)->where('status_delete', '!=', 'Disable')->first();

        if (!$setTime) {
            return redirect()->route('controlsystem.index')->withErrors(['error' => 'SetTime record not found or disabled']);
        }

        return view('controlsystem::settings_time', compact('setTime'));
    }

    function delete($id){
        $previousUrl = session('ControlSystem');

        $data = ModelControlSystem::where('id', $id)->first();
        if ($data) {
            $data->status_delete = 'Disable';
            $data->save();
        }
        return redirect($previousUrl);
    }

    function delete_time($id){
        $data = Modelset_time::where('id', $id)->first();
        if ($data) {
            $data->status_delete = 'Disable';
            $data->save();
        }
        return back();
    }

    public function massDelete(Request $request){
        $ids = $request->input('ids');
        if (!empty($ids)) {
            foreach($ids as $id){
                $data = ModelControlSystem::where('id', $id)->first();
                if ($data) {
                    $data->status_delete = 'Disable';
                    $data->save();
                }
            }
        }
        return back()->with('success', 'ลบกลุ่มผู้ใช้ที่เลือกเรียบร้อยแล้ว');
    }

    public function massDelete_time(Request $request){
        $ids = $request->input('ids');
        if (!empty($ids)) {
            foreach($ids as $id){
                $data = Modelset_time::where('id', $id)->first();
                if ($data) {
                    $data->status_delete = 'Disable';
                    $data->save();
                }
            }
        }
        return back()->with('success', 'ลบกลุ่มผู้ใช้ที่เลือกเรียบร้อยแล้ว');
    }

    function change_set_time($id){
        $change = DB::table('controlsystems')->where('id', $id)->first();
        $data = [
            'set_times' => !$change->set_times
        ];
        DB::table('controlsystems')->where('id', $id)->update($data);
        return back();
    }

    public function device_status_update()
    {
        $devices = ModelControlSystem::where('status_delete', '!=', 'Disable')->get();
        return response()->json($devices);
    }

    public function set_time_status_update()
    {
        $setTimes = Modelset_time::where('status_delete', '!=', 'Disable')->get();
        return response()->json($setTimes);
    }
}
