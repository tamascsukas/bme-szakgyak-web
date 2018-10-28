<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * The model object that represent verification token; helps reaching data from DB.
 *
 * @category Class
 * @package  App
 * @author   Cs.T.
 *
 */
class VerificationToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['token'];

    /**
     * Disable default timestamp properties.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Use the token  field rather than the id  field to do an implicit model binding when we fetch the token from the link
    public function getRouteKeyName()
    {
        return 'token';
    }

    /**
    * Access the user that the specific token belongs to
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
