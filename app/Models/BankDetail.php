<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;

    protected $table = 'bank_details';

    protected $fillable = [
        'user_id',
        'bank_name',
        'account_holder_name',
        'branch_name',
        'account_number',
        'ifsc_code',
        'other_information',
    ];
}
