<?php


namespace App\Service\Settings\SettingsServiceInterface;


use App\Entity\Settings;

interface SettingsServiceInterface
{
    public function settingsFind(): ?Settings;

    public function statusUpdate(Settings $setting): bool;
}