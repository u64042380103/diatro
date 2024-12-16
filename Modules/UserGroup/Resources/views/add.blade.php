@extends('core::layouts.master')
@php
    $page_id = 'users_group';
    $page_name = 'สร้างกลุ่มผู้ใช้';
    $page_title = 'สร้างกลุ่มผู้ใช้';
    $userRoles = $userRoles ?? [];
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">เพิ่มสถานะผู้ใช้</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('usersgroup.make') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input type="text" name="username" id="username" placeholder="กรุณาใส่ชื่อผู้ใช้" value="{{ old('username') }}" class="form-control" required>
                        <input type="hidden" name="id" id="id" value="{{ old('id') }}" placeholder="กรุณาใส่ id">
                        <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}" placeholder="กรุณาใส่ phone">
                        @if ($errors->has('username'))
                            <div class="text-danger">{{ $errors->first('username') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="roles">การเลือกสถานะผู้ใช้</label>
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_admin" name="roles[]" value="1" {{ in_array(1, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_admin">admin-dragon</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_owner" name="roles[]" value="2" {{ in_array(2, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_owner">เจ้าของหอพัก</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_admin_dorm" name="roles[]" value="3" {{ in_array(3, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_admin_dorm">แอดมินหอพัก</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_tenant" name="roles[]" value="4" {{ in_array(4, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_tenant">ผู้เช่า</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_general" name="roles[]" value="5" {{ in_array(5, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_general">ผู้ใช้งานทั่วไป</label>
                                </div>
                            </div>
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
document.getElementById('username').addEventListener('blur', function() {
    var username = this.value;
    if (username) {
        fetch('{{ route("usersgroup.fetchUser") }}', {
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
                document.getElementById('username').value = data.username;
                document.getElementById('phone').value = data.phone;
                document.getElementById('id').value = data.id;
                document.getElementById('username').readOnly = true;

                // ตั้งค่าสถานะเช็คบ็อกซ์ตามสิ่งที่ดึงได้
                let roles = data.userRoles || [];
                document.querySelectorAll('input[name="roles[]"]').forEach(checkbox => {
                    if (roles.includes(parseInt(checkbox.value))) {
                        checkbox.checked = true;
                    } else {
                        checkbox.checked = false;
                    }
                });
            } else {
                alert('ไม่พบข้อมูลในระบบ');
                document.getElementById('username').readOnly = false;
            }
        })
        .catch(error => console.error('Error:', error));
    }
});

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
