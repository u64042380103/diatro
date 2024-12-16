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
use Modules\Dormitory\Entities\Dormitory_Facilitate;

use Modules\User\Entities\Modules_User_review;
use Modules\Dormitory\Entities\Dormitory_Check_Out;


use App\Models\User;

class FacilitateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function insert(Request $request , $id){
        // dd($id);
        $data = new Dormitory_Facilitate;
        $data->dormitory_id  = $request->id;
        $data->data = $request->data;
        $data->save();
    
        return redirect()->back()->with('success', ' insert.');
    }

    public function edit($id){
        $data_Facilitate = Dormitory_Facilitate::where('id', $id)
        ->where('status_delete', '!=', 'Disable')->first();
        $dormitory = Dormitory::where('id',$data_Facilitate->dormitory_id)->first();

        return view('dormitory::edit_Facilitate',compact('dormitory','data_Facilitate'));
    }

    public function update(Request $request, $id)
{
    $data = Dormitory_Facilitate::find($id);
    $data->data = $request->data;
    $data->save();

    $data_Facilitate = Dormitory_Facilitate::where('id', $id)
    ->where('status_delete', '!=', 'Disable')->first();

    $dormitory = Dormitory::where('id',$data_Facilitate->dormitory_id)->first();

    return redirect()->route('dormitorys.show', ['code' => $dormitory->code])->with('success', 'Dormitory updated successfully!');
}
public function delete($id){

        $data = Dormitory_Facilitate::where('id',$id)->first();
        $dormitory = Dormitory::where('id',$data->dormitory_id)->first();
        if ($data) {
            $data->status_delete = 'Disable';
            $data->save();
            }         
    if (!empty($errors)) {
        return back()->withErrors($errors);
    }
    return redirect()->route('dormitorys.show', ['code' => $dormitory->code])->with('success', 'Dormitory updated successfully!');
}
}