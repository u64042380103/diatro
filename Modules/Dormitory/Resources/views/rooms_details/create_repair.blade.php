@extends('core::layouts.master')

@php
    $page_id='dormitory_rooms';
    $page_name='อุปกรณ์ภายในห้อง';
    $page_title='อุปกรณ์ภายในห้อง';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
    use Carbon\Carbon;

@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">อุปกรณ์ภายในห้อง</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('dormitorys.rooms_details.insert_repair', $data_Details->id) }}" >
                    @csrf
                    <div class="modal-body">
                        <label for="price">ค่าซ่อม</label>
                        <input type="number" name="price" id="price" placeholder="กรุณาใส่ค่าซ่อม" value="" maxlength="50" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                    </div>
                    <div class="modal-body">
                        <label for="date">วันที่ซ่อม</label>
                        <input type="text" name="date" id="date" class="form-control datepicker" placeholder="เลือกวันเดือนปี" required>
                    </div>    
                    <div class="modal-body">
                        <label for="details">รายละเอียด</label>
                        <input type="text" name="details" id="details" placeholder="กรุณาใส่รายละเอียด" value="" class="large-input" required>
                    </div>
                    <div class="modal-body text-right">
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-add"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-2">
            <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ url()->previous() }}">
                <i class="mdi mdi-chevron-left"></i> ย้อนกลับ</a>
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
    .card {
        margin: 5px auto;
    }
    .card-header {
        text-align: center;
    }
    .card-body {
        padding: 20px;
    }
    .large-input {
        width: 100%;
    }
    label {
        font-weight: bold;
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
</script>
@endsection
