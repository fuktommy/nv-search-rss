<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>Nicovideo Search RSS</title>
  <link rel="author" href="mailto:webmaster@fuktommy.com" />
  <link rel="contents" href="/" title="feed list" />
</head>
<body>

<h1>Nicovideo Search RSS</h1>
<ul>
{foreach from=$config.queries key=q item=query}
  <li><a href="/{$q|escape:'url'}">{$q}</a>: {$query|json_encode:$smarty.const.JSON_UNESCAPED_UNICODE}</li>
{/foreach}
</ul>
<div>Generated by <a href="https://github.com/fuktommy/nv-search-rss">nicovideo search RSS maker</a></div>
</body>
</html>
