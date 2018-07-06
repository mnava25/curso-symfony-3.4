<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EntryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('title',TextType::class,array("label"=>"Titulo:","attr"=>array(
        			"class"=>"form-nombre form-control"
        	)))
        	->add('content',TextareaType::class,array("label"=>"Contenido:","attr"=>array(
        			"class"=>"form-control"
        	)))
        	->add('status',ChoiceType::class,array("label"=>"Estado:",
        			"choices"=>array(
        					"Publicado" => "public",
        					"Privado" => "private"
        			),
        			"attr"=>array(
        			"class"=>"form-control"
        		)))
        		->add('image',FileType::class,array(
        			"label"=>"Imagen",
        			"data_class" => null,
        			"required"=>false,
        			"attr"=>array(
        				"class"=>"form-control",        			
        	)))
        	->add('category',EntityType::class,array("label"=>"Categorias:",
        			"class"=>"BlogBundle:Category",
        			"attr"=>array("class"=>"form-control")
        			
        	))
        	->add("tags",TextType::class,array("label"=>"Etiqueta:",
        			"mapped"=>false,
        			"attr"=>array(
        				"class"=>"form-control"
        	)))
        	->add("Guardar",SubmitType::class,array("attr"=>array("class"=>"btn btn-success")));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Entry'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_entry';
    }


}
