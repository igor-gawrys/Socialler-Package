<?php

namespace Igorgawrys\Socialler\Console\Commands\Grade;

use Illuminate\Console\Command;

class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grade:create {school_id} {grade_name} {grade_description} {grade_avatar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command create grade';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
