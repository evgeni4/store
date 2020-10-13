<?php


namespace App\Service\Categories;


use App\Entity\Categories;
use App\Repository\CategoriesRepository;

class CategoriesService implements CategoriesServiceInterface
{
    private $categoriesRepository;

    /**
     * CategoriesService constructor.
     * @param $categoriesRepository
     */
    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function menu()
    {
        $data = $this->categoriesRepository->findBy(['published' => '1']);
        $menu = [];
        foreach ($data as $key => $row) {
            $menu[$row->getParentId()][$row->getId()]['id'] = $row->getId();
            $menu[$row->getParentId()][$row->getId()]['title'] = $row->getTitle();
            $menu[$row->getParentId()][$row->getId()]['parent_id'] = $row->getParentId();
        }
        $treeElement = $menu[0];
        $this->generateElemTree($treeElement, $menu);
        return $treeElement;
    }

    public function generateElemTree(&$treeElement, $menu)
    {
        foreach ($treeElement as $keys => $item) {
            if (!isset($item['children'])) {
                $treeElement[$keys]['children'] = [];
            }
            if (array_key_exists($keys, $menu)) {
                $treeElement[$keys]['children'] = $menu[$keys];
                $this->generateElemTree($treeElement[$keys]['children'],$menu);
            }
        }
    }

    public function catEditAdmin()
    {
        $dadaArray = $this->categoriesRepository->findAll();
        $data ['No parent'] = 0;
        foreach ($dadaArray as $item) {
            if ($item->getParentId() !== null) {
                $data[$item->getTitle()] = $item->getParentId();
            }
            $data[$item->getTitle()] = $item->getId();
        }
        return $data;
    }


}