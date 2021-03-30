<?php
/*
 * Copyright (c) 2012,2021 Satoshi Fukutomi <info@fuktommy.com>.
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
namespace Fuktommy\NvSearch;

require_once __DIR__ . '/../libs/Fuktommy/Bootstrap.php';
use Fuktommy\Bootstrap;
use Fuktommy\NvSearch\Model\SearchApi;
use Fuktommy\WebIo\Action;
use Fuktommy\WebIo\Context;


class IndexAction implements Action
{
    public function execute(Context $context)
    {
        $api = new SearchApi($context->getResource());
        $q = $context->get('get', 'q');
        if (empty($q)) {
            $context->putHeader('Content-Type', 'text/html; charset=utf-8');
            $smarty = $context->getSmarty();
            $smarty->assign('config', $context->config);
            $smarty->display('list.tpl');
            return;
        }

        if (! array_key_exists($q, $context->config['queries'])) {
            $context->putHeader('HTTP/1.1 404 Not Found');
            return;
        }
        $query = $context->config['queries'][$q];
        $videos = $api->search($query);

        $smarty = $context->getSmarty();
        $smarty->assign('config', $context->config);
        $smarty->assign('q', $q);
        $smarty->assign('query', $query);
        $smarty->assign('videos', $videos);
        $smarty->display('atom.tpl');
    }
}


(new Controller())->run(new IndexAction(), Bootstrap::getContext());
