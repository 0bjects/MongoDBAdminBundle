<?php

namespace Objects\MongoDBAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Yaml\Dumper;

/**
 * Admin controller.
 */
class AdminController extends Controller {

    public function siteBlocksAction() {
        $footerAboutFilePath = __DIR__ . '/../../../../web/site-blocks/footer-about.txt';
        $data = array();
        $data['footerAboutText'] = file_get_contents($footerAboutFilePath);
        $form = $this->createFormBuilder($data)
                ->add('footerAboutText', 'textarea', array('constraints' => new NotBlank(), 'required' => false, 'attr' => array('class' => 'ckeditor', 'style' => 'width:100%;height:300px')))
                ->getForm();
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                file_put_contents($footerAboutFilePath, $data['footerAboutText']);
                $request->getSession()->getFlashBag()->add('success', 'Saved Successfully');
                exec(PHP_BINDIR . '/php-cli ' . __DIR__ . '/../../../../app/console cache:clear -e prod');
                exec(PHP_BINDIR . '/php-cli ' . __DIR__ . '/../../../../app/console cache:warmup --no-debug -e prod');
            }
        }
        return $this->render('ObjectsMongoDBAdminBundle:Admin:siteBlocks.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function sitePagesAction() {
        $aboutFilePath = __DIR__ . '/../../../../web/site-pages/about.txt';
        $data = array();
        $data['aboutText'] = file_get_contents($aboutFilePath);
        $form = $this->createFormBuilder($data)
                ->add('aboutText', 'textarea', array('constraints' => new NotBlank(), 'required' => false, 'attr' => array('class' => 'ckeditor', 'style' => 'width:100%;height:300px')))
                ->getForm();
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                file_put_contents($aboutFilePath, $data['aboutText']);
                $request->getSession()->getFlashBag()->add('success', 'Saved Successfully');
                exec(PHP_BINDIR . '/php-cli ' . __DIR__ . '/../../../../app/console cache:clear -e prod');
                exec(PHP_BINDIR . '/php-cli ' . __DIR__ . '/../../../../app/console cache:warmup --no-debug -e prod');
            }
        }
        return $this->render('ObjectsMongoDBAdminBundle:Admin:sitePages.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function siteConfigurationsAction() {
        $container = $this->container;
        $data = array();
        $data['contact_email_to'] = $container->getParameter('contact_email_to');
        $form = $this->createFormBuilder($data)
                ->add('contact_email_to', 'email', array('constraints' => array(new NotBlank(), new Email())))
                ->getForm();
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $configFilePath = __DIR__ . '/../../SiteBundle/Resources/config/config.yml';
                $data = $form->getData();
                $dumper = new Dumper();
                file_put_contents($configFilePath, $dumper->dump(array('parameters' => $data), 3));
                $request->getSession()->getFlashBag()->add('success', 'Saved Successfully');
                exec(PHP_BINDIR . '/php-cli ' . __DIR__ . '/../../../../app/console cache:clear -e prod');
                exec(PHP_BINDIR . '/php-cli ' . __DIR__ . '/../../../../app/console cache:warmup --no-debug -e prod');
            }
        }
        return $this->render('ObjectsMongoDBAdminBundle:Admin:siteConfigurations.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
