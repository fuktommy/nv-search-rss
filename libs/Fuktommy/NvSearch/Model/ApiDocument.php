<?php
/*
 * Copyright (c) 2012-2021 Satoshi Fukutomi <info@fuktommy.com>.
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
namespace Fuktommy\NvSearch\Model;

use Fuktommy\NvSearch\Entity\ApiNotice;
use Fuktommy\Http\CachingClient;
use Fuktommy\WebIo\Resource;


class ApiDocument
{
    /**
     * @var Fuktommy\WebIo\Resource
     */
    private $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return Fuktommy\NvSearch\Entity\ApiDocument[]
     */
    public function getHistory(): array
    {
        $url = $this->resource->config['api_document_md'];
        $cacheTime = $this->resource->config['cache_time'];
        $client = new CachingClient($this->resource);
        $text = $client->fetch($url, $cacheTime);

        $histories = [];
        $inHistory = false;
        foreach (explode("\n", $text) as $line) {
            $line = trim($line);
            if ($line === '## 更新履歴') {
                $inHistory = true;
                continue;
            } else if ($line === '') {
                continue;
            } else if (! $inHistory) {
                continue;
            }
            if (! preg_match('/^-\s+(\d{4}).(\d{2}).(\d{2})\s+(.+)/', $line, $matches)) {
                $inHistory = false;
                continue;
            }
            $date = "{$matches[1]}-{$matches[2]}-{$matches[3]}";
            $title = $matches[4];
            $histories[] = new ApiNotice($title, $date);
        }

        return $histories;
    }
}
