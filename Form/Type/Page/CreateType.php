<?php

namespace Stewie\WikiBundle\Form\Type\Page;

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

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('title', TextType::class, array(
               'label' => 'label.title',
               'translation_domain' => 'StewieWikiBundle',
             ))

             ->add('body', TextType::class, array(
                 'label' => 'label.body',
                 'translation_domain' => 'StewieWikiBundle',
             ))

             ->add('submit', SubmitType::class, array('label' => 'label.create',
             'translation_domain' => 'StewieWikiBundle',
             'attr'=> array('class'=>'btn-primary'),))
        ;
    }
}
