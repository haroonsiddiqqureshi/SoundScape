<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Channels\LineChannel;

class ConcertUpdatedNotification extends Notification
{
    use Queueable;

    public $concert;
    public $changes;

    public function __construct($concert, array $changes)
    {
        $this->concert = $concert;
        $this->changes = $changes;
    }

    public function via($notifiable)
    {
        $channels = ['database']; 
        $preferences = $notifiable->notification_preferences ?? [];

        if (in_array('email', $preferences)) {
            $channels[] = 'mail';
        }
        
        if (in_array('line', $preferences) && !empty($notifiable->line_id)) {
            $channels[] = LineChannel::class;
        }

        return $channels;
    }

    protected function getOriginalConcertName()
    {
        if (isset($this->changes['name'])) {
            return $this->changes['name']['old'];
        }
        return $this->concert->name;
    }

    public function toMail($notifiable)
    {
        $url = route('concert.detail', $this->concert->id);
        $oldName = $this->getOriginalConcertName();

        $formattedChanges = [];
        foreach ($this->changes as $field => $change) {
            $formattedChanges[$this->translateField($field)] = $change;
        }

        return (new MailMessage)
            ->subject('อัปเดตคอนเสิร์ต: ' . $oldName)
            ->view('emails.concert-updated', [
                'userName' => $notifiable->name,
                'concertName' => $oldName,
                'changes' => $formattedChanges,
                'url' => $url,
            ]);
    }

    public function toLine($notifiable)
    {
        $oldName = $this->getOriginalConcertName();
        $message = "🚨 อัปเดตคอนเสิร์ต: {$oldName} 🚨\n\n";
        
        foreach ($this->changes as $field => $change) {
            $thaiField = $this->translateField($field);
            $message .= "• {$thaiField}\n\tเดิม: {$change['old']}\n\tใหม่: {$change['new']}\n\n";
        }
        
        // $message .= "ดูรายละเอียด: " . route('concert.detail', $this->concert->id);
        $message .= "ดูรายละเอียดเพิ่มเติมผ่านเว็บไซต์";

        return $message;
    }

    public function toArray($notifiable)
    {
        $fieldsUpdated = count($this->changes);
        $summary = "มีการอัปเดตข้อมูล {$fieldsUpdated} รายการ";
        $oldName = $this->getOriginalConcertName();

        return [
            'concert_id' => $this->concert->id,
            'concert_name' => $oldName,
            'message' => $summary,
            'changes' => $this->changes,
        ];
    }

    private function translateField($field)
    {
        $map = [
            'name' => 'ชื่อคอนเสิร์ต',
            'description' => 'รายละเอียด',
            'event_type' => 'ประเภทงาน',
            'genre' => 'แนวเพลง',
            'venue_name' => 'สถานที่จัดงาน',
            'province_id' => 'จังหวัด',
            'latitude' => 'ละติจูด',
            'longitude' => 'ลองจิจูด',
            'price_min' => 'ราคาต่ำสุด',
            'price_max' => 'ราคาสูงสุด',
            'start_sale_date' => 'วันที่เริ่มขายบัตร',
            'end_sale_date' => 'วันสิ้นสุดการขายบัตร',
            'start_show_date' => 'วันที่เริ่มแสดง',
            'start_show_time' => 'เวลาเริ่มแสดง',
            'end_show_date' => 'วันที่สิ้นสุดการแสดง',
            'end_show_time' => 'เวลาสิ้นสุดการแสดง',
            'start_datetime' => 'วันและเวลาที่เริ่มแสดง',
            'ticket_link' => 'ลิงก์ซื้อบัตร',
            'Poster Picture' => 'รูปภาพโปสเตอร์',
            'Artists' => 'รายชื่อศิลปิน',
        ];

        return $map[$field] ?? ucwords(str_replace('_', ' ', $field));
    }
}