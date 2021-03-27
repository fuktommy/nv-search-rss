<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="/atomfeed.xsl" type="text/xsl"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>動画検索: {$q}</title>
  <subtitle>{$query|json_encode:$smarty.const.JSON_UNESCAPED_UNICODE} の検索結果</subtitle>
  <link rel="self" href="{$config.site_top}/{$q}" />
  {if $query.targets == 'tagsExact'}
    <link rel="alternate" href="{$config.tag_search_page}{$query['q']|escape:'url'}" type="text/html"/>
  {else}
    <link rel="alternate" href="{$config.search_page}{$query['q']|escape:'url'}" type="text/html"/>
  {/if}
  <updated>{if $videos}{$videos[0]->date|atom_date_format}{else}{$smarty.now|atom_date_format}{/if}</updated>
  <generator>https://github.com/fuktommy/nv-search-rss</generator>
  <author><name>nicovideo.jp</name></author>
  <id>tag:fuktommy.com,2021:nvsearch.rss</id>
  <icon>{$config.site_top}/favicon.ico</icon>
{foreach from=$videos item=video}
  <entry>
    <title>{$video->title|htmlspecialchars_decode|mbtruncate:140}</title>
    <link rel="alternate" href="{$config.watch_page}{$video->id}"/>
    <summary type="text">{$video->description|strip_tags|htmlspecialchars_decode|mbtruncate:420}</summary>
    <content type="html"><![CDATA[
        {$video->description|replace:"]]>":"" nofilter}
    ]]></content>
    <published>{$video->published|atom_date_format}</published>
    <updated>{$video->date|atom_date_format}</updated>
    <id>tag:fuktommy.com,2021:nvsearch.rss/{$video->id}</id>
    {foreach from=$video->tags item=tag}
       <category term="{$tag}" label="{$tag}"/>
    {/foreach}
  </entry>
{/foreach}
</feed>
