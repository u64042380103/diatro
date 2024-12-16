@extends('core::layouts.master')

@php
    $page_id = 'dormitory_billings';
    $page_name = 'บิล/การเงินรายเดือน';
    $page_title = 'บิล/การเงินรายเดือน';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
<div class="p-5 mt-n5 mb-3 mx-n5 text-dark" style="background-attachment: fixed; height: 250px; background-color: #333333;">
    <div class="text-center" style="font-size: 20px; color: #ffffff;">บิล/การเงิน ห้อง {{$data_room->name}}</div>
    <div class="mt-1 text-center">
        <div class="mt-1 mb-3 text-center">
            <a href="{{route('dormitorys.show', $dormitory_code)}}" style="font-size: 26px; color:#ffffff;">
                <i class="icon mdi mdi-city-alt"></i> {{$dormitory->name}}
            </a>
        </div>
    </div>
</div>
<form method="POST" action="{{route('dormitorys.billings_month.insert_before',$data_billings->id)}}" enctype="multipart/form-data">
    @csrf
<div class="card shadow" style="margin-top: 15px; border-radius: 15px; background-color: #FBFBFB;">
    <div style="margin-top: -155px;">
        <div class="card p-4">
            <div class="row">
                <div class="d-flex" style="width: 100%;">
                    <div style="margin-left: auto; margin-right: 20px;">
                        <label for="data_type">สถานะ</label>
                        <select name="data_type" id="data_type" required>
                            <option value="">กรุณาเลือกสถานะ</option>
                            <option value="meter">มิเตอร์</option>
                            <option value="month">ค่าเช่า</option>
                            <option value="Deposit">มัดจำ</option>
                            @if($data_room->check_water === 'yes')
                                <option value="water">ค่าน้ำแบบเหมา</option>
                            @endif
                            <option value="other">อื่นๆ</option>
                        </select>
                        
                        <button type="button" id="add-billing" class="btn btn-success btn-rounded">
                            <i class="mdi mdi-plus"></i> เพิ่ม...
                        </button>
                    </div>
                </div>
            </div>
            <div id="billing-tables-container" class="mt-4">
                <!-- New tables will be appended here -->
            </div>
        </div>
    </div>
    <div class="mt-2">
        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
            <i class="mdi mdi-account-add"></i> บันทึก
        </button>
        <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ $previousUrl }}">
            <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
        </a>
    </div>
</div>
</form>
@endsection

@section('css')
<style>
    .black-icon {
        color: rgb(100, 100, 100);
    }
    .btn-large {
        font-size: 1.25rem;
        padding: 1rem;
    }
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
    .large-input {
        width: 100%;
    }
    .table-no-borders {
        border-collapse: collapse;
    }
    .table-no-borders th,
    .table-no-borders td {
        border: none;
    }
</style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataTypeSelect = document.getElementById('data_type');
        const addBillingButton = document.getElementById('add-billing');
        const billingTablesContainer = document.getElementById('billing-tables-container');

        const dataMeters = @json($data_meter);  // Pass PHP data to JavaScript
        const dataMonth = @json($data_month);  // Pass PHP data to JavaScript
        const dataDeposit = @json($data_Lease);  // Pass PHP data to JavaScript
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        addBillingButton.addEventListener('click', function(event) {
            event.preventDefault();
            const dataType = dataTypeSelect.value;
            if (dataType === 'meter') {
                const newTable = document.createElement('table');
                newTable.classList.add('table', 'table-bordered', 'mt-4','table-no-borders','table-striped','table-borderless');
                newTable.innerHTML = `
                    <thead>
                        <tr>
                            <th>ประเภท</th>
                            <th>มิเตอร์</th>
                            <th>รวม 
                                <div style="float:right; margin-right: 20px;">
                                    <button type="button" class="btn btn-danger btn-rounded delete-billing">
                                        <i class="mdi mdi-delete"></i> ลบ</button>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="no-border-x">
                        <tr>
                            <td>
                                <select name="type[]" class="data-type-select form-control">
                                    <option value="">กรุณาเลือกชนิด</option>
                                    <option value="electric">ค่าไฟ</option>
                                    @if($data_room->check_water === 'no')
                                        <option value="water">ค่าน้ำ</option>
                                    @endif
                                </select>
                            </td>
                            <td>
                                <select name="data_id[]" class="data-id-select form-control" required>
                                    <option value="">กรุณารหัสมิเตอร์</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="amount[]" class="total-input form-control" readonly>
                                <input type="hidden" name="payment_status[]" class="payment-input">
                                <input type="hidden" name="data_type[]" value="meter">
                            </td>
                        </tr>
                    </tbody>
                `;
                billingTablesContainer.appendChild(newTable);

                const newDataTypeSelect = newTable.querySelector('.data-type-select');
                const newDataIdSelect = newTable.querySelector('.data-id-select');
                const totalInput = newTable.querySelector('.total-input');
                const paymentInput = newTable.querySelector('.payment-input');
                const deleteButton = newTable.querySelector('.delete-billing');

                deleteButton.addEventListener('click', function() {
                    newTable.remove();
                });

                newDataTypeSelect.addEventListener('change', function() {
                    const selectedType = newDataTypeSelect.value;
                    newDataIdSelect.innerHTML = '<option value="">กรุณารหัสมิเตอร์</option>';
                    const filteredMeters = dataMeters.filter(meter => meter.type === selectedType);
                    filteredMeters.forEach(meter => {
                        const option = document.createElement('option');
                        option.value = meter.id;
                        option.textContent = `${meter.id} (${formatDate(meter.created_at)})`;
                        newDataIdSelect.appendChild(option);
                    });
                });

                newDataIdSelect.addEventListener('change', function() {
                    const selectedId = newDataIdSelect.value;
                    const selectedMeter = dataMeters.find(meter => meter.id == selectedId);
                    if (selectedMeter) {
                        totalInput.value = selectedMeter.Total;
                        paymentInput.value = selectedMeter.payment_status;
                    }
                });
            } else if (dataType === 'month') {
                const newTable = document.createElement('table');
                newTable.classList.add('table', 'table-bordered', 'mt-4','table-no-borders','table-striped','table-borderless');
                newTable.innerHTML = `
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ค่าเช่า
                                <div style="float:right; margin-right: 20px;">
                                    <button type="button" class="btn btn-danger btn-rounded delete-billing">
                                        <i class="mdi mdi-delete"></i> ลบ</button>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="no-border-x">
                        <tr>
                            <td>
                                <select name="data_id[]" class="data-id-select form-control" required>
                                    <option value="">กรุณารหัสสัญญาเช่า</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="amount[]" class="monthly_rent-input form-control" readonly>
                                <input type="hidden" name="payment_status[]" class="payment-input">
                                <input type="hidden" name="data_type[]" value="month">
                            </td>
                        </tr>
                    </tbody>
                `;
                billingTablesContainer.appendChild(newTable);

                const newDataIdSelect = newTable.querySelector('.data-id-select');
                const monthlyRentInput = newTable.querySelector('.monthly_rent-input');
                const paymentInput = newTable.querySelector('.payment-input');
                const deleteButton = newTable.querySelector('.delete-billing');

                deleteButton.addEventListener('click', function() {
                    newTable.remove();
                });

                newDataIdSelect.addEventListener('change', function() {
                    const selectedId = newDataIdSelect.value;
                    const selectedMonth = dataMonth.find(Month => Month.id == selectedId);
                    if (selectedMonth) {
                        monthlyRentInput.value = selectedMonth.monthly_rent;
                        paymentInput.value = selectedMonth.payment_status;
                    }
                });

                newDataIdSelect.innerHTML = '<option value="">กรุณารหัสสัญญาเช่า</option>';
                dataMonth.forEach(Month => {
                    const option = document.createElement('option');
                    option.value = Month.id;
                    option.textContent = `${Month.id} (${formatDate(Month.created_at)})`;
                    newDataIdSelect.appendChild(option);
                });
            } else if (dataType === 'Deposit') {
    const newTable = document.createElement('table');
    newTable.classList.add('table', 'table-bordered', 'mt-4','table-no-borders','table-striped','table-borderless');
    newTable.innerHTML = `
        <thead>
            <tr>
                <th>รหัส</th>
                <th>มัดจำ
                    <div style="float:right; margin-right: 20px;">
                        <button type="button" class="btn btn-danger btn-rounded delete-billing">
                            <i class="mdi mdi-delete"></i> ลบ</button>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody class="no-border-x">
            <tr>
                <td>
                    <select name="data_id[]" class="data-id-select form-control" required>
                        <option value="">กรุณารหัสสัญญาเช่า</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="amount[]" class="deposit-input form-control" readonly>
                    <input type="hidden" name="payment_status[]" class="payment-input">
                    <input type="hidden" name="data_type[]" value="Deposit">
                </td>
            </tr>
        </tbody>
    `;
    billingTablesContainer.appendChild(newTable);

    const newDataIdSelect = newTable.querySelector('.data-id-select');
    const depositInput = newTable.querySelector('.deposit-input');
    const paymentInput = newTable.querySelector('.payment-input');
    const deleteButton = newTable.querySelector('.delete-billing');

    deleteButton.addEventListener('click', function() {
        newTable.remove();
    });

    newDataIdSelect.addEventListener('change', function() {
        const selectedId = newDataIdSelect.value;
        const selectedLease = dataDeposit.find(lease => lease.id == selectedId);
        if (selectedLease) {
            depositInput.value = selectedLease.deposit; // แสดงค่ามัดจำใน input
            paymentInput.value = selectedLease.payment_status; // แสดงสถานะการจ่ายเงิน
        }
    });

    newDataIdSelect.innerHTML = '<option value="">กรุณารหัสสัญญาเช่า</option>';
    dataDeposit.forEach(lease => {
        const option = document.createElement('option');
        option.value = lease.id;
        option.textContent = `${lease.id} (${formatDate(lease.created_at)})`;
        newDataIdSelect.appendChild(option);
    });
}
    else if (dataType === 'other') {
                const newTable = document.createElement('table');
                newTable.classList.add('table', 'table-bordered', 'mt-4', 'table-no-borders', 'table-striped', 'table-borderless');
                newTable.innerHTML = `
                <thead>
                    <tr>
                        <th>รหัส</th>
                        <th>จำนวน
                            <div style="float:right; margin-right: 20px;">
                                <button type="button" class="btn btn-danger btn-rounded delete-billing">
                                    <i class="mdi mdi-delete"></i> ลบ</button>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="no-border-x">
                    <tr>
                        <td>
                            <input type="number" name="data_id[]" class="form-control unique-id" value="{{$data_room->id}}" readonly>
                        </td>
                        <td>
                            <input type="number" name="amount[]" class="form-control">
                            <input type="hidden" name="payment_status[]" value="Unpaid">
                            <input type="hidden" name="data_type[]" value="other">
                        </td>
                    </tr>
                </tbody>
            `;
                    
                billingTablesContainer.appendChild(newTable);
                const deleteButton = newTable.querySelector('.delete-billing');
                deleteButton.addEventListener('click', function() {
                    newTable.remove();
                    
                });

            }else if (dataType === 'water') {
                const newTable = document.createElement('table');
                newTable.classList.add('table', 'table-bordered', 'mt-4', 'table-no-borders', 'table-striped', 'table-borderless');
                newTable.innerHTML = `
                <thead>
                    <tr>
                        <th>รหัส</th>
                        <th>จำนวน
                            <div style="float:right; margin-right: 20px;">
                                <button type="button" class="btn btn-danger btn-rounded delete-billing">
                                    <i class="mdi mdi-delete"></i>ลบ</button>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="no-border-x">
                    <tr>
                        <td>
                            <input type="number" name="data_id[]" class="form-control unique-id" value="{{$data_room->id}}" readonly>
                        </td>
                        <td>
                            @if($data_room->person == 'per_person')
                                <input type="number" name="amount[]" value="{{ $data_room->water * ($data_room->resident + $data_room->residents_additional) }}" class="form-control" readonly>
                            @else
                                <input type="number" name="amount[]" value="{{ $data_room->water }}" class="form-control" readonly>
                            @endif
                            <input type="hidden" name="payment_status[]" value="Unpaid">
                            <input type="hidden" name="data_type[]" value="water">
                        </td>
                    </tr>
                </tbody>
            `;
                    
                billingTablesContainer.appendChild(newTable);
                const deleteButton = newTable.querySelector('.delete-billing');
                deleteButton.addEventListener('click', function() {
                    newTable.remove();
                    
                });

            }
            submitButton.addEventListener('click', function(event) {
        const billingTables = billingTablesContainer.querySelectorAll('table');
        if (billingTables.length === 0) {
            event.preventDefault(); // Prevent form submission
            alert('กรุณาเพิ่มข้อมูลบิลก่อนทำการบันทึก');
        }
    });
        });
    });
</script>
@endsection
