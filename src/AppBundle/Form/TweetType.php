<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TweetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', 'textarea', [
                'attr' => [
                    // TODO Here shouldn't be hardcoded values. Put it in an interface.
                    'cols' => 70,
                    'rows' => 3,
                ]
            ])
            ->add('Update', 'submit');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Transfer\TweetTransfer',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_tweet_type';
    }
}
