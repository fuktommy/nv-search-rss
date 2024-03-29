<?php
/*
 * Copyright (c) 2021,2022 Satoshi Fukutomi <info@fuktommy.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHORS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHORS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

namespace Fuktommy\NvSearch\Entity;

class Video
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $published;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string[]
     */
    public $tags;

    /**
     * @var string
     */
    public $thumbnailUrl;

    public function __construct(array $record, $modifyDate)
    {
        $this->id = $record['contentId'];
        $this->title = $record['title'];
        $this->published = $record['startTime'];
        $this->thumbnailUrl = $record['thumbnailUrl'];
        $this->tags = explode(' ', $record['tags']);

        $this->date = $record['startTime'];
        if ($modifyDate) {
            if (! empty($record['lastCommentTime'])) {
                $this->date = $record['lastCommentTime'];
            }
            $threshold = date('Y-m-d\T00:00:00P', time() - 2 * 24 * 60 * 60);
            if (strtotime($this->date) < strtotime($threshold)) {
                $this->date = $threshold;
            }
        }
    }
}
