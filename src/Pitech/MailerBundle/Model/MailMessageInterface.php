<?php

namespace Pitech\MailerBundle\Model;

interface MailMessageInterface
{
    public function getSubject();
    public function getFrom();
    public function getTo();
    public function getCc();
    public function getBody();
    public function getAttachmentPath();
    public function getAttachmentName();
}
