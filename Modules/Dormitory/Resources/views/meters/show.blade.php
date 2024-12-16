@extends('core::layouts.master')
@php
    $page_id='dormitory_meters';
    $page_name='รายละเอียดมิเตอร์';
    $page_title='รายละเอียดมิเตอร์';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
@endphp

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-header">รายละเอียดมิเตอร์
                    <div class="tools"></div>
                </div>
                <div class="card-body">
                    <div class="table-responsive noSwipe">
                        <form method="POST" action="{{ route('dormitorys.meters.insert',[ 'id_room' => $room->id,'filter' => $filter]) }}">
                            @csrf
                            <td class="text-right">
                                <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{$previousUrl}}">
                                    <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
                                </a>
                                    <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                                        <i class="mdi mdi-account-add"></i> บันทึก
                                    </button>
                            </td>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>                                    
                                        <th style="width:7%;"></th>
                                        <th style="width:13%;">ห้อง</th>
                                        <th style="width:7.5%;">เลขมิเตอร์</th>
                                        <th style="width:7.5%;">หน่วยที่ใช้</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @if ($errors->has('meter'))
                                            <div class="alert alert-danger">
                                                <script>
                                                    alert('{{ $errors->first('meter') }}');
                                                </script>
                                            </div>
                                        @endif
                                        <td></td>
                                        <td>{{ $room->name }} </td>
                                        <td>
                                            <input type="hidden" name="previous_meter" id="previous_meter" 
                                                value="{{ $room->metersLatest ? $room->metersLatest->meter : 0 }}" required>
                                            <input type="number" name="meter" id="meter" value="{{ $room->metersLatest ? $room->metersLatest->meter : 0 }}" 
                                                min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                                            <input type="hidden" name="type" id="type" value="{{$filter}}">
                                        </td>
                                        <td>
                                            <input type="number" name="unit" id="unit" value="0" readonly required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col">
            <a href="{{route('dormitorys.meters.show', ['id_room' => $room->id, 'filter' => 'electric'])}}" class="btn btn-lg btn-block meter01 {{ $filter == 'electric' ? 'active' : '' }}"  title="แสดงมิเตอร์ค่าไฟฟ้า" ><i class="mdi mdi-flash"></i> มิเตอร์ค่าไฟ</a>
        </div>
        <div class="col">
            <a href="{{route('dormitorys.meters.show', ['id_room' => $room->id, 'filter' => 'water'])}}" class="btn btn-lg btn-block meter02 {{ $filter == 'water' ? 'active' : '' }}" 
                title="แสดงมิเตอร์ค่าน้ำประปา"><i class="mdi mdi-invert-colors"></i> มิเตอร์ค่าน้ำ</a>
        </div>
    </div>
    
    <div>
        <div>
            <div class="card card-table">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th style="width:5%;"></th>
                            <th style="width:10%;">ลำดับ</th>
                            <th>รหัส</th>
                            <th style="width:12%;">วันที่จดมิเตอร์</th>
                            <th style="width:26%;">ประวัติเลขมิเตอร์</th>
                            <th style="width:20%;">หน่วยที่ใช้</th>
                            <th style="width:17%;">รวม</th>
                            <th style="width:2%;">
                                <a href="{{ route('dormitorys.meters.edit', ['id' => $room->metersLatest->id, 'filter' => $filter ]) }}" 
                                    class="btn btn-warning text-dark" style="float:right; margin-right: 10px;" title="แก้ไขเลขมิเตอร์ของเดือนก่อน">
                                    แก้ไข</a></th>
                        </tr>
                    </thead>
                    <tbody class="no-border-x">
                        @foreach ($datameter as $index => $data)
                            <tr class="{{ $id_meter == $data->id ? 'highlight' : '' }}">
                                <td class="{{ $id_meter == $data->id ? 'highlight' : '' }}"></td>
                                <td class="{{ $id_meter == $data->id ? 'highlight' : '' }}">
                                    {{ $index + 1 }}
                                </td>
                                <td class="{{ $id_meter == $data->id ? 'highlight' : '' }}">
                                    {{ $data->id }}
                                </td>
                                <td class="{{ $id_meter == $data->id ? 'highlight' : '' }}">
                                    {{ $data->created_at ? $data->created_at->format('d/m/Y') : '' }}
                                </td>
                                <td class="{{ $id_meter == $data->id ? 'highlight' : '' }}">
                                    {{ $data->meter }}
                                </td>
                                <td class="{{ $id_meter == $data->id ? 'highlight' : '' }}">
                                    {{ $data->unit }}
                                </td>
                                <td class=" float  {{ $id_meter == $data->id ? 'highlight' : '' }}">
                                    {{ $data->Total }} บาท/ต่อเดือน
                                </td>
                                <td class="{{ $id_meter == $data->id ? 'highlight' : '' }}">
                                    <a class="btn btn-secondary btn-rounded shadow-sm"  
                                    href="{{ route('dormitorys.meters.edit_payment', ['id' => $data->id, 'filter' => $filter ]) }}">
                                        <i class="mdi mdi-settings"></i> 
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
        .btn-primary {
            margin-right: 10px; /* Adjust the value as needed */
        }
        .meter01 {
            background-color: #FFCDD2;
        }
        .meter02 {
            background-color: #BBDEFB;
        }
        .meter01.active {
            background-color: #ff4c5e;
        }
        .meter02.active {
            background-color: #4ba9fb;
        }
        .highlight {
            background-color: #fa6c6c;
        }
    </style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
<script>
    function calculateUnit() {
        var previousMeter = parseFloat(document.getElementById('previous_meter').value);
        var currentMeter = parseFloat(document.getElementById('meter').value);
        
        console.log('Previous Meter:', previousMeter);
        console.log('Current Meter:', currentMeter);
        
        if (!isNaN(currentMeter) && !isNaN(previousMeter)) {
            var unit = currentMeter - previousMeter;
            document.getElementById('unit').value = unit >= 0 ? unit : 0;
        }
    }

    document.getElementById('meter').addEventListener('input', calculateUnit);
</script>
@endsection
