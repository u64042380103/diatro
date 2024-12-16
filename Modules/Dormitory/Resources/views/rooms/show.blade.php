@extends('core::layouts.master')

@php                    
    // if (auth()->user()->user_type == 5){
    // $page_id = 'dormitory_dt';
    // $page_name = 'รายละเอียดหอพัก';
    // $page_title = 'รายละเอียดหอพัก';}
    // else{
    $page_id = 'dormitory_rooms';
    $page_name = 'รายละเอียดห้องพัก';
    $page_title = 'รายละเอียดห้องพัก';
// }
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;

@endphp

@section('content')
<div class="row">
    <div class="card-header"> ห้อง {{ $data->name }} 
        @if ($data->status == 'Active')
        <a href="{{ route('dormitorys.comment', $data->id) }}" 
            class="btn btn-warning btn-rounded" title="รายงานหอพัก">
            รายงานหอพัก</a>
        @endif
    </div>
    <form action="{{ route('dormitorys.rooms.edit', $data->id) }}" method="POST">
        @csrf
        <div class="col-lg-9 d-flex flex-column">
            <div class="user-info-list card flex-grow-1">
                <div class="card-header card-header-divider d-flex justify-content-between">
                    <span>รายละเอียดห้องพัก @if($data->extension) คำอธิบาย : {{$data->extension}} @endif</span>
                    {{-- @if (in_array(auth()->user()->user_type, [1,2,3])) --}}
                    {{-- <a href="{{ route('dormitorys.rooms.edit', $data->id) }}" class="btn btn-warning btn-rounded text-dark" title="แก้ไขหอพัก">
                        <i class="mdi mdi-settings"></i>
                    </a> --}}
                    {{-- <a href="" role="button" onclick="changeImg(); return false;">
                        <i class="mdi mdi-settings"></i> ตั้งค่า
                    </a> --}}
                    {{-- @endif --}}
                </div>
                <div class="card-body">
                    <table class="no-border no-strip skills">
                        <head>
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
                        </head>
                        <tbody class="no-border-x no-border-y">
                            <tr>
                                <td class="icon"><span class="mdi mdi-image"></span></td>
                                <td class="item">ภาพประกอบ :

                                            @if ($data->img_room)
                                                @php
                                                    $images = json_decode($data->img_room);
                                                @endphp
                                                @forelse ($images as $image)
                                                    <a href="{{ asset('storage/room_images/' . $image) }}" data-lightbox="room-images">
                                                        <img src="{{ asset('storage/room_images/' . $image) }}" alt="Room Image" style="width: 100px; height: 100px; object-fit: cover; margin-right: 10px;">
                                                    </a>
                                                @empty
                                                    ไม่มีรูปภาพประกอบ
                                                @endforelse
                                            @else
                                                ไม่มีรูปภาพประกอบ
                                            @endif
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi mdi-numeric"></span></td>
                                <td class="item">หมายเลขห้อง : {{ $data->name }}</td>
                                <td class="text-right"></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi mdi-floor-plan"></span></td>
                                <td class="item">ชั้น : {{ $data->floor }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi mdi-air-conditioner"></span></td>
                                <td class="item">ประเภท : {{ $data->room_type == 'fan' ? 'ห้องพัดลม' : 'ห้องแอร์' }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi mdi-check-circle"></span></td>
                                <td class="item">สถานะ : {{ room_status($data->status) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi mdi-ruler"></span></td>
                                <td class="item">ขนาดห้อง : {{ $data->area}} ตร.ม</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi mdi-accounts"></span></td>
                                <td class="item">จำนวนผู้พักอาศัย : {{ $data->resident}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi mdi-accounts"></span></td>
                                <td class="item">จำนวนผู้พักอาศัยเพิ่มเติม : {{ $data->residents_additional}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi "></span></td>
                                <td class="item"><span> เลขมิเตอร์ไฟฟ้าล่าสุด : {{$data_electric_latest->meter}}</span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi "></span></td>
                                <td class="item"><span> เลขมิเตอร์น้ำล่าสุด : {{$data_water_latest->meter}}</span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi "></span></td>
                                <td class="item"><span> ค่าเช่า : {{$data->monthly_rent}}</span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="icon"><span class="mdi "></span></td>
                                <td class="item"><span> มัดจำ : {{$data->deposit}}</span></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-body text-right">
                <a class="btn btn-secondary btn-rounded shadow-sm" href="{{ $previousUrl }}">
                    <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
                </a>
            </div>
        </div>
    </form>    
</div>
<div class="col-lg-9 d-flex flex-column">
    <div class="user-info-list card flex-grow-1">
        <div class="card-header card-header-divider d-flex justify-content-between">
            <span>อุปกรณ์ภายในห้อง</span>
            <a href="{{ route('dormitorys.rooms_details.index', $data->id) }}" class="btn btn-primary btn-rounded" title="ดูรายละเอียดอุปกรณ์">
                <i class="mdi mdi-eye"></i>
            </a>
        </div>
        <div class="card-body">
            <table class="no-border no-strip skills">
                <head>
                    <tr>
                        <th style="width:4%;" ></th>
                        <th>ภาพประกอบ</th>
                        <th>ชื่อ</th>
                        <th>ราคา</th>
                        <th>วันที่ซื้อ</th>
                    </tr>
                </head>
                <body class="no-border-x no-border-y">
                    @forelse($details as $data_details)
                    <tr>
                        <td></td>
                        <td>            
                            @if($data_details->img_details)
                            <a href="{{ asset('storage/'. $data_details->img_details) }}" target="_blank">
                                <img src="{{ asset('storage/'. $data_details->img_details) }}" id="img-preview" style="max-width: 50px; height: 50px;">
                            </a>
                            @else ไม่พบข้อมูล
                            @endif                            
                            {{-- <img src="{{ asset('storage/'. $data_details->img_details) }}" id="img-preview" style="max-width: 50px; height: 50px;"> --}}
                        </td>
                        <td>{{$data_details->name}}</td>
                        <td>{{$data_details->price}}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($data_details->date_buy)->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="5">ไม่พบรายการ</td>
                    </tr>
                    @endforelse
                </body>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-9 d-flex flex-column">
    <div class="user-info-list card flex-grow-1">
        <div class="card-header card-header-divider d-flex justify-content-between">
            <span>สัญญาเช่า</span>
            @if ($data->status != 'Free')
            <a href="{{ route('dormitorys.lease_agreements.show', $data->id) }}" class="btn btn-primary btn-rounded" title="ดูบิลที่ค้างชำระ">
                <i class="mdi mdi-eye"></i>
            </a>
            @endif
        </div>
        <div class="card-body">
            <table class="no-border no-strip skills">
                <head>
                    <tr>
                        <th style="width:4%;" ></th>
                        <th>ภาพประกอบ</th>
                        <th>วันเริ่มเช่า</th>
                        <th>วันสิ้นสุดการเช่า</th>
                        <th>มัดจำ</th>
                    </tr>
                </head>
                <body class="no-border-x no-border-y">
                    @if($data_lease)
                    <tr>
                        <td></td>
                        <td>
                            @if($data_lease->img)
                            <a href="{{ asset('storage/'. $data_lease->img) }}" target="_blank">
                                <img src="{{ asset('storage/'. $data_lease->img) }}" id="img-preview" style="max-width: 50px; height: 50px;">
                            </a>
                            @else ไม่พบข้อมูล
                            @endif
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($data_lease->startDate)->format('d/m/Y') }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($data_lease->endDate)->format('d/m/Y') }}
                        </td>
                        <td>
                            {{$data_lease->deposit}}
                            (<span style="color: {{ $data_lease->payment_status == 'Paid' ? 'green' : 'red' }};">
                                {{$data_lease->payment_status == 'Paid' ? 'จ่ายแล้ว' : 'ค้างจ่าย'}}
                            </span>)
                        </td>
                    </tr>
                    @else 
                    <tr>
                        <td class="text-center" colspan="5">ไม่พบรายการ</td>
                    </tr>
                    @endif
                </body>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-9 d-flex flex-column">
    <div class="user-info-list card flex-grow-1">
        <div class="card-header card-header-divider d-flex justify-content-between">
            <span>มิเตอร์ค่าไฟ</span>
            <div>
                <span>
                    ค่าไฟที่ค้าง = {{ $total_electric }} บาท
                </span>
                <br>
                <span> เลขมิเตอร์ล่าสุด : {{$data_electric_latest->meter}}</span>
            </div>
        </div>
        <div class="card-body">
            <table class="no-border no-strip skills">
                <head>
                    <tr>
                        <th style="width:4%;" ></th>
                        <th style="width:15%;">รหัส</th>
                        <th style="width:25%;">วันที่จดมิเตอร์</th>
                        <th style="width:20%;">ประวัติเลขมิเตอร์</th>
                        <th style="width:12%;">หน่วยที่ใช้	</th>
                        <th style="width:22%;">รวม</th>
                    </tr>
                </head>
                <body class="no-border-x no-border-y">
                    @forelse($meter_electric as $item_electric)
                    <tr>
                        <td></td>
                        <td>{{$item_electric->id}}</td>
                        <td>
                        {{ $item_electric->created_at ? $item_electric->created_at->format('d/m/Y') : '' }}
                        </td>
                        <td>{{$item_electric->meter}}</td>
                        <td>{{$item_electric->unit}}</td>
                        <td>{{$item_electric->Total}}
                        <a href="{{route('dormitorys.meters.show',['id_room' => $data->id, 'filter' => $item_electric->type , 'id_meter' => $item_electric->id , 'room_id' => 1]) }}"
                                        style="margin-right: 20px;" title="ดูค่ามิเตอร์นี้">
                            (<span style="color: {{ $item_electric->payment_status == 'Paid' ? 'green' : 'red' }};">
                                {{$item_electric->payment_status == 'Paid' ? 'จ่ายแล้ว' : 'ค้างจ่าย'}}
                            </span>)
                        </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="6">ไม่พบรายการ</td>
                    </tr>
                    @endforelse
                </body>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-9 d-flex flex-column">
    <div class="user-info-list card flex-grow-1">
        <div class="card-header card-header-divider d-flex justify-content-between">
            <span>มิเตอร์ค่าน้ำ</span>
            <div>
                <span>
                    ค่าน้ำที่ค้าง =  {{$total_water}} บาท
                </span>
                <br>
                <span> เลขมิเตอร์ล่าสุด : {{$data_water_latest->meter}}</span>
            </div>
        </div>
        <div class="card-body">
            <table class="no-border no-strip skills">
                <head>
                    <tr>
                        <th style="width:4%;" ></th>
                        <th style="width:15%;">รหัส</th>
                        <th style="width:25%;">วันที่จดมิเตอร์</th>
                        <th style="width:20%;">ประวัติเลขมิเตอร์</th>
                        <th style="width:12%;">หน่วยที่ใช้	</th>
                        <th style="width:22%;">รวม</th>
                    </tr>
                </head>
                <body class="no-border-x no-border-y">
                    @forelse($meter_water as $item_water)
                    <tr>
                        <td></td>
                        <td>{{$item_water->id}}</td>
                        <td>
                            {{ $item_water->created_at ? $item_water->created_at->format('d/m/Y') : '' }}
                        </td>
                        <td>{{$item_water->meter}}</td>
                        <td>{{$item_water->unit}}</td>
                        <td>{{$item_water->Total}}
                        <a href="{{route('dormitorys.meters.show',['id_room' => $data->id, 'filter' => $item_water->type , 'id_meter' => $item_water->id , 'room_id' => 1]) }}"
                                        style="margin-right: 20px;" title="ดูค่ามิเตอร์นี้">
                            (<span style="color: {{ $item_water->payment_status == 'Paid' ? 'green' : 'red' }};">
                                {{$item_water->payment_status == 'Paid' ? 'จ่ายแล้ว' : 'ค้างจ่าย'}}
                            </span>)
                        </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="6">ไม่พบรายการ</td>
                    </tr>
                    @endforelse
                </body>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-9 d-flex flex-column">
    <div class="user-info-list card flex-grow-1">
        <div class="card-header card-header-divider d-flex justify-content-between">
            <span>ค่าเช่า</span>
            ค่าเช่าที่ค้าง =  {{$total_arrears}} บาท
        </div>
        <div class="card-body">
            <table class="no-border no-strip skills">
                <head>
                    <tr>
                        <th style="width:4%;" ></th>
                        <th style="width:20%;">รหัส</th>
                        <th>วันที่สร้างบิล</th>
                        <th>ราคาค่าเช่า</th>
                    </tr>
                </head>
                <body class="no-border-x no-border-y">
                    @forelse($data_month as $item_arrears)
                    <tr>
                        <td></td>
                        <td>{{$item_arrears->id}}</td>
                        <td>
                            {{ $item_arrears->created_at ? $item_arrears->created_at->format('d/m/Y') : '' }}
                        </td>
                        <td>{{$item_arrears->monthly_rent}}
                        <a href="{{route('dormitorys.monthly_rent.index',['id' => $data->id, 'month_id' => $item_arrears->id , 'room_id' => $data->id]) }}"
                            style="margin-right: 20px;" title="ดูค่าเช่านี้">
                            (<span style="color: {{ $item_arrears->payment_status == 'Paid' ? 'green' : 'red' }};">
                                {{$item_arrears->payment_status == 'Paid' ? 'จ่ายแล้ว' : 'ค้างจ่าย'}}
                            </span>)
                        </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="4">ไม่พบรายการ</td>
                    </tr>
                    @endforelse
                </body>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-9 d-flex flex-column">
    <div class="user-info-list card flex-grow-1">
        <div class="card-header card-header-divider d-flex justify-content-between">
            <span>ประวัติผู้เช่าห้องพัก</span>
        </div>
        <div class="card-body">
            <table class="no-border no-strip skills">
                <head>
                    <tr>
                        <th style="width:4%;" ></th>
                        <th>ชื่อ</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>เบอร์โทร</th>
                        <th>วันเริ่มเช่า</th>
                        <th>วันสิ้นสุดการเช่า</th>
                    </tr>
                </head>
                <body class="no-border-x no-border-y">
                    @forelse($data_usered as $usered)
                    <tr>
                        <td></td>
                        <td>{{$usered->name}}</td>
                        <td>{{$usered->username}}</td>
                        <td>{{$usered->phone}}</td>
                        <td>
                            {{ $usered->created_at ? $usered->created_at->format('d/m/Y') : '' }}
                        </td>
                        <td>
                            @if($usered->date_end)
                                {{ $usered->date_end ? $usered->date_end->format('d/m/Y') : '' }}
                            @else
                                ยังไม่มีกำหนด
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="6">ไม่พบรายการ</td>
                    </tr>
                    @endforelse
                </body>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-9 d-flex flex-column">
    <div class="user-info-list card flex-grow-1">
        <div class="card-header card-header-divider d-flex justify-content-between">
            <span>รายชื่อผู้เช่าห้องพัก</span>
        </div>
        <div class="card-body">
            <table class="no-border no-strip skills">
                <head>
                    <tr>
                        <th style="width:4%;" ></th>
                        <th>ชื่อ</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>เบอร์โทร</th>
                        <th>วันเริ่มเช่า</th>
                    </tr>
                </head>
                <body class="no-border-x no-border-y">
                    @forelse($data_user as $user)
                    <tr>
                        <td></td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->username}}</td>
                        <td>{{$user->phone}}</td>
                        <td>
                        {{ $user->created_at ? $user->created_at->format('d/m/Y') : '' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="6">ไม่พบรายการ</td>
                    </tr>
                    @endforelse
                </body>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-9 d-flex flex-column">
    <div class="user-info-list card flex-grow-1">
        <div class="card-header card-header-divider d-flex justify-content-between">
            <span>บิลการเงิน</span>
        </div>
        <div class="card-body">
            <table class="no-border no-strip skills">
                <head>
                    <tr>
                        <th style="width:2%;" ></th>
                        <th width="10%">รหัส</th>
                        <th width="10%">จำนวน</th>
                        <th width="10%">สถานะ</th>
                        <th width="10%">วันที่สร้างบิล</th>
                        <th width="1%"></th>
                        <th width="3%"></th>
                    </tr>
                </head>
                <body class="no-border-x no-border-y">
                    @forelse ($data_Billings as $data_Billing)
                    <tr>
                        <td></td>
                        <td>{{$data_Billing->id}}</td>
                        <td>{{ $data_Billings_month[$data_Billing->id] }}</td>
                        <td>
                            <span style="color: {{ $data_Billing->payment_status == 'Paid' ? 'green' : 'red' }};">
                            {{$data_Billing->payment_status == 'Paid' ? 'จ่ายแล้ว' : 'ค้างจ่าย'}}
                            </span>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($data_Billing->created_at)->format('d/m/Y') }}
                        </td>
                        <td>
                            <a href="{{ route('dormitorys.billings_month.index', ['id' => $data_Billing->id]) }}">
                                <i class="mdi mdi-money" title="ดูบิลที่ค้างชำระ"></i>
                            </a>
                        </td>
                        <td></td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="6">ไม่พบรายการ</td>
                    </tr>
                    @endforelse
                </body>
            </table>
        </div>
    </div>
</div>
<div class="modal fade colored-header colored-header-primary" id="modal-changeImg" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">ตั้งค่า</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
            <form method="POST" action="{{ route('dormitorys.rooms.update', $data->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <label for="name">หมายเลขห้อง</label>
                    <input type="text" name="name" id="name" value="{{ $data->name }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                    @if ($errors->has('name'))
                        <div class="alert alert-danger">
                            <script>
                                alert('{{ $errors->first('name') }}');
                            </script>
                        </div>
                    @endif
                </div>
                <div class="modal-body">
                    <label for="floor">ชั้น</label>
                    <input type="text" name="floor" id="floor" value="{{ $data->floor }}" maxlength="50" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                </div>
                <div class="modal-body">
                    <label for="resident">จำนวนผู้พักอาศัย</label>
                    <input type="number" name="resident" id="resident" value="{{ $total_user }}" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" readonly>
                </div>
                <div class="modal-body">
                    <label for="residents_additional">จำนวนผู้พักอาศัยเพิ่มเติม</label>
                    <input type="number" name="residents_additional" id="residents_additional" value="{{ $data->residents_additional }}" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                </div>
                <div class="modal-body">
                    <label for="room_type">ประเภท</label>
                    <select name="room_type" id="room_type" required>
                        <option value="fan" {{ $data->room_type == 'fan' ? 'selected' : '' }}>พัดลม</option>
                        <option value="air" {{ $data->room_type == 'air' ? 'selected' : '' }}>แอร์</option>
                    </select>
                </div>
                <div class="modal-body">
                    <label for="status">สถานะ</label>
                    <select name="status" id="status" required>
                        <option value="Active" {{ $data->status == 'Active' ? 'selected' : '' }}>ใช้งาน</option>
                        <option value="Free" {{ $data->status == 'Free' ? 'selected' : '' }}>ห้องว่าง</option>
                        <option value="Disable" {{ $data->status == 'Disable' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                        <option value="Booking" {{ $data->status == 'Booking' ? 'selected' : '' }}>จอง</option>
                        <option value="MA" {{ $data->status == 'MA' ? 'selected' : '' }}>ปิดปรับปรุง</option>
                    </select>
                </div>
                <div class="modal-body">
                    <label for="area"> ขนาดห้อง</label>
                    <input type="number" name="area" id="area" value="{{ $data->area }}" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                    <span>ตร.ม</span>
                </div>
                <div class="modal-body">
                    <label for="water"> ราคาเหมาค่าน้ำ</label>
                    <input type="number" name="water" id="water" value="{{ $data->water }}" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                </div>
                <div class="modal-body">
                    <label for="check_water">เหมาจ่าย</label>
                    <select name="check_water" id="check_water" required>
                        <option value="yes" {{ $data->check_water == 'yes' ? 'selected' : '' }}>เหมาจ่าย</option>
                        <option value="no" {{ $data->check_water == 'no' ? 'selected' : '' }}>ไม่เหมาจ่าย</option>
                    </select>
                    <div id="person_group" style="display: {{ $data->check_water == 'yes' ? 'block' : 'none' }};">
                        <label for="check_water">รูปแบบเหมาจ่าย</label>
                        <select name="person" id="person" required>
                            <option value="per_person" {{ $data->person == 'per_person' ? 'selected' : '' }} title="ควณนวนจากจำนวนผู้อยู่อาศัย" >รายคน</option>
                            <option value="all_person" {{ $data->person == 'all_person' ? 'selected' : '' }} title="ควณนวนจากราคาเหมาจ่ายโดยตรง">ทั้งห้อง</option>
                        </select>
                    </div>
                </div>
                <div class="modal-body">
                    <label for="monthly_rent">ค่าเช่า</label>
                    <input type="number" name="monthly_rent" id="monthly_rent" value="{{$data->monthly_rent}}" min="0" placeholder="ค่าเช่า" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                    <span>บาท</span>
                </div>
                <div class="modal-body">
                    <label for="deposit">ค่ามัดจำ</label>
                    <input type="number" name="deposit" id="deposit" value="{{$data->deposit}}" min="0" placeholder="ค่ามัดจำ" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                    <span>บาท</span>
                </div>
                <div class="modal-body">
                    <label for="img_room">รูปภาพประกอบ</label>
                    <div id="img_room-preview-container"></div>
                    <input class="inputfile" id="img_room" type="file" name="img_room[]" accept="image/*" multiple onchange="previewImage(event)">
                    <label class="btn-secondary" id="btnImg" for="img_room"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพเพื่อแก้ไข</span></label>
                </div>
                <div class="modal-body">
                    <label for="extension">คำอธิบาย</label>
                    <input type="test" name="extension" id="extension" value="{{$data->extension}}" class="large-input" placeholder="กรุณาใส่คำอธิบาย" >
                </div>
                <div class="modal-body text-right">
                    <label></label>
                    <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-account-add"></i> อัปเดต
                    </button>
                    {{-- @if (in_array(auth()->user()->user_type, [1,2]))  --}}
                    <a href="{{ route('dormitorys.rooms.delete', $data->id) }}" class="btn btn-danger btn-rounded" onclick="return confirm('คุณต้องการลบ {{ $data->name }} หรือไม่ ?')" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-delete"></i> ลบ
                    </a>
                    {{-- @endif --}}
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js_link')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
    .modal-body {
        margin-bottom: 10px;
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
        padding: 10px;
    }
    label {
        font-weight: bold;
    }
    .custom-dropdown {
        margin-left: 450px;
        margin-right: 0;
    }
    .black-icon {
        color: rgb(100, 100, 100);
    }
    </style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const checkWater = document.getElementById('check_water');
        const personGroup = document.getElementById('person_group');

        checkWater.addEventListener('change', function() {
            if (checkWater.value === 'yes') {
                personGroup.style.display = 'block';
            } else {
                personGroup.style.display = 'none';
            }
        });
    });

    function changeImg() {
        $("#modal-changeImg").modal("show");
    }
    window.addEventListener('load', function() {
        var roomDisplay = document.querySelector('.room-display-container');
        var topRight = document.querySelector('.top-right');
        topRight.style.height = roomyDisplay.offsetHeight + 'px';
    });

    function previewImage(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('img_room-preview-container');
    previewContainer.innerHTML = ''; // ล้างตัวอย่างเก่าก่อนเพิ่มใหม่

    // สร้างอาร์เรย์เพื่อเก็บไฟล์ที่เลือก
    const fileArray = Array.from(files);

    fileArray.forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function(e) {
            // สร้างองค์ประกอบ div สำหรับเก็บรูปภาพและปุ่มลบ
            const imgWrapper = document.createElement('div');
            imgWrapper.style.display = 'inline-block';
            imgWrapper.style.position = 'relative';
            imgWrapper.style.marginRight = '10px';

            const img = document.createElement('img'); // เปลี่ยนจาก img_room เป็น img
            img.src = e.target.result;
            img.style.width = '50px';
            img.style.height = '50px';
            imgWrapper.appendChild(img);

            // ปุ่มลบ
            const deleteButton = document.createElement('button');
            deleteButton.innerHTML = 'ลบ';
            deleteButton.style.position = 'absolute';
            deleteButton.style.top = '0';
            deleteButton.style.right = '0';
            deleteButton.style.background = 'red';
            deleteButton.style.color = 'white';
            deleteButton.style.border = 'none';
            deleteButton.style.cursor = 'pointer';

            // เมื่อคลิกปุ่มลบ
            deleteButton.addEventListener('click', function() {
                fileArray.splice(index, 1); // ลบไฟล์ออกจากอาร์เรย์
                imgWrapper.remove(); // ลบรูปภาพออกจากหน้าจอ

                // อัปเดตไฟล์ใน input element
                const dataTransfer = new DataTransfer();
                fileArray.forEach(file => dataTransfer.items.add(file));
                document.getElementById('img_room').files = dataTransfer.files;

                // ถ้าไม่มีรูปเหลือ ให้รีเซ็ตปุ่มอัปโหลด
                if (fileArray.length === 0) {
                    document.getElementById("nameImg").textContent = 'เพิ่มรูปภาพ';
                    document.getElementById("btnImg").classList.remove('btn-success');
                }
            });

            imgWrapper.appendChild(deleteButton);
            previewContainer.appendChild(imgWrapper);
        }

        reader.readAsDataURL(file); // อ่านไฟล์เพื่อแสดงผล
    });

    document.getElementById("nameImg").textContent = 'เลือกรูปภาพสำเร็จ';
    document.getElementById("btnImg").classList.add('btn-success');
}

</script>
@endsection
