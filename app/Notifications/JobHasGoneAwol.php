<?php

namespace App\Notifications;

use App\Models\Cronjob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobHasGoneAwol extends Notification
{
    use Queueable;

    public $job;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Cronjob $job)
    {
        $this->job = $job;
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
            ->subject(config('cronmon.email_prefix').' Job has not run')
            ->line('Cron job "'.$this->job->name.'" has not run')
            ->action('Check the status', route('job.show', $this->job->id))
            ->line('Job : '.$this->job->name)
            ->line('Last Run : '.$this->job->getLastRun().' ('.$this->job->getLastRunDiff().')')
            ->line('Schedule : '.$this->job->getSchedule());
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
            //
        ];
    }
}
