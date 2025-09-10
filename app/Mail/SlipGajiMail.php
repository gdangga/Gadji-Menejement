<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Karyawan;

class SlipGajiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $namaFile;
    public $karyawan;
    public $periode;

    /**
     * Create a new message instance.
     */
    public function __construct($pdfContent, $namaFile, Karyawan $karyawan, $periode)
    {
        $this->pdfContent = $pdfContent;
        $this->namaFile = $namaFile;
        $this->karyawan = $karyawan;
        $this->periode = $periode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Arsip Slip Gaji: ' . $this->karyawan->nama_lengkap . ' - ' . $this->periode,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.slip_gaji',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, $this->namaFile)
                ->withMime('application/pdf'),
        ];
    }
}