<?php

namespace App;

trait Favoritable
{

    /**
     * A reply can be favorited
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * Favorite the current model
     *
     * @return Model
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * Unavorite the current model
     *
     * @return Model
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        return $this->favorites()->where($attributes)->delete();
    }

    /**
     * Return wether the current model is favorited
     *
     * @return boolean
     */
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * Return wether the current model is favorited
     *
     * @return boolean
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    /**
     * Returns favorites count
     *
     * @return int
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
