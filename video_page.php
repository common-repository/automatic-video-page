<?php
/*
Plugin Name: Automatic Video Page
Plugin URI: http://bumbablog.com
Description: Este plugin permite crear contenido automático y relevante para tu página. Bastará con poner el título de tu post y el plugin se encargará de buscar el contenido relacionado poniendo un shortcode en tus post o pages. Por ej. [videos buscar="Adele" num="10"]. Este plugin es responsive. Contribuirá con el posicionamiento SEO de tu página, ampliando su contenido en pocos segundos. Puedes crear cientos de paginas rápidamente. Por ej. Rihanna, Adele, Paul Simon, Eminem, etc, etc, num = "50" (máximo debe ser 50). [videos buscar="Rihanna" num="50"]
Version: 1.01
Author: BUMBABlog
Author URI: http://bumbablog.com
License: GPL2
*/
?>
<?php
function videopageautomatic($atts){
	    extract(shortcode_atts(array(
                'buscar' => 'No especificado',
				'num' => 'No especificado',
        ), $atts));
		$title = $buscar;
		$title = str_replace(" ", "+", $title);
 		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,"http://goodfidelity.com/artistas.php?buscarcancion=".$title."");
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_MAXREDIRS,10);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,100);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');
		curl_setopt($ch,CURLOPT_HTTP_VERSION,'CURLOPT_HTTP_VERSION_1_1');
		$data = curl_exec($ch);	
		$error = curl_error($ch);
		curl_close($ch);
		preg_match_all("(<span id=\"IDvideo\" style=\"display:none\">(.*)</span>)siU", $data, $matches1);
		preg_match_all("(<span id=\"TITLEvideo\">(.*)</span>)siU", $data, $matches2);
		preg_match_all("(<span id=\"DESvideo\">(.*)</span>)siU", $data, $matches3);
		
		$var1 = "<div style=\"float:left; width:100%; background:#000; margin-bottom:10px\">
        <iframe id=\"pantalla\" name=\"reprotube\" class=\"youtube-player\" type=\"text/html\" width=\"100%\" height=\"375\" src=\"http://youtube.com/embed/".$matches1[1][0]."?autoplay=0&autohide=0&showinfo=1&modestbranding=1&iv_load_policy=3\" frameborder=\"0\"></iframe></div>";
			
		
		for ( $X = 0 ; $X <= $num - 1 ; $X ++) {
			$IDvideo=$matches1[1][$X];
			$IMAGEvideo= "http://i.ytimg.com/vi/".$IDvideo."/2.jpg";
			$TITLEvideo= $matches2[1][$X];
			$DESvideo= $matches3[1][$X];
		
            $var2 = "<a href=\"http://youtube.com/embed/".$IDvideo."?autoplay=1&autohide=0&showinf1=0&modestbranding=1&iv_load_policy=3\" target=\"reprotube\">
            <div style=\"float:left; width:100%; margin-right:10px; margin-bottom:10px;\">
                <div style=\"float:left; width:120px; margin:5px; height: 90px\">
                	<img src=\"".$IMAGEvideo."\" width=\"120\" height=\"90\" /> 
                </div>"
                	.$TITLEvideo."<br />
					<span style=\"color:#444; font-weight:normal\">".$DESvideo."</span>
            </div>
            </a>";
			$acumulado = $acumulado.$var2;
		}
		return $var1.$acumulado;
}

function register_shortcodes(){
   add_shortcode('videos', 'videopageautomatic');
}

add_action( 'init', 'register_shortcodes');
?>
