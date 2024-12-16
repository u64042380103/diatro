@extends('core::layouts.master')

@php
    $page_id = 'controlsystem';
    $page_name = 'ตั้งค่าเวลาการทำงาน';
    $page_title = 'ตั้งค่าเวลาการทำงาน';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">ตั้งค่าเวลาการทำงาน</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('device.insert_time', $device->id) }}">
                    @csrf
                    <input type="hidden" name="device_key" value="{{ $device->id }}">
                    <input type="hidden" name="set_times" value="{{ $device->set_times }}">
                    <div class="form-group">
                        <label for="time">เวลาเริ่มการทำงาน</label>
                        <input type="time" name="time" value="00:00" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="on_off">เลือกการทำงาน</label>
                        <select name="on_off" id="on_off" required>
                            <option value="0">ปิด</option>   
                            <option value="1">เปิด</option>
                        </select>                    </div>
                    
                    <div class="form-group">
                        <label for="loop">เลือกการทำซ้ำ</label>
                        <label><input type="checkbox" name="loop[]" value="1"> วันจันทร์</label>
                        <label><input type="checkbox" name="loop[]" value="2"> วันอังคาร</label>
                        <label><input type="checkbox" name="loop[]" value="3"> วันพุธ</label>
                        <label><input type="checkbox" name="loop[]" value="4"> วันพฤหัสบดี</label>
                        <label><input type="checkbox" name="loop[]" value="5"> วันศุกร์</label>
                        <label><input type="checkbox" name="loop[]" value="6"> วันเสาร์</label>
                        <label><input type="checkbox" name="loop[]" value="7"> วันอาทิตย์</label>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
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
        <a href="{{ route('device.set_time', $device->id) }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
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
<script>
    document.getElementById('timeForm').addEventListener('submit', function() {
        // Remove the default hidden input if any checkbox is checked
        if (Array.from(document.querySelectorAll('input[name="loop[]"]:checked')).length > 0) {
            document.getElementById('loop-default').disabled = true;
        }
    });
</script>
@endsection
