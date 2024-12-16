@extends('core::layouts.master')

@php
    // $user_type = auth()->user()->user_type;
    $page_id = 
    // in_array($user_type, [1, 2, 3]) ? 
    'dormitory_lease_agreements' ;
    // : 'dormitory_rooms';
    $page_name = 
    // in_array($user_type, [1, 2, 3]) ? 
    'สัญญาเช่า' ;
    // : 'รายละเอียดห้องพัก';
    $page_title = $page_name;
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
    <div class="p-5 mt-n5 mb-3 mx-n5 text-dark bg-dark" style="background-attachment: fixed; height: 250px;">
        <div class="text-center text-white" style="font-size: 20px;">{{ $page_title }}</div>
        <div class="mt-1 text-center">
            <a href="{{ route('dormitorys.show', $dormitory_code) }}" class="text-white" style="font-size: 26px;">
                <i class="icon mdi mdi-city-alt"></i> {{ $dormitory_name }}
            </a>
            
        </div>
    </div>
    <form id="deleteForm" action="{{ route('dormitorys.monthly_rent.massDelete') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="card shadow mt-3" style="border-radius: 15px; background-color: #FBFBFB;">
            <div class="card-header d-flex justify-content-between align-items-center" style="margin-top: -155px;">
                <span>สัญญาเช่าห้อง {{$data_room->name}}</span>
                <div>
                    {{-- <a href="{{ route('dormitorys.monthly_rent.create', $data_room->id) }}" class="btn btn-success btn-rounded mr-2">
                        <i class="mdi mdi-plus"></i> เพิ่มค่าเช่า
                    </a> --}}
                    <a href="" role="button" onclick="create(); return false;" class="btn btn-success btn-rounded mr-2" title="เพิ่มสัญญา" >
                        <i class="mdi mdi-add"></i> เพิ่มค่าเช่า
                    </a>
                    <button type="button" class="btn btn-danger btn-rounded mr-2" id="deleteSelectedBtn">
                        <i class="mdi mdi-delete"></i> ลบที่เลือก
                    </button>
                    <a href="{{ $previousUrl }}" class="btn btn-secondary btn-rounded text-dark mr-2">
                        <i class="mdi mdi-undo"></i> ย้อนกลับ
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th style="width:1%;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkAll">
                                    <label class="custom-control-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th width="10%">รหัส</th>
                            <th width="10%">วันที่สร้างบิล</th>
                            <th width="10%">ราคาห้องพัก</th>
                            <th width="10%">สถานะ</th>
                            {{-- @if (in_array($user_type, [1, 2, 3])) --}}
                                <th width="10%">จัดการ</th>
                            {{-- @endif --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_month as $data)
                            <tr>
                                <td class="{{ $month_id == $data->id ? 'highlight' : '' }}">
                                    <div class="custom-control custom-checkbox " >
                                        <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $data->id }}" id="checkbox{{ $data->id }}">
                                        <label class="custom-control-label" for="checkbox{{ $data->id }}"></label>
                                    </div>
                                </td>
                                <td class="{{ $month_id == $data->id ? 'highlight' : '' }}">{{ $data->id }}</td>
                                <td class="{{ $month_id == $data->id ? 'highlight' : '' }}">{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') }}</td>
                                <td class="{{ $month_id == $data->id ? 'highlight' : '' }}">{{ $data->monthly_rent }}</td>
                                <td class="{{ $data->payment_status == 'Unpaid' ? '' : 'text-success' }} {{ $month_id == $data->id ? 'highlight' : '' }}">
                                    {{ $data->payment_status == 'Unpaid' ? 'ค้างจ่าย' : 'จ่ายแล้ว' }}
                                </td>   
                                {{-- @if (in_array($user_type, [1, 2, 3]))     --}}
                                    <td class="{{ $month_id == $data->id ? 'highlight' : '' }}">
                                        <a href="{{ route('dormitorys.monthly_rent.edit', $data->id) }}" class="icon black-icon btn btn-warning text-dark" title="แก้ไขการตั้งค่า">
                                            แก้ไข
                                        </a>

                                        {{-- <a href="" role="button" onclick="edit(); return false;" class="btn btn-warning text-dark" title="แก้ไข">
                                            <i class="mdi mdi-plus"></i>
                                            แก้ไขข
                                        </a> --}}
                                    </td>
                                {{-- @endif --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <div class="modal fade colored-header colored-header-primary" id="modal-create" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">เพิ่มค่าเช่า</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('dormitorys.monthly_rent.insert', $data_room->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                            <div class="modal-body">
                                <label for="rooms_id">ห้อง</label>
                                <input type="text" name="" id="" class="form-control" value="{{$data_room->name}}" readonly required>
                                <input type="hidden" name="rooms_id" id="rooms_id" class="form-control" value="{{$data_room->id}}" readonly required>
                            </div>
                            <div class="modal-body">
                                <label for="monthly_rent">ราคาค่าเช่า</label>
                                <input type="number" name="monthly_rent" id="monthly_rent" class="form-control" value="{{ $data_Lease->monthly_rent }}" placeholder="กรุณาใส่ราคาค่าเช่า" required>
                            </div>
                            <div class="modal-body">
                                <label for="payment_status">สถานะ</label>
                                <select name="payment_status" id="payment_status" class="form-control" required>
                                    <option value="Unpaid">ค้างจ่าย</option>
                                    <option value="Paid">จ่ายแล้ว</option>
                                </select>
                            </div>
                            <div class="modal-body text-right">
                                <button type="submit" class="btn btn-primary btn-rounded">
                                    <i class="mdi mdi-account-add"></i> บันทึก
                                </button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade colored-header colored-header-primary" id="modal-edit" 
            tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">เพิ่มค่าเช่า</h3>
                    <button class="close" type="button" data-dismiss="modal" 
                        aria-hidden="true"><span class="mdi mdi-close"></span></button>
                </div>
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('css')
    <style>
        input, select {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        }
        .form-control {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        }
        .black-icon {
            color: rgb(100, 100, 100);
        }
        .meter01 {
            background-color: #FFCDD2;
        }
        .meter02 {
            background-color: #BBDEFB;
        }
        .meter01.active {
            background-color: #ff4c5e;
        }
        .meter02.active {
            background-color: #4ba9fb;
        }
        .highlight {
            background-color: #fa6c6c;
        }
        label {
        font-weight: bold;
    }
    </style>
@endsection

@section('js_link')
    {{-- Include any required JS links --}}
@endsection

@section('js_script')
    <script>
        document.getElementById('checkAll').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('.custom-control-input:not(#checkAll)');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });

        document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
            e.preventDefault();
            var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked').length;
            if (!selectedCheckboxCount) {
                alert('กรุณาเลือกค่าเช่าที่ต้องการลบ');
                return;
            }

            if (confirm('คุณต้องการลบค่าเช่าที่เลือกหรือไม่ ?')) {
                document.getElementById('deleteForm').submit();
            }
        });
    function create() {
        $("#modal-create").modal("show");
    }
    // function edit() {
    //     $("#modal-edit").modal("show");
    // }
    </script>
@endsection
