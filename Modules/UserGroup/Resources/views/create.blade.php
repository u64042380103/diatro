@extends('core::layouts.master')
@php
    $page_id = 'users_group';
    $page_name = 'สร้างกลุ่มผู้ใช้';
    $page_title = 'สร้างกลุ่มผู้ใช้';
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">สร้างกลุ่มผู้ใช้งาน</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('usersgroup.insert') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">ชื่อกลุ่มผู้ใช้</label>
                        <input type="text" name="name" id="name" placeholder="กรุณาใส่ชื่อกลุ่มผู้ใช้" value="" maxlength="50" required>
                    </div>

                    <div class="form-group">
                        <label for="note">หมายเหตุ</label>
                        <input type="text" name="note" id="note" placeholder="กรุณาใส่หมายเหตุ" value="" maxlength="50" required>

                    </div>
                    <div class="form-group">
                        <label for="status_delete">สถานะ</label>
                        <select name="status_delete" id="status_delete" required>
                            <option value="">กรุณาเลือกสถานะ</option>
                            <option value="Enable">Enable</option>
                            <option value="Disable">Disable</option>
                        </select>
                        @if ($errors->has('room_id'))
                            <div class="text-danger">{{ $errors->first('room_id') }}</div>
                        @endif
                    </div>
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-account-add"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <a href="{{ route('usersgroup.index') }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
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

@section('js_link')
    {{-- coding js link--}}
@endsection

@section('js_script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userGroupForm');
            const submitBtn = document.querySelector('button[type="submit"]');
            const inputs = form.querySelectorAll('input[required], select[required]');

            function validateForm() {
                let valid = true;
                inputs.forEach(input => {
                    if (!input.value) {
                        valid = false;
                    }
                });
                submitBtn.disabled = !valid;
            }

            inputs.forEach(input => {
                input.addEventListener('input', validateForm);
            });

            validateForm(); // Initial check
        });
    </script>
@endsection
