<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserRequest;
use App\Models\Chat;

class CheckUserRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of user requests and their chats';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking UserRequests...');
        
        $requests = UserRequest::with(['chat'])->get();
        
        if ($requests->isEmpty()) {
            $this->warn('No UserRequests found in database.');
            return;
        }
        
        $this->table(
            ['ID', 'Status', 'Chat ID', 'Has Chat', 'Initiator', 'Owner'],
            $requests->map(function($request) {
                return [
                    $request->id,
                    $request->status,
                    $request->chat ? $request->chat->id : 'No Chat',
                    $request->chat ? 'Yes' : 'No',
                    $request->initiator_user_id,
                    $request->announcement_owner_id,
                ];
            })
        );
        
        $this->info('Checking Chats...');
        
        $chats = Chat::with(['userRequest'])->get();
        
        if ($chats->isEmpty()) {
            $this->warn('No Chats found in database.');
            return;
        }
        
        $this->table(
            ['Chat ID', 'Request ID', 'Has Request', 'Request Status'],
            $chats->map(function($chat) {
                return [
                    $chat->id,
                    $chat->request_id,
                    $chat->userRequest ? 'Yes' : 'No',
                    $chat->userRequest ? $chat->userRequest->status : 'N/A',
                ];
            })
        );
    }
}
