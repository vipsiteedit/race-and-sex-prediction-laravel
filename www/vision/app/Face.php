<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Face extends Model
{
    protected $table = 'faces';
    //protected $primaryKey = 'id';
    //public $incrementing = false;
    //public $timestamp = false;


    public static function faceList($userId)
    {
        return static::where('user_id', $userId)->orderBy('id', 'DESC')->get();
    }

    public static function faceItem($userId, $id)
    {
        return static::where('user_id', $userId)->where('id', $id)->first();
    }

    public static function faceDelete($userId, $id)
    {
        try {
            static::with('user_id', $userId)->where('id', $id)->delete();
        } catch (\Exception $e) {

        }
    }

    public static function incomplete()
    {
        return static::where('completed', 0)->get();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * @param mixed $completed
     */
    public function setCompleted($completed): void
    {
        $this->completed = $completed;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }


    /**
     * @param $error
     */
    public function setError($error): void
    {
        $this->error = $error;
    }
}
