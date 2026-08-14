<?php

namespace LMS\Repository;

use LMS\Domain\Reservation;

class ReservationRepository {
    private string $file = __DIR__ . '/../../data/reservations.json';

    private function mapToReservation(array $reservation): Reservation {
        return new Reservation(
            $reservation['reservation_id'],
            $reservation['user_id'],
            $reservation['book_id'],
            $reservation['reservation_date'],
            $reservation['queue_position'],
            $reservation['status']
        );
    }
}