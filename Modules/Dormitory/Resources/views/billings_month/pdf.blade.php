<!DOCTYPE html>
<html>
<head>
    <title>PDF</title>
    <meta http-equiv="Content-Language" content="th" />
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        body {
            font-family: "THSarabunNew";
            margin: 20px;
        }
        .header, .section, .item {
            margin-bottom: 20px;
        }
        .header div {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .section {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .no-border-table {
            border: none;
        }
        .no-border-table td, 
        .no-border-table th {
            border: none;
        }
        .center{
            text-align: center;
        }
        .right{
            text-align: right;
        }
    </style>
</head>
<body>        
    <div class="header">
        <div>ใบแจ้งหนี้</div>
        <div>เลขที่ใบแจ้งหนี้ : {{$data_billings->id}}</div>
    </div>
    <div class="section">
        <div>ออกใบแจ้งหนี้วันที่ {{ date('d/m/Y H:i:s') }}</div>
        <div>กำหนดชำระ : {{ date('d/m/Y', strtotime('+1 month')) }}</div>
    </div>
    
    <table class="no-border-table">
        <td style="width: 75%">
            <div class="section-title">ข้อมูลลูกค้า</div>
            <div>ชื่อผู้ใช้ : ทดลอง{{$data_user->name}}</div>
            <div>เบอร์โทร : 0967354256{{$data_user->phone}}</div>
        </td>
        <td>
            <div class="section-title">ข้อมูลหอพัก</div>
            <div>ชื่อหอพัก : {{$dormitory->name}}</div>
            <div>ที่อยู่หอพัก : {{$dormitory->address}}</div>
            <div>ห้อง : {{$data_room->name}}</div>
            <div>ชั้น : {{$data_room->floor}}</div>
        </td>
    </table>
    <table class="no-border-table">

    <td >
        <div class="section-title">รายละเอียดการชำระเงิน</div>
        <div>ธนาคาร : กรุงไทย</div>
        <div>หมายเลขบัญชี : 098493724</div>
        {{-- <div>กำหนดชำระ : {{ date('d/m/Y', strtotime('+7 days')) }}</div> --}}
    </td>
    </table>
    <table >
        <thead>
            <tr>
                <th class="center">ลำดับ</th>
                <th class="center">ประเภท</th>
                <th class="center">จำนวน (บาท)</th>
            </tr>
        </thead>
        <tbody >
            @php
                $totalAmount = 0;
            @endphp
            @forelse ($data as $item)
            <tr>
                <td >{{$loop->iteration}}</td>
                <td class="right">
                    @if ($item->data_type === 'meter')
                        ค่ามิเตอร์
                    @elseif ($item->data_type === 'month')
                        ค่าเช่า
                    @elseif ($item->data_type === 'water')
                        ค่าน้ำแบบเหมา
                    @else
                        อื่นๆ
                    @endif
                </td>
                <td class="right">{{$item->amount}}</td>
            </tr>
            @php
                $totalAmount += $item->amount;
            @endphp
            @empty
            <tr>
                <td colspan="3">ไม่พบข้อมูล</td>
            </tr>
            @endforelse
            @if(!$data->isEmpty())
            <tr>
                <td colspan="2" ><strong>ยอดรวม</strong></td>
                <td class="right"><strong>{{$totalAmount}} บาท</strong></td>
            </tr>
            @endif
        </tbody>
    </table>
    <table class="no-border-table">

    <td style="width: 70%"></td>
    <td>
    <div class="section center" >
        <div class="section-title">ขอบคุณที่ใช้บริการ</div>
        <div >หากท่านมีข้อสงสัย กรุณาติดต่อเรา</div>
    </div>
    </td>
    </table>
</body>
</html>
