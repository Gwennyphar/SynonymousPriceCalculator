{extends file="parent:frontend/detail/index.tpl"}

{block name='frontend_detail_index_data'}
    {if $sArticle.sBlockPrices}
        {$lowestPrice=false}
        {$highestPrice=false}
        {foreach $sArticle.sBlockPrices as $blockPrice}
            {if $lowestPrice === false || $blockPrice.price < $lowestPrice}
                {$lowestPrice=$blockPrice.price}
            {/if}
            {if $highestPrice === false || $blockPrice.price > $highestPrice}
                {$highestPrice=$blockPrice.price}
            {/if}
        {/foreach}

        <meta itemprop="lowPrice" content="{$lowestPrice}" />
        <meta itemprop="highPrice" content="{$highestPrice}" />
        <meta itemprop="offerCount" content="{$sArticle.sBlockPrices|count}" />
    {else}
        <meta itemprop="priceCurrency" content="{$Shop->getCurrency()->getCurrency()}"/>
    {/if}

    <!-- Replace include of price data with an ESI tag to allow on demand price calculation -->
    {*include file="frontend/detail/data.tpl" sArticle=$sArticle sView=1*}
    {action module=widgets controller=detail action=data articleId=$sArticle.articleID}
{/block}