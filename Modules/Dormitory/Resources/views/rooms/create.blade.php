@extends('core::layouts.master')
@php
    $page_id='dormitory_rooms';
    $page_name='เพิ่มห้องพัก';
    $page_title='เพิ่มห้องพัก';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">เพิ่มห้องพัก</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{route('dormitorys.rooms.insert',$dormitory->code)}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">หมายเลขห้อง</label>
                        <input type="text" name="name" id="name" placeholder="กรุณาใส่หมายเลขห้อง" value="" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
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
                        <input type="text" name="floor" id="floor" placeholder="กรุณาใส่ชั้น" value="" maxlength="50" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                            
                    </div>
                    <div class="form-group">
                        <label for="room_type">ประเภท</label>
                            <select name="room_type" id="room_type" required>
                                <option value="">กรุณาเลือกสถานะ</option>
                                <option value="fan">พัดลม</option>
                                <option value="air">แอร์</option>
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="status">สถานะ</label>
                            <select name="status" id="status" required>
                                <option value="">กรุณาเลือกสถานะ</option>
                                <option value="Active">ใช้งาน</option>
                                <option value="Free">ห้องว่าง</option>
                                <option value="Disable">ไม่ใช้งาน</option>
                                <option value="Booking">จอง</option>
                                <option value="MA">ปิดปรับปรุง</option>
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="area"> ขนาดห้อง</label>
                        <input type="number" name="area" id="area" value="" min="0" placeholder="กรุณาใส่ความสูง" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                        <span>ตร.ม</span>
                    </div>
                    <div class="form-group">
                        <label for="water"> ค่าน้ำราคาเหมา</label>
                        <input type="number" name="water" id="water" value="" min="0" placeholder="ค่าน้ำราคาเหมา" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                        <span>บาท</span>
                    </div>
                    <div class="form-group">
                        <label for="extension">คำอธิบาย</label>
                        <input type="test" name="extension" id="extension" value="" placeholder="กรุณาใส่คำอธิบาย" class="large-input" required>
                    </div>
                    <div class="form-group">
                        <label for="img_room">รูปภาพประกอบ</label>
                        <img src="" id="img_room-preview" style="width: 50px; height: 50px;  display: none; margin-bottom: 10px;">
                        <input type="file" name="img_room" id="img_room" class="form-control" accept="image/*" onchange="previewImage(event)" style="display:none;" required>
                        <label class="btn-secondary" id="btnImg" for="img_room"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพประจำตัว . . . . .</span></label>
                    </div>
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-add"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-2">
            <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ url()->previous() }}">
                <i class="mdi mdi-chevron-left"></i> ย้อนกลับ</a>
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
    {{-- coding js link--}}
@endsection

@section('js_script')
    {{-- coding js script--}}
    <script>
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
