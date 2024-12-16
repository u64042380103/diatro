@extends('core::layouts.master')

@php
    // if (auth()->user()->user_type == 5){
    // $page_id = 'dormitory_dt';
    // $page_name = 'รายละเอียดหอพัก';
    // $page_title = 'รายละเอียดหอพัก';}
    // else{
    $page_id = 'dormitory_rooms';
    $page_name = 'ผังห้องพัก';
    $page_title = 'ผังห้องพัก';
// }
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
    <div class="p-5 mt-n5 mb-3 mx-n5 text-dark" style="background-attachment: fixed; height: 250px; background-color: #333333;">
        <div class="text-center" style="font-size: 20px; color: #ffffff;">ผังห้องพัก</div>
        <div class="mt-1 text-center">
            <div class="mt-1 mb-3 text-center">
                <a href="{{ route('dormitorys.show', $dormitory_code) }}" style="font-size: 26px; color: #ffffff;">
                    <i class="icon mdi mdi-city-alt"></i> {{ $dormitory->name }}
                </a>
            </div>
        </div>
    </div>

    <div style="margin-top: -155px;">
        <div class="d-flex justify-content-between">
            <div>
                {{-- @if (in_array(auth()->user()->user_type, [1,2,3])) --}}
                @if($total_rooms < $dormitory->room)
                <a href="" class="btn btn-success btn-rounded" role="button" onclick="changeImg(); return false;">
                    <i class="mdi mdi-plus"></i> เพิ่มห้องพัก
                </a>
                @endif
                {{-- @endif --}}
                {{-- <a href="" role="button" onclick="changeImg(); return false;">
                    <i class="mdi mdi-settings"></i> ตั้งค่า
                </a> --}}
            </div>
            
            <div>
                <div class="input-group input-search input-group-sm" style="border-radius: 15px; color: #ffffff;">
                    <form action="{{ route('dormitorys.rooms.index', $dormitory->code) }}" method="GET" style="display: flex; width: 100%;">
                        <input class="form-control searchbox" type="text" name="search" placeholder="ค้นหา . . ." style="border-radius: 15px;" value="{{ request()->query('search') }}">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" type="submit" style="border-radius: 15px;">
                                <i class="icon mdi mdi-search"></i>
                            </button>
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow" style="margin-top: 15px; border-radius: 15px; background-color: #FBFBFB;">
        <div class="card-body text-dark">
            @forelse ($rooms->groupBy('floor') as $floor => $roomsOnFloor)
                <div class="mt-3 mb-2 font-weight-bold" style="font-size:18px">ชั้นที่ {{ $floor }}</div>
                <div class="row">
                    @foreach ($roomsOnFloor as $room)
                        <div class="col-6 col-sm-6 col-md-2">
                            <a href="{{ route('dormitorys.rooms.show', $room->id) }}">
                                <div class="card shadow room-card" style="border-radius: 10px; background-color: {{ room_status_color($room->status) }}; color: #000000;">
                                    <div class="card-body pt-4">
                                        <div>ห้อง <strong>{{ $room->name }}</strong></div>
                                        <div class="mt-1">
                                            <i class="mdi mdi-accounts" title="จำนวนผู้พักอาศัย"></i> {{ $room->resident }}
                                            @if ($room->status == 'Free')
                                                <span class="text-right text-success" style="float: right;">˗ˏˋ ว่าง ´ˎ˗</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class=" text-center mt-2" style="font-size:20px">ไม่พบรายการห้องพัก</div>
            @endforelse
        </div>
    </div>

    <div class="modal fade colored-header colored-header-primary" id="modal-changeImg" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">เพิ่มห้องพัก</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                </div>
                <form method="POST" action="{{route('dormitorys.rooms.insert',$dormitory->code)}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <label for="name">หมายเลขห้อง</label>
                        <input type="text" name="name" id="name" placeholder="กรุณาใส่หมายเลขห้อง" value="" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
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
                        <input type="text" name="floor" id="floor" placeholder="กรุณาใส่ชั้น" value="" maxlength="50" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                            
                    </div>
                    <div class="modal-body">
                        <label for="room_type">ประเภท</label>
                            <select name="room_type" id="room_type" required>
                                <option value="">กรุณาเลือกสถานะ</option>
                                <option value="fan">พัดลม</option>
                                <option value="air">แอร์</option>
                            </select>
                    </div>
                    <div class="modal-body">
                        <label for="status">สถานะ</label>
                            <select name="status" id="status" required>
                                <option value="Free">ห้องว่าง</option>
                                <option value="Active">ใช้งาน</option>
                                <option value="Disable">ไม่ใช้งาน</option>
                                <option value="Booking">จอง</option>
                                <option value="MA">ปิดปรับปรุง</option>
                            </select>
                    </div>
                    <div class="modal-body">
                        <label for="area">ขนาดห้อง</label>
                        <input type="number" name="area" id="area" value="" min="0" placeholder="กรุณาใส่ขนาดห้อง" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                        <span>ตร.ม</span>
                    </div>
                    <div class="modal-body">
                        <label for="water">ค่าน้ำราคาเหมา</label>
                        <input type="number" name="water" id="water" value="" min="0" placeholder="ค่าน้ำราคาเหมา" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                        <span>บาท</span>
                    </div>
                    <div class="modal-body">
                        <label for="monthly_rent">ค่าเช่า</label>
                        <input type="number" name="monthly_rent" id="monthly_rent" value="" min="0" placeholder="ค่าเช่า" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                        <span>บาท</span>
                    </div>
                    <div class="modal-body">
                        <label for="deposit">ค่ามัดจำ</label>
                        <input type="number" name="deposit" id="deposit" value="" min="0" placeholder="ค่ามัดจำ" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" required>
                        <span>บาท</span>
                    </div>
                    <div class="modal-body">
                        <label for="extension">คำอธิบาย</label>
                        <input type="test" name="extension" id="extension" value="" placeholder="กรุณาใส่คำอธิบาย" class="large-input" required>
                    </div>
                    {{-- <div class="modal-body">
                        <label for="img_room">รูปภาพประกอบ</label>
                        <img src="" id="img_room-preview" style="width: 50px; height: 50px;  display: none; margin-bottom: 10px;">
                        <input class="inputfile" id="img_room" type="file" name="img_room" accept="image/*" required onchange="previewImage(event)">
                        <label class="btn-secondary" id="btnImg" for="img_room"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพ</span>
                    </div> --}}
                    <div class="modal-body">
                        <label for="img_room">รูปภาพประกอบ</label>
                        <div id="img_room-preview-container"></div>
                        <input class="inputfile" id="img_room" type="file" name="img_room[]" accept="image/*" multiple onchange="previewImage(event)">
                        <label class="btn-secondary" id="btnImg" for="img_room"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพ</span></label>
                    </div>
                    <div class="modal-body text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-add"></i> บันทึก
                        </button>
                    </div>
            </form>
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
        .searchbox:active {
            color: #ffffff;
            border-color: #a41414;
        }

        .searchbox:focus {
            color: #ffffff;
            border-color: #ffffff;
        }

        .room-card {
            position: relative;
            overflow: hidden;
        }

        .room-card:hover .edit-button {
            display: block;
        }
    </style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
    {{-- coding js script --}}
    <script>

    function changeImg() {
        $("#modal-changeImg").modal("show");
    }

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
