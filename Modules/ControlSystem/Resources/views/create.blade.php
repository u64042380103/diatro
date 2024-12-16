@extends('core::layouts.master')
@php
    $page_id='controlsystem';
    $page_name='เพิ่มอุปกรณ์';
    $page_title='เพิ่มอุปกรณ์';
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">เพิ่มอุปกรณ์</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('device.insert') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">ชื่ออุปกรณ์</label>
                        <input type="text" name="name" id="name" placeholder="กรุณาใส่ชื่ออุปกรณ์" value="" maxlength="50" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="username">รหัสอุปกรณ์</label>
                        <input type="text" name="device_id" id="device_id" placeholder="กรุณาใส่รหัสอุปกรณ์" value="" maxlength="50" required>
                    </div>

                    <div class="form-group">
                        <label for="port">พอร์ต</label>
                        <input type="text" name="port" id="port" placeholder="กรุณาใส่รหัสพอร์ต" value="" maxlength="50" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">สถานะการใช้งาน</label>
                        <select name="status" id="status" required>
                            <option value="">กรุณาเลือกสถานะ</option>
                            <option value="1">ทำงาน</option>
                            <option value="0">หยุดทำงาน</option>
                        </select>
                        <input type="hidden" name="set_times" id="set_times" value="0" required>
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
    {{-- coding js link--}}
@endsection

@section('js_script')
    {{-- coding js script--}}
@endsection
