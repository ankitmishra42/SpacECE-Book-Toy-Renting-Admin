<?php

namespace App\Mail;

use App\Models\AppSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class orderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public $delivery_charge;

    public $setting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->delivery_charge = $order->delivery_charge;
        $this->setting = AppSetting::first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->setting->name ?? config('app.name'))
            ->view('mail.mail', ['order' => $this->order, 'delivery_charge' => $this->delivery_charge, 'setting' => $this->setting]);
    }
}
