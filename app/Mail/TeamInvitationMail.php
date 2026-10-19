<?php

namespace App\Mail;

use App\Models\AdminInvitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AdminInvitation $invitation
    ) {
    }

    public function envelope(): Envelope
    {
        $roleLabel = User::ROLES[$this->invitation->role]['label'] ?? 'Team Member';

        return new Envelope(
            subject: "You've Been Invited to Join Cross Rivers Ministries as {$roleLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
            with: [
                'invitation' => $this->invitation,
                'roleDetails' => User::ROLES[$this->invitation->role] ?? null,
                'acceptUrl' => $this->invitation->getInvitationUrl(),
                'inviterName' => $this->invitation->inviter?->name ?? 'The Cross Rivers Ministries Team',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
