<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Service\Categories\CategoriesService;
use Doctrine\DBAL\Types\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
class CategoriesCrudController extends AbstractCrudController
{
    /**
     * @var Categories $category
     */
    private $category;

    public function __construct(CategoriesService $category)
    {
        $this->category = $category;
    }

    public static function getEntityFqcn(): string
    {
        return Categories::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
            return $action->setIcon('fa fa-eye')->setLabel(false)->addCssClass('d');
        }) ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->setIcon('fa fa-pencil-square-o')->setLabel(false)->addCssClass('d');
        })
        ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action->setIcon('fa fa-trash')->setLabel(false)->addCssClass('d');
        });
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('parentId');
    }

    public function configureFields(string $pageName): iterable
    {
        yield  IdField::new('id')->onlyOnIndex();
        yield  TextField::new('title');
        yield  AssociationField::new('user')->onlyOnIndex();
        yield  ChoiceField::new('parentId', 'Parent')
        ->setChoices($this->category->catEditAdmin());
        yield    BooleanField::new('published');
        yield   DateTimeField::new('createDate', 'Create date')
            ->setFormat('dd/MM/yyyy H:mm');

    }

}
