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

use Fuktommy\NvSearch\Entity\Video;
use Fuktommy\Http\CachingClient;
use Fuktommy\WebIo\Resource;


class SearchApi
{
    const LIMIT = 100;
    const MAX_OFFSET = 900;

    /**
     * @var Fuktommy\WebIo\Resource
     */
    private $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return Fuktommy\NvSearch\Entity\Video[]
     */
    public function search(array $query): array
    {
        $query['fields'] = 'contentId,title,startTime,lastCommentTime,tags';
        $query['_context'] = $this->resource->config['context'];
        if (! array_key_exists('_sort', $query)) {
            $query['_sort'] = 'startTime';
        }

        if (array_key_exists('_offset', $query)) {
            $offset = intval($query['_offset']);
        } else {
            $offset = 0;
        }
        $feedSize = $this->resource->config['feed_size'];
        $limit = min(self::LIMIT, $feedSize);

        $videos = [];
        while (count($videos) < $feedSize) {
            $v = $this->doSearch($query, $offset, $limit);
            $isLastPage = count($v) < $limit;
            if (! empty($query['extra']['chOnly'])) {
                $v = array_filter($v, function($u) {
                    return preg_match('/^so/', $u->id);
                });
            }
            $videos = array_merge($videos, $v);
            if ($isLastPage) {
                break;
            }
            $offset += $limit;
            if ($offset > self::MAX_OFFSET) {
                break;
            }
        }
        return array_slice($videos, 0, $feedSize);
    }

    /**
     * @return Fuktommy\NvSearch\Entity\Video[]
     */
    private function doSearch(array $query, $offset, $limit): array
    {
        unset($query['extra']);
        $query['_offset'] = $offset;
        $query['_limit'] = $limit;
        $q = http_build_query($query);
        $url = "{$this->resource->config['search_api']}?{$q}";
        $cacheTime = $this->resource->config['cache_time'];
        $feedSize = $this->resource->config['feed_size'];

        $client = new CachingClient($this->resource);
        $json = $client->fetch($url, $cacheTime);
        $result = json_decode($json, true);

        if ($result['meta']['status'] != 200) {
            $this->resource->getLog('SearchClient')->warning("failed to fetch: {$q} -> {$json}");
            return [];
        }

        $videos = [];
        foreach ($result['data'] as $item) {
            $videos[] = new Video($item);
        }

        return $videos;
    }
}
