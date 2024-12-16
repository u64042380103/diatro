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
                <form method="POST" action="{{ route('device.update_time', $setTime->id) }}">
                    @csrf
                    <input type="hidden" name="set_times"  value="{{ $setTime->set_times }}" required>
                    <input type="hidden" name="device_key" value="{{ $setTime->device_key }}" required>
                    <input type="hidden" name="time" value="{{ $setTime->time }}" required>
                    <input type="hidden" name="on_off"  value="{{ $setTime->on_off }}" required>
                    <div class="form-group">
                        <label for="time">เวลาเริ่มการทำงาน</label>
                        <input type="time" name="time" value="{{$setTime->time}}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="on_off">เลือกการทำงาน</label>
                        <select name="on_off" id="on_off" required>
                            <option value="0" {{ $setTime->on_off == '0' ? 'selected' : '' }}>ปิด</option>   
                            <option value="1" {{ $setTime->on_off == '1' ? 'selected' : '' }}>เปิด</option>
                        </select>                   
                    </div>
                    
                    <div class="form-group">
                        <label for="loop">เลือกการทำซ้ำ</label>
                        @php
                        if($setTime->loop =='8'){
                        $setTime->loop = "1,2,3,4,5,6,7";}

                            $loopValues=(explode(",",$setTime->loop));
                                            
                        @endphp
                        {{-- {{dd($noset->loop)}} --}}
                        {{-- <input type="hidden" name="loop[]" value="" id="loop-default"> --}}
                        <label><input type="checkbox" name="loop[]" value="1" {{ in_array('1', $loopValues) ? 'checked' : '' }}> วันจันทร์</label>
                        <label><input type="checkbox" name="loop[]" value="2" {{ in_array('2', $loopValues) ? 'checked' : '' }}> วันอังคาร</label>
                        <label><input type="checkbox" name="loop[]" value="3" {{ in_array('3', $loopValues) ? 'checked' : '' }}> วันพุธ</label>
                        <label><input type="checkbox" name="loop[]" value="4" {{ in_array('4', $loopValues) ? 'checked' : '' }}> วันพฤหัสบดี</label>
                        <label><input type="checkbox" name="loop[]" value="5" {{ in_array('5', $loopValues) ? 'checked' : '' }}> วันศุกร์</label>
                        <label><input type="checkbox" name="loop[]" value="6" {{ in_array('6', $loopValues) ? 'checked' : '' }}> วันเสาร์</label>
                        <label><input type="checkbox" name="loop[]" value="7" {{ in_array('7', $loopValues) ? 'checked' : '' }}> วันอาทิตย์</label>
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
        <a href="{{ route('device.set_time', $setTime->device_key) }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
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
