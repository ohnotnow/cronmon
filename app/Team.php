<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Cronjob::class);
    }

    public function removeMembers($userIds)
    {
        return $this->members()->detach($userIds);
    }

    public function addMember($userId)
    {
        if ($this->isAlreadyAMember($userId)) {
            return false;
        }

        return $this->members()->attach($userId);
    }

    protected function isAlreadyAMember($userId)
    {
        return $this->members()->where('user_id', $userId)->first();
    }

    public function delete()
    {
        $this->jobs->each->update(['team_id' => null]);
        parent::delete();
    }
}
