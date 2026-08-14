<?php

namespace LMS\Domain;

use DateTimeImmutable;
use LMS\Enums\ReservationStatus;

class Reservation {

    public function __construct(
        private readonly int $reservationId,
        private readonly int $userId,
        private readonly int $bookId,
        private readonly DateTimeImmutable $reservationDate,
        private int $queuePosition,
        private ReservationStatus $status
    ){}

    public function getReservationId(){
        return $this->reservationId;
    }
    public function getReservationUserId(){
        return $this->userId;
    }
    public function getReservationBookId(){
        return $this->bookId;
    }
    public function getReservationDate(){
        return $this->reservationDate;
    }
    public function getReservationQueuePosition(){
        return $this->queuePosition;
    }
    public function getReservationStatus(){
        return $this->status;
    }

}