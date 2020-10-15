<?php

namespace App\Controller\Admin;

use App\Entity\Settings;
use App\Form\AttachmentType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\VichUploaderBundle;

class SettingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Settings::class;
    }
public function configureActions(Actions $actions): Actions
{
    return $actions
//        ->remove(Crud::PAGE_INDEX, Action::NEW)
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
            return $action->setIcon('fa fa-eye')->setLabel(false)->addCssClass('d');
        }) ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->setIcon('fa fa-pencil-square-o')->setLabel(false)->addCssClass('d');
        })
        ->remove(Crud::PAGE_INDEX, Action::DELETE);
}

    public function configureFields(string $pageName): iterable
    {
        $imageFile = ImageField::new('thumbnailFile')->setFormType(VichImageType::class);
        $image = ImageField::new('thumbnail','Logo')->setBasePath("/uploads/logo_sile/");
       $fields= [
            TextAreaField::new('message'),
            DateTimeField::new('startDate')->setFormat('dd/MM/yyyy H:mm'),
            DateTimeField::new('endDate')->setFormat('dd/MM/yyyy H:mm'),
//            CollectionField::new('attachment')->onlyOnForms()
//                ->setEntryType(AttachmentType::class)
//                ->onlyOnForms(),
            BooleanField::new('status'),
        ];
       if($pageName == Crud::PAGE_INDEX || $pageName==Crud::PAGE_DETAIL){
           $fields[]=$image;
       }else{
           $fields[]=$imageFile;
       }
       return $fields;
    }

}
