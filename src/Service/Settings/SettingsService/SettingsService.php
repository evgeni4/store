<?php


namespace App\Service\Settings\SettingsService;


use App\Entity\Settings;
use App\Repository\SettingsRepository;
use App\Service\Settings\SettingsServiceInterface\SettingsServiceInterface;
use phpDocumentor\Reflection\Types\This;

class SettingsService implements SettingsServiceInterface
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function settingsFind(): ?Settings
    {
        return $this->settingsRepository->findOneBy(['id'=>'1']);
    }
   public function oneFind(): ?Settings{
       return $this->settingsRepository->findOneBy(['id'=>1]);
   }
    public function statusUpdate(Settings $setting): bool
    {
        $setting->setStatus(1);
        return $this->settingsRepository->updateStatus($setting);
    }

}