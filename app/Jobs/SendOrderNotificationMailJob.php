<?php

namespace App\Jobs;

use App\Mail\OrderNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderNotificationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a  job instance.
     */
    public $user;
    public $order;
    public $notificationType;
    public function __construct($user, $order, $notificationType)
    {
        // Initialize properties if needed
        $this->user = $user;
        $this->order = $order;
        $this->notificationType = $notificationType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send the appropriate email based on the notification type
        switch ($this->notificationType) {
            case 'cancelled':
                // Send order canceled email
                Mail::to($this->user->email)->send(new OrderNotificationMail($this->user, $this->order, 'order-cancelled'));
                break;
            case 'delivered':
                // Send order delivered email
                Mail::to($this->user->email)->send(new OrderNotificationMail($this->user, $this->order, 'order-delivered'));
                break;
            case 'pending':
                // Send order pending email
                Mail::to($this->user->email)->send(new OrderNotificationMail($this->user, $this->order, 'order-pending'));
                break;
            case 'confirmed':
                // Send order verified email
                Mail::to($this->user->email)->send(new OrderNotificationMail($this->user, $this->order, 'order-verified'));
                break;
        }
    }
}
