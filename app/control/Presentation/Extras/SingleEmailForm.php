<?php
/**
 * LeadsEmailForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SingleEmailForm extends TWindow
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        parent::setTitle('Send e-mail');
        parent::setSize(0.8, 650);
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_EmailForm');
        $this->form->setProperty('style', 'border:0');
        
        $email   = new TEntry('email');
        $subject = new TEntry('subject');
        $message = new THtmlEditor('message');
        
        $email->setSize('70%');
        $subject->setSize('70%');
        $message->setSize('100%', '300');
        
        $this->form->addFields( [new TLabel('Email')], [$email] );
        $this->form->addFields( [new TLabel('Subject')], [$subject] );
        $this->form->addFields( [new TLabel('Message')], [$message] );
        
        // add the search form actions
        $btn = $this->form->addAction('Send message', new TAction(array($this, 'onSendEmail')), 'far:envelope');
        $btn->class = 'btn btn-sm btn-primary';
        
        parent::add($this->form);
    }
    
    public function onLoad($param)
    {
        $data = new stdClass;
        $data->email = $param['email'];
        $this->form->setData($data);
    }
    
    public static function onSendEmail($param)
    {
        try
        {
            /*
            TTransaction::open('permission');
            $preferences = SystemPreference::getAllPreferences();
            TTransaction::close();
            
            MailService::send( trim($param['email']), $data->subject, $data->message );
            */
            
            new TMessage('info', _t('Message sent successfully'));
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
