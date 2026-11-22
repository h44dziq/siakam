<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Report extends Model
{
    public const LABELS = [
        'reported' => 'Dilaporkan',
        'in_progress' => 'Diproses',
        'resolved' => 'Selesai',
    ];

    // State machine: hanya maju satu tahap, tidak ada rollback.
    public const NEXT = [
        'reported' => 'in_progress',
        'in_progress' => 'resolved',
    ];

    protected $fillable = ['user_id', 'room_id', 'category_id', 'description', 'photo_path', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function histories()
    {
        return $this->hasMany(ReportStatusHistory::class)->orderBy('id');
    }

    public function label(): string
    {
        return self::LABELS[$this->status] ?? $this->status;
    }

    public function step(): int
    {
        return array_search($this->status, array_keys(self::LABELS)) + 1;
    }

    public function nextStatus(): ?string
    {
        return self::NEXT[$this->status] ?? null;
    }

    public function advance(User $by, string $to, ?string $note = null): void
    {
        if ($this->nextStatus() !== $to) {
            throw ValidationException::withMessages([
                'status' => 'Status hanya bisa maju satu tahap dan tidak bisa dikembalikan.',
            ]);
        }

        DB::transaction(function () use ($by, $to, $note) {
            $from = $this->status;
            $this->update(['status' => $to]);
            $this->histories()->create([
                'changed_by' => $by->id,
                'from_status' => $from,
                'to_status' => $to,
                'note' => $note,
            ]);
        });
    }

    public function daysOpen(): int
    {
        return (int) $this->created_at->diffInDays(now());
    }

    public function scopeFilter(Builder $query, array $f): Builder
    {
        return $query
            ->when($f['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($f['q'] ?? null, fn ($q, $v) => $q->whereHas('user', fn ($u) => $u->where(
                fn ($w) => $w->where('name', 'like', "%{$v}%")->orWhere('nim', 'like', "%{$v}%")
            )))
            ->when($f['building_id'] ?? null, fn ($q, $v) => $q->whereHas('room', fn ($r) => $r->where('building_id', $v)))
            ->when($f['room_id'] ?? null, fn ($q, $v) => $q->where('room_id', $v))
            ->when($f['category_id'] ?? null, fn ($q, $v) => $q->where('category_id', $v))
            ->when($f['from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($f['to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->when($f['month'] ?? null, fn ($q, $v) => $q->whereMonth('created_at', $v))
            ->when($f['year'] ?? null, fn ($q, $v) => $q->whereYear('created_at', $v));
    }
}
