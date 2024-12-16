@extends('core::layouts.master')
@php
    use Carbon\Carbon;
    $page_id='dormitory_meters';
    $page_name='จดมิเตอร์';
    $page_title='จดมิเตอร์';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
@endphp
@section('content')
    <div class="text-center" style="font-size: 20px;">จดมิเตอร์</div>
    <div class="mt-1 mb-3 text-center"><a href="{{route('dormitorys.show',$dormitory_code)}}" style="font-size: 26px; color:#262626;"><i class="icon mdi mdi-city-alt"></i> {{$dormitory->name}}</a></div>
    <div class="row mb-2">
        <div class="col">
            <a href="{{route('dormitorys.meters.index', ['code' => $dormitory->code, 'filter' => 'electric'])}}" class="btn btn-lg btn-block meter01 {{ $filter == 'electric' ? 'active' : '' }}" title="แสดงมิเตอร์ค่าไฟฟ้า"><i class="mdi mdi-flash"></i> มิเตอร์ค่าไฟ</a>
        </div>
        <div class="col">
            <a href="{{route('dormitorys.meters.index', ['code' => $dormitory->code, 'filter' => 'water'])}}" class="btn btn-lg btn-block meter02 {{ $filter == 'water' ? 'active' : '' }}" title="แสดงมิเตอร์ค่าน้ำประปา"><i class="mdi mdi-invert-colors"></i> มิเตอร์ค่าน้ำ</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card p-4">
                @forelse ($dormitory->rooms->groupBy('floor') as $floor => $rooms)
                    {{-- @if($filter == 'water')
                    @php
                    $filteredRooms = $filter == 'water'
                        ? $rooms->filter(fn($room) => $room->check_water == 'no'): $rooms;
                    @endphp            
                    @elseif($filter =='person')  
                    @php      
                    $filteredRooms = $filter == 'water'
                        ? $rooms->filter(fn($room) => $room->check_water == 'yes')
                        : $rooms;
                    @endphp   
                    @else 
                    @php $filteredRooms= $rooms; @endphp
                    @endif
                    @if($filteredRooms->isNotEmpty() ) --}}
                        <div class="font-weight-bold">ชั้นที่ {{$floor}}</div>
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th width="13%">ห้อง</th>
                                    <th width="12%">สถานะ</th>
                                    <th width="15%">เลขมิเตอร์เดือนก่อนหน้า</th>
                                    <th width="15%">เลขมิเตอร์เดือนล่าสุด</th>
                                    <th width="12%">หน่วยที่ใช้</th>
                                    <th width="17%">วันที่จดมิเตอร์</th>
                                    <th width="10%">กระบวนการ</th>
                                </tr>
                            </thead>
                            <tbody class="no-border-x">
                                @foreach ($rooms as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ room_status($item->status) }} </td>
                                        <td>
                                            {{ $item->previousMonthMeter ? $item->previousMonthMeter->meter : 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $item->metersLatest && $item->metersLatest->type == $filter ? $item->metersLatest->meter : 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $item->metersLatest && $item->metersLatest->type == $filter && $item->previousMonthMeter ? $item->metersLatest->meter - $item->previousMonthMeter->meter : '0' }}
                                        </td>
                                        <td>
                                            {{ $item->metersLatest && $item->metersLatest->type == $filter ? Carbon::parse($item->metersLatest->created_at)->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('dormitorys.meters.show', ['id_room' => $item->id, 'filter' => $filter]) }}" class="btn btn-warning text-dark" title="ดูเลขประวัติมิเตอร์ของห้อง">จัดการ</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    {{-- @endif --}}
                @empty
                    <div class="text-center mt-2" style="font-size:20px">ไม่พบรายการห้องพัก</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
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
    </style>
@endsection

@section('js_link')
@endsection

@section('js_script')
@endsection
