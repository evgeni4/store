<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye')->setLabel(false)->addCssClass('d');
            })->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-pencil-square-o')->setLabel(false)->addCssClass('d');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel(false)->addCssClass('d');
            });

    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('roles');
    }

    public function configureFields(string $pageName): iterable
    {

        return [

            IntegerField::new('id')->onlyOnIndex(),
            TextField::new('firstName'),
            TextField::new('lastName'),

            EmailField::new('email'),
            IntegerField::new('phone')->onlyOnForms(),
            TextField::new('country', 'Country')->onlyOnDetail(),
            TextField::new('state', 'State')->onlyOnDetail(),
            TextField::new('cityTown', 'City')->onlyOnDetail(),
            TextField::new('address')->onlyOnDetail(),
            IntegerField::new('postCode', 'Zip')->onlyOnDetail(),
            ChoiceField::new('roles')
                ->setChoices(['Super Admin' => 'ROLE_SUPER_ADMIN', 'Admin' => 'ROLE_ADMIN', 'Customer' => 'ROLE_USER'])
                ->allowMultipleChoices(), DateTimeField::new('createDate', 'Create date')
                ->setFormat('dd/MM/yyyy H:mm'),
            BooleanField::new('is_verified', 'Active')->onlyOnForms(),
            BooleanField::new('banned', 'Banned')->setPermission('ROLE_SUPER_ADMIN'),
//            ImageField::new('avatar', 'Photo')
//                ->setFormType(VichImageType::class),
            ImageField::new('avatar')
                ->setBasePath('/uploads/user_image/')
                ->onlyOnIndex()
        ];
    }

}
