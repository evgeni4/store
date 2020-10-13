<?php

namespace App\Controller\Admin;

use App\Entity\States;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StatesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return States::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
          //  IntegerField::new('id'),
            TextField::new('name'),
            AssociationField::new('country'),
           // IntegerField::new('country_id')
        ];
    }

}
