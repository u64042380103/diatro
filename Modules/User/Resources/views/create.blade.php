@extends('core::layouts.master')

@php
    $page_id = 'users';
    $page_name = 'สร้างบัญชีผู้ใช้';
    $page_title = 'สร้างบัญชีผู้ใช้';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">สร้างบัญชีผู้ใช้</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('users.insert') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">ชื่อ</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="กรุณาใส่ชื่อ" maxlength="50"pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="กรุณาใส่ชื่อผู้ใช้" maxlength="50" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">อีเมล</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="กรุณาใส่อีเมล" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="imgpro">รูปประจำตัว</label>
                        <img src="" id="imgpro-preview" style="width: 50px; height: 50px; border-radius: 50%; display: none; margin-bottom: 10px;">

                        <input type="file" name="imgpro" id="imgpro" class="form-control" accept="image/*" onchange="previewImage(event)" style="display:none;" >
                        <label class="btn-secondary" id="btnImg" for="imgpro"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพประจำตัว . . . . .</span></label>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="กรุณาใส่รหัสผ่าน" maxlength="50" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="กรุณาใส่เบอร์โทร" maxlength="10" pattern="\d*" title="กรุณาใส่เฉพาะตัวเลข" required>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-rounded">
                            <i class="mdi mdi-account-add"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
            <i class="mdi mdi-undo"></i> ย้อนกลับ
        </a>
    </div>
</div>
@endsection

@section('css')
<style>
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
</style>
@endsection

@section('js_link')
    {{-- Custom JS links if needed --}}
@endsection

@section('js_script')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imgpro-preview');
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
