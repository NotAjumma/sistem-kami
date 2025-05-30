<?php

namespace App\Jobs;

use App\Models\Participant; // Pastikan model betul
use App\Mail\ParticipantConfirmationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessParticipant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $data = $this->data;

        if (Participant::where('email', $data['email'])->exists()) {
            return;
        }

        $participant = Participant::create([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        Mail::to($participant->email)->send(new ParticipantConfirmationMail($participant));
    }
}

