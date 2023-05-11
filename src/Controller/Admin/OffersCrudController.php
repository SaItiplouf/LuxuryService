<?php
namespace App\Controller\Admin;

use App\Entity\Offers;
use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class OffersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offers::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('reference');
        yield TextareaField::new('description');
        yield TextField::new('title');
        yield TextField::new('type');
        yield TextField::new('location');
        yield MoneyField::new('salary')->setCurrency('EUR');
        yield DateField::new('creating_date');
        yield DateField::new('closing_date');
        yield AssociationField::new('jobCategory')->autocomplete();
        yield BooleanField::new('activity');
        yield AssociationField::new('client')->autocomplete();
        yield TextareaField::new('notes');
    }
}