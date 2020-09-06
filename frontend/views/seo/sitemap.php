<?PHP

use yii\helpers\Url;

$lang = Yii::$app->current->lang('url');

    echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
    foreach($data as $key => $val)
    {
        $loc = Url::base(true).'/'.$lang.'/sitemap-'.$val['loc'].'.xml';
        $last_mod = $val["last_mod"];
        if(strpos($last_mod,'-'))
        {
            $last_mod = strtotime($last_mod);
        }
        $last_mod = date("c",$last_mod);
        echo "<sitemap>
                <loc>{$loc}</loc>
                <lastmod>{$last_mod}</lastmod>
             </sitemap>";
    }
    echo "</sitemapindex>";


?>
