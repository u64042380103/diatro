@extends('core::layouts.master')

@php
    $page_id = 'dormitorys';
    $page_name = 'เพิ่มหอพัก';
    $page_title = 'เพิ่มหอพัก';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">เพิ่มหอพัก</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('dormitorys.insert') }}">
                    @csrf
                    <div class="form-group">
                        <label for="code">code</label>
                        <input type="text" name="code" id="code" placeholder="กรุณาใส่รหัส" value="{{ old('code') }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข"required>
                        @error('code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror                    
                    </div>
                    
                    <div class="form-group">
                        <label for="name">ชื่อหอพัก</label>
                        <input type="text" name="name" id="name" placeholder="กรุณาใส่ชื่อหอพัก" value="{{ old('name') }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข"required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror                  
                    </div>

                    <div class="form-group">
                        <label for="address">ที่อยู่</label>
                        <input type="text" name="address" id="address" placeholder="กรุณาใส่ที่อยู่" value="{{ old('address') }}" maxlength="50" required>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror                    </div>
                    <div class="form-group">
                        <label for="loop">สถานะ</label>
                        <select name="status" id="status" required>
                            <option value="">กรุณาเลือกสถานะ</option>
                            <option value="Enable" {{ old('status') == 'Enable' ? 'selected' : '' }}>ทำงาน</option>
                            <option value="Disable" {{ old('status') == 'Disable' ? 'selected' : '' }}>หยุดทำงาน</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="loop">ค่าน้ำราคาเหมา</label>
                        <input type="number" name="water" id="water" value="" min="0" placeholder="ค่าน้ำราคาเหมา" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                        <span>บาท</span>
                        <input type="hidden" name="check_water" value="no">
                        <input name="check_water" type="checkbox" value="yes" id="check_water" >
                    </div>
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-add"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ route('dormitorys.index') }}">
            <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
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
