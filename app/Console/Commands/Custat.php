<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
USE Carbon\Carbon;

class Custat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:custat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach( DB::table('customers')->orderBy('id', 'ASC')->get() as $item){
            $bills = DB::table('orders')->where('name', $item->name)->where('deleted',NULL)->where('save', NULL)->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-1'])
            ->where('created_at', '>=', now()->subMonth(6))->groupBy('orderid')->get();

            $numbills = count($bills);
            if($numbills > 3){
                $status = 'regular';
                $col = 'green';
            }
            elseif($numbills >= 1 && $numbills <= 3){
                $status = 'occasional';
                $col = 'blue';
            }
            elseif($numbills = 0){
                $status = 'inactive';
                $col = 'red';
            }
            else{
                $status = 'inactive';
                $col = 'red';
            }
            DB::table('customers')->where('id', $item->id)->update([
                'activity'=>$status,
                'actcolor'=>$col,
                'billcnt'=>$numbills,
            ]);
        }
    }
}
