@extends('core::layouts.master')

@php
    $page_id = 'users';
    $page_name = 'เพิ่มคอมเมนต์ผู้ใช้';
    $page_title = 'เพิ่มคอมเมนต์ผู้ใช้';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">เพิ่มคอมเมนต์ผู้ใช้</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('users_review.insert', $id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">ชื่อ</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $users->name }}" maxlength="50" readonly required>
                        <input type="hidden" name="users_id" id="users_id" class="form-control" value="{{ $id }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="dormitorys_name">หอพัก</label>
                        <input type="text" name="dormitorys_name" id="dormitorys_name" class="form-control" value="{{ $dormitory ? $dormitory->name : '' }}" placeholder="กรุณาใส่ชื่อหอพัก" maxlength="50" required>
                        <input type="hidden" name="dormitorys_id" id="dormitorys_id" class="form-control" value="{{ $dormitory ? $dormitory->id : '' }}" required>
                        @error('dormitorys_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="star">ดาว</label>
                        <select name="star" id="star" class="form-control" required>
                            <option value="" disabled selected>กรุณาให้คะแนน</option>
                            <option value=1>1 ดาว</option>
                            <option value=2>2 ดาว</option>
                            <option value=3>3 ดาว</option>
                            <option value=4>4 ดาว</option>
                            <option value=5>5 ดาว</option>
                        </select>
                        @error('star')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
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
    @if($dormitoryUser)
    document.getElementById('dormitorys_name').addEventListener('blur', function() {
        var dormitorys_name = this.value;
        if (dormitorys_name) {
            fetch('{{ route("users_review.change") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ dormitorys_name: dormitorys_name })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('dormitorys_name').value = data.dormitorys_name;
                    document.getElementById('dormitorys_id').value = data.dormitorys_id;
                    document.getElementById('dormitorys_name').readOnly = true;
                } else {
                    alert('ไม่พบข้อมูลในระบบ');
                    document.getElementById('dormitorys_name').readOnly = false;
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
    @endif
</script>
@endsection
