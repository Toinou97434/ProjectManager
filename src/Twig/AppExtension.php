<?php

namespace App\Twig;

use App\Entity\Project;
use App\Entity\ProjectTimesheet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('date_diff', [$this, 'getDateDifference']),
            new TwigFilter('days_left', [$this, 'getDaysDifference']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('timesheet_minutes', [$this, 'timesheetMinutes']),
            new TwigFunction('timesheet_percent', [$this, 'timesheetPercent']),
        ];
    }

    public function getDateDifference(?\DateTime $start): string
    {
        $now = new \DateTime();
        $diff = $now->diff($start)->days;

        $prefix = $now >= $start ? '-' : '';
        return "{$prefix}{$diff}";
    }

    public function getDaysDifference(?\DateTime $start): string
    {
        $now = new \DateTime();
        $diff = $now->diff($start)->days;

        $prefix = $now >= $start ? '-' : '';
        return "{$prefix}{$diff}";
    }

    public function timesheetMinutes(Project $project, User $user)
    {
        $timesheets = $this->entityManager->getRepository(ProjectTimesheet::class)->findBy(['created_by' => $user, 'project' => $project]);
        $time = 0;
        $today = new \DateTimeImmutable();
        $today = $today->format('Y-m-d');

        foreach ($timesheets as $timesheet) {
            $date = $timesheet->getCreatedAt()->format('Y-m-d');

            if ($date === $today) {
                $time = $time + $timesheet->getLength();
            }
        }
        return "{$time}";
    }

    public function timesheetPercent(Project $project, User $user)
    {
        $timesheets = $this->entityManager->getRepository(ProjectTimesheet::class)->findBy(['created_by' => $user]);
        $time = 0;
        $project_time = 0;
        $today = new \DateTimeImmutable();
        $today = $today->format('Y-m-d');

        foreach ($timesheets as $timesheet) {
            $date = $timesheet->getCreatedAt()->format('Y-m-d');

            if ($date === $today) {
                if ($timesheet->getProject() === $project) {
                    $project_time = $project_time + $timesheet->getLength();
                }

                $time = $time + $timesheet->getLength();
            }
        }

        $return = 0;
        if ($project_time !== 0) {
            $return = $project_time / $time;
        }

        return $return;
    }
}