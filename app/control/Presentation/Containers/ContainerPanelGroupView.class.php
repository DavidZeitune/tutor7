<?php

class ContainerPanelGroupView extends TPage
{
    
    function __construct()
    {
        parent::__construct();
        
        // creates a panel
        $panel = new TPanelGroup('Panel group title');
        
        $table = new TTable;
        $table->border = 1;
        $table->style = 'border-collapse:collapse';
        $table->width = '100%';
        $table->addRowSet('a1','a2');
        $table->addRowSet('b1','b2');
        $panel->add($table);
        $panel->addFooter('footer');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);
        
        parent::add($vbox);
    }
    
}
