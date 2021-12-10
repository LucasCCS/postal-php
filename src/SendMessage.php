<?php
namespace Postal;

class SendMessage
{
    protected $client;

    public $attributes = [];

    public function __construct($client)
    {
        $this->client = $client;
        $this->attributes['to'] = [];
        $this->attributes['cc'] = [];
        $this->attributes['bcc'] = [];
        $this->attributes['headers'] = null;
        $this->attributes['attachments'] = [];
    }

    public function to($address): self
    {
        $this->attributes['to'][] = $address;

        return $this;
    }

    public function cc($address): self
    {
        $this->attributes['cc'][] = $address;

        return $this;
    }

    public function bcc($address): self
    {
        $this->attributes['bcc'][] = $address;

        return $this;
    }

    public function from($address): self
    {
        if (is_array($address)) {
            $this->attributes['from'] = sprintf('%s <%s>', $address[key($address)], key($address));
        } else {
            $this->attributes['from'] = $address;
        }

        return $this;
    }

    public function sender($address): self
    {
        $this->attributes['sender'] = $address;

        return $this;
    }

    public function subject($subject): self
    {
        $this->attributes['subject'] = $subject;

        return $this;
    }

    public function tag($tag): self
    {
        $this->attributes['tag'] = $tag;

        return $this;
    }

    public function replyTo($replyTo): self
    {
        if (is_array($replyTo)) {
            $this->attributes['reply_to'] = sprintf('%s <%s>', $replyTo[key($replyTo)], key($replyTo));
        } else {
            $this->attributes['reply_to'] = $replyTo;
        }

        return $this;
    }

    public function plainBody($content): self
    {
        $this->attributes['plain_body'] = $content;

        return $this;
    }

    public function htmlBody($content): self
    {
        $this->attributes['html_body'] = $content;

        return $this;
    }

    public function header($key, $value): self
    {
        $this->attributes['headers'][$key] = $value;

        return $this;
    }

    public function attach($filename, $content_type, $data): self
    {
        $attachment = [
            'name' => $filename,
            'content_type' => $content_type,
            'data' => base64_encode($data),
        ];

        $this->attributes['attachments'][] = $attachment;

        return $this;
    }


    public function send()
    {
        $result = $this->client->makeRequest('send', 'message', $this->attributes);

        return new SendResult($this->client, $result);
    }
}
