<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\ReservationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'chronolock:expire-holds',
    description: 'Marks expired HELD reservations as EXPIRED.'
)]
final class ExpireHeldReservationsCommand extends Command
{
    public function __construct(
        private ReservationRepository $reservations
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable();

        $expiredCount = $this->reservations->expireHeldReservations($now);

        $output->writeln(sprintf(
            '[%s] Expired %d held reservation(s).',
            $now->format(\DateTimeInterface::ATOM),
            $expiredCount
        ));

        return Command::SUCCESS;
    }
}
