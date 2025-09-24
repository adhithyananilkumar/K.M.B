<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [ 'support_ticket_id','user_id','message','attachment_path' ];

    public function ticket(): BelongsTo { return $this->belongsTo(SupportTicket::class,'support_ticket_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
