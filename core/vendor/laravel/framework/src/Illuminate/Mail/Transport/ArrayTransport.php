<?php

namespace Illuminate\Mail\Transport;

use Swift_Mime_Message;
use Illuminate\Support\Collection;

class ArrayTransport extends Transport
{
    /**
     * The collection of Swift Messages.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $messages;

    /**
     * Create a new array transport instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->messages = new Collection;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $this->messages[] = $message;

        return $this->numberOfRecipients($message);
    }

    /**
     * Retrieve the collection of messages.
     *
     * @return \Illuminate\Support\Collection
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Clear all of the messages from the local collection.
     *
<<<<<<< HEAD
     * @return \Illuminate\Support\Collection
=======
     * @return void
>>>>>>> 7ac4634153a5f74a4bb46f5763b8a8ea5d024577
     */
    public function flush()
    {
        return $this->messages = new Collection;
    }
}
