<?php
/**
 * SplitRegisterView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SplitRegisterView extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        $html = new THtmlRenderer('app/resources/split_view.html');
        $html->enableSection('main');
        
        $params = [];
        $params['register_state'] = 'false'; // avoid update URL with inner page address
        $params['target_container'] = 'left-panel';
        AdiantiCoreApplication::loadPage('SplitLeftRegisterView', null, $params); // load page
        
        $params = [];
        $params['register_state'] = 'false'; // avoid update URL with inner page address
        $params['target_container'] = 'right-panel';
        AdiantiCoreApplication::loadPage('SplitRightRegisterView', null, $params); // load page
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($html);
        parent::add($vbox);
    }
}
