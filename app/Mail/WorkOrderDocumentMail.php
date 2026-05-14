<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\WorkPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkOrderDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Company $company,
        public WorkPlan $workOrder,
        public string $documentLabel,
        public ?string $documentNumber,
        private string $filename,
        private string $pdfContent
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->documentLabel} PDF - {$this->documentNumber}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.work-order-document',
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn() => $this->pdfContent, $this->filename)
                ->withMime('application/pdf'),
        ];
    }
}
