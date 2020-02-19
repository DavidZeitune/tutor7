<?php
/**
 * FormEventsPanelView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormEventsPanelView extends TPage
{
    protected $html;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        
        // creates the form
        $this->html = new TElement('div');
        $this->html->setProperty('style', 'margin:20px; font-size: 14pt;');
        
        $al = new TActionLink(_t('Close'), new TAction([$this, 'onClose']), null, null, null, 'fa:times red' );
        $al->class = 'btn btn-default';
        $al->style = 'clear:both';
        $this->html->add($al);
        $this->html->add(new TElement('br'));
        $this->html->add(new TElement('br'));
        
        parent::add($this->html);
    }
    
    /**
     * Load content
     */
    public function onLoad($param)
    {
        $this->html->add(base64_decode($param['content']));
    }
    
    /**
     * Close side panel
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}