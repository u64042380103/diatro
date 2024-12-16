@extends('core::layouts.master')
@php
    $page_id='dormitory_rooms';
    $page_name='อุปกรณ์ภายในห้อง';
    $page_title='อุปกรณ์ภายในห้อง';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">อุปกรณ์ภายในห้อง</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{route('dormitorys.rooms_details.insert',$data_room->id)}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">ชื่อ</label>
                        <input type="text" name="name" id="name" placeholder="กรุณาใส่ชื่อ" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="price">ราคา</label>
                        <input type="number" name="price" id="price" placeholder="กรุณาจำนวน" value="" maxlength="50" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                    </div>
                    <div class="form-group">
                        <label for="date_buy">วันที่ซื้อ</label>
                        <input type="text" name="date_buy" id="date_buy" class="form-control datepicker" required>
                        {{-- <input type="date" name="date_buy" id="date_buy" required> --}}
                    </div>    
                    <div class="form-group">
                        <label for="details">รายละเอียด</label>
                        <input type="text" name="details" id="details" placeholder="กรุณาใส่รายละเอียด" value="" class="large-input" required>
                    </div>
                    <div class="form-group">
                        <label for="img_details">รูปภาพประกอบ</label>
                        <img src="" id="img_details-preview" style="width: 50px; height: 50px;  display: none; margin-bottom: 10px;">
                        <input class="inputfile" id="img_details" type="file" name="img_details" accept="image/*" onchange="previewImage(event)">
                        <label class="btn-secondary" id="btnImg" for="img_details"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพ</span>                    </div>
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
    .large-input {
        width: 100%;
    }
    label {
        font-weight: bold;
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
    {{-- coding js script--}}
    <script>
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
