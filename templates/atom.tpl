<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="/atomfeed.xsl" type="text/xsl"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>動画検索: {$q}</title>
  <link rel="self" href="{$config.site_top}" />
  <link rel="alternate" href="{$config.search_page}{$query['q']|escape:'url'}" type="text/html"/>
  <updated>{if $videos}{$videos[0]->date|atom_date_format}{else}{$smarty.now|atom_date_format}{/if}</updated>
  <generator>https://github.com/fuktommy/nv-search-rss</generator>
  <id>tag:fuktommy.com,2021:nvsearch.rss</id>
  <icon>{$config.site_top}/favicon.ico</icon>
{foreach from=$videos item=video}
  <entry>
    <title>{$video->title|mbtruncate:140}</title>
    <link rel="alternate" href="{$config.watch_page}{$video->id}"/>
    <summary type="text">{$video->description|strip_tags|htmlspecialchars_decode}</summary>
    <content type="html"><![CDATA[
        {$video->description|replace:"]]>":"" nofilter}
    ]]></content>
    <published>{$video->date|atom_date_format}</published>
    <updated>{$video->date|atom_date_format}</updated>
    <id>tag:fuktommy.com,2021:nvsearch.rss/{$video->id}</id>
  </entry>
{/foreach}
</feed>
