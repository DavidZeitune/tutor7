<?php
/**
 * CardView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CardView extends TPage
{
	private $form;
	
	public function __construct()
	{
		parent::__construct();
		
		$cards = new TCardView;
		//$cards->setUseButton();
		$items = [];
		$items[] = (object) [ 'id' => 1, 'title' => 'item 1', 'content' => 'item 1 content', 'color' => '#57D557'];
		$items[] = (object) [ 'id' => 2, 'title' => 'item 2', 'content' => 'item 2 content', 'color' => '#57D557'];
		$items[] = (object) [ 'id' => 3, 'title' => 'item 3', 'content' => 'item 3 content', 'color' => '#5950F1'];
		$items[] = (object) [ 'id' => 4, 'title' => 'item 4', 'content' => 'item 4 content', 'color' => '#57D557'];
		$items[] = (object) [ 'id' => 5, 'title' => 'item 5', 'content' => 'item 5 content', 'color' => '#CC2EC9'];
		$items[] = (object) [ 'id' => 6, 'title' => 'item 6', 'content' => 'item 6 content', 'color' => '#5950F1'];
		
		foreach ($items as $key => $item)
		{
			$cards->addItem($item);
		}
		
		$cards->setTitleAttribute('title');
		$cards->setColorAttribute('color');
		
        //$cards->setTemplatePath('app/resources/card.html');
		$cards->setItemTemplate('<b>Content</b>: {content}');
		$edit_action   = new TAction([$this, 'onItemEdit'], ['id'=> '{id}']);
		$delete_action = new TAction([$this, 'onItemDelete'], ['id'=> '{id}']);
		$cards->addAction($edit_action,   'Edit',   'far:edit blue');
		$cards->addAction($delete_action, 'Delete', 'far:trash-alt red');
		
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($cards);

        parent::add($vbox);
	}
    
    /**
     * Item edit action
     */
	public static function onItemEdit($param = NULL)
	{
		new TMessage('info', '<b>onItemEdit</b><br>'.str_replace(',', '<br>', json_encode($param)));
	}
	
    /**
     * Item delete action
     */
	public static function onItemDelete($param = NULL)
	{
		new TMessage('info', '<b>onItemDelete</b><br>'.str_replace(',', '<br>', json_encode($param)));
	}
}
