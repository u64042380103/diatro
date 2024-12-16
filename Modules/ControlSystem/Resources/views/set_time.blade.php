@extends('core::layouts.master')

@php
    $page_id = 'controlsystem';
    $page_name = 'อุปกรณ์';
    $page_title = 'อุปกรณ์';
@endphp

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">{{ $device->name }}
                <th>
                </th>
                <div class="tools">
                    @if($device->set_times == 1)
                    <a href="{{ route('device.create_time', $device->id) }}" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;">
                        <i class="mdi mdi-account-add"></i> เพิ่มการตั้งค่า
                    </a>
                    
                    <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-delete"></i> ลบการตั้งค่า
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <form id="deleteForm" action="{{ route('device.massDelete_time') }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width:4%;">
                                        @if($device->set_times == "1")
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="checkAll">
                                            <label class="custom-control-label" for="checkAll"></label>
                                        </div>
                                        @endif
                                    </th>
                                    <th></th>
                                    <th>เวลา</th>
                                    <th>ระบบการทำงาน</th>
                                    <th>การทำซ้ำ</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- @php
                                        $currentDate = date('H:i');
                                        echo $currentDate;
                                    @endphp --}}
                                @if($device->set_times == "1")
                                @forelse ($setTime as $item)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}" @if ($item->username == 'admin') disabled @endif>
                                                <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>{{ date('H:i', strtotime($item->time)) }}</td>
                                        <td>
                                            @if ($item->on_off == '1')
                                            <span class="text-success">เปิด</span>
                                        @else
                                            <span class="text-danger">ปิด</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $loopValues = explode(",", $item->loop); // use $item->loop
                                            $days = [
                                                '0' => 'ครั้งเดียว',
                                                '1' => 'วันจันทร์',
                                                '2' => 'วันอังคาร',
                                                '3' => 'วันพุธ',
                                                '4' => 'วันพฤหัสบดี',
                                                '5' => 'วันศุกร์',
                                                '6' => 'วันเสาร์',
                                                '7' => 'วันอาทิตย์',
                                                '8' => 'ทุกวัน'
                                            ];
                                        @endphp
                                    
                                        @if(count($loopValues) === 1 && $loopValues[0] == '0')
                                            <span name="loop[]" value="0">ไม่ได้เลือก</span>
                                        @elseif(implode(",", $loopValues) === "1,2,3,4,5,6,7")
                                            <span name="loop[]" value="8">ทุกวัน</span>
                                        @else
                                            @foreach ($days as $id => $day)
                                                @if (in_array($id, $loopValues) && $id != '0')
                                                    <span name="loop[]" value="{{ $id }}">{{ $day }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if ($device->status == '1')
                                            <span class="text-success">กำลังทำงาน</span>
                                        @else
                                            <span class="text-danger">หยุดทำงาน</span>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('device.delete_time', $item->id ) }}" class="btn btn-danger btn-rounded" style="float:right; margin-right: 20px;">
                                            <i class="mdi mdi-delete"></i> ลบ 
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('device.settings_time',$item->id) }}" class="btn btn-warning btn-rounded text-dark">
                                            <i class="mdi mdi-wrench"></i> ตั้งค่าอุปกรณ์
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            ไม่พบรายการ
                                        </td>
                                    </tr>
                                @endforelse
                                @else
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>ไม่มีการตั้งค่าระยะเวลาการทำงาน</td>
                                    <td>ไม่มีการตั้งค่าระยะเวลาการทำงาน</td>
                                    <td>ไม่มีการตั้งค่าระยะเวลาการทำงาน</td>
                                    <td>ไม่มีการตั้งค่าระยะเวลาการทำงาน</td>
                                    <td></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- {{$device->id}} --}}
<form method="GET" action="{{ route('device.change_set_time', $device->id) }}">
    @csrf
    <button type="submit" class="btn btn-rounded text-light" style="float:right; margin-right: 20px; background-color: {{ $device->set_times ? 'red' : 'green' }};">
        <i class="mdi {{ $device->set_times ? 'mdi-alarm' : 'mdi-alarm-off' }}"></i>
        {{ $device->set_times ? 'ไม่ตั้งค่าระยะเวลาการทำงาน' : 'ตั้งค่าระยะเวลาการทำงาน' }}
    </button>
</form>

<a href="{{ $previousUrl }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
    <i class="mdi mdi-undo"></i> ย้อนกลับ
</a>
@endsection

@section('css')
<style>

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

    input,
    select {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }

    .btn-primary {
        margin-right: 10px;
    }
</style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
<script>
    document.getElementById('checkAll').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.custom-control-input:not(#checkAll)');
        checkboxes.forEach(function(checkbox) {
            if (!checkbox.disabled) {
                checkbox.checked = document.getElementById('checkAll').checked;
            }
        });
    });

    document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default form submission behavior

        // Check if any checkboxes are selected
        var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked').length;
        if (!selectedCheckboxCount) {
            alert('กรุณาเลือกกลุ่มผู้ใช้งานที่ต้องการลบ'); // Prompt user to select items
            return;
        }

        // Confirmation prompt before submission
        if (confirm('คุณต้องการลบกลุ่มผู้ใช้งานที่เลือกหรือไม่ ?')) {
            // If confirmed, submit the form with the selected IDs
            document.getElementById('deleteForm').submit();
        }
    });
</script>
@endsection
