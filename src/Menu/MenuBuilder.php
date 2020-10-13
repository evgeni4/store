<?php

namespace App\Menu;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var CategoriesRepository
     */
    private $categoryRepository;

    /**
     * @param FactoryInterface $factory
     * @param CategoriesRepository $categoryRepository
     */
    public function __construct(
        FactoryInterface $factory,
        CategoriesRepository $categoryRepository
    ) {
        $this->factory = $factory;
        $this->categoryRepository = $categoryRepository;
    }

    public function createSidebarMenu(RequestStack $requestStack): ItemInterface
    {
        $activeParentCategories = $this->categoryRepository ->findAll();
        $data=[];
        $menu = $this->factory
            ->createItem('root')
            ->setChildrenAttribute('class', 'vertical_nav categories_nav');
        foreach ($activeParentCategories as $row ){
            if(!$row->getParentId()){
                $data[$row->getParentId()][] = $row->getTitle();
            }else{
                $data[$row->getParentId()]['sub'][$row->getId()] = $row->getTitle();
            }
        }
//        foreach ($activeParentCategories as $category) {
//            $categoryMenuItem = $this->createMenuItemByCategory($category);
//
//             $this->addCategoryChildrenMenu($category, $categoryMenuItem);
//
//            $menu->addChild($categoryMenuItem);
//        }
dd($data);
        return $menu;
    }
//    /**
//     * Create menu item using route whether for parent or child category as route is the same for both
//     *
//     * @param Categories $category
//     * @return ItemInterface
//     */
//    private function createMenuItemByCategory(Categories $category): ItemInterface
//    {
//        return $this->factory->createItem($category->getTitle(), [
//            'route' => 'category_show',
//            'routeParameters' => ['id' => $category->getId()],
//        ]);
//    }
//    private function addCategoryChildrenMenu(Categories $category, ItemInterface $categoryMenuItem): void
//    {
//
//        foreach ($category as $childCategory) {
//            if ($childCategory->getParentId()->isVisibleInMenu()) {
//                continue;
//            }
//            $childCategoryMenuItem = $this->createMenuItemByCategory($childCategory);
//            $categoryMenuItem->addChild($childCategoryMenuItem);
//        }
//    }
}