@extends('core::layouts.master')
@php
    $page_id='dormitory_lease_agreements';
    $page_name='สัญญาเช่า';
    $page_title='สัญญาเช่า';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
    use Carbon\Carbon;
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">สัญญาเช่า</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('dormitorys.monthly_rent.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="rooms_id">ห้อง</label>
                        <input type="text" name="" id="" class="form-control" value="{{$data_room->name}}" readonly required>
                        <input type="hidden" name="room_id" id="room_id" class="form-control" value="{{$data_room->id}}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="monthly_rent">ราคาค่าเช่า</label>
                        <input type="number" name="monthly_rent" id="monthly_rent" class="form-control" value="{{$data->monthly_rent}}" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_status">สถานะ</label>
                        <select name="payment_status" id="payment_status" class="form-control" required>
                            <option value="Unpaid" {{ $data->payment_status == 'Unpaid' ? 'selected' : '' }}>ค้างจ่าย</option>
                            <option value="Paid" {{ $data->payment_status == 'Paid' ? 'selected' : '' }}>จ่ายแล้ว</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <a href="{{ route('dormitorys.monthly_rent.delete', $data->id) }}" class="btn btn-danger btn-rounded"  style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ลบ
                        </a>
                        <button type="submit" class="btn btn-primary btn-rounded">
                            <i class="mdi mdi-account-add"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <a href="{{ route('dormitorys.monthly_rent.index', $data_room->id) }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
            <i class="mdi mdi-undo"></i> ย้อนกลับ
        </a>
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

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('img-preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
