@extends('core::layouts.master')

@php
    $page_id = 'dormitory_rooms';
    $page_name = 'รายละเอียดห้องพัก';
    $page_title = 'รายละเอียดห้องพัก';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">รายละเอียดห้องพัก</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('dormitorys.rooms.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">หมายเลขห้อง</label>
                        <input type="text" name="name" id="name" value="{{ $data->name }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        @if ($errors->has('name'))
                        <div class="alert alert-danger">
                            <script>
                                alert('{{ $errors->first('name') }}');
                            </script>
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="floor">ชั้น</label>
                        <input type="text" name="floor" id="floor" value="{{ $data->floor }}" maxlength="50" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="resident">จำนวนผู้พักอาศัย</label>
                        <input type="number" name="resident" id="resident" value="{{ $total_user }}" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" readonly>
                    </div>

                    <div class="form-group">
                        <label for="residents_additional">จำนวนผู้พักอาศัยเพิ่มเติม</label>
                        <input type="number" name="residents_additional" id="residents_additional" value="{{ $data->residents_additional }}" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                    </div>

                    <div class="form-group">
                        <label for="room_type">ประเภท</label>
                        <select name="room_type" id="room_type" required>
                            <option value="fan" {{ $data->room_type == 'fan' ? 'selected' : '' }}>พัดลม</option>
                            <option value="air" {{ $data->room_type == 'air' ? 'selected' : '' }}>แอร์</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">สถานะ</label>
                        <select name="status" id="status" required>
                            <option value="Active" {{ $data->status == 'Active' ? 'selected' : '' }}>ใช้งาน</option>
                            <option value="Free" {{ $data->status == 'Free' ? 'selected' : '' }}>ห้องว่าง</option>
                            <option value="Disable" {{ $data->status == 'Disable' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                            <option value="Booking" {{ $data->status == 'Booking' ? 'selected' : '' }}>จอง</option>
                            <option value="MA" {{ $data->status == 'MA' ? 'selected' : '' }}>ปิดปรับปรุง</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="monthly_rent">ค่าเช่า</label>
                        <input type="number" name="monthly_rent" id="monthly_rent" value="{{$data_lease->monthly_rent}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                    </div>
                    <div class="form-group">
                        <label for="area"> ขนาดห้อง</label>
                        <input type="number" name="area" id="area" value="{{ $data->area }}" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                        <span>ตร.ม</span>
                    </div>
                    <div class="form-group">
                        <label for="water"> ราคาเหมาค่าน้ำ</label>
                        <input type="number" name="water" id="water" value="{{ $data->resident*$dormitory->water }}" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" readonly required>
                        
                    </div>
                    <div class="form-group">
                        <label for="check_water">เหมาจ่าย</label>
                        <select name="check_water" id="check_water" required>
                            <option value="yes" {{ $data->check_water == 'yes' ? 'selected' : '' }}>เหมาจ่าย</option>
                            <option value="no" {{ $data->check_water == 'no' ? 'selected' : '' }}>ไม่เหมาจ่าย</option>
                        </select>
                        <div id="person_group" style="display: {{ $data->check_water == 'yes' ? 'block' : 'none' }};">
                            <label for="check_water">รูปแบบเหมาจ่าย</label>
                            <select name="person" id="person" required>
                                <option value="per_person" {{ $data->person == 'per_person' ? 'selected' : '' }} title="ควณนวนจากจำนวนผู้อยู่อาศัย" >รายคน</option>
                                <option value="all_person" {{ $data->person == 'all_person' ? 'selected' : '' }} title="ควณนวนจากราคาเหมาจ่ายโดยตรง">ทั้งห้อง</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deposit">มัดจำ</label>
                        <input type="number" name="deposit" id="deposit" value="{{$data_lease->deposit}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                    </div>
                    <div class="form-group">
                        <label for="img_room">รูปภาพประกอบ</label>
                        
                        @if($data->img_room)
                            <img src="{{ asset('storage/' . $data->img_room) }}" id="img_room-preview" style="width: 50px; height: 50px;  display: block; margin-bottom: 10px;">
                            @else
                            <img src="" id="img_room-preview" style="width: 50px; height: 50px;  display: none; margin-bottom: 10px;">
                            @endif
                        <input type="file" name="img_room" id="img_room" class="form-control" accept="image/*" onchange="previewImage(event)" style="display:none;" >
                        <label class="btn-secondary" id="btnImg" for="img_room"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพประจำตัว . . . . .</span></label>
                    </div>
                    <div class="form-group">
                        <label for="extension">คำอธิบาย</label>
                        <input type="test" name="extension" id="extension" value="{{$data->extension}}" class="large-input" placeholder="กรุณาใส่คำอธิบาย" required>
                    </div>
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-account-add"></i> อัปเดต
                        </button>
                        {{-- @if (in_array(auth()->user()->user_type, [1,2]))  --}}
                        <a href="{{ route('dormitorys.rooms.delete', $data->id) }}" class="btn btn-danger btn-rounded" onclick="return confirm('คุณต้องการลบ {{ $data->name }} หรือไม่ ?')" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ลบ
                        </a>
                        {{-- @endif --}}
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-2">
            <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ url()->previous() }}">
                <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
            </a>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    input, select {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
    .btn-primary {
        margin-right: 10px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-control {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
    .btn-primary {
        margin-right: 10px;
    }
    .card {
        margin: 5px auto;
    }
    .card-header {
        text-align: center;
    }
    .card-body {
        padding: 20px;
    }
    label {
        font-weight: bold;
    }
    .large-input {
        width: 100%;
    }
</style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
    {{-- coding js script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const checkWater = document.getElementById('check_water');
        const personGroup = document.getElementById('person_group');

        checkWater.addEventListener('change', function() {
            if (checkWater.value === 'yes') {
                personGroup.style.display = 'block';
            } else {
                personGroup.style.display = 'none';
            }
        });
    });
    function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('img_room-preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
    // Update button text and style
    document.getElementById("nameImg").textContent = 'เลือกรูปภาพสำเร็จ';
    document.getElementById("btnImg").classList.add('btn-success');
    }
    </script>
@endsection
