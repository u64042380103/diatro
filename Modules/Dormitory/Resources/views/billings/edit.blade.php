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
                <form method="POST" action="{{ route('dormitorys.billings.update', $data->id) }}">
                    @csrf
                    @php
                        $monthly_rent = $dataroom->Monthlys_rent->where('status_delete', '!=', 'Disable')->where('payment_status', 'Unpaid')->sum('monthly_rent');
                        $metersWater = $dataroom->metersWater->where('status_delete', '!=', 'Disable')->where('payment_status', 'Unpaid')->sum('Total');
                        $metersElectric = $dataroom->metersElectric->where('status_delete', '!=', 'Disable')->where('payment_status', 'Unpaid')->sum('Total');
                        $meter_total = $metersWater + $metersElectric;
                        $sum = $monthly_rent + $meter_total;
                        $amount = $dataroom->billings->amount ?? 0;
                    @endphp
                    <div class="form-group">
                        <label for="room_id">รหัสห้อง</label>
                        {{ $dataroom->name }}
                    </div>
                    <div class="form-group">
                        <label for="monthly_rent">ค่าเช่า/เดือน</label>
                        <input type="number" name="monthly_rent" id="monthly_rent" class="form-control" value="{{ $monthly_rent }}" readonly required>
                        <input type="hidden" name="meter_id" id="meter_id" value="{{ $data->meter_id }}" required>
                    </div>
                    <div class="form-group">
                        <label for="meter_total">ค่าน้ำ/ไฟ</label>
                        <input type="number" name="meter_total" id="meter_total" class="form-control" value="{{ $meter_total }}" min="0" step="0.01" oninput="validateDecimal(this)" required>
                    </div>
                    <div class="form-group">
                        <label for="sum">ยอดรวมสุทธิ</label>
                        @php
                            $sum = $monthly_rent + $meter_total;
                        @endphp
                        <input type="number" name="sum" id="sum" class="form-control" value="{{ $sum }}" min="0" step="0.01" required>
                    </div>
                    {{-- <div class="form-group">
                        <label for="amount">ชำระแล้ว</label>
                        <input type="number" name="amount" id="amount" class="form-control" 
                        value="{{ $data->amount }}" min="0" step="0.01" oninput="validateDecimal(this)" required>
                    </div> --}}
                    <div class="form-group">
                        <label for="payment_status">สถานะ</label>
                        <select name="payment_status" id="payment_status" required>
                            <option value="Paid" {{ $data->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Unpaid" {{ $data->payment_status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-account-add"></i> อัปเดต
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <a href="{{ route('dormitorys.billings.index', $dormitory_code) }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
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
