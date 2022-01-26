<?php

namespace Stewie\WikiBundle\Form\Type\Space;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
// use Stewie\UserBundle\Entity\Role;
// use Doctrine\ORM\EntityRepository;
// use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// use Vich\UploaderBundle\Form\Type\VichImageType;
// use Symfony\Component\Form\Extension\Core\Type\DateType;
// use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('name', TextType::class, array(
               'label' => 'label.name',
               'translation_domain' => 'StewieWikiBundle',
             ))

             ->add('description', TextType::class, array(
                 'label' => 'label.description',
                 'translation_domain' => 'StewieWikiBundle',
             ))

             ->add('submit', SubmitType::class, array('label' => 'label.update',
             'translation_domain' => 'StewieWikiBundle',
             'attr'=> array('class'=>'btn-primary'),))
        ;
    }
}
