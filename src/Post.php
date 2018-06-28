<?php


namespace Revonia\BlogHub;


class Post
{
    protected $content;

    protected $parsers = [];

    protected $processors = [];

    protected $attachments = [];

    protected $metainfo = [];

    protected $processedContent;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function process()
    {

        return $this;
    }



    /**
     * @return mixed
     */
    public function getMetainfo($name)
    {
        return $this->metainfo[$name] ?? null;
    }

    /**
     * @return mixed
     */
    public function setMetainfo($name, $value)
    {
        return $this->metainfo[$name] = $value;
    }

    /**
     * @return mixed
     */
    public function getAttachment($name)
    {
        return $this->attachments[$name] ?? false;
    }

    /**
     * @return mixed
     */
    public function setAttachment($name, $attachment)
    {
        return $this->attachments[$name] = $attachment;
    }

    /**
     * @return mixed
     */
    public function getProcessedContent()
    {
        if ($this->processedContent === null) {

        }
        return $this->processedContent;
    }

}