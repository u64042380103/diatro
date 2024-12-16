@extends('core::layouts.master')

@php
    $page_id = 'dormitory_lease_agreements';
    $page_name = 'สัญญาเช่า';
    $page_title = 'สัญญาเช่า';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
    <div class="p-5 mt-n5 mb-3 mx-n5 text-dark" style="background-attachment: fixed; height: 250px; background-color: #333333;">
        <div class="text-center" style="font-size: 20px; color: #ffffff;">สัญญาเช่า</div>
        <div class="mt-1 text-center">
            <div class="mt-1 mb-3 text-center">
                <a href="{{ route('dormitorys.show', $dormitory_code) }}" style="font-size: 26px; color:#ffffff;">
                    <i class="icon mdi mdi-city-alt"></i> {{ $dormitory->name }}
                </a>
                
            </div>
        </div>
    </div>

    <div class="card shadow" style="margin-top: 15px; border-radius: 15px; background-color: #FBFBFB;">
        <div style="margin-top: -155px;">
            <div class="card-header d-flex justify-content-between">
                สัญญาเช่า
            </div>
            <div class="card p-4">
                @forelse ($dormitory->rooms->groupBy('floor') as $floor => $rooms)
                    <div class="font-weight-bold">ชั้นที่ {{ $floor }}</div>
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th width="10%">ห้อง</th>
                                <th width="10%">วันเริ่มเช่า</th>
                                <th width="10%">วันสิ้นสุดการเช่า</th>
                                <th width="10%">ราคาค่าเช่า</th>
                                <th width="10%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rooms as $room)
                                @php
                                    $latestLease = $room->latestLease->firstWhere('status_delete', '!=', 'Disable');
                                @endphp
                                <tr>
                                    <td>{{ $room->name }}</td>
                                    <td>{{ $latestLease ? \Carbon\Carbon::parse($latestLease->startDate)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $latestLease ? \Carbon\Carbon::parse($latestLease->endDate)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $room->monthly_rent }}</td>
                                    <td>
                                        @if(!$latestLease)
                                            <a class="btn btn-warning text-dark " href="{{ route('dormitorys.lease_agreements.create', $room->id) }}" style="margin-right: 20px;" title="เพิ่มสัญญา">
                                                <i class="mdi mdi-add"></i>เพิ่ม
                                            </a>
                                        @else
                                        <a class="icon black-icon" href="{{ route('dormitorys.lease_agreements.show', $room->id) }}" style="margin-right: 20px;" title="ดูสัญญาเช่า">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a class="icon black-icon" href="{{ route('dormitorys.monthly_rent.index', $room->id) }}" style="margin-right: 20px;" title="ดูค่าเช่าทั้งหมด">
                                            <i class="mdi mdi-money"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                @empty
                    <div colspan="5" class="text-center" style="font-size:20px">ไม่พบรายการห้องพัก</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="modal fade colored-header colored-header-primary" id="modal-review_edit" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">แก้ไขคอมเมนต์</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                </div>
                    <div class="modal-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            @csrf
                            
                        </form>
                    </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .black-icon {
            color: rgb(100, 100, 100);
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
    </style>
@endsection

@section('js_link')
    {{-- Include any required JS links --}}
@endsection

@section('js_script')
    {{-- Include any required JS scripts --}}
    <script>
    function create() {
        $("#modal-create").modal("show");
    }
    </script>
@endsection
