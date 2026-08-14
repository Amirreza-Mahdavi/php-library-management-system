<?php

namespace LMS\Repository;

use LMS\Domain\Reservation;

class ReservationRepository {
    private string $file = __DIR__ . '/../../data/reservations.json';

    public function findByUserId(int $userId): array {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);
        $reservations = [];

        foreach ($data as $reservation) {
            if ($reservation['user_id'] === $userId)
                $reservations[] = $this->mapToReservation($reservation);
        }
        return $reservations;
    }

    public function findByBookId(int $bookId): array {
        $data = json_decode(file_get_contents($this->file), true, LOCK_EX);
        $reservations = [];

        foreach ($data as $reservation) {
            if ($reservation['book_id'] === $bookId)
                $reservations[] = $this->mapToReservation($reservation);
        }
        return $reservations;
    }

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

    private function mapToStorage(Reservation $reservation): array {
        return [
            'reservation_id' => $reservation->getReservationId(),
            'user_id' => $reservation->getReservationUserId(),
            'book_id' => $reservation->getReservationBookId(),
            'reservation_date' => $reservation->getReservationDate(),
            'queue_position' => $reservation->getReservationQueuePosition(),
            'status' => $reservation->getReservationStatus()
        ];
    }
}