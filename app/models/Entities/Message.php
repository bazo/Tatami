<?php
namespace Entity;

/** @Entity */
class Message extends BaseEntity
{
    protected
    /**
    * @id @column(type="integer")
    * @generatedValue
    */
    $id,

    /** @OneToOne(targetEntity="User") */
    $from,

    /** @OneToOne(targetEntity="User") */
    $to,

    /** @column(type="text") */
    $subject,

    /** @column(type="text") */
    $body,

    /** @OneToOne(targetEntity="Message") */
    $reply_to,

    /**
     *
     */
    $created
    ;
}