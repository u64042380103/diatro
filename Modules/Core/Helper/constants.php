<?php
function vault($key,$default=null)
{
    switch ($key) {
        case 'DB_DATABASE':
            return 'dormitory_data';
            break;
        case 'DB_USERNAME':
            return 'dormitory_data';
            break;
        case 'DB_PASSWORD':
            return 'PoGim@NPV0V[Qa;!';
            break;

        default:
            return $default;
            break;
    }
}
