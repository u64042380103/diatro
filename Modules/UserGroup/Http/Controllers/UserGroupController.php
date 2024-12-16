<?php

namespace Modules\UserGroup\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\UserGroup\Entities\ModelUserGroup;
use Modules\User\Entities\Modules_User_Lavel;
use Modules\User\Entities\Modules_User;
use App\Models\User;

class UserGroupController extends Controller
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
        $usergroup = ModelUserGroup::where('status_delete', '!=', 'Disable')->paginate(10);



        return view('usergroup::index',['usergroup'=>$usergroup]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // dd('create');
        return view('usergroup::create');}

    public function add()
    {
        return view('usergroup::add');}

    public function fetchUser(Request $request)
    {
        $username = $request->username;
        $user = User::where('name', $username)->where('status_delete', '!=', 'Disable')->first();
    
        if ($user) {
            // ดึงสถานะของผู้ใช้จากตาราง Modules_User_Lavel
            $userRoles = Modules_User_Lavel::where('user_id', $user->id)
                ->where('status_delete', '!=', 'Disable')
                ->pluck('group_id')
                ->toArray();
            
            return response()->json([
                'success' => true,
                'username' => $user->username,
                'phone' => $user->phone,
                'id' => $user->id,
                'userRoles' => $userRoles // ส่งสิทธิ์ของผู้ใช้กลับไปด้วย
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    
    public function make(Request $request){
    // Update Modules_User_Lavel
    $data_user = User::where('id', $request->id)->where('status_delete', '!=', 'Disable')->first();

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
        return redirect('/usergroup');
    }

    public function insert(Request $request){
        $request->validate([
            'name'=>'required|max:100',
            'note'=>'required',
            'status'=>'required',
        ],[
            'name.required'=>'กรุณาป้อนชื่อกลุ่ม',
            'name.max'=>'ชื่อกลุ่มไม่ควรเกิน 100 ตัวอักษร',
            'note.required'=>'กรุณาป้อนหมายเหตุ',
            'status.required'=>'กรุณาเลือกสถานะ',
        ]);
        $data = new ModelUserGroup;
        $data->name = $request->name;
        $data->note = $request->note;
        $data->status_delete = $request->status_delete;
        $data->save();

        return redirect('/usergroup');
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
        // dd($id);
        $data_lavel = Modules_User_Lavel::where('group_id',$id)->where('status_delete', '!=', 'Disable')->get();
        // dd($data_lavel);
        $usergroup = ModelUserGroup::where('id',$id)->where('status_delete', '!=', 'Disable')->first();

        return view('usergroup::show',compact("usergroup",'data_lavel'));
        // return view('usergroup::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $usergroup = ModelUserGroup::find($id);
        //dd($usergroup);
        return view('usergroup::edit',compact("usergroup"));
    }

    public function out($id)
    {
        // dd($id);
        $data_lavel = Modules_User_Lavel::where('id',$id)->where('status_delete', '!=', 'Disable')->first();
        // dd($data_lavel);
        $data_lavel->status_delete = 'Disable';
        $data_lavel->save();
        // return view('usergroup::edit',compact("usergroup"));
        return redirect()->back()->with('success', 'Profile image updated successfully.');
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete($id)
    {
        $usergroups = ModelUserGroup::find($id);
        if ($usergroups) {
            $usergroups->status_delete = 'Disable';
            $usergroups->save();
        }
        return redirect('/usergroup');
    }

    public function update(Request $request, $id){
        $request->validate([
            'name'=>'required|max:100',
            'note'=>'required',
            'status'=>'required'
        ],
        [
            'name.required'=>'กรุณาป้อนชื่อกลุ่ม',
            'name.max'=>'ชื่อกลุ่มไม่ควรเกิน 100 ตัวอักษร',
            'note.required'=>'กรุณาป้อนหมายเหตุ',
            'status.required'=>'กรุณาเลือกสถานะ'
        ]
);
        $data =ModelUserGroup::where('id',$id)->first();
        $data->name = $request->name;
        $data->note = $request->note;
        $data->save();
        
        return redirect('/usergroup');
    }

    public function massDelete(Request $request)
{
    $ids = $request->input('ids');
    if (!empty($ids)) {
        // dd($ids);
    foreach($ids as $itm){
                    $usergroups = ModelUserGroup::find($id);
                    if ($usergroups) {
                        $usergroups->status_delete = 'Disable';
                        $usergroups->save();
                    }
                }
    }
    return redirect()->route('usersgroup.index')->with('success', 'ลบกลุ่มผู้ใช้ที่เลือกเรียบร้อยแล้ว');
}

public function massout(Request $request)
{
    // Get selected IDs from the request
    $ids = $request->input('ids');

    if (!empty($ids)) {
        foreach ($ids as $id) {
            // Fetch the record by ID
            $data = Modules_User_Lavel::find($id);

            if ($data) {
                // Change status to 'Disable' and save the data
                $data->status_delete = 'Disable';
                $data->save();
            }
        }
    }

    // Redirect back with a success message
    return redirect()->route('usersgroup.index')->with('success', 'ลบกลุ่มผู้ใช้ที่เลือกเรียบร้อยแล้ว');
}


}
