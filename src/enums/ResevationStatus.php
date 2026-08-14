<?php

namespace LMS\Enums;

enum ReservationStatus: string {

    case Pending = 'pending';
    case Active = 'active';
    case Fulfilled = 'fulfilled';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

}