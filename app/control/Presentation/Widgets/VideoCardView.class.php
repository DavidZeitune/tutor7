<?php
/**
 * VideoCardView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class VideoCardView extends TPage
{
	private $form;
	
	public function __construct()
	{
		parent::__construct();
		
		$cards = new TCardView;
		$cards->setUseButton();
		$items = [];
		$items[] = (object) [ 'id' => 1, 'title' => 'Melhorias do Framework 4.0', 'source' => 'M91oklMkJTU'];
		$items[] = (object) [ 'id' => 2, 'title' => 'Melhorias do Framework 5.0', 'source' => 'IF5f1cnGl04'];
		$items[] = (object) [ 'id' => 3, 'title' => 'Melhorias do Framework 5.5', 'source' => 'HnC0gg1ik8o'];
		
		foreach ($items as $key => $item)
		{
			$cards->addItem($item);
		}
		
		$cards->setTitleAttribute('title');
		$cards->setColorAttribute('color');
		
		$cards->setItemTemplate('<iframe width="100%" height="300px" src="https://www.youtube.com/embed/{source}""></iframe>');
		
		$action = new TAction([$this, 'onGotoVideo'], ['source'=>'{source}']);
		$cards->addAction($action, 'Go to Youtube', 'far:play-circle red');
		
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
	public static function onGotoVideo($param = NULL)
	{
	    $source = $param['source'];
		TScript::create("window.open('https://www.youtube.com/watch?v={$source}')");
	}
}
