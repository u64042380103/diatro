@extends('core::layouts.master')
@php
    $page_id = 'dormitory_billings';
    $page_name = 'บิล/การเงิน';
    $page_title = 'บิล/การเงิน';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">บิล/การเงิน</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('dormitorys.billings_month.update', $data->id) }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="room_id">รหัสห้อง</label>
                        {{ $dataroom->name }}
                    </div>
                    <div class="form-group">
                        <label for="data_type">ประเภท</label>
                        <input type="text" name="data_type" id="data_type" class="form-control" 
                        value="{{ $data->data_type == 'other' ? 'อื่นๆ' : ($data->data_type == 'meter' ? 'มิเตอร์' : ($data->data_type == 'month' ? 'ค่าเช่า' : ($data->data_type == 'water' ? 'ค่าน้ำแบบเหมา' : ''))) }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="amount">จำนวน</label>
                        <input type="number" name="amount" id="amount" class="form-control" value="{{ $data->amount }}" min="0" step="0.01" oninput="validateDecimal(this)"readonly required>
                    </div>
                    <div class="form-group">
                        <label for="payment_status">สถานะ</label>
                        <select name="payment_status" id="payment_status" required>
                            <option value="Paid" {{ $data->payment_status == 'Paid' ? 'selected' : '' }}>จ่ายแล้ว</option>
                            <option value="Unpaid" {{ $data->payment_status == 'Unpaid' ? 'selected' : '' }}>ค้างจ่าย</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-account-add"></i> อัปเดต
                        </button>
                        <a href="{{ route('dormitorys.billings_month.delete', $data->id) }}" class="btn btn-danger btn-rounded" onclick="return confirm('คุณต้องการลบ {{ $data->name }} หรือไม่ ?')" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ลบ
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
            <i class="mdi mdi-undo"></i> ย้อนกลับ
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
{{-- coding js link --}}
@endsection

@section('js_script')
{{-- coding js script --}}
<script>
    function validateDecimal(input) {
        input.value = input.value.replace(/[^0-9.]/g, '');  // Remove non-numeric characters
        if ((input.value.match(/\./g) || []).length > 1) {  // Prevent multiple decimal points
            input.value = input.value.replace(/\.+$/, '');
        }
    }

    function calculateSum() {
        var monthly_rent = parseFloat(document.getElementById('monthly_rent').value) || 0;
        var meter_total = parseFloat(document.getElementById('meter_total').value) || 0;
        var sum = monthly_rent + meter_total;
        document.getElementById('sum').value = sum.toFixed(2);
        updatePaymentStatus();
    }

    function updatePaymentStatus() {
        var amount = parseFloat(document.getElementById('amount').value) || 0;
        var sum = parseFloat(document.getElementById('sum').value) || 0;
        var payment_status = document.getElementById('payment_status');

        if (amount >= sum) {
            payment_status.value = 'Paid';
        } else {
            payment_status.value = 'Unpaid';
        }
    }

    document.getElementById('meter_total').addEventListener('input', calculateSum);
    document.getElementById('monthly_rent').addEventListener('input', calculateSum);
    document.getElementById('amount').addEventListener('input', updatePaymentStatus);

    // Initial calculation and status update when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        calculateSum();
    });

</script>
@endsection
