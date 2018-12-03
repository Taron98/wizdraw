<?php

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Wizdraw\Models\Vip;

class UpdateVipUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-vip-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command updating the vip users for 7-eleven QR code generating';

    /**
     * UpdateVipUsers constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $vip = Vip::all();

        foreach ($vip as $v){
            $vipNumber = $v->getNumber();
            $clientId = $v->getClientId();
            $this->fileService->uploadQrVip($clientId, $vipNumber);
        }
    }
}
