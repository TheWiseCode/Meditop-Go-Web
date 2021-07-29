<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationNotify extends Notification
{
    use Queueable;

    private $accept;
    private $detail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($accept, $detail = null)
    {
        $this->accept = $accept;
        $this->detail = $detail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = env('APP_URL');
        $name = env('APP_NAME');
        if($this->accept){
            $message = "Hola {$notifiable->name}, te notificamos que tu registro de medico para la plataforma ha sido aceptada, puedes acceder al siguiente link para empezar a trabajar, gracias por preferirnos";
        }else{
            $message = "Hola {$notifiable->name}, te notificamos que tu registro de medico ha sido rechazado, debido a {$this->detail}, puedes acceder a la plataforma para una solicitar nueva verificación";
        }
        return (new MailMessage)
            ->subject("{$name} Verificación de registro respondida")
            ->greeting($message)
            ->action('Acceder ahora', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
