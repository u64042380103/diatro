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
                <a href="{{ route('dormitorys.show', $dormitory_code) }}" style="font-size: 26px; color:#ffffff;">
                    <i class="icon mdi mdi-city-alt"></i> {{$dormitory->name}}
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow" style="margin-top: 15px; border-radius: 15px; background-color: #FBFBFB;">
        <div style="margin-top: -155px;">
            <div class="card p-4">
                <div>
                    <a href="{{ route('dormitorys.billings_month.add', $data_billings->id) }}" class="btn btn-success btn-rounded" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-plus"></i> เพิ่ม...
                    </a>
                    <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-delete"></i> ลบที่เลือก
                    </a>
                    <a href="{{ route('dormitorys.billings_month.generate_pdf', $data_billings->id) }}" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-plus"></i> ออกใบแจ้งหนี้
                    </a>
                </div>
                <form id="deleteForm" action="{{ route('dormitorys.billings_month.massDelete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th style="width:1%;">
                                <div class="custom-control custom-control-sm custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkAll">
                                    <label class="custom-control-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th width="10%">รหัส</th>
                            <th width="10%">ประเภท</th>
                            <th width="10%">จำนวน</th>
                            <th width="7%">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                            <tr>
                                <td>
                                    <div class="custom-control custom-control-sm custom-checkbox">
                                        <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}" @if ($item->username == 'admin') disabled @endif>
                                        <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->data_type === 'meter')
                                        <a class="icon black-icon" href="{{ route('dormitorys.meters.show', ['id_room' => $data_room->id, 'filter' => $data_meter[$index]->type ?? '', 'id_meter' => $data_meter[$index]->id ?? '']) }}">
                                            {{ $item->data_id }}
                                        </a>
                                    @elseif ($item->data_type === 'month')
                                    <a class="icon black-icon" href="{{ route('dormitorys.monthly_rent.index',['id' => $data_room->id,'month_id' => $data_month[$index]->id ?? '']) }}" style="margin-right: 20px;" title="ดูค่าเช่าทั้งหมด">
                                        {{ $item->data_id }}
                                    </a>
                                    @else
                                        {{ $item->data_id }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->data_type === 'meter')
                                        ค่ามิเตอร์
                                    @elseif ($item->data_type === 'month')
                                        ค่าเช่า
                                    @elseif ($item->data_type === 'water')
                                        ค่าน้ำแบบเหมา
                                    @else
                                        อื่นๆ
                                    @endif
                                </td>
                                <td>{{ $item->amount }} บาท</td>
                                <td class="{{ $item->payment_status == 'Unpaid' ? '' : 'text-success' }}">
                                    {{ $item->payment_status == 'Unpaid' ? 'ค้างจ่าย' : 'จ่ายแล้ว' }}
                                    @if ($item->data_type === 'other' || $item->data_type === 'water')
                                        <a class="btn btn-secondary btn-rounded shadow-sm"  
                                        href="{{ route('dormitorys.billings_month.edit', ['id' => $item->id]) }}">
                                            <i class="mdi mdi-settings"></i> 
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                                <tr>
                                    <td colspan="5" class="text-center" style="font-size:20px">ไม่พบข้อมูล</td>
                                </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
                <br>
            </div>
        </div>
    </div>
    <div class="mt-2">
        <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ $previousUrl }}">
            <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
        </a>
    </div>
@endsection

@section('css')
<style>
    .black-icon {
        color: rgb(100, 100, 100);
    }
</style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
<script>
    document.getElementById('checkAll').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.custom-control-input:not(#checkAll)');
        checkboxes.forEach(function(checkbox) {
            if (!checkbox.disabled) {
                checkbox.checked = document.getElementById('checkAll').checked;
            }
        });
    });

    document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
        e.preventDefault();

        var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked').length;
        if (!selectedCheckboxCount) {
            alert('กรุณาเลือกกลุ่มผู้ใช้งานที่ต้องการลบ');
            return;
        }

        if (confirm('คุณต้องการลบกลุ่มผู้ใช้งานที่เลือกหรือไม่ ?')) {
            document.getElementById('deleteForm').submit();
        }
    });
</script>
@endsection
