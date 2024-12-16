<?php
function ofz()
{
    return 'OFz97';
}

function room_status($status)
{
    switch ($status) {
        case 'Booking':
            return "จอง";
            break;
        case 'MA':
            return "ปิดปรับปรุง";
            break;
        case 'Active':
            return "ใช้งาน";
            break;
        case 'Disable':
            return "ไม่ใช้งาน";
            break;
        case 'Free':
            return "ห้องว่าง";
            break;
        default:
            return $status;
            break;
    }
}
function room_status_color($status){
    switch ($status) {
        case 'Booking':
            $card_color="#FFF176";
            break;
        case 'MA':
            $card_color="#FFAB91";
            break;
        case 'Active':
            $card_color="#C5E1A5";
            break;
        case 'Disable':
            $card_color="#EF9A9A";
            break;
        default:
            $card_color="#FBFBFB";
            break;
    }
    return $card_color;
}


