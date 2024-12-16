<?php

namespace Modules\ApiDevice\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ApiDevice\Entities\ApiDeviceModules;

class ApiDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function deviceinfo($device_id)
    {
        $device = ApiDeviceModules::select("device_id","name","status","port")->where('device_id',$device_id)->first();
        if($device){
            return response()->json(['status'=>true,'data'=>$device]);
        }   
        return response()->json(['status'=>false,"msq"=>"data not found"]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('apidevice::create');
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
return response()->json(['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('apidevice::edit');
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
