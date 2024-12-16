<?php

namespace Modules\Dormitory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Dormitory\Entities\Dormitory;
use Modules\Dormitory\Entities\DormitoryUser;
use Modules\Dormitory\Entities\DormitoryRoom;
use Modules\Dormitory\Entities\DormitoryRoom_User;
use Modules\User\Entities\Modules_User_review;
use Modules\Dormitory\Entities\Dormitory_Check_Out;
use Modules\User\Entities\Modules_User_Lavel;

use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($code, Request $request) {
        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();
        $search = $request->input('search');
    
        // ดึงข้อมูลห้องทั้งหมดในหอพัก
        $rooms = DormitoryRoom::where('dormitorys_id', $dormitory->id)
            ->where('status_delete', '!=', 'Disable')
            ->orderby('name', 'asc')
            ->get(['id', 'name']);
    
        // Query ผู้ใช้งานในหอพักนั้น
        $query = DormitoryUser::where('dormitorys_id', $dormitory->id)
    ->where('status_delete', '!=', 'Disable')
    ->where('staying', 'live')
    // ->groupBy('users_id')
    // ->select('users_id')
    ;    
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('dormitory_users.username', 'like', '%' . $search . '%')
                    ->orWhere('dormitory_users.name', 'like', '%' . $search . '%')
                    ->orWhereHas('rooms', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }
    
        $data = $query->paginate(10);
        // $data = $query->get();
    
        // dd($data );
        // เก็บค่า URL ของหน้านี้ใน session
        session(['ooo' => url()->current()]);
    
    
        return view('dormitory::users.index', compact('dormitory', 'data', 'rooms'));
    }
    



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($code){
        $dormitory = Dormitory::where('code',$code)->where('status_delete', '!=', 'Disable')->first();
        $rooms = DormitoryRoom::where('dormitorys_id', $dormitory->id)->where('status_delete', '!=', 'Disable')->orderby('name','asc')->get(['id', 'name']);
        return view('dormitory::users.create',compact('dormitory','rooms'));
    }
    

    public function insert(Request $request, $code)
    {
        $dormitory = Dormitory::where('code', $code)->first();
    
        $request->validate([
            'username' => 'required',
            'room_id' => 'required',
        ], [
            'username.required' => 'กรุณาป้อนชื่อผู้ใช้',
            'room_id.required' => 'กรุณาเลือกห้อง',
        ]);



        // $existingDormitoryUser = DormitoryUser::where('dormitorys_id', $request->dormitorys_id)->where('username', $request->username)->where('status_delete', '!=', 'Disable')->where('staying', '!=', 'out')->first();
    
        // if ($existingDormitoryUser) {
        //     if ($existingDormitoryUser->staying == 'yes') {
        //         $data = DormitoryUser::where('dormitorys_id', $request->dormitorys_id)->where('username', $request->username)->where('status_delete', '!=', 'Disable')->first();
        //             $data->staying = 'no';
        //             $data->save();

        //             $data_room = DormitoryRoom::where('id', $request->room_id)->where('status_delete', '!=', 'Disable')->first();
        //             // dd($data_room);
        //             $data_room->status = 'Active';
        //             $data_room->save();

        //             return redirect()->route('dormitorys.users.index', ['code' => $code]);
        //         } else {
        //         return redirect()->back()->withErrors(['username' => 'ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว']);
        //     }
        // }
            $data_user = User::where('status_delete', '!=', 'Disable')->find($request->user_id);
    
    
        $insert = new DormitoryUser;
        $insert->users_id = $request->user_id;
        $insert->room_id = $request->room_id;
        $insert->dormitorys_id = $dormitory->id;
        $insert->save();

        $data = new DormitoryUser_Room;
        $data->user_id = $insert->id;
        $data->room_id = $request->room_id;
        $data->dormitorys_id = $dormitory->id;
        $data->save();

        $item = new DormitoryRoom_User;
        $item->room_id = $request->room_id;
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
    
        return redirect()->route('dormitorys.users.index', ['code' => $code]);
    }

    public function fetchUser(Request $request){
        $username = $request->username;
        $user = User::where('username', $username)->where('status_delete', '!=', 'Disable')->first();
        // dd($request->username);
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
    public function store(Request $request){
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id){
        // dd($id);
        return view('dormitory::users.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id){
        // dd($id);
        $room_id = request('room_id');
        $user_id = request('user_id');
        // dd($user_id);
        $data = DormitoryUser::where('users_id',$id)->where('status_delete', '!=', 'Disable')->first();
        // dd($data);
        $dormitory = Dormitory::where('id',$data->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
        $rooms = DormitoryRoom::where('dormitorys_id', $dormitory->id)->where('status_delete', '!=', 'Disable')->orderby('name','asc')->get(['id', 'name']);
        $user = User::where('id',$data->users_id)->where('status_delete', '!=', 'Disable')->first();

        return view('dormitory::users.edit',compact('dormitory','data','rooms','user','room_id','user_id'));
    }
    

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id){

        // dd($request->room_id_new);
        $data_d = DormitoryUser::where('id',$id)->first();
        // dd($request->user_id);
        $dormitory = Dormitory::where('id',$data_d->dormitorys_id)->first();
        
        $request->validate([
            'room_id'=>'required',
        ],
        [
            'room_id.required'=>'กรุณาเลือกห้อง',
        ]);


        $data = DormitoryUser::where('id',$request->user_id)->first();
        $data->users_id = $data_d->users_id;
        $data->room_id = $request->room_id_new;
        $data->dormitorys_id = $dormitory->id;
        $data->save();

        $user_new = DormitoryUser::where('room_id',$request->room_id_new)->where('status_delete', '!=', 'Disable')->where('staying','live')->get();
        $total_user_new = $user_new->count();

        $rooms_new = DormitoryRoom::where('dormitorys_id', $dormitory->id)->where('id',$request->room_id_new)->where('status_delete', '!=', 'Disable')->first();
        $rooms_new->resident = $total_user_new;
        $rooms_new->status = 'Active';
        $rooms_new->save();
        
        // dd($rooms);
        $user = DormitoryUser::where('room_id',$request->room_id)->where('status_delete', '!=', 'Disable')->where('staying','live')->get();
        $total_user = $user->count();

        $rooms = DormitoryRoom::where('dormitorys_id', $dormitory->id)->where('id',$request->room_id)->where('status_delete', '!=', 'Disable')->first();
        $rooms->resident = $total_user;
        if($total_user == 0){
        $rooms->status = 'Free';
        }
        $rooms->save();


        $data_user = User::where('id',$data_d->users_id)->first();
        $user_lavels = Modules_User_Lavel::where('user_id', $data_user->id)->where('group_id', "4")->first();

        if (!$user_lavels) {
            $data_lavel = new Modules_User_Lavel;
            $data_lavel->user_id = $data_user->id;
            $data_lavel->group_id = '4';
            $data_lavel->save();
        }
        elseif($user_lavels->status_delete == "Disable"){
            $user_lavels->status_delete = 'Enable';
            $user_lavels->save();
            }

        return redirect()->route('dormitorys.users.index', ['code' => $dormitory->code]);
    }

    
    public function exit(Request $request, $id){
        // dd($request->room_id);
        $data_d = DormitoryUser::where('id',$id)->first();

        // dd($data_d);
        $dormitory = Dormitory::where('id',$data_d->dormitorys_id)->first();

        $data_user = DormitoryUser::where('id',$id)->first();
        $data_user->staying = 'waiting_out' ;
        $data_user->save();

        $user_lavels = Modules_User_Lavel::where('user_id', $data_user->users_id)->where('group_id', "4")->first();
        $user_lavels->status_delete = "Disable" ;
        $user_lavels->save();

        $user_new = DormitoryUser::where('room_id',$data_user->room_id)->where('status_delete', '!=', 'Disable')->where('staying','live')->get();
        $total_user = $user_new->count();

        $data_room = DormitoryRoom::where('id',$data_d->room_id)->first();
        $data_room->resident = $total_user;
        $data_room->status = 'Free';
        $data_room->save();

        $insert = new Dormitory_Check_Out;
        $insert->users_id = $data_d->users_id;
        $insert->dormitorys_id = $data_d->dormitorys_id;
        $insert->room_id = $data_d->room_id;
        $insert->user_type = 'Waiting';
        $insert->save();

        return redirect()->route('dormitorys.users.index', ['code' => $dormitory->code]);
    }

    public function Checkout($code)
    {
        $dormitory = Dormitory::where('code', $code)->where('status_delete', '!=', 'Disable')->first();

        return view('dormitory::check_out.index',compact('dormitory'));
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
        $data = DormitoryUser::where('id',$id)->first();
        // dd($data);
        $dormitory = Dormitory::where('id',$data->dormitorys_id)->first();

        if ($data) {
            $data->status_delete = 'Disable';
            $data->save();
        }        
        return redirect()->route('dormitorys.users.index', ['code' => $dormitory->code]);
    }

    public function massDelete(Request $request){

        $ids = $request->input('ids');
        // dd($ids);
        if (!empty($ids)) {
        foreach($ids as $itm){
            $data = DormitoryUser::where('id',$itm)->first();
            if ($data) {
                $data->status_delete = 'Disable';
                $data->save();
                }            
            }
        }
        return back();
    }

}
