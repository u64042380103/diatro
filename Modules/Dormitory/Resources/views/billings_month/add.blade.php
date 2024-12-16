@extends('core::layouts.master')

@php
    $page_id = 'dormitory_billings';
    $page_name = 'บิล/การเงินรายเดือน';
    $page_title = 'บิล/การเงินรายเดือน';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
    <div class="p-5 mt-n5 mb-3 mx-n5 text-dark" style="background-attachment: fixed; height: 250px; background-color: #333333;">
        <div class="text-center" style="font-size: 20px; color: #ffffff;">บิล/การเงิน ห้อง {{$data_room->name}}</div>
        <div class="mt-1 text-center">
            <div class="mt-1 mb-3 text-center">
                <a href="{{route('dormitorys.show', $dormitory_code)}}" style="font-size: 26px; color:#ffffff;">
                    <i class="icon mdi mdi-city-alt"></i> {{$dormitory->name}}
                </a>
            </div>
        </div>
    </div>
    <div class="card shadow " style="margin-top: 15px; border-radius: 15px; background-color: #FBFBFB;">
        <div style="margin-top: -155px;">
            
            <div class="card p-4">
                <div>
                    <a href="{{ route('dormitorys.billings_month.before', $data_billings->id) }}" class="btn btn-success btn-rounded" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-plus"></i> เพิ่ม...
                    </a>
                </div>
                <form method="POST" action="{{route('dormitorys.billings_month.insert',$data_billings->id)}}" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-add"></i> บันทึก
                        </button>
                    </div>
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th width="10%">รหัส</th>
                                <th width="10%">จำนวน</th>
                                <th width="10%">สถานะ</th>
                                <th width="1%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{$item->data_id}}</td>
                                    <td>{{$item->amount}} บาท</td>
                                    <td class="{{ $item->payment_status == 'Unpaid' ? '' : 'text-success' }}">
                                        {{ $item->payment_status == 'Unpaid' ? 'ค้างจ่าย' : 'จ่ายแล้ว' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('dormitorys.billings_month.delete', $item->id) }}" class="btn btn-danger btn-rounded" style="float:right; margin-right: 20px;">
                                            <i class="mdi mdi-delete"></i> ลบ
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center" style="font-size:20px">ไม่พบรายการ</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                </form>
            </div>
            <div class="mt-2">
                <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ $previousUrls }}">
                    <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    .black-icon {
        color: rgb(100, 100, 100);
    }
    .btn-large {
        font-size: 1.25rem;
        padding: 1rem;
    }
</style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
    {{-- coding js script --}}
@endsection
