@extends('core::layouts.master')
@php
    $page_id='dormitory_users';
    $page_name='เพิ่มผู้ใช้งาน';
    $page_title='เพิ่มผู้ใช้งาน';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">เพิ่มผู้ใช้งาน</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{route('dormitorys.users.insert',$dormitory->code)}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input type="text" name="username" id="username" placeholder="กรุณาใส่ชื่อผู้ใช้" value="{{ old('username') }}" class="form-control" required>
                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                        @if ($errors->has('username'))
                            <div class="text-danger">{{ $errors->first('username') }}</div>
                        @endif
                    </div>
                    <input type="hidden" name="dormitorys_id" id="dormitorys_id" value="{{$dormitory->id}}">
                    <div class="form-group">
                        <label for="name">ชื่อ</label>
                        <input type="text" name="name" id="name" placeholder="กรุณาใส่ชื่อ" value="{{ old('name') }}" class="form-control" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข"required>
                        @if ($errors->has('name'))
                            <div class="text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email">อีเมล</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="กรุณาใส่อีเมล" required>
                    </div>
                    <div class="form-group">
                        <label for="imgpro">รูปประจำตัว</label>
                        <img src="" id="imgpro-preview" style="width: 50px; height: 50px;  display: none; margin-bottom: 10px;">
                        
                        <input class="inputfile" id="imgpro" type="file" name="imgpro" accept="image/*" required onchange="previewImage(event)">
                        <label class="btn-secondary" id="btnImg" for="imgpro"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพประจำตัว . . . . .</span>
                    </div>
                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="กรุณาใส่รหัสผ่าน" maxlength="50" required>
                    </div>
                    <div class="form-group">
                        <label for="room_id">รหัสห้อง</label>
                        <select name="room_id" id="room_id" class="form-control" required>
                            <option value="">กรุณาเลือกห้อง</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                @endforeach
                        </select>
                        @if ($errors->has('room_id'))
                            <div class="text-danger">{{ $errors->first('room_id') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label>
                        <input type="text" name="phone" id="phone" placeholder="กรุณาใส่เบอร์โทร" value="{{ old('phone') }}" maxlength="10" pattern="\d*" title="กรุณาใส่เฉพาะตัวเลข" class="form-control" required>
                        @if ($errors->has('phone'))
                            <div class="text-danger">{{ $errors->first('phone') }}</div>
                        @endif
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-rounded">
                            <i class="mdi mdi-account-add"></i> บันทึก
                        </button>
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
</style>
@endsection

@section('js_script')
<script>
document.getElementById('username').addEventListener('blur', function() {
    var username = this.value;
    if (username) {
        fetch('{{ route("dormitorys.users.fetchUser") }}', {
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
                document.getElementById('name').value = data.name;
                document.getElementById('phone').value = data.phone;
                document.getElementById('id').value = data.id;
                document.getElementById('email').value = data.email;
                document.getElementById('password').value = data.password;
                document.getElementById('status').value = data.status;
                if (data.imgpro) {
                    document.getElementById('imgpro-preview').src = data.imgpro;
                    document.getElementById('imgpro-preview').style.display = 'block';
                } else {
                    document.getElementById('imgpro-preview').style.display = 'none';
                }

                // Set the fields to read-only
                document.getElementById('name').readOnly = true;
                document.getElementById('phone').readOnly = true;
                document.getElementById('password').readOnly = true;
            } else {
                alert('ไม่พบข้อมูลในระบบ');

                // Make sure fields are editable
                document.getElementById('name').readOnly = false;
                document.getElementById('phone').readOnly = false;
                document.getElementById('password').readOnly = false;
                document.getElementById('status').disabled = false;
                document.getElementById('imgpro-preview').style.display = 'none';
            }
        })
        .catch(error => console.error('Error:', error));
    }
});

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
