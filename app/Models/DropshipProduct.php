<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Generalsetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class DropshipProduct extends Model
{
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Subcategory');
    }

    public function childcategory()
    {
        return $this->belongsTo('App\Models\Childcategory');
    }

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function clicks()
    {
        return $this->hasMany('App\Models\ProductClick');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Report', 'user_id');
    }

    public function conversations()
    {
        return $this->hasMany('App\Models\Conversation');
    }

    public function rp($angka = 0)
    {
        return number_format($angka, 0, ',', '.');
    }

    public function vendorPrice()
    {
        $gs = Generalsetting::findOrFail(1);
        $price = $this->price;
        if ($this->user_id != 0) {
            $price = $this->publish_price <> '' ? $this->publish_price : $this->price;
        }

        return $price;
    }

    public function vendorSizePrice()
    {
        $gs = Generalsetting::findOrFail(1);
        $price = $this->price;
        if ($this->user_id != 0) {
            $price = $this->publish_price <> '' ? $this->publish_price : $this->price;
        }
        if (!empty($this->size)) {
            $price += $this->size_price[0];
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }

        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }

                }
            }
        }

        // Attribute Section Ends

        return $price;
    }

    public function setCurrency()
    {
        $gs = Generalsetting::findOrFail(1);
        $price = $this->previous_price;
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $price = round($price * $curr->value, 2);
        $price = $this->rp($price);
        if ($gs->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function showPrice()
    {
        $gs = Generalsetting::findOrFail(1);
        $price = $this->publish_price;


        if (!empty($this->size)) {
            $price += $this->size_price[0];
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }
        // dd($attrArr);
        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }

                }
            }
        }

        // Attribute Section Ends

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }

        $price = round(($price) * $curr->value, 2);
        $price = $this->rp($price);
        if ($gs->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $curr->sign . $price;
        }
    }


    public function showCurrentPrice()
    {
        return $this->publish_price;
    }

    public function showPrevPrice()
    {
        return $this->publish_previous_price;
    }


    public function showPreviousPrice()
    {
        $gs = Generalsetting::findOrFail(1);
        $price = $this->previous_price;
        if (!$price) {
            return '';
        }
        if ($this->user_id != 0) {
            $price = $this->publish_previous_price <> '' ? $this->publish_previous_price : $this->previous_price;
        }

        if (!empty($this->size)) {
            $price += $this->size_price[0];
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }
        // dd($attrArr);
        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }

                }
            }
        }

        // Attribute Section Ends

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $price = round($price * $curr->value, 2);
        $price = $this->rp($price);
        if ($gs->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $curr->sign . $price;
        }
    }

    public function showCoin()
    {
        $gs = Generalsetting::findOrFail(1);
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $price = round(str_replace(['Rp ', '.',],  '', $this->showPrice()) * $curr->value);
        $price = round($price / $gs->edccash_currency, 3); //change to edc
        if ($gs->currency_format == 0) {
            return $price;
        } else {
            return $price;
        }
    }


    public static function showCoin2($publish_price)
    {
        $gs = Generalsetting::findOrFail(1);
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $price = round($publish_price * $curr->value);
        $price = round($price / $gs->edccash_currency, 3); //change to edc
        if ($gs->currency_format == 0) {
            return $price;
        } else {
            return $price;
        }
    }


    
    public static function convertPrice($price)
    {
        $gs = Generalsetting::findOrFail(1);
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $price = round($price * $curr->value, 2);
        $price = parent::rp($price);
        if ($gs->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $curr->sign . $price;
        }
    }
    //new function
    public static function convertPriceToCoint($price)
    {
        $gs = Generalsetting::findOrFail(1);
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $price = round($price * $curr->value);
        $price = round($price / $gs->edccash_currency, 3); //change to edc
        $price = str_replace('.', ',', $price);
        if ($gs->currency_format == 0) {
            return $price;
        } else {
            return $price;
        }
    }
    //new function

    public static function vendorConvertPrice($price)
    {
        $gs = Generalsetting::findOrFail(1);

        $curr = Currency::where('is_default', '=', 1)->first();
        $price = round($price * $curr->value, 2);
        $price = parent::rp($price);
        if ($gs->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $curr->sign . $price;
        }
    }

    public static function convertPreviousPrice($price)
    {
        $gs = Generalsetting::findOrFail(1);
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $price = round($price * $curr->value, 2);
        $price = parent::rp($price);
        if ($gs->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function showName()
    {
        $name = strlen($this->name) > 43 ? substr($this->name, 0, 43) . '...' : $this->name;
        return $name;
    }

    public function emptyStock()
    {
        $stck = (string) $this->stock;
        if ($stck == "0") {
            return true;
        }
    }

    public static function showTags()
    {
        $tags = null;
        $tagz = '';
        $name = Product::where('status', '=', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            if (!empty($nm)) {
                foreach ($nm as $n) {
                    $tagz .= $n . ',';
                }
            }
        }
        $tags = array_unique(explode(',', $tagz));
        return $tags;
    }

    public function getSizeAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizeQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizePriceAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getTagsAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMetaTagAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getFeaturesAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorsAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getLicenseAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',,', $value);
    }

    public function getLicenseQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellDiscountAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }
    
}
