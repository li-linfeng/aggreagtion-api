<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Carbon;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QueryExecuted  $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {

        $sql = str_replace(array('%', '?'), array('"%%"', '"%s"'), $event->sql);
        foreach ($event->bindings as &$bingding) {
            if ($bingding instanceof Carbon) {
                $bingding = $bingding->toDateTimeString();
            }
        }

        $sql = vsprintf($sql, object2Array($event->bindings));
        $log = ' execution time: ' . $event->time . "ms;" . $sql . "\n";

        // if (config('app.debug')) {
        if (!preg_match("`telescope_entries`", $log)) {
            \Log::channel('sql')->info($log);
        }
    }
}
