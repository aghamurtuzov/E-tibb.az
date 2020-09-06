<?php
/**
 * Created by PhpStorm.
 * User: Taleh
 * Date: 1/9/2019
 * Time: 2:36 PM
 */

namespace frontend\components;

use Yii;
use yii\base\BaseObject;
use yii\helpers\Url;

class SeoLib extends BaseObject
{

    public $shortcut_icon_link;
    public $author;
    public $google_verification;
    public $yandex_verification;
    public $bing_verification;
    public $og_locale;
    public $og_locale_alternate;
    public $site_name;
    //public $article_publisher;
    public $default_keywords;
    public $social_logo_link;
    public $twitter_author;
    public $facebook_app_id;
    public $rss_site_name;
    public $copyright;
    public $feed_docs_url;
    public $feed_lang;
    public $editor_mail;
    public $editor_name;
    public $webmaster_mail;
    public $webmaster_name;
    public $feed_publication;
    public $url_without_ssl;
    public $feed_source_url_text;

    //public $fb_article_publisher;
    //public $fb_article_author;
    public $content_lang;
    public $abstract_desc;
    //public $designer;
    //public $distribution;
    public $robots_action;
    public $contact_mail;
    //public $generator;
    //public $rating;
    //public $reply_to;
    //public $web_author;

    public $organization_schema_name;
    public $organization_schema_alternate_name;
    public $organization_logo;
    public $social_links;
    public $organization_contact_datas;
    public $organization_phone_number;
    public $organization_address;
    public $organization_image;

    public $business_service_type;
    public $business_service_area_state;

    public $product_bestrating;
    public $product_worstrating;

    public $youtube_api_key;
    public $sitemap_types;
    public $used_dns_pref;
    public $feed_urls;

    public function __construct(){

        $this->shortcut_icon_link = Yii::$app->params['seo']["shortcut_icon_link"];
        $this->author = Yii::$app->params['seo']["author"];
        $this->google_verification = Yii::$app->params['seo']["google-site-verification"];
        $this->yandex_verification = Yii::$app->params['seo']["yandex-verification"];
        $this->bing_verification = Yii::$app->params['seo']["bing-verification"];
        $this->og_locale = Yii::$app->params['seo']["og_locale"];
        //$this->og_locale_alternate = Yii::$app->params['seo']["og_locale_alternate"];
        $this->site_name = Yii::$app->params['seo']["site_name"];
        //$this->article_publisher = Yii::$app->params['seo']["article_publisher"];
        $this->default_keywords = Yii::$app->params['seo']["default_keywords"];
        $this->social_logo_link = Yii::$app->params['seo']["social_logo_link"];
        $this->twitter_author = Yii::$app->params['seo']["twitter_author"];
        $this->facebook_app_id = Yii::$app->params['seo']["facebook_app_id"];
        $this->rss_site_name = Yii::$app->params['seo']["rss_site_name"];
        $this->copyright = '© '.date('Y').' '.Yii::$app->params['seo']["site_name"].' - '.Yii::$app->params['seo']["copyright"];
        $this->feed_docs_url = Yii::$app->params['seo']["feed_docs_url"];
        $this->feed_lang = Yii::$app->params['seo']["feed_lang"];
        $this->editor_mail = Yii::$app->params['seo']["editor_mail"];
        $this->editor_name = Yii::$app->params['seo']["editor_name"];
        $this->webmaster_mail = Yii::$app->params['seo']["webmaster_mail"];
        $this->webmaster_name = Yii::$app->params['seo']["webmaster_name"];
        //$this->feed_publication = Yii::$app->params['seo']["feed_publication"];
        $this->url_without_ssl = Yii::$app->params['seo']["url_without_ssl"];
        $this->feed_source_url_text = Yii::$app->params['seo']["feed_source_url_text"];

        //$this->fb_article_publisher = Yii::$app->params['seo']["fb_article_publisher"];
        //$this->fb_article_author = Yii::$app->params['seo']["fb_article_author"];
        $this->content_lang = Yii::$app->params['seo']["content_lang"];
        $this->abstract_desc = Yii::$app->params['seo']["abstract_desc"];
        //$this->designer = Yii::$app->params['seo']["designer"];
        //$this->distribution = Yii::$app->params['seo']["distribution"];
        $this->robots_action = Yii::$app->params['seo']["robots_action"];
        $this->contact_mail = Yii::$app->params['seo']["contact_mail"];
        //$this->generator = Yii::$app->params['seo']["generator"];
        //$this->rating = Yii::$app->params['seo']["rating"];
        //$this->reply_to = Yii::$app->params['seo']["reply_to"];
        //$this->web_author = Yii::$app->params['seo']["web_author"];

        $this->organization_schema_name = Yii::$app->params['seo']["organization_schema_name"];
        $this->organization_schema_alternate_name = Yii::$app->params['seo']["organization_schema_alternate_name"];
        $this->organization_logo = Yii::$app->params['seo']["organization_logo"];
        $this->social_links = Yii::$app->params['seo']["social_links"];
        $this->organization_contact_datas = Yii::$app->params['seo']["organization_contact_datas"];
        $this->organization_phone_number = Yii::$app->params['seo']["organization_phone_number"];
        $this->organization_address = Yii::$app->params['seo']["organization_address"];
        $this->organization_image = Yii::$app->params['seo']["organization_image"];

        //$this->business_service_type = Yii::$app->params['seo']["business_service_type"];
        //$this->business_service_area_state = Yii::$app->params['seo']["business_service_area_state"];

        $this->product_bestrating = Yii::$app->params['seo']["product_bestrating"];
        $this->product_worstrating = Yii::$app->params['seo']["product_worstrating"];

        $this->youtube_api_key = Yii::$app->params['seo']["youtube_api_key"];
        $this->sitemap_types = Yii::$app->params['seo']["sitemap_types"];
        $this->used_dns_pref = Yii::$app->params['seo']["used_dns_pref"];
        $this->feed_urls = Yii::$app->params['seo']["feed_urls"];

    }

    public function make_page_header( $default_datas ){

        $default_datas["title"]         = isset($default_datas["title"]) ? $default_datas["title"] : Yii::$app->params['seo']['site_title'];
        $default_datas["description"]   = isset($default_datas["description"]) ? trim(str_replace('&nbsp;',' ',$default_datas["description"])) : Yii::$app->params['seo']['site_description'];
        $default_datas["article_keywords"] = isset($default_datas["article_keywords"]) ? explode(',',$default_datas["article_keywords"]) : '';
        $default_datas["amp_status"] = isset($default_datas["amp_status"]) ? $default_datas["amp_status"]: 0;

        $returned_string = "";

        $returned_string .= '<link rel="shortcut icon" type="image/x-icon" href="'.Url::base('https').$this->shortcut_icon_link.'">'."\n";

        //$returned_string .= '<meta charset="UTF-8">'."\n";

        if(!empty($this->google_verification))
        {
            $returned_string .= '<meta name="google-site-verification" content="'.$this->google_verification.'"/>'."\n";
        }

        if(!empty($this->yandex_verification))
        {
            $returned_string .= '<meta name="yandex-verification" content="'.$this->yandex_verification.'" />'."\n";
        }

        if(!empty($this->bing_verification))
        {
            $returned_string .= '<meta name="msvalidate.01" content="'.$this->bing_verification.'" />'."\n";
        }

        //$returned_string .= '<meta name="viewport" content="width=device-width, initial-scale=1">'."\n";
        //$returned_string .= '<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">'."\n";
        //$returned_string .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">'."\n";
        //$returned_string .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";

        $returned_string .= '<title>'.$this->make_cleared_content( $default_datas["title"], "title", "google" ).'</title>'."\n";
        $returned_string .= '<meta name="author" content="'.$this->author.'">'."\n";
        $returned_string .= '<meta name="description" content="'.$this->make_cleared_content( $default_datas["description"], "description", "google" ).'"/>'."\n";
        $returned_string .= '<meta name="abstract" content="'.$this->abstract_desc.'" />'."\n";

        if( !empty($this->content_lang) and is_array($this->content_lang) ){
            foreach( $this->content_lang as $lang_value ){
                $returned_string .= '<meta name="content-language" content="'.$lang_value.'" />'."\n";
            }
        }

        $returned_string .= '<meta name="copyright" content="'.$this->copyright.'" />'."\n";
        //$returned_string .= '<meta name="designer" content="'.$this->designer.'" />'."\n";
        //$returned_string .= '<meta name="distribution" content="'.$this->distribution.'" />'."\n";
        $returned_string .= '<meta name="robots" content="'.$this->robots_action[$default_datas["robots_action"]].'" />'."\n";
        $returned_string .= '<meta name="contact" content="'.$this->contact_mail.'" />'."\n";
        //$returned_string .= '<meta name="formatter" content="'.$this->generator.'" />'."\n";
        //$returned_string .= '<meta name="rating" content="'.$this->rating.'" />'."\n";
        //$returned_string .= '<meta name="reply-to" content="'.$this->reply_to.'" />'."\n";
        //$returned_string .= '<meta name="web_author" content="'.$this->web_author.'" />'."\n";

        $keyword_string = "";

        if( !empty($default_datas["article_keywords"]) and is_array($default_datas["article_keywords"]) ){
            $keyword_string .= ",".implode(",", $default_datas["article_keywords"]);
        }

        if(empty($keyword_string))
        {
            if( !empty($this->default_keywords) and is_array($this->default_keywords) ){
                $keyword_string = implode(",", $this->default_keywords);
            }
        }

        $returned_string .= '<meta name="keywords" content="'.trim($keyword_string,',').'"/>'."\n";
        $returned_string .= '<link rel="canonical" href="'.$default_datas["canonical"].'"/>'."\n";
        //$returned_string .= '<link rel="alternate" hreflang="en" href="'.$default_datas["canonical"].'" />'."\n";

        if( isset($default_datas["next_link"]) and !empty($default_datas["next_link"]) ){
            $returned_string .= '<link rel="next" href="'.$default_datas["next_link"].'" />'."\n";
        }

        $returned_string .= "\n";

        if( $default_datas["page_type"] != "website" ){

            $returned_string .= '<meta name="syndication-source" content="'.$default_datas["canonical"].'"/>'."\n";
            //$returned_string .= '<link rel="publisher" href="'.$this->article_publisher.'" />'."\n";
            $returned_string .= '<meta name="news_keywords" content="'.trim($keyword_string,',').'" />'."\n";

        }

        if(!empty($this->facebook_app_id))
        {
            $returned_string .= '<meta property="fb:app_id" content="'.$this->facebook_app_id.'" />'."\n";
        }

        $returned_string .= '<meta property="og:locale" content="'.$this->og_locale.'" />'."\n";
        $returned_string .= '<meta property="og:type" content="'.$default_datas["page_type"].'" />'."\n";
        $returned_string .= '<meta property="og:title" content="'.$this->make_cleared_content( $default_datas["title"], "title", "social" ).'"/>'."\n";
        $returned_string .= '<meta property="og:description" content="'.$this->make_cleared_content( $default_datas["description"], "description", "social" ).'"/>'."\n";
        $returned_string .= '<meta property="og:url" content="'.$default_datas["canonical"].'"/>'."\n";
        $returned_string .= '<meta property="og:site_name" content="'.$this->site_name.'"/>'."\n";

        if( $default_datas["page_type"] == "article" ){

            //$returned_string .= '<meta property="article:author" content="'.$this->fb_article_author.'" />'."\n";
            //$returned_string .= '<meta property="article:publisher" content="'.$this->fb_article_publisher.'" />'."\n";

            $k = 0;
            if( !empty($default_datas["article_keywords"]) and is_array($default_datas["article_keywords"]) ){
                foreach( $default_datas["article_keywords"] as $keyword_value ){
                    $returned_string .= '<meta property="article:tag" content="'.$keyword_value.'" />'."\n";
                }
                $k =1;
            }

            if($k == 0)
            {
                if( !empty($this->default_keywords) and is_array($this->default_keywords) ){
                    foreach( $this->default_keywords as $keyword_value ){
                        $returned_string .= '<meta property="article:tag" content="'.$keyword_value.'" />'."\n";
                    }
                }
            }

            if( !empty($default_datas["article_section"]) and is_array($default_datas["article_section"]) ){
                foreach( $default_datas["article_section"] as $article_sec_value ){
                    $returned_string .= '<meta property="article:section" content="'.$article_sec_value.'" />'."\n";
                }
            }

            if(!empty($default_datas["published_time"]))
            {
                $returned_string .= '<meta property="article:published_time" content="'.date('c', $default_datas["published_time"]).'"/>'."\n";
            }

            if(!empty($default_datas["modified_time"]))
            {
                $returned_string .= '<meta property="article:modified_time" content="'.date('c', $default_datas["modified_time"]).'" />'."\n";
            }

            if(!empty($default_datas["modified_time"]))
            {
                $returned_string .= '<meta property="og:updated_time" content="'.date('c', $default_datas["modified_time"]).'" />'."\n";
            }

            if( !empty($default_datas["see_also"]) and is_array($default_datas["see_also"]) ){
                foreach( $default_datas["see_also"] as $see_also_value ){
                    $returned_string .= '<meta property="og:see_also" content="'.$see_also_value.'" />'."\n";
                }
            }

//            if(isset($default_datas["rate"]) and is_array($default_datas["rate"]) and $default_datas["rate"]["status"] == 1)
//            {
//                $returned_string .= '<meta property="og:rating" content="'.$default_datas["rate"]["rating"].'" />'."\n";
//                $returned_string .= '<meta property="og:rating_scale" content="'.$default_datas["rate"]["rating_scale"].'" />'."\n";
//                $returned_string .= '<meta property="og:rating_count" content="'.$default_datas["rate"]["rating_count"].'" />'."\n";
//            }

        }elseif( $default_datas["page_type"] == "video.other" ){

            $returned_string .= '<meta property="og:video:url" content="https://www.youtube.com/v/'.$default_datas["video_id"].'" />'."\n";
            $returned_string .= '<meta property="og:video:secure_url" content="https://www.youtube.com/v/'.$default_datas["video_id"].'" />'."\n";
            $returned_string .= '<meta property="og:video:type" content="text/html" />'."\n";
            $returned_string .= '<meta property="og:video:width" content="1280" />'."\n";
            $returned_string .= '<meta property="og:video:height" content="720" />'."\n";

            $returned_string .= '<meta property="og:video:url" content="https://www.youtube.com/v/'.$default_datas["video_id"].'" />'."\n";
            $returned_string .= '<meta property="og:video:secure_url" content="https://www.youtube.com/v/'.$default_datas["video_id"].'" />'."\n";
            $returned_string .= '<meta property="og:video:type" content="application/x-shockwave-flash" />'."\n";
            $returned_string .= '<meta property="og:video:width" content="1280" />'."\n";
            $returned_string .= '<meta property="og:video:height" content="720" />'."\n";

            if( !empty($this->default_keywords) and is_array($this->default_keywords) ){
                foreach( $this->default_keywords as $keyword_value ){
                    $returned_string .= '<meta property="video:tag" content="'.$keyword_value.'" />'."\n";
                }
            }

            if( !empty($default_datas["video_keywords"]) and is_array($default_datas["video_keywords"]) ){
                foreach( $default_datas["video_keywords"] as $keyword_value ){
                    $returned_string .= '<meta property="video:tag" content="'.$keyword_value.'" />'."\n";
                }
            }

        }

        if( $default_datas["page_type"] == "website" ){

            $returned_string .= '<meta property="og:image" content="'.Url::base('https').$this->social_logo_link.'"/>'."\n";
            $returned_string .= '<meta property="og:image:secure_url" content="'.Url::base('https').$this->social_logo_link.'"/>'."\n";
            $returned_string .= '<meta property="og:image:alt" content="'.$this->make_cleared_content( $default_datas["title"], "title", "social" ).'" />'."\n";

        }elseif( $default_datas["page_type"] == "article" ){

            if(!empty($default_datas["article_image"])) {
                $returned_string .= '<meta property="og:image" content="' . $default_datas["article_image"] . '"/>' . "\n";
                $returned_string .= '<meta property="og:image:secure_url" content="' . $default_datas["article_image"] . '"/>' . "\n";
                $returned_string .= '<meta property="og:image:width" content="1024" />' . "\n";
                $returned_string .= '<meta property="og:image:height" content="512" />' . "\n";
            }
            $returned_string .= '<meta property="og:image:alt" content="'.$this->make_cleared_content( $default_datas["title"], "title", "social" ).'" />'."\n";

        }elseif( $default_datas["page_type"] == "video.other" ){

            $returned_string .= '<meta property="og:image" content="https://img.youtube.com/vi/'.$default_datas["video_id"].'/hqdefault.jpg" />'."\n";
            $returned_string .= '<meta property="og:image:alt" content="'.$this->make_cleared_content( $default_datas["title"], "title", "social" ).'" />'."\n";

        }

        $returned_string .= "\n";

        $returned_string .= '<meta name="twitter:title" content="'.$this->make_cleared_content( $default_datas["title"], "title", "social" ).'"/>'."\n";
        $returned_string .= '<meta name="twitter:description" content="'.$this->make_cleared_content( $default_datas["description"], "description", "social" ).'"/>'."\n";
        $returned_string .= '<meta name="twitter:site" content="'.$this->twitter_author.'"/>'."\n";

        if( $default_datas["page_type"] == "video.other" ){
            $returned_string .= '<meta name="twitter:card" content="player" />'."\n";
            $returned_string .= '<meta name="twitter:image" content="https://img.youtube.com/vi/'.$default_datas["video_id"].'/maxresdefault.jpg"/>'."\n";
            $returned_string .= '<meta property="twitter:player" content="https://www.youtube.com/v/'.$default_datas["video_id"].'" />'."\n";
            $returned_string .= '<meta property="twitter:player:width" content="1280" />'."\n";
            $returned_string .= '<meta property="twitter:player:height" content="720" />'."\n";
        }else{
            $returned_string .= '<meta name="twitter:card" content="summary_large_image" />'."\n";
            $returned_string .= '<meta name="twitter:creator" content="'.$this->twitter_author.'" />'."\n";
            if( $default_datas["page_type"] == "website" ){
                $returned_string .= '<meta name="twitter:image" content="'.Url::base('https').$this->social_logo_link.'"/>'."\n";
            }elseif( $default_datas["page_type"] == "article" && !empty($default_datas["article_image"])){
                $returned_string .= '<meta name="twitter:image" content="'.$default_datas["article_image"].'"/>'."\n";
            }
        }

        if( !empty($this->used_dns_pref) and is_array($this->used_dns_pref) ){
            foreach( $this->used_dns_pref as $dns_val ){
                $returned_string .= '<link rel="dns-prefetch" href="'.$dns_val.'" />'."\n";
            }
        }

        if( $default_datas["amp_status"] == 1 ){
            $returned_string .= '<link rel="amphtml" href="'.$default_datas["amp_url"].'" />'."\n";
        }

        if( !empty($this->feed_urls) and is_array($this->feed_urls) ){
            foreach( $this->feed_urls as $feed_key => $feed_val ){
                $returned_string .= '<link rel="alternate" type="application/rss+xml" title="'.$feed_val["title"].'" href="'.Url::base('https').'/'.$feed_val["url"].'.rss"/>'."\n";
            }
        }

        return $returned_string;

    }

    public function make_google_searchbox(){

        $returned_array = array();
        $returned_array["@context"] = "http://schema.org";
        $returned_array["@type"] = "WebSite";
        $returned_array["url"] = Url::base('https');
        $returned_array["potentialAction"] = array();
        $returned_array["potentialAction"]["@type"] = "SearchAction";
        $returned_array["potentialAction"]["target"] = Url::base('https')."/"."axtar?q={search_term_string}";
        $returned_array["potentialAction"]["query-input"] = "required name=search_term_string";

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }

    ////////////////////////////// SCHEMA CONSTRUCTOR //////////////////////////////
    public function make_organization_schema(){

        $returned_array = array();
        $returned_array["@context"] = "http://schema.org";
        $returned_array["@type"] = "Organization";
        $returned_array["@id"] = Url::base('https')."#organization";
        $returned_array["name"] = $this->make_cleared_content( $this->organization_schema_name, "title", "google" );
        $returned_array["alternateName"] = $this->make_cleared_content( $this->organization_schema_alternate_name, "title", "google" );
        $returned_array["url"] = Url::base('https');
        $returned_array["logo"] = Url::base('https').$this->organization_logo;
        $returned_array["telephone"] = $this->organization_phone_number;
        $returned_array["address"] = $this->organization_address;

        foreach ($this->organization_image as $img_key => $img_val) {
            $this->organization_image[$img_key] = Url::base('https').$img_val;
        }

        $returned_array["image"] = $this->organization_image;

        if( !empty($this->social_links) and is_array($this->social_links) ){
            $returned_array["sameAs"] = $this->social_links;
        }

        $returned_array["contactPoint"] = $this->organization_contact_datas;

        $returned_array["aggregateRating"] = array();
        $returned_array["aggregateRating"]["@type"] = "AggregateRating";
        $returned_array["aggregateRating"]["ratingValue"] = 4.7;
        $returned_array["aggregateRating"]["reviewCount"] = 30;

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }

        return $returned_string;

    }

    public function make_page_breadcrumb( $datas ){

        if( !empty($datas) and is_array($datas) ){

            $returned_array = array();
            $returned_array["@context"] = "http://schema.org";
            $returned_array["@type"] = "BreadcrumbList";
            $returned_array["itemListElement"] = array();

            foreach ($datas as $data_key => $data_val ) {
                $returned_array["itemListElement"][$data_key]["@type"] = "ListItem";
                $returned_array["itemListElement"][$data_key]["position"] = $data_key + 1;
                $returned_array["itemListElement"][$data_key]["item"]["@id"] = $data_val["link"];
                $returned_array["itemListElement"][$data_key]["item"]["name"] = $data_val["text"];
            }

        }

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }

    public function make_site_navigation_element( $datas ){

        if( !empty($datas) and is_array($datas) ){

            $returned_array = array();
            $returned_array["@context"] = "http://schema.org";
            $returned_array["@type"] = "ItemList";
            $returned_array["itemListElement"] = array();

            foreach ($datas as $data_key => $data_val ) {
                $returned_array["itemListElement"][$data_key]["@type"] = "SiteNavigationElement";
                $returned_array["itemListElement"][$data_key]["position"] = $data_key + 1;
                $returned_array["itemListElement"][$data_key]["name"] = $this->make_cleared_content( $data_val["title"], "title", "google" );
                $returned_array["itemListElement"][$data_key]["description"] = $this->make_cleared_content( $data_val["description"], "description", "google" );
                $returned_array["itemListElement"][$data_key]["url"] = $data_val["url"];
            }

        }

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }

    public function make_article_list_schema($datas, $main_url, $amp_string = false){

        if( !empty($datas) and is_array($datas) ){

            $returned_array = array();
            $returned_array["@context"] = "http://schema.org";
            $returned_array["@type"] = "ItemList";
            $returned_array["itemListElement"] = array();

            foreach( $datas as $data_key => $data_val ){

                $returned_array["itemListElement"][$data_key]["@type"] = "ListItem";
                $returned_array["itemListElement"][$data_key]["position"] = $data_key + 1;
                $returned_array["itemListElement"][$data_key]["item"] = array();

                $article_data = array();
                $article_data["@type"] = "Article";
                $article_data["mainEntityOfPage"] = array(
                    "@type" => "WebPage",
                    "@id" => site_url($main_url).$amp_string."#".$data_val["slug"]
                );

                if( isset($data_val["image"]) and !empty($data_val["image"]) ){
                    $article_data["image"] = $data_val["image"];
                }

                if( isset($data_val["video_img"]) and !empty($data_val["video_img"]) ){
                    $article_data["image"] = $data_val["video_img"];
                }

                $article_data["url"] = site_url($main_url).$amp_string."#".$data_val["slug"];
                $article_data["headline"] = $this->make_cleared_content( $data_val["title"], "title", "google" );
                $article_data["description"] = $this->make_cleared_content( $data_val["description"], "description", "google" );
                $article_data["datePublished"] = date("c", strtotime($data_val["created_at"]));
                $article_data["dateModified"] = date("c", strtotime($data_val["updated_at"]));

                if( isset($data_val["category_datas"]) and !empty($data_val["category_datas"]) ){
                    $article_data["articleSection"] = implode(",", array_column($data_val["category_datas"], "title"));
                }else{
                    $article_data["articleSection"] = implode(",", $this->default_keywords);
                }


                if( isset($data_val["tag_datas"]) and !empty($data_val["tag_datas"]) ){
                    $article_data["keywords"] = implode(", ", $this->default_keywords).", ".implode(", ", array_column($data_val["tag_datas"], "tag"));
                }

                if( isset($data_val["keyword"]) and !empty($data_val["keyword"]) ){
                    $article_data["keywords"] = implode(", ", $this->default_keywords).", ".implode(", ", $data_val["keyword"]);
                }

                $article_data["articleBody"] = $this->make_cleared_content( $data_val["description"], "blog", "short_blog" );

                $article_data["publisher"] = array(
                    "@type" => "Organization",
                    "@id" => Url::base('https')."#organization",
                    "name" => $this->organization_schema_name,
                    "url" => Url::base('https'),
                    "logo" => array(
                        "@type" => "ImageObject",
                        "url" => Url::base('https').$this->organization_logo,
                        "width" => "600",
                        "height" => "60"
                    )
                );


                $article_data["author"] = array(
                    "@type" => "Person",
                    "name" => $data_val["author"],
                    "url" => $data_val["author_url"],
                    "description" => $this->make_cleared_content( $data_val["author_desc"], "description", "google" ),
                    "image" => array(
                        "@type" => "ImageObject",
                        "url" => $data_val["author_image"],
                        "height" => "96",
                        "width" => "96"
                    )
                );


                $article_data["aggregateRating"] = array();
                $article_data["aggregateRating"]["@type"] = "AggregateRating";
                $article_data["aggregateRating"]["ratingValue"] = $data_val["rating_value"];
                $article_data["aggregateRating"]["reviewCount"] = $data_val["review_count"];

                $returned_array["itemListElement"][$data_key]["item"] = $article_data;

            }

        }

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }

    public function make_article_schema($data, $main_url, $amp_string){

        if( !empty($data) and is_array($data) ){

            $returned_array = array();
            $returned_array["@context"] = "http://schema.org";
            $returned_array["@type"] = "Article"; //or NewsArticle

            $returned_array["mainEntityOfPage"] = array(
                "@type" => "WebPage",
                "@id" => Url::base('https').'/'.$main_url.$data["slug"].$amp_string
            );
            $returned_array["image"]        = $data["post_image"];
            $returned_array["url"]          = Url::base('https').'/'.$main_url.$data["slug"].$amp_string;
            $returned_array["headline"]     = $this->make_cleared_content( $data["title"], "title", "google" );
            $returned_array["description"]  = $this->make_cleared_content( $data["description"], "description", "google" );
            $returned_array["datePublished"]= date("c", strtotime($data["created_at"]));
            $returned_array["dateModified"] = date("c", strtotime($data["updated_at"]));

            if( isset($data["category_datas"]) and !empty($data["category_datas"]) ){
                $returned_array["articleSection"] = implode(",", array_column($data["category_datas"], "title"));
            }else{
                $returned_array["articleSection"] = implode(",", $this->default_keywords);
            }


            if( isset($data["tag_datas"]) and !empty($data["tag_datas"]) ){
                $returned_array["keywords"] = implode(", ", $this->default_keywords).", ".implode(", ", array_column($data["tag_datas"], "tag"));
            }

            if( isset($data["keyword"]) and !empty($data["keyword"]) ){
                $returned_array["keywords"] = implode(", ", $this->default_keywords).", ".implode(", ", $data["keyword"]);
            }


            $returned_array["articleBody"] = $this->make_cleared_content( $data["description"], "blog", "short_blog" );

            $returned_array["publisher"] = array(
                "@type" => "Organization",
                "@id" => Url::base('https')."#organization",
                "name" => $this->organization_schema_name,
                "alternateName" => $this->organization_schema_alternate_name,
                "url" => Url::base('https'),
                "logo" => array(
                    "@type" => "ImageObject",
                    "url" => Url::base('https').'/'.$this->organization_logo,
                    "width" => "600",
                    "height" => "60"
                )
            );



            $returned_array["author"] = array(
                "@type" => "Person",
                "name" => $data["author"],
                "url" => $data["author_url"],
                "description" => $this->make_cleared_content( $data["author_desc"], "description", "google" ),
                "image" => array(
                    "@type" => "ImageObject",
                    "url" => $data["author_image"],
                    "height" => "96",
                    "width" => "96"
                )
                //"sameAs" => $data["author_datas"]["sameas"]
            );


            $returned_array["aggregateRating"] = array();
            $returned_array["aggregateRating"]["@type"] = "AggregateRating";
            $returned_array["aggregateRating"]["ratingValue"] = $data["rating_value"];
            $returned_array["aggregateRating"]["reviewCount"] = $data["review_count"];

            // if( !empty($data["video_datas"]) and is_array($data["video_datas"]) ){

            // 	$get_youtube_api_datas = $this->get_youtube_api_datas( $data["video_datas"]["video_id"], array("snippet", "contentDetails", "statistics") );

            // 	$returned_array["video"]["@type"] = "VideoObject";
            // 	$returned_array["video"]["name"] = $this->make_cleared_content( $data["title"], "title", "google" );
            // 	$returned_array["video"]["description"] = $this->make_cleared_content( $data["description"], "description", "google" );
            // 	$returned_array["video"]["thumbnailUrl"] = array(
            // 		"https://i.ytimg.com/vi/".$data["video_datas"]["video_id"]."/default.jpg",
            // 		"https://i.ytimg.com/vi/".$data["video_datas"]["video_id"]."/mqdefault.jpg",
            // 		"https://i.ytimg.com/vi/".$data["video_datas"]["video_id"]."/hqdefault.jpg",
            // 		"https://i.ytimg.com/vi/".$data["video_datas"]["video_id"]."/sddefault.jpg",
            // 		"https://i.ytimg.com/vi/".$data["video_datas"]["video_id"]."/maxresdefault.jpg"
            // 	);
            // 	$returned_array["video"]["uploadDate"] = $get_youtube_api_datas["publishedAt"];
            // 	$returned_array["video"]["duration"] = $get_youtube_api_datas["duration_time"];
            // 	$returned_array["video"]["contentUrl"] = $data["video_datas"]["video_secure_url"];
            // 	$returned_array["video"]["embedUrl"] = "https://www.youtube.com/embed/".$data["video_datas"]["video_id"];
            // 	$returned_array["video"]["interactionCount"] = $get_youtube_api_datas["viewCount"];

            // }


            // if( !empty($data["reviews"]) and is_array($data["reviews"]) ){

            // 	$returned_array["review"] = array();
            // 	foreach( $data["reviews"] as $review_key => $review_val ){

            // 		$rating_schema = array();
            // 		$rating_schema["@type"] = "Rating";
            // 		$rating_schema["bestRating"] = $this->product_bestrating;
            // 		$rating_schema["ratingValue"] = $review_val["rate_value"];
            // 		$rating_schema["worstRating"] = $this->product_worstrating;

            // 		$returned_array["review"][] = array(
            // 			"@type" => "Review",
            // 			"author" => $review_val["author"],
            // 			"datePublished" => $review_val["datePublished"],
            // 			"name" => $review_val["name"],
            // 			"description" => $review_val["description"],
            // 			"reviewRating" => $rating_schema
            // 		);
            // 	}

            // }


        }

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }

    public function make_faq_question_schema( $data, $main_url, $amp_string ){

        if( !empty($data) and is_array($data) ){

            $returned_array = array();
            $returned_array["@context"] = "http://schema.org";
            $returned_array["@type"] = "QAPage";
            $returned_array["mainEntity"] = array();
            $returned_array["mainEntity"]["@type"] = "Question";
            $returned_array["mainEntity"]["name"] = $this->make_cleared_content( $data["title"], "title", "google" );
            $returned_array["mainEntity"]["text"] = $this->make_cleared_content( $data["description"], "description", "google" );
            $returned_array["mainEntity"]["answerCount"] = 1;
            $returned_array["mainEntity"]["dateCreated"] = date('c', strtotime($data["created_at"]));

            $returned_array["mainEntity"]["author"] = array();
            $returned_array["mainEntity"]["author"]["@type"] = "Person";
            $returned_array["mainEntity"]["author"]["name"] = "User";

            $returned_array["mainEntity"]["acceptedAnswer"] = array();
            $returned_array["mainEntity"]["acceptedAnswer"]["@type"] = "Answer";
            $returned_array["mainEntity"]["acceptedAnswer"]["text"] = $this->make_cleared_content( $data["description"], "description", "google" );
            $returned_array["mainEntity"]["acceptedAnswer"]["dateCreated"] = date('c', strtotime($data["created_at"]));
            $returned_array["mainEntity"]["acceptedAnswer"]["url"] = site_url($main_url).$data["slug"].$amp_string;

            $returned_array["mainEntity"]["acceptedAnswer"]["author"] = array();
            $returned_array["mainEntity"]["acceptedAnswer"]["author"]["@type"] = "Person";
            $returned_array["mainEntity"]["acceptedAnswer"]["author"]["name"] = $data["author"];

        }

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }


    public function make_amp_video_schema($data){

        if( !empty($data) and is_array($data) ){

            $returned_array = array();
            $returned_array["@context"] = "http://schema.org";
            $returned_array["@type"] = "VideoObject";
            $returned_array["name"] = $this->make_cleared_content( $data["title"], "title", "google" );
            $returned_array["description"] = $this->make_cleared_content( $data["description"], "description", "google" );
            $returned_array["thumbnailUrl"] = "https://i.ytimg.com/vi/".$data["video_id"]."/default.jpg";

            $get_youtube_api_datas = $this->get_youtube_api_datas( $data["video_id"], array("snippet", "contentDetails", "statistics") );
            $returned_array["uploadDate"] = $get_youtube_api_datas["publishedAt"];
            $returned_array["duration"] = $get_youtube_api_datas["duration_time"];

            $returned_array["publisher"] = array(
                "@type" => "Organization",
                "name" => $this->site_name,
                "logo" => array(
                    "@type" => "ImageObject",
                    "url" => Url::base('https').'/'.$this->organization_logo,
                    "width" => "300",
                    "height" => "60"
                )
            );

            $returned_array["contentUrl"] = $data["link"];
            $returned_array["embedUrl"] = "https://www.youtube.com/embed/".$data["video_id"];
            $returned_array["interactionCount"] = $get_youtube_api_datas["viewCount"];

            if( isset($data["keyword"]) and !empty($data["keyword"]) ){
                $returned_array["keywords"] = implode(", ", $this->default_keywords).", ".implode(", ", $data["keyword"]);
            }

            $returned_array["aggregateRating"] = array();
            $returned_array["aggregateRating"]["@type"] = "AggregateRating";
            $returned_array["aggregateRating"]["ratingValue"] = $data["rating_value"];
            $returned_array["aggregateRating"]["reviewCount"] = $data["review_count"];

        }

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }


    public function make_standart_video_schema($data){

        if( !empty($data) and is_array($data) ){

            $returned_array = array();
            $returned_array["@context"] = "http://schema.org";
            $returned_array["@type"] = "VideoObject";
            $returned_array["name"] = $this->make_cleared_content( $data["title"], "title", "google" );
            $returned_array["description"] = $this->make_cleared_content( $data["description"], "description", "google" );
            $returned_array["thumbnailUrl"] = array(
                "https://i.ytimg.com/vi/".$data["video_id"]."/default.jpg",
                "https://i.ytimg.com/vi/".$data["video_id"]."/mqdefault.jpg",
                "https://i.ytimg.com/vi/".$data["video_id"]."/hqdefault.jpg",
                "https://i.ytimg.com/vi/".$data["video_id"]."/sddefault.jpg",
                "https://i.ytimg.com/vi/".$data["video_id"]."/maxresdefault.jpg"
            );

            $get_youtube_api_datas = $this->get_youtube_api_datas( $data["video_id"], array("snippet", "contentDetails", "statistics") );
            $returned_array["uploadDate"] = $get_youtube_api_datas["publishedAt"];
            $returned_array["duration"] = $get_youtube_api_datas["duration_time"];
            $returned_array["contentUrl"] = $data["link"];
            $returned_array["embedUrl"] = "https://www.youtube.com/embed/".$data["video_id"];
            $returned_array["interactionCount"] = $get_youtube_api_datas["viewCount"];

            if( isset($data["keyword"]) and !empty($data["keyword"]) ){
                $returned_array["keywords"] = implode(", ", $this->default_keywords).", ".implode(", ", $data["keyword"]);
            }

            $returned_array["aggregateRating"] = array();
            $returned_array["aggregateRating"]["@type"] = "AggregateRating";
            $returned_array["aggregateRating"]["ratingValue"] = $data["rating_value"];
            $returned_array["aggregateRating"]["reviewCount"] = $data["review_count"];

        }

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }

    public function make_service_catalog_schema($datas){

        if( !empty($datas) and is_array($datas) ){

            $returned_array = array();
            $returned_array["@context"] = "http://schema.org";
            $returned_array["@type"] = "Service";
            $returned_array["serviceType"] = $this->business_service_type;

            $returned_array["provider"] = array(
                "@type" => "Organization",
                "name" => $this->site_name,
                "logo" => array(
                    "@type" => "ImageObject",
                    "url" => Url::base('https').$this->organization_logo,
                    "width" => "300",
                    "height" => "60"
                )
            );

            $returned_array["areaServed"] = array(
                "@type" => "State",
                "name" => $this->business_service_area_state
            );

            $returned_array["hasOfferCatalog"] = array();
            $returned_array["hasOfferCatalog"]["@type"] = "OfferCatalog";
            $returned_array["hasOfferCatalog"]["name"] = $this->business_service_type;
            $returned_array["hasOfferCatalog"]["itemListElement"] = array();

            foreach ($datas as $data_key => $data_val) {

                $returned_array["hasOfferCatalog"]["itemListElement"][$data_key]["@type"] = "Offer";
                $returned_array["hasOfferCatalog"]["itemListElement"][$data_key]["itemOffered"] = array();
                $returned_array["hasOfferCatalog"]["itemListElement"][$data_key]["itemOffered"]["@type"] = "Service";
                $returned_array["hasOfferCatalog"]["itemListElement"][$data_key]["itemOffered"]["name"] = $data_val["title"];
                $returned_array["hasOfferCatalog"]["itemListElement"][$data_key]["itemOffered"]["description"] = $data_val["description"];

            }


        }

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }



    public function make_product_schema($data){

        if( !empty($data) and is_array($data) ){

            $returned_array = array();
            $returned_array["@context"] = "http://schema.org";
            $returned_array["@type"] = "Product";
            $returned_array["name"] = $this->make_cleared_content( $data["name"], "title", "google" );
            $returned_array["description"] = $this->make_cleared_content( $data["description"], "description", "google" );
            $returned_array["image"] = Url::base('https').$data["image"];

            if( !empty($data["brand"]) ){
                $returned_array["brand"] = array();
                $returned_array["brand"]["@type"] = "Thing";
                $returned_array["brand"]["name"] = $data["brand"];
            }

            $returned_array["offers"] = array();
            $returned_array["offers"]["@type"] = "Offer";
            $returned_array["offers"]["itemCondition"] = "http://schema.org/UsedCondition";
            $returned_array["offers"]["availability"] = "http://schema.org/InStock";
            $returned_array["offers"]["url"] = Url::base('https')."product/".$data["slug"];
            $returned_array["offers"]["price"] = $data["price"];
            $returned_array["offers"]["priceCurrency"] = $data["currency"];
            $returned_array["offers"]["seller"] = array();
            $returned_array["offers"]["seller"]["@type"] = "Organization";
            $returned_array["offers"]["seller"]["name"] = $this->organization_schema_name;

            if( !empty($data["reviews"]) and is_array($data["reviews"]) ){

                $returned_array["aggregateRating"] = array();
                $returned_array["aggregateRating"]["@type"] = "AggregateRating";
                $returned_array["aggregateRating"]["ratingValue"] = $data["rating_value"];
                $returned_array["aggregateRating"]["reviewCount"] = $data["review_count"];

                $returned_array["review"] = array();
                foreach( $data["reviews"] as $review_key => $review_val ){

                    $rating_schema = array();
                    $rating_schema["@type"] = "Rating";
                    $rating_schema["bestRating"] = $this->product_bestrating;
                    $rating_schema["ratingValue"] = $review_val["rate_value"];
                    $rating_schema["worstRating"] = $this->product_worstrating;

                    $returned_array["review"][] = array(
                        "@type" => "Review",
                        "author" => $review_val["author"],
                        "datePublished" => $review_val["datePublished"],
                        "name" => $review_val["name"],
                        "description" => $review_val["description"],
                        "reviewRating" => $rating_schema
                    );
                }

            }

        }

        $returned_string = "";
        if( !empty($returned_array) and is_array($returned_array) ){
            $returned_string .= '<script type="application/ld+json">'."\n";
            $returned_string .= json_encode($returned_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
            $returned_string .= '</script>'."\n";
        }
        return $returned_string;

    }


    ////////////////////////////// SCHEMA CONSTRUCTOR //////////////////////////////





    ////////////////////////////// FEED CONSTRUCTOR //////////////////////////////
    public function make_sitemap_datas( $datas ){

        $returned_string = "";
        $returned_string .= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $returned_string .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        if( !empty($datas) and is_array($datas) ){
            foreach( $datas as $data_key => $data_val ){

                $returned_string .= '<url>'."\n";
                $returned_string .= '<loc>'.$this->convert_sitemap_char($data_val["loc"]).'</loc>'."\n";
                $returned_string .= '<lastmod>'.date("c", $data_val["last_mod"]).'</lastmod>'."\n";

                if( isset($data_val["change_freq"]) and !empty($data_val["change_freq"]) ){
                    $returned_string .= '<changefreq>'.$data_val["change_freq"].'</changefreq>'."\n";
                }

                if( isset($data_val["priority"]) and !empty($data_val["priority"]) ){
                    $returned_string .= '<priority>'.$data_val["priority"].'</priority>'."\n";
                }

                $returned_string .= '</url>'."\n";

            }
        }

        $returned_string .= '</urlset>'."\n";

        return $returned_string;

    }

    public function make_rss_feed_datas( $default_datas ){

        $returned_string = "";

        $returned_string .= '<rss version="2.0" xml:base="'.$this->url_without_ssl.'"'."\n";
        $returned_string .= 'xmlns:atom="http://www.w3.org/2005/Atom"'."\n";
        $returned_string .= 'xmlns:dc="http://purl.org/dc/elements/1.1/"'."\n";
        $returned_string .= 'xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"'."\n";
        $returned_string .= 'xmlns:media="http://search.yahoo.com/mrss/">'."\n";


        $returned_string .= '<channel>'."\n";

        $returned_string .= '<atom:link rel="self" href="'.$default_datas["feed_url"].'" type="application/rss+xml" />'."\n";
        $returned_string .= '<title><![CDATA['.$default_datas["title"].']]></title>'."\n";
        $returned_string .= '<description><![CDATA['.$default_datas["description"].']]></description>'."\n";
        $returned_string .= '<link>'.$this->url_without_ssl.'</link>'."\n";
        $returned_string .= '<language>'.$this->feed_lang.'</language>'."\n";
        $returned_string .= '<managingEditor>'.$this->editor_mail.' '.$this->editor_name.'</managingEditor>'."\n";
        $returned_string .= '<webMaster>'.$this->webmaster_mail.' '.$this->webmaster_name.'</webMaster>'."\n";
        $returned_string .= '<docs>'.$this->feed_docs_url.'</docs>'."\n";

        $returned_string .= '<copyright>'.$this->copyright.'</copyright>'."\n";

        $returned_string .= '<image>'."\n";
        $returned_string .= '<url>'.$this->url_without_ssl.$this->social_logo_link.'</url>'."\n";
        $returned_string .= '<title><![CDATA['.$default_datas["title"].']]></title>'."\n";
        $returned_string .= '<link>'.$this->url_without_ssl.'</link>'."\n";
        $returned_string .= '<description><![CDATA['.$default_datas["description"].']]></description>'."\n";
        $returned_string .= '<width>50</width>'."\n";
        $returned_string .= '<height>50</height>'."\n";
        $returned_string .= '</image>'."\n";

        if( !empty($default_datas["article"]) and is_array($default_datas["article"]) ){
            foreach( $default_datas["article"] as $article_key => $article_val ){

                $returned_string .= '<item>'."\n";
                $returned_string .= '<title><![CDATA['.$article_val["title"].']]></title>'."\n";
                $returned_string .= '<link>'.$article_val["link"].'</link>'."\n";
                $returned_string .= '<description><![CDATA['.$article_val["description"].']]></description>'."\n";

                if( isset($article_val["image"]) and !empty($article_val["image"]) ){

                    $returned_string .= '<enclosure url="'.$this->url_without_ssl.$article_val["image"].'" length="'.$article_val["image_size"].'" type="'.$article_val["image_type"].'" />'."\n";

                }


                if( isset($article_val["video_image"]) and !empty($article_val["video_image"]) ){

                    $returned_string .= '<enclosure url="'.$article_val["video_image"].'" length="'.$article_val["video_image_size"].'" type="'.$article_val["video_image_type"].'" />'."\n";

                }

                $returned_string .= '<guid isPermaLink="true">'.$article_val["link"].'</guid>'."\n";
                $returned_string .= '<pubDate>'.$article_val["pub_date"].'</pubDate>'."\n";
                $returned_string .= '<author>'.$this->editor_mail.' ('.$this->author.')'.'</author>'."\n";

                if( isset($article_val["comment_url"]) and !empty($article_val["comment_url"]) ){
                    $returned_string .= '<comments>'.$article_val["comment_url"].'</comments>'."\n";
                }

                if( !empty($article_val["categories"]) and is_array($article_val["categories"]) ){
                    foreach( $article_val["categories"] as $article_cat_key => $article_cat_val ){
                        $returned_string .= '<category domain="'.$article_cat_val["link"].'">'.$article_cat_val["title"].'</category>'."\n";
                    }
                }

                $returned_string .= '</item>'."\n";
            }
        }


        $returned_string .= '</channel>'."\n";
        $returned_string .= '</rss>'."\n";

        return $returned_string;

    }
    ////////////////////////////// FEED CONSTRUCTOR //////////////////////////////




    ////////////////////////// HELPER FUNCTIONS ///////////////////////////////////
    public function convert_sitemap_char( $data ){

        if( isset($data) and !empty($data) ){

            $data = str_replace(
                array("&", "‘", "“", ">", "<", "'", '"'),
                array("&amp;", "&apos;", "&quot;", "&gt;", "&lt;", "&apos;", "&quot;"),
                $data
            );

        }

        return $data;

    }


    public function check_sitemap_type( $data ){

        if (strpos($data, '.xml') !== false) {
            $data = str_replace(".xml", "", $data);
            if( in_array($data, $this->sitemap_types) ){
                return $data;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }


    public function get_youtube_api_datas( $video_id, $part_type ){

        //snippet
        //contentDetails - duration time is here.
        //statistics

        $api_key = $this->youtube_api_key;
        $returned_array = array();

        if( !empty($part_type) and is_array($part_type) ){
            foreach( $part_type as $part_key => $part_val ){
                if( $part_val == "contentDetails" ){

                    $video_datas = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=$part_val&id=$video_id&key=$api_key");
                    $video_datas_decode = json_decode($video_datas, true);
                    $returned_array["duration_time"] = $video_datas_decode["items"][0]["contentDetails"]["duration"];

                }elseif( $part_val == "statistics" ){

                    $video_datas = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=$part_val&id=$video_id&key=$api_key");
                    $video_datas_decode = json_decode($video_datas, true);
                    $returned_array["viewCount"] = $video_datas_decode["items"][0]["statistics"]["viewCount"];
                    $returned_array["likeCount"] = $video_datas_decode["items"][0]["statistics"]["likeCount"];
                    $returned_array["dislikeCount"] = $video_datas_decode["items"][0]["statistics"]["dislikeCount"];
                    $returned_array["favoriteCount"] = $video_datas_decode["items"][0]["statistics"]["favoriteCount"];
                    $returned_array["commentCount"] = $video_datas_decode["items"][0]["statistics"]["commentCount"];

                }elseif( $part_val == "snippet" ){

                    $video_datas = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=$part_val&id=$video_id&key=$api_key");
                    $video_datas_decode = json_decode($video_datas, true);
                    $returned_array["publishedAt"] = $video_datas_decode["items"][0]["snippet"]["publishedAt"];

                }
            }
        }

        return $returned_array;

    }

    public function calculate_product_rating_value( $datas ){

        $returned_value = 0;

        $review_count = count($datas);
        $rating_value = 0;
        foreach( $datas as $data_key => $data_val ){
            $rating_value += $data_val["rate_value"];
        }
        $returned_value = round($rating_value / $review_count , 1);

        return $returned_value;

    }


    public function calculate_blog_post_rating_value( $datas ){

        $returned_value = 0;

        $review_count = count($datas);
        $rating_value = 0;
        foreach( $datas as $data_key => $data_val ){
            $rating_value += $data_val["rate_value"];
        }
        $returned_value = round($rating_value / $review_count , 1);

        return $returned_value;

    }


    public function make_cleared_content( $data, $content_type = "", $platform_type = "" ){

        $data = strip_tags(trim($data));
        //$data = preg_replace("/[^A-Za-z0-9\.,'-]/", ' ', $data);
        $data = htmlspecialchars($data);
        $data = html_entity_decode($data, ENT_QUOTES, "UTF-8");
        $data = preg_replace( "/\r|\n/", "", $data );
        $data = str_replace(array("'", '"'), array("",""), $data);

        if( $content_type == "title" ){

            if( $platform_type == "google" ){
                $data = mb_substr($data, 0, 60);
            }elseif ($platform_type == "social") {
                $data = mb_substr($data, 0, 90);
            }

        }elseif( $content_type == "description" ){

            if( $platform_type == "google" ){
                $data = mb_substr($data, 0, 155);
            }elseif ($platform_type == "social") {
                $data = mb_substr($data, 0, 250);
            }elseif ($platform_type == "google_snippet") {
                $data = mb_substr($data, 0, 320);
            }

        }elseif( $content_type == "blog" ){

            if( $platform_type == "short_blog" ){
                $data = mb_substr($data, 0, 400);
            }

        }

        return $data;

    }

    public function parse_youtube_url($url){
        $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
        preg_match($pattern, $url, $matches);
        return (isset($matches[1])) ? $matches[1] : false;
    }

    public function getMIMEType($filename){
        $finfo = finfo_open();
        $fileinfo = finfo_file($finfo, $filename, FILEINFO_MIME_TYPE);
        finfo_close($finfo);
        return $fileinfo;
    }

    public function current_url(){
        $url = $this->ci->config->site_url($this->ci->uri->uri_string());
        return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
    }

}