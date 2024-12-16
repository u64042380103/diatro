@extends('core::layouts.master')
@php
    $page_id='dormitory_lease_agreements';
    $page_name='สัญญาเช่า';
    $page_title='สัญญาเช่า';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">เพิ่มค่าเช่า</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('dormitorys.monthly_rent.insert', $data_room->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                    <div class="form-group">
                        <label for="rooms_id">ห้อง</label>
                        <input type="text" name="" id="" class="form-control" value="{{$data_room->name}}" readonly required>
                        <input type="hidden" name="rooms_id" id="rooms_id" class="form-control" value="{{$data_room->id}}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="monthly_rent">ราคาค่าเช่า</label>
                        <input type="number" name="monthly_rent" id="monthly_rent" class="form-control" value="" placeholder="กรุณาใส่ราคาค่าเช่า" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_status">สถานะ</label>
                        <select name="payment_status" id="payment_status" class="form-control" required>
                            <option value="Unpaid">ค้างจ่าย</option>
                            <option value="Paid">จ่ายแล้ว</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-rounded">
                            <i class="mdi mdi-account-add"></i> บันทึก
                        </button>
                    </div>
                </form>
                
            </div>
            
        </div>
        
    </div>
    <br>
        <a href="{{ route('dormitorys.lease_agreements.index', $dormitory->code) }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
            <i class="mdi mdi-undo"></i> ย้อนกลับ
        </a>
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
@endsection

@section('js_script')
@endsection
