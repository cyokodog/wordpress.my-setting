<?php
/*
Plugin Name: MySetting
Plugin URI: http://www.cyokodog.net
Description: function.phpに書くのが面倒なかゆいとこを設定できるプラグイン
Author: cyokodog
Version: 0.1
Author URI: http://www.cyokodog.net
*/
class MySetting {

	private $options;

	function __construct() {

		$o = $this;

		$o->loadOptions();

		add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', array($this, 'adminMenuAction'));
		add_action('wp_footer', array($this, 'wpFooterAction'));
		add_action('pre_ping', array($this, 'prePingAction'));
		add_action('wp_print_scripts', array($this, 'wpPrintScriptsAction'));

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

	function adminMenuAction() {
		add_menu_page('My Setting','My Setting',  'level_8', __FILE__, array($this,'mySettingPage'));
	}

	function theSelectHtml($name,$val,$selected){
		$yes = ($this->getParam( $name ) == $val ? $selected : ($selected == 'selected' ? '' : 'selected'));
		$no = ($yes == '' ? 'selected' : '');
		echo '
			<select name="mysetting_options['.$name.']">
				<option value="1" '.$yes.'>はい</option>
				<option value="0" '.$no.'>いいえ</option>
			</select>
		';
	}

	function theInputHtml($name){
		echo '<input name="mysetting_options['.$name.']" type="text" value="'.$this->getParam( $name ).'" class="regular-text" />';
	}

	function loadOptions(){
		$this->options = get_option('mysetting_options');
	}

	function getParam( $name ){
		return isset($this->options[$name]) ? $this->options[$name]: null;
	}

	function saveOptions( $options ){
		update_option('mysetting_options', $options);
		return 'Options saved.';
	}

	function mySettingPage() {

		$o = $this;

		if ( isset($_POST['mysetting_options'])) {
			check_admin_referer('shoptions');
			$message = $this->saveOptions($_POST['mysetting_options']);
			if($message){
				?>
					<div class="updated fade">
						<p><strong><?php _e( $message ); ?></strong></p>
					</div>
				<?php
			}
			$o->loadOptions();
		}

		?>
			<div class="wrap">
				<div id="icon-options-general" class="icon32"><br /></div>
				<h2>My Setting</h2>
				<form action="" method="post">
					<?php wp_nonce_field('shoptions') ?>
					<table class="form-table">
						<tr valign="top">
							<th>Google Analytics ID</th>
							<td><?php $o->theInputHtml('ga_id') ?></td>
						</tr>
						<tr valign="top">
							<th>WordPress のバージョンを出力する</th>
							<td><?php $o->theSelectHtml('wp_ver','0','') ?></td>
						</tr>
						<tr valign="top">
							<th>カスタムナビゲーションメニューを有効にする</th>
							<td><?php $o->theSelectHtml('cus_nav','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>セルフピンバックを無効にする</th>
							<td><?php $o->theSelectHtml('self_pin','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>固定ページでの抜粋を有効にする</th>
							<td><?php $o->theSelectHtml('page_excerpt','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>自動保存を無効にする</th>
							<td><?php $o->theSelectHtml('no_autosave','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>外部サイトのリンクに target="_blank" を設定する</th>
							<td><?php $o->theSelectHtml('external','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>Google Code Prettify を有効にする</th>
							<td><?php $o->theSelectHtml('prettify','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>アイキャッチ画像を有効にする</th>
							<td><?php $o->theSelectHtml('post_thum','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>アイキャッチ画像のサイズ（幅）</th>
							<td><?php $o->theInputHtml('thum_width') ?></td>
						</tr>
						<tr valign="top">
							<th>アイキャッチ画像のサイズ（高さ）</th>
							<td><?php $o->theInputHtml('thum_height') ?></td>
						</tr>
						<tr valign="top">
							<th>アイキャッチ画像を切り抜きモードにする</th>
							<td><?php $o->theSelectHtml('thum_cut','1','selected') ?></td>
						</tr>
					</table>
					<p class="submit"><input type="submit" class="button-primary" value="変更を保存" /></p>
				</form>
			</div>
		<?php
	}

	function wpFooterAction(){
		$o = $this;
		if($o->getParam( 'ga_id' )){
			?>
				<script type="text/javascript">
					var _gaq = _gaq || [];
					_gaq.push(['_setAccount', '<?php echo $o->getParam( 'ga_id' ) ?>']);
					_gaq.push(['_trackPageview']);
					(function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
					})();
				</script>
			<?php
		}
		if($o->getParam( 'external' )){
			?>
				<style>
					a:hover.external{
						padding-right:14px;
						background:url("<?php echo plugins_url("jquery.external/external.gif", __FILE__) ?>") no-repeat right 3px #ffffcc;
						color:#ff00ff;
					}
				</style>
				<script type="text/javascript" src="<?php echo plugins_url("jquery.external/jquery.external.js", __FILE__) ?>"></script>
				<script type="text/javascript">
					$('a').external();
				</script>
			<?php
		}
		if($o->getParam( 'prettify' )){
			?>
				<link rel="stylesheet" type="text/css" media="screen" href="<?php echo plugins_url("google-code-prettify/prettify-a.css", __FILE__) ?>">
				<script type="text/javascript" src="<?php echo plugins_url("google-code-prettify/prettify.js", __FILE__) ?>"></script>
				<script type="text/javascript">
					$('pre').each(function(){
						$(this)[0].className || $(this).addClass('prettyprint linenums');
					});
					prettyPrint();
				</script>
			<?php
		}

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
}
$mySetting = new MySetting;