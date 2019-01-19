<?php

namespace App;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }
        
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     * Returns the activities which will be recorded
     *
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * Records user's activities.
     *
     * @param string $event
     * @return void
     */
    protected function recordActivity($event)
    {
        $this->activities()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->id()
        ]);
    }

    /**
     * Returns the activity's type.
     *
     * @param string $event
     * @return string
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

    /**
     * The model has activities.
     *
     * @return void
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
