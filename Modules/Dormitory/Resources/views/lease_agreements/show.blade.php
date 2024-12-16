@extends('core::layouts.master')

@php
// if (in_array(auth()->user()->user_type, [1,2,3])) {
    $page_id = 'dormitory_lease_agreements';
    $page_name = 'สัญญาเช่า';
    $page_title = 'สัญญาเช่า';
//     }else {
    // $page_id = 'dormitory_rooms';
    // $page_name = 'รายละเอียดห้องพัก';
    // $page_title = 'รายละเอียดห้องพัก';
    // }
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="p-5 mt-n5 mb-3 mx-n5 text-dark" style="background-attachment: fixed; height: 250px; background-color: #333333;">
        <div class="text-center" style="font-size: 20px; color: #ffffff;">สัญญาเช่า</div>
        <div class="mt-1 text-center">
            <div class="mt-1 mb-3 text-center">
                <a href="{{ route('dormitorys.show', $dormitory_code) }}" style="font-size: 26px; color:#ffffff;">
                    <i class="icon mdi mdi-city-alt"></i> {{ $dormitory->name }}
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow" style="margin-top: 15px; border-radius: 15px; background-color: #FBFBFB;">
        <div style="margin-top: -155px;">
            <div class="card-header d-flex justify-content-between">
                สัญญาเช่าห้อง {{$data_room->name}}
                <div>
                    <a href="{{ $previousUrl }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-undo"></i> ย้อนกลับ
                    </a>
                </div>
            </div>
            <div class="card p-4">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th width="10%">วันเริ่มเช่า</th>
                            <th width="10%">วันสิ้นสุดการเช่า</th>
                            <th width="10%">ราคาห้องพัก</th>
                            <th width="10%">มัดจำ</th>
                            <th width="10%">สถานะ</th>
                            {{-- @if (in_array(auth()->user()->user_type, [1,2,3])) --}}
                            <th width="10%">จัดการ</th>
                            {{-- @endif --}}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($data->startDate)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->endDate)->format('d/m/Y') }}</td>
                            <td>{{ $data->monthly_rent }}</td>
                            <td>{{ $data->deposit }}</td>
                            <td class="{{ $data->payment_status == 'Unpaid' ? '' : 'text-success' }}">
                                {{ $data->payment_status == 'Unpaid' ? 'ค้างจ่าย' : 'จ่ายแล้ว' }}
                            </td>   
                            {{-- @if (in_array(auth()->user()->user_type, [1,2,3]))     --}}
                            <td>
                                {{-- <a  href="{{ route('dormitorys.lease_agreements.edit', $data->id) }}"class="icon black-icon btn btn-warning text-dark" style="margin-right: 20px;" title="แก้ไขการตั้งค่า">
                                    แก้ไข
                                </a> --}}
                                <a href="" role="button" onclick="edit(); return false;" class="icon black-icon btn btn-warning text-dark" style="margin-right: 20px;" title="แก้ไขการตั้งค่า">
                                    แก้ไข
                                </a>
                            </td>
                            {{-- @endif --}}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade colored-header colored-header-primary" id="modal-edit" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">สัญญาเช่า</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('dormitorys.lease_agreements.update', $data->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="rooms_id">ห้อง</label>
                                <input type="text" name="" id="" class="form-control" value="{{$data_room->name}}" readonly required>
                                <input type="hidden" name="rooms_id" id="rooms_id" class="form-control" value="{{$data_room->id}}" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="username">ผู้ทำสัญญา</label>
                                <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{$data->user_id}}" readonly required>
                                <input type="text" name="username" id="username" class="form-control" value="{{ $data->username->username ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="startDate">วันเริ่มเช่า</label>
                                <input type="text" name="startDate" id="startDate" class="form-control datepicker" value="{{ Carbon::parse($data->startDate)->format('d-m-Y') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="endDate">วันสินสุดการเช่า</label>
                                <input type="text" name="endDate" id="endDate" class="form-control datepicker" value="{{ Carbon::parse($data->endDate)->format('d-m-Y') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="monthly_rent">ราคาค่าเช่า</label>
                                <input type="number" name="monthly_rent" id="monthly_rent" class="form-control" value="{{$data->monthly_rent}}" required>
                            </div>
                            <div class="form-group">
                                <label for="deposit">มัดจำ</label>
                                <input type="number" name="deposit" id="deposit" class="form-control" value="{{$data->deposit}}" required>
                            </div>
                            <div class="form-group">
                                <label for="img">หลักฐานสัญญาเช่า</label>
                                @if($data->img)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/'. $data->img) }}" id="img-preview" style="max-width: 100%; height: auto;">
                                    </div>
                                @endif
                                <input class="inputfile" id="img" type="file" name="img" accept="image/*" onchange="previewImage(event)">
                                <label class="btn-secondary" id="btnImg" for="img"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพ . . . . .</span>
                            </div>
                            <div class="form-group">
                                <label for="payment_status">สถานะ</label>
                                <select name="payment_status" id="payment_status" class="form-control" required>
                                    <option value="Unpaid" {{ $data->payment_status == 'Unpaid' ? 'selected' : '' }}>ค้างจ่าย</option>
                                    <option value="Paid" {{ $data->payment_status == 'Paid' ? 'selected' : '' }}>จ่ายแล้ว</option>
                                </select>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-rounded">
                                    <i class="mdi mdi-account-add"></i> บันทึก
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
        label {
        font-weight: bold;
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
    </style>
@endsection

@section('js_link')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.th.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection

@section('js_script')
    {{-- Include any required JS scripts --}}
    <script>
            function edit() {
        $("#modal-edit").modal("show");
    }
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

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('img-preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);

        document.getElementById("nameImg").textContent = 'เลือกรูปภาพสำเร็จ';
        document.getElementById("btnImg").classList.add('btn-success');
    }

    document.getElementById('username').addEventListener('blur', function() {
    var username = this.value;
    if (username) {
        fetch('{{ route("dormitorys.lease_agreements.fetchUser") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({username: username})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ถ้าพบผู้ใช้ ให้ล็อกข้อมูลในฟิลด์ username
                document.getElementById('user_id').value = data.user_id;
                document.getElementById('username').value = data.username;
                document.getElementById('username').readOnly = true;
            } else {
                // ถ้าไม่พบผู้ใช้ ให้ปลดล็อกฟิลด์ username และแจ้งเตือนผู้ใช้
                alert('ไม่พบข้อมูลในระบบ กรุณากรอกข้อมูลใหม่');
                document.getElementById('username').readOnly = false;  // ปลดล็อกฟิลด์
                document.getElementById('username').value = '';  // ล้างข้อมูลเดิม
                document.getElementById('user_id').value = '';  // ล้างข้อมูล user_id เดิม
            }
        })
        .catch(error => console.error('Error:', error));
    }
});

    </script>
@endsection
