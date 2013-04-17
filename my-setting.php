<?php
/*
Plugin Name: MySetting
Plugin URI: http://www.cyokodog.net
Description: function.phpに書くのが面倒なかゆいとこを設定できるプラグイン
Author: cyokodog
Version: 0.5
Author URI: http://www.cyokodog.net
*/
class MySetting {

	private $options;
	private $message;

	function __construct() {

		$o = $this;

		$o->loadOptions();

//		add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', array($this, 'adminMenuAction'));
		add_action('admin_menu', array($this, 'adminMenuAction'));
		add_action('wp_footer', array($this, 'wpFooterAction'));
		add_action('pre_ping', array($this, 'prePingAction'));
		add_action('wp_print_scripts', array($this, 'wpPrintScriptsAction'));
		add_action('wp_enqueue_scripts', array($this, 'wpEnqueueScripts'), 50 );
		add_action( 'admin_head-post.php', array($this, 'headPost') );
		add_action( 'admin_head-post-new.php', array($this, 'headPost') );

		if($o->getParam( 'wp_ver' ) == '0') remove_action('wp_head','wp_generator');
		if($o->getParam( 'cus_nav' ) == '1') add_theme_support('menus');
		if($o->getParam( 'page_excerpt' ) == '1') add_post_type_support( 'page', 'excerpt' );
		if($o->getParam( 'post_thum' ) == '1') add_theme_support( 'post-thumbnails' );

		$width = $o->getParam( 'thum_width' );
		$height = $o->getParam( 'thum_height' );
		$cut = $o->getParam( 'thum_cut' ) == '1' ? true : false;
		if($width != '' || $height != '')
			set_post_thumbnail_size($width, $height, $cut );
	}


	function headPost(){
		if($this->getParam( 'behave' )){
			?>
			<script type="text/javascript">
				jQuery.mysetting = <?php echo json_encode($this->options); ?>;
			</script>
			<script src="<?php echo plugins_url("behave/behave.js", __FILE__); ?>"></script>
			<script src="<?php echo plugins_url("my-setting-admin-post.js", __FILE__); ?>"></script>
			<?php
		}
	}

	function adminMenuAction() {
		add_menu_page(
			'My Setting',		//HTMLのページタイトル
			'My Setting',		//管理画面メニューの表示名
			'administrator',	//この機能を利用できるユーザ
			 __FILE__,			//urlに入る名前
			 array($this,'mySettingPage')	//機能を提供するメソッド
		);
	}

	function theYesNoHtml($name,$val,$selected){
		$yes = ($this->getParam( $name ) == $val ? $selected : ($selected == 'selected' ? '' : 'selected'));
		$no = ($yes == '' ? 'selected' : '');
		echo '
			<select name="mysetting_options['.$name.']">
				<option value="1" '.$yes.'>はい</option>
				<option value="0" '.$no.'>いいえ</option>
			</select>
		';
	}

	function theSocialButtonHtml($name){
		echo '
			<select name="mysetting_options['.$name.']">
				<option value="0">非表示</option>
		';
		for($i = 1; $i <= 4; $i ++){
			echo '<option value="'.$i.'" '.($this->getParam( $name ) == $i ? 'selected' : '').'>'.$i.'番目に表示</option>';
		}
		echo '
			</select>
		';
	}

	function theInputHtml($name,$placeholder="",$class="regular-text",$defaultVal=""){
		$v = $this->getParam( $name );
		if(is_null($v)) $v = $defaultVal;
		echo '<input name="mysetting_options['.$name.']" type="text" value="'.$v.'" class="'.$class.'" placeholder="'.$placeholder.'"/>';
	}

	function theCheckboxHtml($name,$display=""){
		$checked = $this->getParam( $name ) == '1' ? 'checked' : '';
		echo '<label><input name="mysetting_options['.$name.']" type="checkbox" value="1" '.$checked.'/> '.$display. '</label> ';
	}

	function theTextareaHtml($name,$placeholder){
		echo '<textarea name="mysetting_options['.$name.']" class="large-text code" placeholder="'.$placeholder.'">'.$this->getParam( $name ).'</textarea>';
	}

	function loadOptions(){
		$this->options = get_option('mysetting_options');
	}

	function getParam( $name ){
		return isset($this->options[$name]) ? $this->options[$name]: null;
	}

	function saveOptions( $options ){
		delete_option('mysetting_options');
		update_option('mysetting_options', $options);
		return 'Options saved.';
	}

	function mySettingPage() {
		$o = $this;
		if ( isset($_POST['mysetting_options'])) {
			check_admin_referer('mysetting_check');
			$o->message = $this->saveOptions($_POST['mysetting_options']);
			$o->loadOptions();
		}
		include 'my-setting-view.php';
	}

	function wpFooterAction(){
		if($this->getParam( 'ga_id' )){
			?>
				<script type="text/javascript">
					var _gaq = _gaq || [];
					_gaq.push(['_setAccount', '<?php echo $this->getParam( 'ga_id' ) ?>']);
					_gaq.push(['_trackPageview']);
					(function() {
						var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
						ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
					})();
				</script>
			<?php
		}
		?>
			<script type="text/javascript">
				jQuery.mysetting = <?php echo json_encode($this->options); ?>;
			</script>
		<?php
	}

	function prePingAction( &$links ){
		if($this->getParam( 'self_pin' ) == '1'){
			$home = get_option( 'home' );
			foreach ( $links as $l => $link )
				if ( 0 === strpos( $link, $home ) )
					unset($links[$l]);
		}
	}

	function wpPrintScriptsAction(){
		if($this->getParam( 'no_autosave' ) == '1') wp_deregister_script('autosave');
	}

	function wpEnqueueScripts(){
		if ( !is_admin() ) {
			wp_enqueue_style( 'sitekit', plugins_url("jquery.sitekit/sitekit.css", __FILE__));
			wp_enqueue_script( 'jquery.sitekit', plugins_url("jquery.sitekit/jquery.sitekit.js", __FILE__), array('jquery'),false,true);

			if($this->getParam( 'prettify' )){
				wp_enqueue_style( 'google-code-prettify', plugins_url("google-code-prettify/prettify-a.css", __FILE__));
				wp_enqueue_script( 'google-code-prettify', plugins_url("google-code-prettify/prettify.js", __FILE__), array('jquery'),false,true);
			}
			wp_enqueue_script( 'my-setting', plugins_url("my-setting.js", __FILE__), array('jquery.sitekit'),false,true);
		}
		else{
			wp_enqueue_script( 'behave', plugins_url("behave/behave.js", __FILE__), array('jquery'),false,true);
		}
	}
}
$mySetting = new MySetting;