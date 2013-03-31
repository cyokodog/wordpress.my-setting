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

		add_action( 'wp_enqueue_scripts', array($this, 'wpEnqueueScripts'), 50 );


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



	function theInputHtml($name,$placeholder="",$class="regular-text"){
		echo '<input name="mysetting_options['.$name.']" type="text" value="'.$this->getParam( $name ).'" class="'.$class.'" placeholder="'.$placeholder.'"/>';
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
			<style>
				.indent{padding-left:26px!important;}
				tr.social-button-sort label{
					margin-right:16px;
				}
			</style>
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
							<td><?php $o->theYesNoHtml('wp_ver','0','') ?></td>
						</tr>
						<tr valign="top">
							<th>カスタムナビゲーションメニューを有効にする</th>
							<td><?php $o->theYesNoHtml('cus_nav','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>セルフピンバックを無効にする</th>
							<td><?php $o->theYesNoHtml('self_pin','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>固定ページでの抜粋を有効にする</th>
							<td><?php $o->theYesNoHtml('page_excerpt','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>自動保存を無効にする</th>
							<td><?php $o->theYesNoHtml('no_autosave','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>外部サイトのリンクに target="_blank" を設定する</th>
							<td><?php $o->theYesNoHtml('external','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>Google Code Prettify を有効にする</th>
							<td><?php $o->theYesNoHtml('prettify','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th>Google Custom Search ID</th>
							<td><?php $o->theInputHtml('google_search_id') ?></td>
						</tr>
						<tr valign="top">
							<th class="indent">検索ウィジェットを置き換える</th>
							<td><?php $o->theYesNoHtml('google_search_replace','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th class="indent">フォーム挿入先の jQuery セレクタ<br/>※置換する場合は指定不要</th>
							<td><?php $o->theInputHtml('google_search_area','例) div.search_form') ?></label></td>
						</tr>
						<tr valign="top">
							<th>リンクにはてブ件数を表示する</th>
							<td>
								<?php $o->theYesNoHtml('hatebu_users','1','selected') ?><br/>
							</td>
						</tr>
						<tr valign="top">
							<th class="indent">対象リンクの jQuery セレクタ</th>
							<td><?php $o->theInputHtml('hatebu_users_code','例) #sidebar a') ?></label></td>
						</tr>
						<tr valign="top" class="social-button-sort">
							<th>ソーシャルボタンの表示</th>
							<td>
								<label>はてブ<?php $o->theSocialButtonHtml('hatebu_sort') ?></label>
								<label>Twitter<?php $o->theSocialButtonHtml('twitter_sort') ?></label>
								<label>Facebook<?php $o->theSocialButtonHtml('facebook_sort') ?></label>
								<label>Google+<?php $o->theSocialButtonHtml('googleplus_sort') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th class="indent">対象リンクの jQuery セレクタ</th>
							<td><?php $o->theInputHtml('social_btn_link','例) article h2 a') ?></label></td>
						</tr>
						<tr valign="top">
							<th class="indent">ボタン挿入先の jQuery セレクタ</th>
							<td><?php $o->theInputHtml('social_btn_area','例) article div.social_area') ?></label></td>
						</tr>
						<tr valign="top">
							<th class="indent">大きいボタンにする</th>
							<td><?php $o->theYesNoHtml('social_large_btn','1','selected') ?></label></td>
						</tr>
						<tr valign="top">
							<th>アイキャッチ画像を有効にする</th>
							<td><?php $o->theYesNoHtml('post_thum','1','selected') ?></td>
						</tr>
						<tr valign="top">
							<th class="indent">画像のサイズ（幅 × 高さ）</th>
							<td><?php $o->theInputHtml('thum_width','','small-text') ?> × <?php $o->theInputHtml('thum_height','','small-text') ?></td>
						</tr>
						<tr valign="top">
							<th class="indent">切り抜きモードにする</th>
							<td><?php $o->theYesNoHtml('thum_cut','1','selected') ?></td>
						</tr>
					</table>
					<p class="submit"><input type="submit" class="button-primary" value="変更を保存" /></p>
				</form>
			</div>
		<?php
	}

	function wpFooterAction(){
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

			wp_enqueue_script( 'my-setting', plugins_url("my-setting.js", __FILE__), array('jquery.sitekit','google-code-prettify'),false,true);
		}
	}



}
$mySetting = new MySetting;