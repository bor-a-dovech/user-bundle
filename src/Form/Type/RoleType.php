<?php

namespace Glavnivc\UserBundle\Form\Type;

use App\DTO\FiasDTO;
use App\Entity\Chs;
use App\Entity\Classifier\ChsCharacter;
use App\Form\Transformer\JsonTransformer;
use App\Service\FiasToSearchHashService;
use Glavnivc\UserBundle\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
//    private string $fiasUrl;
//    private JsonTransformer $jsonTransformer;
//    private FiasToSearchHashService $fiasToHashService;

    public function __construct(
//        JsonTransformer $jsonTransformer,
//        FiasToSearchHashService $fiasToSearchHashService
    ) {
//        $this->fiasUrl = $_ENV['FIAS_URL'];
//        $this->jsonTransformer = $jsonTransformer;
//        $this->fiasToHashService = $fiasToSearchHashService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Машинное имя',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Машинное имя',
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Название на русском',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Название на русском',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Описание',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Описание',
                ],
            ])

        ;
/*            ->add('character', EntityType::class, [
                'class' => ChsCharacter::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => 'Характер ЧС',
                'attr' => [
                    'data-select2-width' => '100%',
                ],
            ])
            ->add('regionCode', TextType::class, [
                'label' => 'Код региона',
                'required' => true,
            ])
            ->add('startDatetime', DateType::class, [
                'format' => 'dd.MM.yyyy',
                'label' => 'Дата начала ЧС',
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'ДД.ММ.ГГГГ',
                    'data-calendar' => true,
                ],
            ])
            ->add('endDatetime', DateType::class, [
                'format' => 'dd.MM.yyyy',
                'label' => 'Дата завершения ЧС',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'ДД.ММ.ГГГГ',
                    'data-calendar' => true,
                ],
            ])
            ->add('registeredDatetime', DateType::class, [
                'format' => 'dd.MM.yyyy',
                'label' => 'Дата регистрации ЧС',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'ДД.ММ.ГГГГ',
                    'data-calendar' => true,
                ],
            ])
            ->add('epguPublishEndDatetime', DateType::class, [
                'format' => 'dd.MM.yyyy',
                'label' => 'Дата завершения публикации на ЕПГУ',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'ДД.ММ.ГГГГ',
                    'data-calendar' => true,
                ],
            ])
            ->add('zone', ChoiceType::class, [
                'label' => 'Зона ЧС',
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
                },
                'choices' => $options['data']->getZone(), // TODO:
                'required' => true,
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'fias-multiselect',
                    'data-json-field' => '#chs_zoneJson',
                    'data-fias-url' => $this->fiasUrl,
                    'data-select2-width' => '100%',
                ],
            ])
            ->add('zoneJson', HiddenType::class, [
                'required' => false,
            ])
            ->add('searchHash', HiddenType::class, [
                'required' => false,
            ])
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    // подставляем массив гуидов для сохранения
                    // в zone сохраняется массив гуидов
                    // в zone_json - массив объектов ФИАС
                    $form = $event->getForm();
                    $data = $event->getData();
                    $form->remove('zone');
                    $form->add(
                        'zone',
                        ChoiceType::class,
                        [
                            'label'        => 'Зона ЧС',
                            'choice_label' => function ($choice, $key, $value) {
                                return $value;
                            },
                            'choices'      => $data['zone'],
                            'required'     => false,
                            'multiple'     => true,
                            'expanded'     => false,
                            'attr' => [
                                'class' => 'fias-multiselect',
                                'data-json-field' => '#chs_zoneJson',
                                'data-fias-url' => $this->fiasUrl,
                                'data-select2-width' => '100%',
                            ],
                        ]
                    );
                    // заполняем поле для поиска
                    $data['searchHash'] = $this->fiasToHashService->jsonToHash($data['zoneJson']);
                    $event->setData($data);
                }
            )
        ;
        // преобразование поля при загрузке формы
        $builder->get('zoneJson')
            ->addModelTransformer($this->jsonTransformer)
        ;*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}