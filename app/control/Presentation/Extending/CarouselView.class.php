<?php
/**
 * CarouselView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CarouselView extends TPage
{
    /**
     * Page constructor
     */
    function __construct()
    {
        parent::__construct();
        
        $images = [];
        $images[] = ['index' => '0', 'image' => "app/images/nature/nature1.jpg", 'caption' => 'Image 1', 'class' => 'active'];
        $images[] = ['index' => '1', 'image' => "app/images/nature/nature2.jpg", 'caption' => 'Image 1', 'class' => ''];
        $images[] = ['index' => '2', 'image' => "app/images/nature/nature3.jpg", 'caption' => 'Image 1', 'class' => ''];
        $images[] = ['index' => '3', 'image' => "app/images/nature/nature4.jpg", 'caption' => 'Image 1', 'class' => ''];
        
        $html = new THtmlRenderer('app/resources/carousel.html');
        $html->enableSection('main', []);
        $html->enableSection('indicator', $images, true);
        $html->enableSection('slide', $images, true);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($html);
        
        parent::add($vbox);
    }
}
