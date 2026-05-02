<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class QrisOrder extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'items' => 'array',
        'confirmed_at' => 'datetime',
    ];

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending_payment'   => 'Menunggu Pembayaran',
            'payment_uploaded'  => 'Bukti Dikirim',
            'confirmed'         => 'Dikonfirmasi',
            'processing'        => 'Sedang Diproses',
            'completed'         => 'Selesai',
            'cancelled'         => 'Dibatalkan',
            default             => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending_payment'   => 'yellow',
            'payment_uploaded'  => 'blue',
            'confirmed'         => 'indigo',
            'processing'        => 'green',
            'completed'         => 'emerald',
            'cancelled'         => 'red',
            default             => 'gray',
        };
    }

    public static function generateOrderCode(): string
    {
        do {
            $code = 'GC-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }
}
