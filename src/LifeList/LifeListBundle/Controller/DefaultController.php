<?php

namespace LifeList\LifeListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LifeListLifeListBundle:Default:index.html.twig');
    }

    /**
     *
     * @Cache(smaxage="15", etag="'Test'")
     */
    public function listsAction()
    {
        return $this->render('LifeListLifeListBundle:Default:lists.html.twig');
    }
}
