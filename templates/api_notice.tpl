<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="/atomfeed.xsl" type="text/xsl"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>Nicovideo Search API Document Notice</title>
  <subtitle>APIドキュメントの更新履歴</subtitle>
  <link rel="self" href="{$config.site_top}/api.notice" />
  <link rel="alternate" href="{$config.api_document}" type="text/html"/>
  <updated>{if $histories}{$histories[0]->date|atom_date_format}{else}{$smarty.now|atom_date_format}{/if}</updated>
  <generator>https://github.com/fuktommy/nv-search-rss</generator>
  <author><name>nicovideo.jp</name></author>
  <id>tag:fuktommy.com,2021:nvsearch.rss</id>
  <icon>{$config.site_top}/favicon.ico</icon>
{foreach from=$histories item=history}
  <entry>
    <title>{$history->title|htmlspecialchars_decode|mbtruncate:140}</title>
    <link rel="alternate" href="{$config.api_document}?date={$history->date|atom_date_format|escape:'url'}"/>
    <summary type="text">{$history->title|htmlspecialchars_decode}</summary>
    <published>{$history->date|atom_date_format}</published>
    <updated>{$history->date|atom_date_format}</updated>
    <id>tag:fuktommy.com,2021:nvsearch.rss/api.notice/{$history->date|atom_date_format|escape:'url'}</id>
  </entry>
{/foreach}
</feed>
