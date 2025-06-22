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
    public $order;
    public $user;
    public $notificationType;
    public function __construct($order, $user, $notificationType)
    {
        // Initialize properties if needed
        $this->order = $order;
        $this->user = $user;
        $this->notificationType = $notificationType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send the appropriate email based on the notification type
        switch ($this->notificationType) {
            case 'order-canceled':
                // Send order canceled email
                Mail::to($this->user->email)->send(new OrderNotificationMail($this->order, $this->user, 'order-canceled'));
                break;
            case 'order-delivered':
                // Send order delivered email
                Mail::to($this->user->email)->send(new OrderNotificationMail($this->order, $this->user, 'order-delivered'));
                break;
            case 'order-pending':
                // Send order pending email
                Mail::to($this->user->email)->send(new OrderNotificationMail($this->order, $this->user, 'order-pending'));
                break;
            case 'order-verified':
                // Send order verified email
                Mail::to($this->user->email)->send(new OrderNotificationMail($this->order, $this->user, 'order-verified'));
                break;
        }
    }
}
