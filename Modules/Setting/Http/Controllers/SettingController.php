<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $wUnit = 30; //หน่วยที่ใช้
        //ถ้าใช้เกิน 31 หน่วย
        if($wUnit > 31){
                //step1 *5
                $step1 = 20;
                $Totalstep1 = ($step1*5);
                echo 'step1 คำนวณ '.$step1 .' หน่วย x5บาท/หน่วย = '.$Totalstep1 .' บาท';
                echo '<br> -เหลือเอาไปคำนวณ สเตป2 อีก '.($wUnit-$step1). ' หน่วย';
                echo '<hr>';
                //step2 *7
                $step2 = 10;
                $Totalstep2 = ($step2*7);
                echo 'step2 คำนวณ '.$step2. ' หน่วย x7บาท/หน่วย= '.$Totalstep2. ' บาท';
                echo '<br> -เหลือเอาไปคำนวณ สเตป3 อีก '.($wUnit-$step1-$step2). ' หน่วย';
                echo '<hr>';
                //step3 *10
                $step3 = ($wUnit-30);
                $Totalstep3 = ($step3*10);
                echo 'step3 คำนวณ '.$step3 . ' หน่วย x10บาท/หน่วย = '.$Totalstep3 .' บาท';
                //หาผลรวม
                $total = ($Totalstep1 + $Totalstep2 + $Totalstep3);
                echo '<hr> ใช้น้ำไป '.$wUnit.' หน่วย ค่าน้ำ = '.$total. ' บาท';
        }else{
                $step1 = $wUnit;
                $Totalstep1 = $wUnit*5;
                echo 'step1 คำนวณ '.$step1 .' หน่วย x5บาท/หน่วย = '.$Totalstep1 .' บาท';
                echo '<hr> ใช้น้ำไป '.$wUnit.' หน่วย ค่าน้ำ = '.$Totalstep1. ' บาท';
                echo '<hr>';
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('setting::create');
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
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('setting::edit');
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
