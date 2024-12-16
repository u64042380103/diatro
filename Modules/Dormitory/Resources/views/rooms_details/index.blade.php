@extends('core::layouts.master')

@php
    // if (auth()->user()->user_type == 5){
    // $page_id = 'dormitory_dt';
    // $page_name = 'รายละเอียดหอพัก';
    // $page_title = 'รายละเอียดหอพัก';}
    // else{
    $page_id = 'dormitory_rooms';
    $page_name = 'อุปกรณ์ภายในห้อง';
    $page_title = 'อุปกรณ์ภายในห้อง';
// }
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">อุปกรณ์ภายในห้อง 
                <div class="tools">                
                    {{-- @if (in_array(auth()->user()->user_type, [1,2,3]))  --}}
                    {{-- <a href="{{ route('dormitorys.rooms_details.create', $data_room->id) }}" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;">
                        <i class="mdi mdi-account-add"></i> เพิ่มอุปกรณ์
                    </a> --}}
                    <a href="" role="button" onclick="create(); return false;" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;" title="เพิ่มอุปกรณ์">
                        <i class="mdi mdi-edit"></i> เพิ่มอุปกรณ์
                    </a>
                    {{-- @endif --}}
                    {{-- @if (in_array(auth()->user()->user_type, [1,2]))  --}}
                    <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-delete"></i> ลบที่เลือก
                    </a>
                    {{-- @endif --}}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <form id="deleteForm" action="{{ route('dormitorys.rooms_details.massDelete',$data_room->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <table class="table table-striped table-hover">
                            <thead>
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                                @endif
                                <tr>
                                    <th style="width:4%;">
                                        {{-- @if (in_array(auth()->user()->user_type, [1,2])) --}}
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="checkAll">
                                            <label class="custom-control-label" for="checkAll"></label>
                                        </div>
                                        {{-- @endif --}}
                                    </th>
                                    <th>ภาพประกอบ</th>
                                    <th>ชื่อ</th>
                                    <th>ราคา</th>
                                    <th>วันที่ซื้อ</th>
                                    <th>รายละเอียด</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr>
                                        <td>
                                            {{-- @if (in_array(auth()->user()->user_type, [1,2])) --}}
                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}">
                                                <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                            </div>
                                            {{-- @endif --}}
                                        </td>
                                        <td>
                                            {{-- {{$item->id}} --}}
                                            @if($item->img_details)
                                            <a href="{{ asset('storage/'. $item->img_details) }}" target="_blank">
                                                <img src="{{ asset('storage/'. $item->img_details) }}" id="img-preview" style="max-width: 50px; height: 50px;">
                                            </a>
                                            @else ไม่พบข้อมูล
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('dormitorys.rooms_details.edit', $item->id) }}">
                                            {{ $item->name }}
                                            </a>
                                            {{-- <a href="" role="button" onclick="edit(); return false;" class="btn btn-primary btn-rounded" title="เพิ่มอุปกรณ์">
                                                <i class="mdi mdi-account-add"></i> แก้ไข                                            
                                            </a> --}}
                                        </td>      
                                        <td>{{$item->price}}</td>  
                                        <td>{{ date('d/m/Y', strtotime($item->date_buy)) }}</td>
                                        <td>{{$item->details }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownFanRooms" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    ประวัติการซ่อม
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownFanRooms" style="max-height: 250px; overflow-y: auto;">
                                                    <a href="{{ route('dormitorys.rooms_details.create_repair', $item->id) }}" role="button" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;" title="เพิ่มอุปกรณ์">
                                                        <i class="mdi mdi-edit"></i> เพิ่ม
                                                    </a>
                                                    @if ($item->Repairs->isEmpty())
                                                        <div class="dropdown-item text-center">ไม่พบข้อมูล</div>
                                                    @else
                                                        <table class="dropdown-item">
                                                            <thead>
                                                                <tr>
                                                                    <th>ครั้งที่</th>
                                                                    <th>รายละเอียด</th>
                                                                    <th>ราคา</th>
                                                                    <th>วันที่</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($item->Repairs as $data)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $data->details }}</td>
                                                                        <td>{{ $data->price }}</td>
                                                                        <td>{{ $data->date }}</td>
                                                                        <td>
                                                                            <a href="{{ route('dormitorys.rooms_details.edit_repair',$data->id) }}" role="button" class="btn btn-warning btn-rounded" style="float:right; margin-right: 10px;" title="เพิ่มอุปกรณ์">
                                                                                <i class="mdi mdi-settings"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            ไม่พบรายการ
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            <div class="mt-2">
                <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ $Url_details }}">
                    <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade colored-header colored-header-primary" id="modal-create" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">เพิ่มอุปกรณ์</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
                <div class="modal-body">
                    <form method="POST" action="{{route('dormitorys.rooms_details.insert',$data_room->id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <label for="name">ชื่อ</label>
                            <input type="text" name="name" id="name" placeholder="กรุณาใส่ชื่อ" value="" required>
                        </div>
                        <div class="modal-body">
                            <label for="price">ราคา</label>
                            <input type="number" name="price" id="price" placeholder="กรุณาจำนวน" value="" maxlength="50" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        </div>
                        <div class="modal-body">
                            <label for="date_buy">วันที่ซื้อ</label>
                            <input type="text" name="date_buy" id="date_buy" placeholder="เลือกวันเดือนปี" class="form-control datepicker" required>
                            {{-- <input type="date" name="date_buy" id="date_buy" required> --}}
                        </div>    
                        <div class="modal-body">
                            <label for="details">รายละเอียด</label>
                            <input type="text" name="details" id="details" placeholder="กรุณาใส่รายละเอียด" value="" class="large-input" required>
                        </div>
                        <div class="modal-body">
                            <label for="img_details">รูปภาพประกอบ</label>
                            <img src="" id="img_details-preview" style="width: 50px; height: 50px;  display: none; margin-bottom: 10px;">
                            <input class="inputfile" id="img_details" type="file" name="img_details" accept="image/*" onchange="previewImage(event)">
                            <label class="btn-secondary" id="btnImg" for="img_details"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพ</span>                    
                        </div>
                        <div class="modal-body text-right">
                            <label></label>
                            <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                                <i class="mdi mdi-add"></i> บันทึก
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
    input, select {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
    
    .btn-primary {
        margin-right: 10px;
    }
    .modal-body {
        margin-bottom: 15px;
    }
    .form-control {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
        .searchbox:active {
            color: #ffffff;
            border-color: #a41414;
        }

        .searchbox:focus {
            color: #ffffff;
            border-color: #ffffff;
        }

        .room-card {
            position: relative;
            overflow: hidden;
        }

        .room-card:hover .edit-button {
            display: block;
        }
    </style>
@endsection

@section('js_link')
    {{-- coding js link--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.th.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection

@section('js_script')
    {{-- coding js script --}}
    <script>
    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });

        $('form').on('submit', function() {
            $('.datepicker').each(function() {
                var date = $(this).datepicker('getDate');
                $(this).val(moment(date).format('YYYY-MM-DD'));
            });
        });
    });

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

    //     document.getElementById('showBtn').addEventListener('click', function(e) {
    //     e.preventDefault();
    //     document.getElementById('combinedForm').action = "";
    //     document.getElementById('combinedForm').submit();
    // });

        function create() {
        $("#modal-create").modal("show");
    }
    function create_repair(itemId) {
    
    $("#modal-create_repair").modal("show");
}

    function edit_repair() {
        $("#modal-edit_repair").modal("show");
    }
    
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('img_details-preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        document.getElementById("nameImg").textContent = 'เลือกรูปภาพสำเร็จ';
        document.getElementById("btnImg").classList.add('btn-success');
    }
    
    </script>
@endsection
