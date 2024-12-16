@extends('core::layouts.master')
@php
    $page_id = 'dormitory_check_in';
    $page_name = 'เช็คอินเข้า';
    $page_title = 'เช็คอินเข้า';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp
@section('content')
<div class="p-5 mt-n5 mb-3 mx-n5 text-dark" style="background-attachment: fixed; height: 250px; background-color: #333333;">
    <div class="text-center" style="font-size: 20px; color: #ffffff;">เช็คอินเข้า</div>
    <div class="mt-1 text-center">
        <div class="mt-1 mb-3 text-center">
            <a href="{{ route('dormitorys.show', $dormitory_code) }}" style="font-size: 26px; color: #ffffff;">
                <i class="icon mdi mdi-city-alt"></i> {{ $dormitory->name }}
            </a>
        </div>
    </div>
</div>
<div class="card shadow" style="margin-top: 15px; border-radius: 15px; background-color: #FBFBFB; margin-top: -155px;">
    <div class="card-body text-dark">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-8 mt-3">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('dormitorys.check_in.Waiting', $dormitory->code) }}" style="width: 48%;">
                        <div class="card shadow room-card" style="border-radius: 10px; color: #000000;">
                            <div class="card-body pt-4">
                                <div>รอย้ายเข้า</div>
                                <div class="mt-1">
                                    <i class="mdi mdi-accounts" title="จำนวนผู้รอย้ายออก"></i> {{$waiting_count}}
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('dormitorys.check_in.in', $dormitory->code) }}" style="width: 48%;">
                        <div class="card shadow room-card" style="border-radius: 10px; color: #000000;">
                            <div class="card-body pt-4">
                                <div>ย้ายเข้าแล้ว</div>
                                <div class="mt-1">
                                    <i class="mdi mdi-accounts" title="จำนวนผู้ย้ายออกแล้ว"></i> {{$In_count}}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    {{-- coding css --}}
@endsection

@section('js_link')
    {{-- coding js link--}}
@endsection

@section('js_script')
    {{-- coding js script--}}
@endsection