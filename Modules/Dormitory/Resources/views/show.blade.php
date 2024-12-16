@extends('core::layouts.master')

@php
    $page_id = 'dormitory_dt';
    $page_name = 'รายละเอียดหอพัก';
    $page_title = 'รายละเอียดหอพัก';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
<div class="row">
    <div class="main-content container-fluid">
        <div class="dormitory-profile">
            <div class="row">
                <div class="col-lg-6 position-relative">
                    <div class="user-info-list card flex-grow-1">
                        <div class="card-header card-header-divider d-flex justify-content-between">
                            <span>รายละเอียดหอพัก</span>
                            {{-- @if(in_array(auth()->user()->user_type, [1, 2])) --}}
                                {{-- <a href="{{ route('dormitorys.edit', $dormitory->code) }}" class="btn btn-rounded text-dark" title="แก้ไขหอพัก">
                                    <i class="mdi mdi-settings"></i>
                                </a> --}}
                                <a href="" role="button" onclick="edit(); return false;" class="btn btn-rounded text-dark" title="แก้ไขหอพัก">
                                    <i class="mdi mdi-settings"></i></a>
                            {{-- @endif --}}
                        </div>
                        <div class="card-body">
                            <table class="no-border no-strip skills">
                                <head>
                                    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
                                </head>
                                <tbody class="no-border-x no-border-y">
                                    <tr>
                                        <td class="icon" style="width:1%;"><span class="mdi mdi-city"></span></td> <!-- ไอคอนหอพัก -->
                                        <td class="item" style="width:5%;">ชื่อหอพัก</td>
                                        <td style="width:15%;">{{ $dormitory->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-globe-alt"></span></td> <!-- ไอคอนแผนที่ -->
                                        <td class="item">ที่อยู่</td>
                                        <td>{{ $dormitory->address }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-pin"></span></td> <!-- ไอคอนเว็บ -->
                                        <td class="item">Website</td>
                                        <td>{{ $dormitory->web }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-money"></span></td> <!-- ไอคอนเงิน -->
                                        <td class="item">ค่าเช่า</td>
                                        <td>{{ $dormitory->Rent_min }}-{{ $dormitory->Rent_max }} บาท/เดือน</td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-phone"></span></td> <!-- ไอคอนโทรศัพท์ -->
                                        <td class="item">เบอร์โทร</td>
                                        <td>{{ $dormitory->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-map"></span></td> <!-- ไอคอนข้อความ -->
                                        <td class="item">คำอธิบาย</td>
                                        <td>{{ $dormitory->description }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-map"></span></td> <!-- ไอคอนข้อความ -->
                                        <td class="item">สถานที่ใกล้เคียง</td>
                                        <td>{{ $dormitory->Nearby }}
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-image"></span></td> <!-- ไอคอนรูปภาพ -->
                                        <td class="item">รูปภาพประกอบ</td>
                                        <td>
                                            @if ($dormitory->img)
                                                @php
                                                    $images = json_decode($dormitory->img);
                                                @endphp
                                                @foreach ($images as $image)
                                                    <a href="{{ asset('storage/Dormitory_images/' . $image) }}" data-lightbox="dormitory-images">
                                                        <img src="{{ asset('storage/Dormitory_images/' . $image) }}" alt="Dormitory Image" style="width: 100px; height: 100px; object-fit: cover; margin-right: 10px;">
                                                    </a>
                                                @endforeach
                                            @else
                                                ไม่มีรูปภาพประกอบ
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-door"></span></td> <!-- ไอคอนห้องพัก -->
                                        <td class="item">การเลี้ยงสัตว์</td>
                                        <td>
                                            {{ $dormitory->animal == 'yes' ? 'อนุญาตให้เลี้ยงได้' : 'ไม่อนุญาตให้เลี้ยง' }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-door"></span></td> <!-- ไอคอนห้องพัก -->
                                        <td class="item">จำนวนห้องพักที่กำหนด </td>
                                        <td> {{ $dormitory->room }} ห้อง</td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-door-closed"></span></td> <!-- ไอคอนห้องพักว่าง -->
                                        <td class="item">
                                            <a class="dropdown-toggle" href="{{ route('dormitorys.rooms.index', ['code' => $dormitory->code, 'status' => 'free']) }}">
                                                ห้องพักที่ว่าง
                                            </a>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- @if(!in_array(auth()->user()->user_type, [1, 2, 3])) --}}
                            <a class="btn btn-secondary btn-rounded shadow-sm mt-3" style="position: absolute; bottom: 20px; right: 20px;" href="{{ url()->previous() }}">
                                <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
                            </a>
                        {{-- @endif --}}
                    </div>
                </div>
                {{-- @if(in_array(auth()->user()->user_type, [1, 2,3])) --}}
                <div class="col-lg-6 d-flex flex-column">
                    <div class="user-info-list card flex-grow-1">
                        <div class="card-header card-header-divider d-flex justify-content-between">
                            <span>รายละเอียดห้องพัก</span>
                        </div>
                        <div class="card-body">
                            <table class="no-border no-strip skills">
                                <tbody class="no-border-x no-border-y">
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-room"></span></td>
                                        <td width="40%" class="item">ห้องพักทั้งหมด : {{ $total_rooms }} ห้อง</td>
                                        <td width="50%" >ค่าเช่า :  {{$Lease_count}} บาท : {{$Lease_count_net}} บาท</td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-pinwheel-outline"></span></td>
                                        <td class="item">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownFanRooms" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    พัดลม : {{ $total_fan }} ห้อง
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownFanRooms">
                                                    <a class="dropdown-item" href="#">ว่าง : {{ $fan }}</a>
                                                    @foreach($free_fan as $item)
                                                        <a class="dropdown-item" href="{{ route('dormitorys.rooms.show', $item->id) }}">{{ $item->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                        <td>ค่าเช่า : {{$Lease_count_fan}} : {{$Lease_count_fan_net}}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-air-conditioner"></span></td>
                                        <td class="item">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownAirRooms" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    แอร์ : {{ $total_air }} ห้อง
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownAirRooms">
                                                    <a class="dropdown-item" >ว่าง : {{ $air }}</a>
                                                    @foreach($free_air as $item)
                                                        <a class="dropdown-item" href="{{ route('dormitorys.rooms.show', $item->id) }}">{{ $item->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                        <td>ค่าเช่า : {{$Lease_count_air}} : {{$Lease_count_air_net}}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>จำนวนผู้พักอาศัย : 
                                        </td><td>
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownresidents_count" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{$residents_count}}คน
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownAirRooms">
                                                @foreach($data_room as $room)
                                                    <div  class="dropdown-item">{{ $room->name }}: {{ $room->resident }} คน</div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @php
                    $sum = \Modules\Dormitory\Entities\DormitoryComment::where('status_delete', '!=', 'Disable')->where('dormitorys_id', $dormitory->id)->sum('star');
                    $count = \Modules\Dormitory\Entities\DormitoryComment::where('status_delete', '!=', 'Disable')->where('dormitorys_id', $dormitory->id)->count();
                    $average = $count > 0 ? $sum / $count : 0;
                    @endphp
                    <div class=" position-relative">
                    <div class="user-info-list card flex-grow-1">
                        <div class="card-header  d-flex justify-content-between">
                            <span>สิ่งอำนวยความสะดวก</span>
                            <a href="#" style="float: right;">
                                <i class="btn btn-rounded text-dark mdi mdi-plus" onclick="create_Fac(); return false;"title="เพิ่มสิ่งอำนวยความสะดวก"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="no-border no-strip skills">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                <thead class="card-header-divider">
                                    <tr>
                                        <th style="width:3%;"></th>
                                        <th style="width:14%;"></th>
                                        <th style="width:5%;"></th>
                                    </tr>
                                </thead>
                                <tbody class="no-border-x no-border-y">
                                    @forelse ($data_Facilitate as $data_Fac)
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-door"></span></td> <!-- ไอคอนห้องพัก -->
                                        <td class="item">{{ $data_Fac->data }}</td>
                                        <td>
                                                <a href="{{ route('dormitorys.edit_Facilitate', ['id' => $data_Fac->id]) }}" role="button" title="แก้ไขสิ่งอำนวยความสะดวก">
                                                    แก้ไข
                                                </a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">
                                                ไม่พบรายการ
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class=" position-relative">
                        <div class="user-info-list card flex-grow-1">
                            <div class="card-header  d-flex justify-content-between">
                                <span>คอมเมนต์หอพัก
                                    ค่าเฉลี่ย: {{$average}} <i class="mdi mdi-star"></i>
                                </span>
                            </div>
                            <div class="card-body">
                                <table class="no-border no-strip skills">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                <div>{{ $error }}</div>
                                            @endforeach
                                        </div>
                                    @endif
            
                                    <thead class="card-header-divider">
                                        <tr>
                                            <th style="width:3%;"></th>
                                            <th style="width:14%;"></th>
                                            <th style="width:5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="no-border-x no-border-y">
                                        @forelse ($comments as $data)
                                            <tr>
                                                <td>{{ $data->recorder->name }} : </td>
                                                <td >
                                                    {{ $data->comment }} {{$data->star}} <i class="mdi mdi-star"></i>
                                                </td>
                                                <td>
                                                    <span style="color: #888;">
                                                        ({{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') }})
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="5">
                                                    ไม่พบรายการ
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>
        
                </div>
                <a class="btn btn-secondary btn-rounded shadow-sm mt-3" style="position: absolute; bottom: 20px; right: 20px;" href="{{ url()->previous() }}">
                    <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
                </a>
                {{-- @endif --}}
                
<div class="modal fade colored-header colored-header-primary" id="modal-edit" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">แก้ไขหอพัก</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
            <form method="POST" action="{{ route('dormitorys.update', $dormitory->id) }}" enctype="multipart/form-data">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="modal-body">
                    <label for="code">รหัส</label>
                    <input type="text" name="code" id="code" value="{{ old('code', $dormitory->code) }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        @error('code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror                    
                </div>
                <div class="modal-body">
                    <label for="name">ชื่อ</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $dormitory->name) }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror                 
                </div>
                
                <div class="modal-body">
                    <label for="address">ที่อยู่</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $dormitory->address) }}" required>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror               
                </div>
                <div class="modal-body">
                    <label for="web">Website</label>
                    <input type="text" name="web" id="web" value="{{ old('web', $dormitory->web) }}"  placeholder="กรุณาใส่Website" required>              
                </div>
                <div class="modal-body">
                    <label for="loop">สถานะ</label>
                    <select name="status" id="status" required>
                        <option value="Enable" {{ $dormitory->status == 'Enable' ? 'selected' : '' }}>กำลังใช้งาน</option>
                        <option value="Disable" {{ $dormitory->status == 'Disable' ? 'selected' : '' }}>หยุดใช้งาน</option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-body">
                    <label for="Rent">ค่าเช่า</label>
                    <input type="number" name="Rent_min" id="Rent_min" value="{{ $dormitory->Rent_min }}" placeholder="กรุณาใส่ค่าเช่าขั้นต่ำ" required pattern="[A-Za-z0-9ก-๙\s]+" min="0" oninput="validateRent()">
                    <span>-</span>
                    <input type="number" name="Rent_max" id="Rent_max" value="{{ $dormitory->Rent_max }}" placeholder="กรุณาใส่ค่าเช่าราคาสูงสุด" required pattern="[A-Za-z0-9ก-๙\s]+" min="0" oninput="validateRent()">
                    <span>บาท</span>
                    <div id="rent-error" class="text-danger"></div>
                </div>
                <div class="modal-body">
                    <label for="phone">เบอร์โทร</label>
                    <input type="text" name="phone" id="phone" value="{{$dormitory->phone}}" maxlength="10" pattern="\d*" placeholder="กรุณาใส่เบอร์โทร">                  
                </div>
                <div class="modal-body">
                    <label for="description">คำอธิบาย</label>
                    <input type="text" name="description" id="description" value="{{$dormitory->description}}" placeholder="กรุณาใส่คำอธิบาย">             
                </div>
                <div class="modal-body">
                    <label for="Nearby">สถานที่ใกล้เคียง</label>
                    <input type="text" name="Nearby" id="Nearby" value="{{$dormitory->Nearby}}" placeholder="กรุณาใส่สถานที่ใกล้เคียง">                  
                </div>
                <div class="modal-body">
                    <label for="loop">การเลี้ยงสัตว์</label>
                    <select name="animal" id="animal" required>
                        <option value="yes" {{ $dormitory->animal == 'yes' ? 'selected' : '' }}>อนุญาติให้เลี้ยงได้</option>
                        <option value="no" {{ $dormitory->animal == 'no' ? 'selected' : '' }}>ไม่อนุญาติให้เลี้ยง</option>
                    </select>
                </div>
                <div class="modal-body">
                    <label for="room">จำนวนห้องพักที่กำหนด</label>
                    <input type="number" name="room" id="room" value="{{$dormitory->room}}" placeholder="กรุณาใส่จำนวนห้องที่กำหนด" required min="0">             
                </div>
                <div class="modal-body">
                    <label for="img">รูปภาพประกอบ</label>
                    <div id="image-preview-container"></div>
                    <input class="inputfile" id="img" type="file" name="img[]" accept="image/*" multiple onchange="previewImage(event)">
                    <label class="btn-secondary" id="btnImg" for="img"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพเพื่อแก้ไข</span></label>
                </div>
                
                <div class="modal-body text-right">
                    <label></label>
                    <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-account-add"></i> อัปเดต
                    </button>
                    {{-- @if(in_array(auth()->user()->user_type, [1])) --}}
                    <a href="{{ route('dormitorys.delete', $dormitory->id) }}" class="btn btn-danger btn-rounded" onclick="return confirm('คุณต้องการลบ {{$dormitory->name}} หรือไม่ ?')" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-delete"></i> ลบ
                    </a>
                    {{-- @endif --}}
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade colored-header colored-header-primary" id="modal-create_Fac" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">เพิ่มสิ่งอำนวยความสะดวก</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
            <form method="POST" action="{{ route('dormitorys.insert_Facilitate', $dormitory->id) }}">
                @csrf
                <div class="modal-body">
                    <label for="data">สิ่งอำนวยความสะดวก</label>
                    <input type="text" name="data" id="data" placeholder="กรุณาใส่สิ่งอำนวยความสะดวก">                  
                </div>
                <div class="modal-body text-right">
                    <label></label>
                    <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-account-add"></i> บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <div class="modal fade colored-header colored-header-primary" id="modal-edit_Fac" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">แก้ไขสิ่งอำนวยความสะดวก</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
            <form method="POST" action="{{ route('dormitorys.update_Facilitate', $dormitory->id) }}">
                @csrf
                <div class="modal-body">
                    <label for="data">สิ่งอำนวยความสะดวก</label>
                    <input type="text" name="data" id="data" value="data" placeholder="กรุณาใส่สิ่งอำนวยความสะดวก">                  
                </div>
                <div class="modal-body text-right">
                    <label></label>
                    <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-account-add"></i> บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@endsection

@section('css')
<style>
        input, select {
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
    .card {
        border-radius: 15px;
    }
    .d-flex {
        display: flex;
    }
    .justify-content-between {
        justify-content: space-between;
    }
    .text-right {
        text-align: right;
    }
    .position-relative {
        position: relative;
    }
    .top-right {
        position: relative;
        top: 0;
        right: 0;
    }
</style>
@endsection

@section('js_link')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection

@section('js_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
function validateRent() {
        const rentMin = parseFloat(document.getElementById('Rent_min').value);
        const rentMax = parseFloat(document.getElementById('Rent_max').value);
        const rentError = document.getElementById('rent-error');
        const submitButton = document.querySelector('button[type="submit"]');

        if (rentMin && rentMax && rentMax < rentMin) {
            rentError.textContent = 'ค่าเช่าราคาสูงสุดต้องมากกว่าค่าเช่าขั้นต่ำ';
            submitButton.disabled = true;  // Disable the submit button if the values are invalid
        } else {
            rentError.textContent = '';
            submitButton.disabled = false; // Enable the submit button if the values are valid
        }
    }

    window.addEventListener('load', function() {
        var dormitoryDisplay = document.querySelector('.dormitory-display-container');
        var topRight = document.querySelector('.top-right');
        topRight.style.height = dormitoryDisplay.offsetHeight + 'px';
    });

    function edit() {
        $("#modal-edit").modal("show");
        }

        function create_Fac() {
        $("#modal-create_Fac").modal("show");
        }

        function edit_Fac() {
        $("#modal-edit_Fac").modal("show");
        }
        
        function previewImage(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('image-preview-container');
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

            const img = document.createElement('img');
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
                document.getElementById('img').files = dataTransfer.files;

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
