<?php

/**
 * Description of UserAdmin
 *
 * @author Mahmoud
 */

namespace Objects\MongoDBAdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class UserAdmin extends Admin {

    /**
     * this variable holds the route name prefix for this actions
     * @var string
     */
    protected $baseRouteName = 'user_admin';

    /**
     * this variable holds the url route prefix for this actions
     * @var string
     */
    protected $baseRoutePattern = 'user';

    /**
     * this function configure the list action fields
     * @param ListMapper $listMapper
     */
    public function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('id')
                ->add('firstName')
                ->add('lastName')
                ->add('loginName')
                ->add('email', NULL, array('template' => 'ObjectsMongoDBAdminBundle:General:list_email.html.twig'))
                ->add('image', NULL, array('template' => 'ObjectsMongoDBAdminBundle:General:list_image.html.twig'))
                ->add('gender', NULL, array('template' => 'ObjectsMongoDBAdminBundle:General:list_gender.html.twig'))
                ->add('createdAt')
                ->add('locked')
                ->add('enabled')
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'view' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    )
                ))
        ;
    }

    /**
     * this function configure the show action fields
     * @param ShowMapper $showMapper
     */
    public function configureShowField(ShowMapper $showMapper) {
        $showMapper
                ->add('id')
                ->add('firstName')
                ->add('lastName')
                ->add('loginName')
                ->add('email', NULL, array('template' => 'ObjectsMongoDBAdminBundle:General:show_email.html.twig'))
                ->add('image', NULL, array('template' => 'ObjectsMongoDBAdminBundle:General:show_image.html.twig'))
                ->add('gender', NULL, array('template' => 'ObjectsMongoDBAdminBundle:General:show_gender.html.twig'))
                ->add('createdAt')
                ->add('locked')
                ->add('enabled')
        ;
    }

    /**
     * this function configure the list action filters fields
     * @param DatagridMapper $datagridMapper
     */
    public function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('id')
                ->add('firstName')
                ->add('lastName')
                ->add('loginName')
                ->add('email')
                ->add('gender')
                ->add('locked')
                ->add('enabled')
        ;
    }

    /**
     * this function configure the new, edit form fields
     * @param FormMapper $formMapper
     */
    public function configureFormFields(FormMapper $formMapper) {
        $imageAttributes = array(
            'onchange' => 'readURL(this);'
        );
        if ($this->getSubject() && $this->getSubject()->getId() && $this->getSubject()->getImage()) {
            $imageAttributes['data-image-url'] = $this->getRequest()->getBasePath() . '/' . $this->getSubject()->getSmallImageUrl(60, 60);
            //$imageAttributes['data-image-remove-url'] = $this->getConfigurationPool()->getContainer()->get('router')->generate('admin_remove_customer_image', array('customerId' => $this->getSubject()->getId()));
        }
        $formMapper
                ->with('Required Fields')
                ->add('loginName')
                ->add('email')
                ->add('userPassword', 'repeated', array(
                    'required' => false,
                    'type' => 'password',
                    'first_options'  => array('label' => 'Password', 'attr' => array('autocomplete' => 'off')),
                    'second_options' => array('label' => 'Repeat Password', 'attr' => array('autocomplete' => 'off')),
                    'invalid_message' => "The passwords don't match"))
                ->end()
                ->with('Not Required Fields', array('collapsed' => true))
                ->add('gender', 'choice', array('required' => false, 'choices' => array('1' => 'Male', '0' => 'Female'), 'expanded' => true, 'multiple' => false))
                ->add('firstName', null, array('required' => false))
                ->add('lastName', null, array('required' => false))
                ->add('file', 'file', array('required' => false, 'label' => 'image', 'attr' => $imageAttributes))
                ->add('locked', null, array('required' => false))
                ->end()
                ->setHelps(array(
                    'locked' => 'to prevent the user from logging into his account'
                ))
        ;
    }

    /**
     * this function is used to set a different validation group for the form
     */
    public function getFormBuilder() {
        if (is_null($this->getSubject()->getId())) {
            $this->formOptions = array('validation_groups' => array('signup'));
        } else {
            $this->formOptions = array('validation_groups' => array('edit'));
        }
        $formBuilder = parent::getFormBuilder();
        return $formBuilder;
    }

    /**
     * this function is for editing the routes of this class
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('delete');
    }

    /**
     * @param Objects\MongoDBUserBundle\Document\User $user
     */
    public function prePersist($user) {
        $user->setRoles(array('ROLE_USER'));
    }

}

?>
