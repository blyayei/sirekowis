<?php

namespace App\Database\Query\Grammars;

use Illuminate\Database\Query\Grammars\MySqlGrammar;

class CompatibleMySqlGrammar extends MySqlGrammar
{
    /**
     * Compile a query to retrieve the current open connection count.
     *
     * Some MySQL-compatible servers do not expose
     * performance_schema.session_status. The information_schema status table
     * is available on older MySQL-compatible servers and returns one scalar
     * value as expected by db:show.
     */
    public function compileThreadCount()
    {
        return "select variable_value as `Value` from information_schema.global_status where variable_name = 'Threads_connected'";
    }
}
