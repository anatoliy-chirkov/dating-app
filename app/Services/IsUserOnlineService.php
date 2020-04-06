<?php

namespace Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class IsUserOnlineService
{
    private const
        ACTIVE   = 'active',
        RECENTLY = 'recently',
        LONG     = 'long'
    ;

    public function getViewCircle(int $isConnected, string $lastConnected)
    {
        $lastConnectedDate = Carbon::parse($lastConnected);
        $nowDate = Carbon::now();

        return $nowDate->diffInMinutes($lastConnectedDate) < 5 || $isConnected
            ? $this->getOnlyCircleHTML(self::ACTIVE)
            : ''
        ;
    }

    public function getViewElement(string $sex, int $isConnected, string $lastConnected)
    {
        $lastConnectedDate = Carbon::parse($lastConnected);
        $nowDate = Carbon::now();

        $diffInMinutes = $nowDate->diffInMinutes($lastConnectedDate);

        if (($diffInMinutes < 5 || $isConnected) && $diffInMinutes < 60) {
            return $this->getHTML(self::ACTIVE, 'Online');
        } else {
            $text = 'Был' . ($sex === 'man' ? '' : 'а') . ' ' . $lastConnectedDate->locale('ru')
                    ->diffForHumans($nowDate, [
                        'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
                        'options' => Carbon::ONE_DAY_WORDS
                    ])
            ;

            if ($nowDate->diffInHours($lastConnectedDate) < 24) {
                return $this->getHTML(self::RECENTLY, $text);
            }

            if ($nowDate->diffInHours($lastConnectedDate) < 48) {
                $text .= ' в ' . $lastConnectedDate->format('H:i');
            }

            return $this->getHTML(self::LONG, $text);
        }
    }

    private function getHTML(string $status, string $text)
    {
        return <<<HTML
            <div class="status {$status}"><div class="circle"></div>{$text}</div>
HTML;
    }

    private function getOnlyCircleHTML(string $status)
    {
        return <<<HTML
            <div class="user-status-circle {$status}"></div>
HTML;

    }
}
