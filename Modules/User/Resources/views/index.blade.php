@extends('core::layouts.master')
@php
    $page_id = 'users';
    $page_name = 'ผู้ใช้งาน';
    $page_title = 'ผู้ใช้งาน';
@endphp

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">ผู้ใช้งาน 
                {{-- ผู้ล็อกอิน:{{ $currentUser->name }} {{$currentUser->user_group->name}} --}}
                <div class="tools">
                    {{-- <a href="{{ route('users.create') }}" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;">
                        <i class="mdi mdi-account-add"></i> เพิ่มผู้ใช้
                    </a> --}}
                    
                    <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-delete"></i> ลบที่เลือก
                    </a>
                    <a href="" role="button" onclick="create(); return false;" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;" title="เพิ่มผู้ใช้">
                        <i class="mdi mdi-account-add"></i> เพิ่มผู้ใช้
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <form id="deleteForm" action="{{ route('users.massDelete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <table class="table table-striped table-hover">
                            <thead>
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                                @endif
                                <tr>
                                    <th style="width:4%;">
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="checkAll">
                                            <label class="custom-control-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                    <th>ชื่อ</th>
                                    <th>ชื่อผู้ใช้</th>
                                    <th>อีเมล</th>
                                    {{-- <th>ระดับ</th> --}}
                                    <th>เบอร์โทร</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $item)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}" @if ($item->username == 'admin') disabled @endif>
                                                <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($item->username != 'admin')
                                            <a class="icon black-icon" href="{{ route('users_review.index', $item->id) }}">
                                            @endif
                                                {{ $item->name }}
                                            </a>
                                        </td>                                        
                                        <td>{{ $item->username }}</td>
                                        <td>{{ $item->email }}</td>
                                        {{-- <td>{{ $item->user_group->name }}</td> --}}
                                        <td>{{ $item->phone }}</td>
                                        <td class="text-right">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            ไม่พบรายการ
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                    </form>
                </div>
            </div>
            <div class="card-footer pt-5 pb-1">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
<div class="modal fade colored-header colored-header-primary" id="modal-create" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">เพิ่มผู้ใช้</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('users.insert') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <label for="username">ชื่อผู้ใช้</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="กรุณาใส่ชื่อผู้ใช้" maxlength="50" >
                        </div>
                        <div class="modal-body">
                            <label for="National_id">รหัสบัตรประชาชน</label>
                            <input type="text" name="National_id" id="National_id" class="form-control" placeholder="กรุณาใส่รหัสบัตรประชาชน(ใส่หรือไม่ไส่ก็ได้)" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข">
                        </div>
                        <div class="modal-body">
                            <label for="name">ชื่อ</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="กรุณาใส่ชื่อ" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        </div>
                        <div class="modal-body">
                            <label for="last_name">นามสกุล</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="กรุณาใส่นามสกุล"  pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        </div>
                        <div class="modal-body">
                            <label for="sex">เพศ</label>
                                <select name="sex" id="sex" required>
                                    <option value="Not_specified">ไม่ระบุ</option>
                                    <option value="Male">ผู้ชาย</option>
                                    <option value="female">ผู้หญิง</option>
                                </select>
                        </div>
                        <div class="modal-body">
                            <label for="Date_birth">วันเดือนปีเกิด</label>
                            <input type="text" name="Date_birth" id="Date_birth"  class="form-control datepicker" placeholder="กรุณาเลือกวันเดือนปีเกิด" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        </div>
                        <div class="modal-body">
                            <label for="email">อีเมล</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="กรุณาใส่อีเมล" >
                        </div>
                        
                        <div class="modal-body">
                            <label for="imgpro">รูปประจำตัว</label>
                            <img src="" id="imgpro-preview" style="width: 50px; height: 50px; border-radius: 50%; display: none; margin-bottom: 10px;">
                            
                            <input class="inputfile" id="imgpro" type="file" name="imgpro" accept="image/*"  onchange="previewImage(event)">
                            <label class="btn-secondary" id="btnImg" for="imgpro"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพประจำตัว . . . . .</span>
                        </div>
                        <div class="modal-body">
                            <label for="password">รหัสผ่าน</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="กรุณาใส่รหัสผ่าน" maxlength="50" >
                        </div>
                        <div class="modal-body">
                            <label for="phone">เบอร์โทร</label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="กรุณาใส่เบอร์โทร" maxlength="10" pattern="\d*" title="กรุณาใส่เฉพาะตัวเลข" >
                        </div>
                        <div class="modal-body">
                            <label for="Emergency">บุคคลที่ติดต่อฉุกเฉิน</label>
                            
                            <div class="form-group row">
                                <label for="Emergency_name" class="col-sm-3 col-form-label">ชื่อผู้ติดต่อ</label>
                                <div class="col-sm-9">
                                    <input type="text" name="Emergency_name" id="Emergency_name" class="form-control" placeholder="กรุณาใส่ชื่อผู้ที่เกี่ยวข้อง">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="relationship" class="col-sm-3 col-form-label">ความสัมพันธ์</label>
                                <div class="col-sm-9">
                                    <input type="text" name="relationship" id="relationship" class="form-control" placeholder="กรุณาใส่ความสัมพันธ์">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Emergency_phone" class="col-sm-3 col-form-label">เบอร์โทร</label>
                                <div class="col-sm-9">
                                    <input type="text" name="Emergency_phone" id="Emergency_phone" class="form-control" placeholder="กรุณาใส่เบอร์โทร" maxlength="10" pattern="\d*" title="กรุณาใส่เฉพาะตัวเลข">
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-body">
                            <label for="roles">การเลือกสถานะผู้ใช้</label>
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_admin" name="roles[]" value="1">
                                    <label class="custom-control-label" for="role_admin">admin-dragon</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_owner" name="roles[]" value="2">
                                    <label class="custom-control-label" for="role_owner">เจ้าของหอพัก</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_admin_dorm" name="roles[]" value="3">
                                    <label class="custom-control-label" for="role_admin_dorm">แอดมินหอพัก</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_tenant" name="roles[]" value="4">
                                    <label class="custom-control-label" for="role_tenant">ผู้เช่า</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_general" name="roles[]" value="5">
                                    <label class="custom-control-label" for="role_general">ผู้ใช้งานทั่วไป</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-body text-right">
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
        .form-control {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
    .pagination {
        display: flex;
        justify-content: center;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    .page-link {
        color: #c0c0c0;
    }
    .page-link:hover {
        color: #0056b3;
    }
    .black-icon {
        color: rgb(100, 100, 100);
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


    document.getElementById('checkAll').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.custom-control-input:not(#checkAll)');
        checkboxes.forEach(function(checkbox) {
            if (!checkbox.disabled) {
                checkbox.checked = document.getElementById('checkAll').checked;
            }
        });
    });

    document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
        e.preventDefault();

        var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked').length;
        if (!selectedCheckboxCount) {
            alert('กรุณาเลือกกลุ่มผู้ใช้งานที่ต้องการลบ');
            return;
        }

        if (confirm('คุณต้องการลบกลุ่มผู้ใช้งานที่เลือกหรือไม่ ?')) {
            document.getElementById('deleteForm').submit();
        }
    });

    function create() {
        $("#modal-create").modal("show");
    }

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
