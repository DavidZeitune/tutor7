<?php
/**
 * AlertView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class AlertView extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        parent::add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TAlert('info', 'Info alert'));
        $vbox->add(new TAlert('success', 'Success alert'));
        $vbox->add(new TAlert('warning', 'Warning alert'));
        $vbox->add(new TAlert('danger', 'Danger alert'));
        
        parent::add($vbox);
    }
}
