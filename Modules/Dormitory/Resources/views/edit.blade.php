@extends('core::layouts.master')

@php
    $page_id = 'dormitory_dt';
    $page_name = 'รายละเอียดหอพัก';
    $page_title = 'รายละเอียดหอพัก';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">รายละเอียดหอพัก</div>
            <div class="card-body" style="padding: 20px;">
                
                <form method="POST" action="{{ route('dormitorys.update', $dormitory->id) }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="code">รหัส</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $dormitory->code) }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                            @error('code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror                    
                    </div>
                    <div class="form-group">
                        <label for="name">ชื่อ</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $dormitory->name) }}" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror                 
                    </div>
                    
                    <div class="form-group">
                        <label for="address">ที่อยู่</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $dormitory->address) }}" required>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror               
                    </div>
                    <div class="form-group">
                        <label for="web">Website</label>
                        <input type="text" name="web" id="web" value="{{ old('web', $dormitory->web) }}"  placeholder="กรุณาใส่Website" required>          
                    </div>
                    <div class="form-group">
                        <label for="loop">สถานะ</label>
                        <select name="status" id="status" required>
                            <option value="Enable" {{ $dormitory->status == 'Enable' ? 'selected' : '' }}>กำลังใช้งาน</option>
                            <option value="Disable" {{ $dormitory->status == 'Disable' ? 'selected' : '' }}>หยุดใช้งาน</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="Rent">ค่าเช่า</label>
                        <input type="number" name="Rent_min" id="Rent_min" value="{{ $dormitory->Rent_min }}" placeholder="กรุณาใส่ค่าเช่าขั้นต่ำ" required pattern="[A-Za-z0-9ก-๙\s]+" min="0" oninput="validateRent()">
                        <span>-</span>
                        <input type="number" name="Rent_max" id="Rent_max" value="{{ $dormitory->Rent_max }}" placeholder="กรุณาใส่ค่าเช่าราคาสูงสุด" required pattern="[A-Za-z0-9ก-๙\s]+" min="0" oninput="validateRent()">
                        <span>บาท</span>
                        <div id="rent-error" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label>
                        <input type="text" name="phone" id="phone" value="{{$dormitory->phone}}" maxlength="10" pattern="\d*" placeholder="กรุณาใส่เบอร์โทร">                  
                    </div>
                    <div class="form-group">
                        <label for="description">คำอธิบาย</label>
                        <input type="text" name="description" id="description" value="{{$dormitory->description}}" placeholder="กรุณาใส่คำอธิบาย">             
                    </div>
                    <div class="modal-body">
                        <label for="Nearby">สถานที่ใกล้เคียง</label>
                        <input type="text" name="Nearby" id="Nearby" value="{{$dormitory->Nearby}}" placeholder="กรุณาใส่สถานที่ใกล้เคียง">                  
                    </div>
                    <div class="form-group">
                        <label for="loop">การเลี้ยงสัตว์</label>
                    <select name="animal" id="animal" required>
                        <option value="yes" {{ $dormitory->animal == 'yes' ? 'selected' : '' }}>อนุญาติให้เลี้ยงได้</option>
                        <option value="no" {{ $dormitory->animal == 'no' ? 'selected' : '' }}>ไม่อนุญาติให้เลี้ยง</option>
                    </select>
                    </div>
                    <div class="form-group">
                        <label for="room">จำนวนห้องพักที่กำหนด</label>
                        <input type="number" name="room" id="room" value="{{$dormitory->room}}" placeholder="กรุณาใส่จำนวนห้องที่กำหนด" required min="0">             
                    </div>
                    <div class="form-group">
                        <label for="img">รูปภาพประกอบ</label>
                        <div id="image-preview-container"></div>
                        <input class="inputfile" id="img" type="file" name="img[]" accept="image/*" multiple onchange="previewImage(event)">
                        <label class="btn-secondary" id="btnImg" for="img"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพเพื่อแก้ไข</span></label>
                    </div>
                    
                    <div class="form-group text-right">
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
        <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ url()->previous() }}">
            <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
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
        margin: 5px auto; /* Center the card horizontally */
    }
    .card-header {
        text-align: center;
    }
    .card-body {
        padding: 20px; /* Add padding to card body */
    }
    label {
        font-weight: bold; /* Make label text bold */
    }
</style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
    {{-- coding js script --}}
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
