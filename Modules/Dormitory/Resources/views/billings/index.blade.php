@extends('core::layouts.master')

@php
    $page_id = 'dormitory_billings';
    $page_name = 'บิล/การเงิน';
    $page_title = 'บิล/การเงิน';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
    <div class="p-5 mt-n5 mb-3 mx-n5 text-dark" style="background-attachment: fixed; height: 250px; background-color: #333333;">
        <div class="text-center" style="font-size: 20px; color: #ffffff;">บิล/การเงิน</div>
        <div class="mt-1 text-center">
            <div class="mt-1 mb-3 text-center">
                <a href="{{route('dormitorys.show', $dormitory_code)}}" style="font-size: 26px; color:#ffffff;">
                    <i class="icon mdi mdi-city-alt"></i> {{$dormitory->name}}
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow " style="margin-top: 15px; border-radius: 15px; background-color: #FBFBFB;">
        <div style="margin-top: -155px;">
            <div class="card p-4">
                <table class="table table-striped table-borderless">
                    <div style="text-align: right;">
                        <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn">
                            <i class="mdi mdi-delete"></i> ลบที่เลือก
                        </a>
                        <a href="" role="button" onclick="create(); return false;" class="btn btn-success btn-rounded" title="เพิ่มบิล">
                            <i class="mdi mdi-plus"></i> เพิ่มบิล
                        </a>
                        
                    </div>
                    
                        <form id="deleteForm" action="{{ route('dormitorys.billings.massDelete') }}" method="POST">
                            @csrf
                            @method('DELETE')
                    <thead>
                        <tr>
                            <th width="4%">
                                <div class="custom-control custom-control-sm custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkAll">
                                    <label class="custom-control-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th width="10%">รหัส</th>
                            <th width="10%">ห้อง</th>
                            <th width="10%">จำนวน</th>
                            <th width="10%">สถานะ</th>
                            <th width="10%">วันที่สร้างบิล</th>
                            <th width="10%">กระบวนการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td>
                                    <div class="custom-control custom-control-sm custom-checkbox">
                                        <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}">
                                        <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->rooms->name}}</td>
                                <td>{{ $billingAmounts[$item->id] }}</td>
                                <td class="{{ $billingAmounts[$item->id] > 0 ? '' : 'text-success' }}">
                                    {{ $billingAmounts[$item->id] > 0 ? 'ค้างจ่าย' : 'จ่ายแล้ว' }}
                                </td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('dormitorys.billings_month.index', ['id' => $item->id]) }}">
                                        <i class="mdi mdi-money" title="ดูบิลที่ค้างชำระ"></i>
                                    </a>
                                    
                                </td>
                            </tr>
                            @empty
                            <div class="text-center mt-2" style="font-size:20px">ไม่พบรายการห้องพัก</div>
                        @endforelse
                    </tbody>
                </form>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade colored-header colored-header-primary" id="modal-create" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">เพิ่มบิล</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                </div>
                    <div class="modal-body">
                        <form action="{{route('dormitorys.billings.insert',$dormitory->code)}}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <label for="room_id">รหัสห้อง</label>
                                <select name="room_id" id="room_id" class="form-control">
                                    <option value="">กรุณาเลือกห้อง</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="modal-body text-right">
                                <button type="submit" class="btn btn-primary btn-rounded">
                                    <i class="mdi mdi-account-add"></i> บันทึก
                                </button>
                            </div>
                        </form>
                    </div>
            </div>
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
    .form-control {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
    .pagination {
        display: flex;
        justify-content: center;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    .page-link {
        color: #c0c0c0;
    }
    .page-link:hover {
        color: #0056b3;
    }
    .black-icon {
        color: rgb(100, 100, 100);
    }
    label {
        font-weight: bold;
    }
    .black-icon {
        color: rgb(100, 100, 100);
    }
</style>
@endsection

@section('js_link')
    {{-- coding js link--}}
@endsection

@section('js_script')
    {{-- coding js script--}}
    <script>
                function create() {
        $("#modal-create").modal("show");
                }
                document.getElementById('checkAll').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('.custom-control-input:not(#checkAll)');
            checkboxes.forEach(function(checkbox) {
                if (!checkbox.disabled) {
                    checkbox.checked = document.getElementById('checkAll').checked;
                }
            });
        });

        document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
            e.preventDefault();
            var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked').length;
            if (!selectedCheckboxCount) {
                alert('กรุณาเลือกรายการที่ต้องการลบ');
                return;
            }
            if (confirm('คุณต้องการลบที่เลือกหรือไม่ ?')) {
                document.getElementById('deleteForm').submit();
            }
        });
    </script>
@endsection
