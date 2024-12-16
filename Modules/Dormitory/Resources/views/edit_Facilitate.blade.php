@extends('core::layouts.master')

@php
    $page_id = 'dormitory_dt';
    $page_name = 'รายละเอียดหอพัก';
    $page_title = 'รายละเอียดหอพัก';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">รายละเอียดหอพัก</div>
            <div class="card-body" style="padding: 20px;">
                
                <form method="POST" action="{{ route('dormitorys.update_Facilitate', $data_Facilitate->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="data">สิ่งอำนวยความสะดวก</label>
                        <input type="text" name="data" id="data" value="{{$data_Facilitate->data}}" placeholder="กรุณาใส่สิ่งอำนวยความสะดวก">                  
                    </div>
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-account-add"></i> บันทึก
                        </button>
                        <a href="{{ route('dormitorys.delete_Facilitate', $data_Facilitate->id) }}" class="btn btn-danger btn-rounded" onclick="return confirm('คุณต้องการลบ {{$data_Facilitate->data}} หรือไม่ ?')" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ลบ
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ url()->previous() }}">
            <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
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
        font-weight: bold; /* Make label text bold */
    }
</style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
<script></script>
@endsection
