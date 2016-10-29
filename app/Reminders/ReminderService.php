<?php

namespace Codice\Reminders;

use Codice\Note;
use Codice\Reminder;

abstract class ReminderService implements ReminderServiceInterface
{
    /**
     * Internal reminder's identifier. Maximum 10 characters!
     *
     * @var string
     */
    protected $id;

    /**
     * List of registered ReminderServices.
     *
     * @var array
     */
    protected static $services = [];

    /**
     * Register new ReminderService.
     */
    public static function register($classname)
    {
        $object = new $classname;

        self::$services[$object->getID()] = $classname;

        return true;
    }

    /**
     * Return list of registered ReminderServices.
     */
    public static function getRegistered()
    {
        return self::$services;
    }

    /**
     * Return list of registered ReminderServices' IDs.
     */
    public static function getRegisteredKeys()
    {
        return array_keys(self::$services);
    }


    /**
     * Factory returning registered ReminderService object.
     */
    public static function get($id)
    {
        $classname = self::$services[$id];
        return new $classname;
    }

    /**
     * {@inheritdoc}
     */
    public function set(Note $note, $input, $data = [])
    {
        return Reminder::create([
            'note_id' => $note->id,
            'remind_at' => strtotime($input["reminder_{$this->id}"]),
            'data' => $data,
            'type' => $this->id,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function change(Reminder $reminder, $input, $data = [])
    {
        $reminder->remind_at = strtotime($input["reminder_{$this->id}"]);
        $reminder->data = $data;
        return $reminder->save();
    }

    /**
     * {@inheritdoc}
     */
    public function cancel($id)
    {
        return Reminder::find($id)->delete();
    }

    /**
     * Return internal reminder's identifier.
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Process reminder while editing a note.
     */
    public function process(Note $note, $input)
    {
        $inputKey = $input["reminder_{$this->id}"];
        $reminder = $note->reminder($this->id);

        // Note has a reminder and form has it - update existing one
        if (!empty($inputKey) && !empty($reminder)) {
            $this->change($reminder, $input);
        // Note doesn't have a reminder but it's present in form - set one
        } elseif (!empty($inputKey) && $reminder === null) {
            $this->set($note, $input);
        // Note have a reminder but it's missing in form - cancel reminder
        } elseif (empty($inputKey) && !empty($reminder)) {
            $this->cancel($reminder);
        }
    }
}
