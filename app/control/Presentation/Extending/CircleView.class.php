<?php
/**
 * CircleView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CircleView extends TPage
{
    /**
     * Page constructor
     */
    function __construct()
    {
        parent::__construct();
        
        TScript::importFromFile('app/lib/include/circle/circle.js');
        TStyle::importFromFile('app/lib/include/circle/circle.css');
        
        $html = new THtmlRenderer('app/resources/circle.html');
        $html->enableSection('main', ['value' => 50]);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($html);
        
        parent::add($vbox);
    }
}
