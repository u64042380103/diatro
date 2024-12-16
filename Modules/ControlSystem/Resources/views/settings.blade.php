@php
$page_id = 'controlsystem';
$page_name = 'ตั้งค่าอุปกรณ์';
$page_title = 'ตั้งค่าอุปกรณ์';
@endphp

@extends('core::layouts.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">{{$device->name}}</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('device.update', $device->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">ชื่ออุปกรณ์</label>
                        <input type="text" name="name" id="name" value="{{ $device->name }}" maxlength="50" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="device_id">รหัสอุปกรณ์</label>
                        <input type="text" name="device_id" id="device_id" value="{{ $device->device_id }}" maxlength="50" required>
                    </div>

                    <div class="form-group">
                        <label for="port">พอร์ต</label>
                        <input type="text" name="port" id="port" value="{{ $device->port }}" maxlength="50" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">สถานะการใช้งาน</label>
                        <select name="status" id="status" required>
                            <option value="1" {{ $device->status == '1' ? 'selected' : '' }}>กำลังทำงาน</option>
                            <option value="0" {{ $device->status == '0' ? 'selected' : '' }}>หยุดทำงาน</option>
                        </select>
                        <input type="hidden" name="set_times" id="set_times" value="0" required>
                    </div>
                    
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-account-add"></i> อัปเดต
                        </button>
                        <a href="{{ route('device.delete', $device->id) }}" class="btn btn-danger btn-rounded" onclick="return confirm('คุณต้องการลบ {{$device->name}} หรือไม่ ?')" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ลบ {{$device->name}}
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <a href="{{ $previousUrl }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
            <i class="mdi mdi-undo"></i> ย้อนกลับ
        </a>
    </div>
</div>
@endsection

@section('css')
<style>
  /* Custom CSS if needed */
  input, select {
      border-radius: 15px;
      padding: 10px;
      border: 1px solid #ccc;
  }
        /* Custom CSS if needed */
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
            margin: 5px auto; /* Center the card horizontally */
        }
        .card-header {
            text-align: center;
        }
        .card-body {
            padding: 20px; /* Add padding to card body */
        }
        label {
            font-weight: bold ; /* Make label text bold */
        }
    </style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
{{-- <script>
$(document).ready(function() {
    $('#set_time').change(function() {
        if ($(this).val() === 'on') {
            $('#status').val('on').prop('selected', true);
        }
        else{
            $('#status').val('off').prop('selected', false);
        }
    });
});
</script> --}}
@endsection
