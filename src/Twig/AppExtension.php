<?php

namespace App\Twig;

use App\Entity\Project;
use App\Entity\ProjectTimesheet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vich\UploaderBundle\Storage\StorageInterface;

class AppExtension extends AbstractExtension
{
    private const DEFAULT_PICTURE = '/images/user/avatar/default.png';
    private EntityManagerInterface $entityManager;
    private CacheManager $imagineCacheManager;
    private StorageInterface $handler;

    public function __construct(EntityManagerInterface $entityManager, CacheManager $imagineCacheManager, StorageInterface $handler)
    {
        $this->entityManager = $entityManager;
        $this->imagineCacheManager = $imagineCacheManager;
        $this->handler = $handler;
    }

    public function getFilters(): ?array
    {
        return [
            new TwigFilter('date_diff', [$this, 'getDateDifference']),
            new TwigFilter('days_left', [$this, 'getDaysDifference']),
            new TwigFilter('avatar', [$this, 'getUserPicture']),
        ];
    }

    public function getFunctions(): ?array
    {
        return [
            new TwigFunction('timesheet_minutes', [$this, 'timesheetMinutes']),
            new TwigFunction('timesheet_percent', [$this, 'timesheetPercent']),
            new TwigFunction('time_spent', [$this, 'getTimeSpent']),
        ];
    }

    public function getUserPicture(User $user): string
    {
        $package = new Package(new EmptyVersionStrategy());
        $user_picture = $package->getUrl(self::DEFAULT_PICTURE);

        if (!empty($user->getPicture()) && $user->getPicture() !== null) {
            $user_picture = $this->handler->resolveUri($user->getPicture());
        }

        return $this->imagineCacheManager->getBrowserPath($user_picture, 'avatar');
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

    public function timesheetMinutes(Project $project, User $user, \DateTimeImmutable $date = new \DateTimeImmutable()): string
    {
        $timesheets = $this->entityManager->getRepository(ProjectTimesheet::class)->getUserTimesheets($user, ['project' => $project, 'date' => $date]);
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

    public function timesheetPercent(Project $project, User $user, \DateTimeImmutable $selected_date = new \DateTimeImmutable()): float
    {
        $timesheets = $this->entityManager->getRepository(ProjectTimesheet::class)->getUserTimesheets($user, ['date' => $selected_date]);
        $time = 0;
        $project_time = 0;
        $today = new \DateTimeImmutable();
        $today = $selected_date->format('Y-m-d');

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

        return round($return, 2, PHP_ROUND_HALF_UP);
    }

    public function getTimeSpent(Project $project, User $user): float
    {
        $timesheets = $this->entityManager->getRepository(ProjectTimesheet::class)->getUserTimesheets($user, ['project' => $project]);

        $time = 0;
        foreach ($timesheets as $timesheet) {
            $time = $time + $timesheet->getLength();
        }

        if ($time > 0) {
            $time = $time / 600;
        }

        return round($time, 1, PHP_ROUND_HALF_UP);
    }
}