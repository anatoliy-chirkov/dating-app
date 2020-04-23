<?php

namespace Admin\Services;

use Admin\Repositories\AdvantageRepository;
use Core\ServiceContainer;

class AdvantageService
{
    /** @var AdvantageRepository $advantageRepository */
    private $advantageRepository;

    public function __construct()
    {
        $this->advantageRepository = ServiceContainer::getInstance()->get('advantage_repository');
    }

    public function create(
        int $groupId,
        string $name,
        array $permissionId,
        int $accessId,
        float $price,
        int $duration,
        $isActive = false
    ) {

        $id = $this->advantageRepository->addAdvantage($name, $groupId, $accessId, $duration, $price, $isActive);
        $this->savePermissions($id, $permissionId);
    }

    public function update(
        int $id,
        int $groupId,
        string $name,
        array $permissionId,
        int $accessId,
        float $price,
        int $duration,
        $isActive = false
    ) {
        $this->advantageRepository->updateAdvantage($id, $name, $groupId, $accessId, $duration, $price, $isActive);
        $this->advantageRepository->removeAdvantagePermissions($id);
        $this->savePermissions($id, $permissionId);
    }

    private function savePermissions(int $id, array $permissionId)
    {
        foreach ($permissionId as $permissionSingleId) {
            $this->advantageRepository->addAdvantagePermission($id, $permissionSingleId);
        }
    }
}
