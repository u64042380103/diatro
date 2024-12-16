@extends('core::layouts.master')
@php
    $page_id = 'dormitorys';
    $page_name = 'รายชื่อหอพัก';
    $page_title = 'รายชื่อหอพัก';
@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-header">รายชื่อหอพัก
                    <div class="tools">
                        {{-- @if (in_array(auth()->user()->user_type, [1])) --}}
                        {{-- <a href="{{ route('dormitorys.create') }}" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;">
                            <i class="mdi mdi-account-add"></i> สร้างหอพัก
                        </a> --}}
                        <a href="" role="button" onclick="create(); return false;" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;">
                            <i class="mdi mdi-account-add"></i> สร้างหอพัก
                        </a>
                        <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ลบที่เลือก
                        </a>
                        {{-- @endif --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive noSwipe">
                        <form id="deleteForm" action="{{ route('dormitorys.massDelete') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:4%;">
                                            {{-- @if (in_array(auth()->user()->user_type, [1]))  --}}
                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="checkAll">
                                                <label class="custom-control-label" for="checkAll"></label>
                                            </div>
                                            {{-- @endif --}}
                                        </th>
                                        <th>ชื่อหอพัก</th>
                                        <th>ที่อยู่</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                <p>{{ $error }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                    @forelse ($lists as $item)
                                        <tr>
                                            <td>
                                                {{-- @if (in_array(auth()->user()->user_type, [1]))  --}}
                                                <div class="custom-control custom-control-sm custom-checkbox">
                                                    <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}">
                                                    <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                                </div>
                                                {{-- @endif --}}
                                            </td>
                                            <td class="cell-detail">
                                                {{-- {{$item->id}} --}}
                                                <span><a href="{{ route('dormitorys.show', $item->code) }}">{{ $item->name }}</a></span>
                                                <span class="cell-detail-description">Code: {{ $item->code }}</span>
                                            </td>
                                            <td>{{ $item->address }}</td>
                                            <td class="{{ $item->status == 'Enable' ? 'text-success' : 'text-danger' }}">{{ $item->status }}</td>
                                            <td class="text-right">
                                                {{-- @if (in_array(auth()->user()->user_type, [1,2,3]))  --}}
                                                    <a href="{{ route('dormitorys.edit', $item->code) }}" class="btn btn-warning btn-rounded text-dark" title="แก้ไขหอพัก"><i class="mdi mdi-wrench"></i> แก้ไข</a>
                                                    {{-- <a href="" role="button" onclick="edit(); return false;" class="btn btn-warning btn-rounded text-dark" title="แก้ไขหอพัก">
                                                        <i class="mdi mdi-wrench"></i>dqfg แก้ไข</a> --}}
                                                    {{-- @endif --}}
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
                        </form>
                    </div>
                </div>
                <div class="card-footer pt-5 pb-1">
                    {{ $lists->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade colored-header colored-header-primary" id="modal-create" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">เพิ่มหอพัก</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                </div>
                <form method="POST" action="{{ route('dormitorys.insert') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <label for="code">code</label>
                        <input type="text" name="code" id="code" placeholder="กรุณาใส่รหัส" value="{{ old('code') }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข"required>
                        @error('code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror                    
                    </div>
                    <div class="modal-body">
                        <label for="name">ชื่อหอพัก</label>
                        <input type="text" name="name" id="name" placeholder="กรุณาใส่ชื่อหอพัก" value="{{ old('name') }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข"required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror                  
                    </div>
                    
                    <div class="modal-body">
                        <label for="address">ที่อยู่</label>
                        <input type="text" name="address" id="address" placeholder="กรุณาใส่ที่อยู่" value="{{ old('address') }}" maxlength="50" required>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror                    
                    </div>
                    <div class="modal-body">
                        <label for="web">Website</label>
                        <input type="text" name="web" id="web" placeholder="กรุณาใส่Website" value="" maxlength="50" required>                    
                    </div>
                    <div class="modal-body">
                        <label for="loop">สถานะ</label>
                        <select name="status" id="status" required>
                            <option value="">กรุณาเลือกสถานะ</option>
                            <option value="Enable" {{ old('status') == 'Enable' ? 'selected' : '' }}>ทำงาน</option>
                            <option value="Disable" {{ old('status') == 'Disable' ? 'selected' : '' }}>หยุดทำงาน</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-body">
                        <label for="phone">เบอร์โทร</label>
                        <input type="text" name="phone" id="phone" maxlength="10" pattern="\d*" placeholder="กรุณาใส่เบอร์โทร">                  
                    </div>
                    <div class="modal-body">
                        <label for="Rent">ค่าเช่า</label>
                        <input type="number" name="Rent_min" id="Rent_min" value="" placeholder="กรุณาใส่ค่าเช่าขั้นต่ำ" required min="0" oninput="validateRent()">
                        <span>-</span>
                        <input type="number" name="Rent_max" id="Rent_max" value="" placeholder="กรุณาใส่ค่าเช่าราคาสูงสุด" required min="0" oninput="validateRent()">
                        <span>บาท</span>
                        <div id="rent-error" class="text-danger"></div>
                    </div>
                    
                    <div class="modal-body">
                        <label for="description">คำอธิบายเพิ่มเติม</label>
                        <input type="text" name="description" id="description" placeholder="กรุณาใส่รหัส">                  
                    </div>
                    <div class="modal-body">
                        <label for="Nearby">สถานที่ใกล้เคียง</label>
                        <input type="text" name="Nearby" id="Nearby" placeholder="กรุณาใส่สถานที่ใกล้เคียง">                  
                    </div>
                    <div class="modal-body">
                        <label for="loop">การเลี้ยงสัตว์</label>
                        <select name="animal" id="animal" required>
                            <option value="yes" {{ 'animal' == 'yes' ? 'selected' : '' }}>อนุญาติให้เลี้ยงได้</option>
                            <option value="no" {{ 'animal' == 'no' ? 'selected' : '' }}>ไม่อนุญาติให้เลี้ยง</option>
                        </select>
                    </div>
                    <div class="modal-body">
                        <label for="room">จำนวนห้องพักที่กำหนด</label>
                        <input type="number" name="room" id="room" placeholder="กรุณาใส่จำนวนห้องที่กำหนด" required min="0">                  
                    </div>
                    <div class="modal-body">
                        <label for="img">รูปภาพประกอบ</label>
                        <div id="image-preview-container"></div>
                        <input class="inputfile" id="img" type="file" name="img[]" accept="image/*" multiple onchange="previewImage(event)">
                        <label class="btn-secondary" id="btnImg" for="img"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพเพื่อแก้ไข</span></label>
                    </div>
                    
                    <div class="modal-body text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" id="submit-btn" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-add"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    {{-- coding css --}}
    <style>
    input, select {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
    /* Custom CSS if needed */
    .modal-body {
        margin-bottom: 15px;
    }
    .form-control {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
    </style>

@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
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

document.getElementById('submit-btn').addEventListener('click', function(event) {
        var rentMin = parseFloat(document.getElementById('Rent_min').value);
        var rentMax = parseFloat(document.getElementById('Rent_max').value);
        var errorDiv = document.getElementById('rent-error');
        
        if (rentMax < rentMin) {
            event.preventDefault(); // หยุดการส่งฟอร์ม
            errorDiv.style.display = 'block'; // แสดงข้อความแจ้งเตือน
        } else {
            errorDiv.style.display = 'none'; // ซ่อนข้อความแจ้งเตือน
        }
    });

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
                alert('กรุณาเลือกหอพักที่ต้องการลบ'); 
                return;
            }

            if (confirm('คุณต้องการลบหอพักที่เลือกหรือไม่ ?')) {
                document.getElementById('deleteForm').submit();
            }
        });

        function create() {
        $("#modal-create").modal("show");
        }
        function edit() {
        $("#modal-edit").modal("show");
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
