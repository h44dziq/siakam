<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportStatusHistory extends Model
{
    protected $fillable = ['report_id', 'changed_by', 'from_status', 'to_status', 'note'];

    public function author()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
