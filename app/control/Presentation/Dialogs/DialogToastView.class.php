<?php
/**
 * DialogToastView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class DialogToastView extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        TToast::show('show', 'Toast test 1', 'top right', 'far:check-circle' );
        TToast::show('info', 'Toast test 2', 'top right', 'far:check-circle' );
        TToast::show('warning', 'Toast test 3', 'top right', 'far:check-circle' );
        TToast::show('error', 'Toast test 4', 'top right', 'far:check-circle' );
        TToast::show('success', 'Toast test 4', 'top right', 'far:check-circle' );
        
        parent::add(new TXMLBreadCrumb('menu.xml', __CLASS__));
    }
}
