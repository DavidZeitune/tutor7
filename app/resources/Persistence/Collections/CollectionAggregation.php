<?php
class CollectionAggregation extends TPage
{
    public function __construct()
    {
        parent::__construct();
        try
        {
            TTransaction::open('samples');
            TTransaction::setLogger(new TLoggerSTD);
            
            echo '<pre>';
            
    		var_dump(Sale::sumBy('total'));
    		var_dump(Sale::countDistinctBy('total'));
    		var_dump(Sale::groupBy('date, customer_id')->sumBy('total'));
    		var_dump(Sale::where('date', '>', '2015-03-12')->sumBy('total'));
    		var_dump(Sale::where('date', '>', '2015-04-12')->countDistinctBy('id'));
    		var_dump(Sale::where('date', '>', '2015-03-12')->groupBy('date')->maxBy('total'));
    		var_dump(Sale::where('date', '>', '2015-04-12')->where('date', '<', '2019-04-12')->sumBy('total'));
    		var_dump(Sale::where('date', '>', '2015-04-12')->where('date', '<', '2019-04-12')->groupBy('customer_id')->sumBy('total'));
            
            echo '</pre>';
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
