<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>Nicovideo Search RSS</title>
  <meta name="description" content="動画の検索結果をRSSにしています。" />
  <link rel="author" href="mailto:webmaster@fuktommy.com" />
  <link rel="contents" href="/" title="トップ" />
</head>
<body>

<h1>Nicovideo Search RSS</h1>
<ul>
{foreach from=$config.queries key=q item=query}
  <li><a href="/{$q|escape:'url'}">{$q}</a>: {$query|json_encode:$smarty.const.JSON_UNESCAPED_UNICODE}</li>
{/foreach}
</ul>
</body>
</html>
