<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TweetType extends AbstractType
{
    const TEXTAREA_COLUMN_SIZE = 70;
    const TEXTAREA_ROW_SIZE    = 3;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', 'textarea', [
                'attr' => [
                    'cols' => self::TEXTAREA_COLUMN_SIZE,
                    'rows' => self::TEXTAREA_ROW_SIZE,
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
