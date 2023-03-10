<?php

namespace App\Controller\Admin;

use App\Entity\Note;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NoteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Note::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('recette_id'),
            TextEditorField::new('valeurNote'),           
            AssociationField::new('user')
        ];
    }
    
}
