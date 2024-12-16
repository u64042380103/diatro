<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Dormitory\Entities\Dormitory;

use Modules\Dormitory\Entities\DormitoryRoom;
use Modules\ControlSystem\Entities\Modelset_time;
use Modules\Dormitory\Entities\DormitoryUser;

use Modules\User\Entities\Modules_Users_Comment;
use Modules\User\Entities\Modules_User_review;
use Modules\User\Entities\Modules_User;
use Modules\Dormitory\Entities\DormitoryMonthly_rent;
use Modules\User\Entities\Modules_User_Lavel;

use App\Models\User;

class User_reviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id, Request $request)
    {
        // dd($id);
        session(['user' => url()->current()]);
        $previousUrll = session('ooo');
        
        $review = Modules_User_review::where('users_id', $id)
            ->where('status_delete', '!=', 'Disable')
            ->paginate(10);
        $users_review = Modules_User_review::where('users_id', $id)
            ->where('status_delete', '!=', 'Disable')
            ->first();
        $username = Modules_User::where('id', $id)
            ->where('status_delete', '!=', 'Disable')
            ->value('name');
        
        $user = Modules_User::where('id', $id)
            ->where('status_delete', '!=', 'Disable')->first();
        // dd($user);
        $comments = Modules_Users_Comment::where('users_id', $id)
            ->where('status_delete', '!=', 'Disable')->get();
        
        $dormitoryUser = $request->input('dormitory_user', false);
        $room_id = $request->input('room_id'); 
        
        $data_laravel = Modules_User_Lavel::where('user_id',$user->id)->where('status_delete', '!=', 'Disable')->get();
        // dd($data_laravel);
        if ($dormitoryUser) {
            $room = DormitoryRoom::where('id', $room_id)->where('status_delete', '!=', 'Disable')->first();
            $dormitory = Dormitory::where('id', $room->dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
            $data_month = DormitoryMonthly_rent::where('room_id', $room->id)
                    ->where('payment_status', 'Unpaid')
                    ->where('status_delete', '!=', 'Disable')
                    ->get();
            // dd($data_month);
            $total_unpaid = $data_month->sum('monthly_rent');
            // dd($total_unpaid);
            // dd($dormitory);
        } else {
            $room = null;
            $dormitory = null;
            $data_month = null;
            $total_unpaid = null;
        }

        $userRoles = Modules_User_Lavel::where('user_id', $id)
        ->where('status_delete', '!=', 'Disable')
        ->pluck('group_id')
        ->toArray();
        // dd($userRoles);
        $user_id = request('user');

return view('user::users_review.index', compact('data_laravel', 'userRoles', 'users_review', 'total_unpaid', 'review', 'id', 'username', 'user','user_id' ,'dormitoryUser', 'previousUrll', 'comments', 'dormitory', 'room', 'data_month'));
}
    
    

public function create($id, Request $request)
{
    $users = Modules_User::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
    $dormitoryUser = $request->input('dormitory_user', false);
    // dd($request->dormitory_id);
    if ($dormitoryUser) {
            $dormitory = Dormitory::where('id', $request->dormitory_id)->where('status_delete', '!=', 'Disable')->first();
            // dd($dormitory);
    } else {
        $dormitory = null;
    }

    return view('user::users_review.create', compact('users', 'id', 'dormitory', 'dormitoryUser'));
}


    public function insert(Request $request, $id)
    {
        // dd($request->dormitorys_name);
        $dormitorys_id = Dormitory::where('name', $request->dormitorys_name)->where('status_delete', '!=', 'Disable')->value('id');
        // dd($dormitorys_id);
        $request->validate([
            'star' => 'required',
        ], [
            'star.required' => 'กรุณาให้คะแนน',
        ]);

        $existingDormitory_id = Modules_User_review::where('users_id', $request->users_id)->where('dormitorys_id', $dormitorys_id)->where('status_delete', '!=', 'Disable')->first();
        
        if ($existingDormitory_id) {
            return redirect()->back()->withErrors(['dormitorys_name' => 'ชื่อหอพักนี้ถูกใช้ไปแล้ว']);
        }

        $data = new Modules_User_review;
        $data->users_id = $request->users_id;
        $data->dormitorys_id = $dormitorys_id;
        $data->star = $request->star;
        $data->save();

        return redirect()->route('users_review.index', ['id' => $id]);
    }

    public function store(Request $request)
    {
        //
    }

    public function change(Request $request) {
        $dormitorys_name = $request->dormitorys_name;
        $dormitory = Dormitory::where('name', $dormitorys_name)
                                ->where('status_delete', '!=', 'Disable')
                                ->first();
    
        if ($dormitory) {
            return response()->json([
                'success' => true,
                'dormitorys_id' => $dormitory->id,
                'dormitorys_name' => $dormitory->name,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลในระบบ',
            ]);
        }
    }

    public function show($id)
    {
        $user = User::where('name', 'id')->first();
        return view('user::show');
    }

    public function edit(Request $request, $id)
{

    $users_review = Modules_User_review::where('users_id', $id)
        ->where('status_delete', '!=', 'Disable')
        ->first();
    
    // Retrieve the previous URL from the session
    
    return view('user::users_review.edit', compact("users_review"));
}

    

    public function update(Request $request, $id)
    {
        $request->validate([
            'star' => 'required',
        ], [
            'star.required' => 'กรุณาให้คะแนน',
        ]);
        // dd($room);
        $data = Modules_User_review::where('id', $id)->where('status_delete', '!=', 'Disable')->first();
        $data->users_id = $request->users_id;
        $data->dormitorys_id = $request->dormitorys_id;
        $data->star = $request->star;
        $data->save();

        $room = DormitoryUser::where('users_id', $data->users_id)->where('status_delete', '!=', 'Disable')->first();



        return redirect()->route('users_review.index', ['id' => $request->users_id, 'dormitory_user' => true,'room_id' => $room->room_id]);
    }

    public function delete($id)
    {
        $user = Modules_User_review::find($id);
        if ($user) {
            $user->status_delete = 'Disable';
            $user->save();
            
        $laravel = Modules_User_Lavel::where('user_id', $id)->first();
        $laravel->status_delete = 'Disable';
        $laravel->save();

        $Duser = DormitoryUser::where('users_id', $id)->first();
        $Duser->status_delete = 'Disable';
        $Duser->save();
        }
        
        return redirect()->route('users_review.index', ['id' => $user->users_id]);
    }

    public function destroy($id)
    {
        //
    }

    public function massDelete(Request $request)
    {
        $ids = $request->input('ids');
        
        if (!empty($ids)) {
            try {
                DB::beginTransaction(); // Start transaction
                
                foreach ($ids as $id) {
                    $user = Modules_User_review::find($id);
                    if ($user) {
                        $user->status_delete = 'Disable';
                        $user->save();

                        $laravel = Modules_User_Lavel::where('user_id', $id)->first();
                        $laravel->status_delete = 'Disable';
                        $laravel->save();

                        $Duser = DormitoryUser::where('users_id', $id)->first();
                        $Duser->status_delete = 'Disable';
                        $Duser->save();
                    }
                }
                
                DB::commit(); // Commit transaction
                return redirect()->route('users_review.index', ['id' => $user->users_id])->with('success', 'ลบกลุ่มผู้ใช้ที่เลือกเรียบร้อยแล้ว');
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback transaction on error
                return redirect()->route('users_review.index', ['id' => $user->users_id])->withErrors(['error' => 'เกิดข้อผิดพลาดในการลบข้อมูล ยังมีข้อมูลที่ไม่ได้ลบ']);
            }
        } else {
            return redirect()->route('users_review.index', ['id' => $user->users_id])->withErrors(['error' => 'ไม่พบข้อมูลผู้ใช้ที่เลือก']);
        }
    }
}
