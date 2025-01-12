<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WorkplaceSetNotification extends Notification
{
    use Queueable;

    protected $university;
    protected $faculty;
    protected $department;
    protected $notificationType;

    public function __construct($university, $faculty, $department, $notificationType)
    {
        $this->university = $university;
        $this->faculty = $faculty;
        $this->department = $department;
        $this->notificationType = $notificationType;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'university' => $this->university,
            'faculty' => $this->faculty,
            'department' => $this->department,
            'notification_type' => $this->notificationType,
            'message' => 'User has submitted their workplace preferences.',
        ];
    }
}
