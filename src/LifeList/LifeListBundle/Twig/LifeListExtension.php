<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace LifeList\LifeListBundle\Twig;

use Symfony\Component\HttpFoundation\Request;
class LifeListExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('extends', array($this, 'extendsFilter')),
        );
    }

    public function extendsFilter($template, Request $request = null, $suffix = ".partial")
    {
	if($request === null)
	{
	    return $template;
	}

	$isPJAX = $request->headers->has("X-PJAX");

	if(! $isPJAX)
	{
	    $suffix = "";
	}

	return preg_replace("/\.html(\.twig)?/i", $suffix.".html.twig", $template);
    }

    public function getName()
    {
        return 'life_extension';
    }
}
