<?php


declare(strict_types=1);

namespace App\Service;

use App\Entity\Resource;
use App\Repository\ReservationRepository;
use DateTimeImmutable;

final class GetAvailabilityService
{
    public function __construct(
        private ReservationRepository $reservations
    ) {}

    public function getAvailability(Resource $resource, DateTimeImmutable $from, DateTimeImmutable $to): array
    {
        $utc = new \DateTimeZone('UTC');
        $now = new DateTimeImmutable('now', $utc);

        $from = $from->setTimezone($utc);
        $to   = $to->setTimezone($utc);

        $busyIntervals = $this->loadBusyIntervals($resource, $from, $to, $now);

        if ($busyIntervals === []) {
            return [['startAt' => $from, 'endAt' => $to]];
        }

        $mergedBusy = $this->mergeIntervals($busyIntervals);

        return $this->computeFreeIntervals($mergedBusy, $from, $to);
    }

    private function loadBusyIntervals(
        Resource $resource,
        DateTimeImmutable $from,
        DateTimeImmutable $to,
        DateTimeImmutable $now
    ): array {
        $busy = $this->reservations->findBusyIntervals($resource, $from, $to, $now);

        return array_map(static fn($r) => [
            'startAt' => $r->startAt,
            'endAt'   => $r->endAt,
        ], $busy);
    }

    private function mergeIntervals(array $intervals): array
    {
        usort($intervals, static fn($a, $b) => $a['startAt'] <=> $b['startAt']);

        $merged = [];
        foreach ($intervals as $int) {
            if ($merged === []) {
                $merged[] = $int;
                continue;
            }

            $lastIndex = count($merged) - 1;
            $last = $merged[$lastIndex];

            if ($int['startAt'] <= $last['endAt']) {
                if ($int['endAt'] > $last['endAt']) {
                    $merged[$lastIndex]['endAt'] = $int['endAt'];
                }
            } else {
                $merged[] = $int;
            }
        }

        return $merged;
    }

    private function computeFreeIntervals(array $mergedBusy, DateTimeImmutable $from, DateTimeImmutable $to): array
    {
        $free = [];
        $cursor = $from;

        foreach ($mergedBusy as $b) {
            $busyStart = $b['startAt'] < $from ? $from : $b['startAt'];
            $busyEnd   = $b['endAt']   > $to   ? $to   : $b['endAt'];

            if ($busyStart > $cursor) {
                $free[] = ['startAt' => $cursor, 'endAt' => $busyStart];
            }

            if ($busyEnd > $cursor) {
                $cursor = $busyEnd;
            }
        }

        if ($cursor < $to) {
            $free[] = ['startAt' => $cursor, 'endAt' => $to];
        }

        return $free;
    }
}
