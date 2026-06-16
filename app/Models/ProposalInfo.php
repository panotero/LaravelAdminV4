<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalInfo extends Model
{
    use HasFactory;

    protected $table = 'proposals_rates';

    protected $fillable = [
        'proposal_id',
        'proposed_rate',
        'route_from',
        'route_to',
        'min_van_qty',
        'van_type',
        'van_size',
        'origin_service_type',
        'destination_service_type',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function routeFrom()
    {

        return $this->belongsTo(Route::class, 'route_from', 'id');
    }
    public function routeTo()
    {

        return $this->belongsTo(Route::class, 'route_to', 'id');
    }

    public function vanType()
    {

        return $this->belongsTo(VanType::class, 'van_type', 'id');
    }
    public function vanSize()
    {

        return $this->belongsTo(VanSize::class, 'van_type', 'id');
    }
    public function serviceOrigin()
    {

        return $this->belongsTo(Service::class, 'origin_service_type', 'id');
    }
    public function serviceDestination()
    {

        return $this->belongsTo(Service::class, 'destination_service_type', 'id');
    }
}
