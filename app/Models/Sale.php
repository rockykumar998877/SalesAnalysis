<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['Segment', 'Country', 'Product', 'Discount_Band', 'Units_Sold', 'Manufacturing_Price','Sale_Price', 'Gross_Sales','Discounts','Sales','COGS','Profit','sale_date','Month_Number','Month_Name','Year'];
}
