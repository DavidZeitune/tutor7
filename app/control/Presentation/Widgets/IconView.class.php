<?php
/**
 * IconView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class IconView extends TPage
{
    private $iconview;
    
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates iconview
        $this->iconview = new TIconView;
        $this->iconview->setIconAttribute('icon');
        $this->iconview->setLabelAttribute('name');
        $this->iconview->setInfoAttributes(['name', 'path']);
        $this->iconview->enablePopover('', '<b>Name</b>: {name} <br> <b>Path</b>: {path}', 'top');
        $display_condition = function($object) {
            return $object->type == 'file';
        };
        
        $this->iconview->addContextMenuOption('Options');
        $this->iconview->addContextMenuOption('');
        $this->iconview->addContextMenuOption('Acao 1', new TAction([$this, 'onAction']), 'far:folder blue');
        $this->iconview->addContextMenuOption('Acao 2', new TAction([$this, 'onAction']), 'far:check-circle green');
        $this->iconview->addContextMenuOption('Acao 3', new TAction([$this, 'onAction']), 'far:trash-alt red', $display_condition);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->iconview);

        parent::add($vbox);
    }
    
    /**
     * Dropdown action
     */
    public static function onAction($param)
    {
        new TMessage('info', '<b>Path: </b>'.  $param['path'] .
                       '<br> <b> Name: </b>' . $param['name']);
    }
    
    /**
     * Load the data into the iconview
     */
    function onReload()
    {
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->type     = 'folder';
        $item->path     = '/folder-a';
        $item->name     = 'Folder A';
        $item->icon     = 'far:folder blue fa-4x';
        $this->iconview->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->type     = 'file';
        $item->path     = '/file-a';
        $item->name     = 'File A';
        $item->icon     = 'far:file orange fa-4x';
        $this->iconview->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->type     = 'file';
        $item->path     = '/file-b';
        $item->name     = 'File B';
        $item->icon     = 'far:file orange fa-4x';
        $this->iconview->addItem($item);
        
        // add an regular object to the datagrid
        $item = new StdClass;
        $item->type     = 'file';
        $item->path     = '/file-c';
        $item->name     = 'File C';
        $item->icon     = 'far:file orange fa-4x';
        $this->iconview->addItem($item);
    }
    
    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}
