<?php
/**
 * FilesystemIconView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FilesystemIconView extends TPage
{
    private $iconview;
    
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->iconview = new TIconView;
        
        $dir = new DirectoryIterator( getcwd() );
        
        foreach ($dir as $fileinfo)
        {
            if (!$fileinfo->isDot())
            {
                $item = new stdClass;
                
                if ($fileinfo->isDir())
                {
                    $item->type = 'folder';
                    $item->icon = 'far:folder blue fa-4x';
                }
                else
                {
                    $item->type = 'file';
                    $item->icon = 'far:file orange fa-4x';
                }
                
                $item->path = $fileinfo->getPath();
                $item->name = $fileinfo->getFilename();
            }
            
            $this->iconview->addItem($item);
        }
        
        // $this->iconview->enablePopover('', '<b>Name:</b> {name}');
        
        $this->iconview->setIconAttribute('icon');
        $this->iconview->setLabelAttribute('name');
        $this->iconview->setInfoAttributes(['name', 'path']);
        
        $display_condition = function($object) {
            return ($object->type == 'file');
        };
        
        $this->iconview->addContextMenuOption('Options');
        $this->iconview->addContextMenuOption('');
        $this->iconview->addContextMenuOption('Open',   new TAction([$this, 'onOpen']),   'far:folder-open blue');
        $this->iconview->addContextMenuOption('Rename', new TAction([$this, 'onRename']), 'far:edit green');
        $this->iconview->addContextMenuOption('Delete', new TAction([$this, 'onDelete']), 'far:trash-alt red', $display_condition);
        
        parent::add( $this->iconview );
    }
    
    /**
     * Open action
     */
    public static function onOpen($param)
    {
        new TMessage('info', '<b>Name: </b>' . $param['name'] . 
                        '<br> <b>Path: </b>' . $param['path']);
    }
    
    /**
     * Rename action
     */
    public static function onRename($param)
    {
        new TMessage('info', '<b>Name: </b>' . $param['name'] . 
                        '<br> <b>Path: </b>' . $param['path']);
    }
    
    /**
     * Delete action
     */
    public static function onDelete($param)
    {
        new TMessage('info', '<b>Name: </b>' . $param['name'] . 
                        '<br> <b>Path: </b>' . $param['path']);
    }
}