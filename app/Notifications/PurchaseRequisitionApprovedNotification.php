<?php

namespace App\Notifications;

use App\Models\PurchaseRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaseRequisitionApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $purchaseRequisition;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\PurchaseRequisition $purchaseRequisition
     */
    public function __construct(PurchaseRequisition $purchaseRequisition)
    {
        $this->purchaseRequisition = $purchaseRequisition;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->purchaseRequisition->number . ' - new purchase requisition was approved')
            ->greeting('Notification')
            ->line('A new purchase requisition was approved - ' . $this->purchaseRequisition->number)
            ->action('View Purchase Requisition', route('purchase-requisitions.show', ['id' => $this->purchaseRequisition->id]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'purchase_requisition_id' => $this->purchaseRequisition->id,
            'number' => $this->purchaseRequisition->number,
            'message' => 'A new purchase requisition was approved',
        ];
    }
}
