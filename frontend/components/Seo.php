<?PHP
/**
 * Created by PhpStorm.
 * User: Taleh
 * Date: 1/9/2019
 * Time: 2:36 PM
 */
namespace frontend\components;
use Yii;
use yii\base\BaseObject;
use frontend\models\MainModel;

class Seo extends BaseObject
{

    public function site_map_pages($datas){
        $returned_string = "";
        $returned_string .= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $returned_string .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        if( !empty($datas) and is_array($datas) ){
            foreach( $datas as $data_key => $data_val ){
                $returned_string .= '<sitemap>'."\n";
                $returned_string .= '<loc>'.$this->convert_sitemap_char($data_val["loc"]).'</loc>'."\n";
                $returned_string .= '<lastmod>'.date("c", $data_val["last_mod"]).'</lastmod>'."\n";
                $returned_string .= '</sitemap>'."\n";
            }
        }
        $returned_string .= '</sitemapindex>'."\n";
        return $returned_string;
    }

    public function site_map_url($datas){
        $returned_string = "";
        $returned_string .= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $returned_string .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        if( !empty($datas) and is_array($datas) ){
            foreach( $datas as $data_key => $data_val ){
                $returned_string .= '<url>'."\n";
                $returned_string .= '<loc>'.$this->convert_sitemap_char($data_val["loc"]).'</loc>'."\n";
                $returned_string .= '<lastmod>'.date("c", $data_val["last_mod"]).'</lastmod>'."\n";
                $returned_string .= '</url>'."\n";
            }
        }
        $returned_string .= '</urlset>'."\n";
        return $returned_string;
    }


    public function convert_sitemap_char( $data )
    {
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
}
?>