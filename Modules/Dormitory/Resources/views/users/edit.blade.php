@extends('core::layouts.master')

@php
    $page_id = 'dormitory_users';
    $page_name = 'แก้ไขผู้ใช้งาน';
    $page_title = 'แก้ไขผู้ใช้งาน';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
    use Carbon\Carbon;
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">แก้ไขผู้ใช้งาน</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('dormitorys.users.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input type="text" name="username" id="username" value="{{ $data->user->username }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        <input type="hidden" name="users_id" id="users_id" value="{{ $data->users_id }}" >
                        <input type="hidden" name="room_id" id="room_id" value="{{ $room_id }}" >
                        <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}" >
                        
                    </div>
                    
                    <div class="form-group">
                        <label for="room_id">รหัสห้อง</label>
                        
                        <select name="room_id_new" id="room_id_new" required>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" @if($room->id == $room_id) selected @endif>{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-account-add"></i> อัปเดต
                        </button>
                        <a href="{{ route('dormitorys.users.delete', $data->id) }}" class="btn btn-danger btn-rounded" onclick="return confirm('คุณต้องการลบ {{ $data->name }} หรือไม่ ?')" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ลบ
                        </a>
                        <a href="{{ route('dormitorys.users.exit', $data->id) }}" class="btn btn-danger btn-rounded" onclick="return confirm('เปลี่ยนให้ {{ $data->name }} กำลังย้ายออกหรือไม่ ?')" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ออกจาหอพัก
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
            <i class="mdi mdi-undo"></i> ย้อนกลับ
        </a>
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
</style>
@endsection

@section('js_link')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.th.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection

@section('js_script')
<script>
        function previewImage_dormitory(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imgpro_dormitory-preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
        // Update button text and style
    document.getElementById("nameImg").textContent = 'เลือกรูปภาพสำเร็จ';
    document.getElementById("btnImg").classList.add('btn-success');
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
</script>
@endsection
