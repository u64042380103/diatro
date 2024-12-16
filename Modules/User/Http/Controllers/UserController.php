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
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Modules_Users_Comment;
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
    public function index()
    {
        $users = User::where('status_delete', '!=', 'Disable')->paginate(10);
        // Store the current URL in the session with the key 'ooo'
        $currentUser = Auth::user(); // ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่
        // dd($currentUser);
        session(['ooo' => url()->current()]);
        return view('user::index', compact('users', 'currentUser'));
    }
    
    public function change_img_pro(Request $request)
{
    $request->validate([
        'imgpro' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $user = auth()->user(); // ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่ในปัจจุบัน

    if ($request->hasFile('imgpro')) {
        // ลบรูปภาพเก่า ถ้ามี
        if ($user->imgpro && Storage::disk('public')->exists($user->imgpro)) {
            Storage::disk('public')->delete($user->imgpro);
        }

        // จัดเก็บรูปภาพใหม่
        $imgproPath = $request->file('imgpro')->store('profile_images', 'public');
        $user->imgpro = $imgproPath;
        $user->save();
    }

    return redirect()->back()->with('success', 'Profile image updated successfully.');
}

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('user::create');
    }
    
    public function comment_insert(Request $request, $id)
    {
        $data = new Modules_Users_Comment;
        $data->users_id = $id;
        $data->comment = $request->comment;
        $data->recorder_id = auth()->user()->id; // Save the current user's ID
        $data->save();
    
        return redirect()->route('users_review.index', ['id' => $id]);
    }
    /**
     * Insert a newly created user in the database.
     * @param Request $request
     * @return Renderable
     */
    public function insert(Request $request)
{
    // Validate inputs
    $request->validate([
        'name' => 'required|max:100',
        'username' => 'required',
        'email' => 'required|email',
        'imgpro' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'password' => 'required|max:50',
        'phone' => 'required|max:10',
        'roles' => 'required|array|min:1' // Ensure at least one role is selected
    ], [
        'name.required' => 'กรุณาป้อนชื่อ',
        'name.max' => 'ชื่อไม่ควรเกิน 100 ตัวอักษร',
        'username.required' => 'กรุณาป้อนชื่อผู้ใช้',
        'email.required' => 'กรุณาป้อนอีเมล',
        'email.email' => 'กรุณาใส่อีเมลให้ถูกต้อง',
        'imgpro.required' => 'กรุณาใส่รูปประจำตัว',
        'imgpro.image' => 'ไฟล์รูปประจำตัวควรเป็นรูปภาพ',
        'imgpro.mimes' => 'รูปประจำตัวควรเป็นไฟล์ประเภท jpeg, png, jpg, gif, svg',
        'imgpro.max' => 'ขนาดไฟล์รูปประจำตัวไม่ควรเกิน 2MB',
        'password.required' => 'กรุณาป้อนรหัสผ่าน',
        'phone.required' => 'กรุณาใส่เบอร์โทร',
        'phone.max' => 'เบอร์โทรไม่ควรเกิน 10 ตัวอักษร',
        'roles.required' => 'กรุณาเลือกอย่างน้อย 1 บทบาท'
    ]);

    // Save user data
    $imgproPath = $request->file('imgpro')->store('profile_images', 'public');
    $data = new User;
    $data->name = $request->name;
    $data->username = $request->username;
    $data->last_name = $request->last_name;
    $data->National_id = $request->National_id;
    $data->sex = $request->sex;
    $data->Date_birth = $request->Date_birth;
    $data->email = $request->email;
    $data->imgpro = $imgproPath;
    $data->password = bcrypt($request->password);
    $data->phone = $request->phone;
    $data->Emergency_name = $request->Emergency_name;
    $data->relationship = $request->relationship;
    $data->Emergency_phone = $request->Emergency_phone;

    $data->save();

    // Assign roles
    foreach ($request->roles as $role) {
        $data_lavel = new Modules_User_Lavel;
        $data_lavel->user_id = $data->id;
        $data_lavel->group_id = $role;
        $data_lavel->save();
    }

    return redirect('/users');
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
	    $user = User::where('name','id')->first();
	    // dd($user);
        // where('code', $code)->where('status_delete', '!=', 'Disable')->orderby('id','asc')->first();
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($name)
{
    $user = User::where('name',$name)->first();
    return view('user::edit', compact("user"));
}

public function change_img_user(Request $request, $id)
{
    
    $data_user = User::where('id', $id)->first();
    if ($request->hasFile('imguser')) {
        $imgproPath = $request->file('imguser')->store('profile_images', 'public');
        $data_user->imgpro = $imgproPath;
    }
    // dd($data_user->imgpro);
    $data_user->save();

    return redirect()->route('users_review.index', ['id' => $id]);
}

public function update(Request $request, $name)
{
    // Validate the request
    $request->validate([
        'name' => 'required|max:100',
        'username' => 'required',
        'email' => 'required',
        'password' => 'nullable',
    ], [
        'name.required' => 'กรุณาป้อนชื่อ',
        'name.max' => 'ชื่อไม่ควรเกิน 100 ตัวอักษร',
        'username.required' => 'ชื่อผู้ใช้',
        'email.required' => 'กรุณาป้อนอีเมล',
    ]);

    // Update the user
    $data_user = User::where('name', $name)->first();
    $data_user->username = $request->username;
    $data_user->name = $request->name;
    $data_user->email = $request->email;
    $data_user->last_name = $request->last_name;
    $data_user->National_id = $request->National_id;
    $data_user->sex = $request->sex;
    $data_user->Date_birth = $request->Date_birth;

    if ($request->hasFile('imgpro')) {
        $imgproPath = $request->file('imgpro')->store('profile_images', 'public');
        $data_user->imgpro = $imgproPath;
    }

    if ($request->password) {
        $data_user->password = bcrypt($request->password);
    }

    $data_user->phone = $request->phone;
    $data_user->Emergency_name = $request->Emergency_name;
    $data_user->relationship = $request->relationship;
    $data_user->Emergency_phone = $request->Emergency_phone;
    $data_user->save();

    // Update DormitoryUser
    $update = DormitoryUser::where('users_id', $data_user->id)->first();
    if ($update) {
        $update->username = $request->username;
        $update->name = $request->name;
        $update->phone = $request->phone;
        $update->save();
    }

    // Update Modules_User_Lavel
    $user_lavels = Modules_User_Lavel::where('user_id', $data_user->id)->get();
    $roles = $request->roles ?? [];

    foreach ($user_lavels as $lavel) {
        // If the role exists in request and the current status is disabled, enable it
        if (in_array($lavel->group_id, $roles) && $lavel->status_delete == 'Disable') {
            $lavel->status_delete = 'Enable';
            $lavel->save();
        }
        // If the role exists in request and is already enabled, skip the update (optimization)
        elseif (in_array($lavel->group_id, $roles) && $lavel->status_delete == 'Enable') {
            continue;
        }
        // If the role does not exist in request, disable it
        elseif (!in_array($lavel->group_id, $roles)) {
            $lavel->status_delete = 'Disable';
            $lavel->save();
        }
    }

    foreach ($roles as $role) {
        if (!$user_lavels->contains('group_id', $role)) {
            $new_lavel = new Modules_User_Lavel;
            $new_lavel->user_id = $data_user->id;
            $new_lavel->group_id = $role;
            $new_lavel->status_delete = 'Enable';
            $new_lavel->save();
        }
    }


    return redirect()->route('users_review.index', ['id' => $data_user->id]);
}
    
    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->status_delete = 'Disable';
            $user->save();
        }
        $user = DormitoryUser::where('users_id', $id)->first();;
        if ($user) {
            $user->status_delete = 'Disable';
            $user->save();
        }
        return redirect('/users');
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
    public function massDelete(Request $request)
    {
        $ids = $request->input('ids');
        
        if (!empty($ids)) {
            try {
                DB::beginTransaction(); // Start transaction
                
                foreach ($ids as $id) {
                    $user = User::find($id);
                    if ($user) {
                        $user->status_delete = 'Disable';
                        $user->save();
                    }
                    $user = DormitoryUser::where('users_id', $id)->first();;
                    if ($user) {
                        $user->status_delete = 'Disable';
                        $user->save();
                    }
                }
                
                DB::commit(); // Commit transaction
                return redirect()->route('users.index')->with('success', 'ลบกลุ่มผู้ใช้ที่เลือกเรียบร้อยแล้ว');
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback transaction on error
                return redirect()->route('users.index')->withErrors(['error' => 'เกิดข้อผิดพลาดในการลบข้อมูล ยังมีข้อมูลที่ไม่ได้ลบ']);
            }
        } else {
            return redirect()->route('users.index')->withErrors(['error' => 'ไม่พบข้อมูลผู้ใช้ที่เลือก']);
        }
    }
}    