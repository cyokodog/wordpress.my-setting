<?php $o = $this ?>
<style>
	.indent{
		padding-left:26px!important;
	}
	tr.social-button-sort label{
		margin-right:16px;
	}
	.form-table{
		margin-bottom:32px;
	}
</style>
<div class="wrap">
	<?php
		if($o->message){
			?>
				<div class="updated fade">
					<p><strong><?php _e( $o->message ); ?></strong></p>
				</div>
			<?php
		}
	?>
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>My Setting</h2>
	<form action="" method="post">
		<?php wp_nonce_field('mysetting_check') ?>

		<h3>WordPress Setting</h3>

		<table class="form-table">
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

		<h3>Behave.js Setting</h3>

		<table class="form-table">
			<tr valign="top">
				<th>投稿画面で<a href="http://jakiestfu.github.io/Behave.js/" target="_blank">Behave.js</a>を有効にする</th>
				<td><?php $o->theYesNoHtml('behave','1','selected') ?></td>
			</tr>
			<tr valign="top">
				<th class="indent">replaceTab</th>
				<td><?php $o->theYesNoHtml('behave-replaceTab','0','') ?></td>
			</tr>
			<tr valign="top">
				<th class="indent">softTabs</th>
				<td><?php $o->theYesNoHtml('behave-softTabs','0','') ?></td>
			</tr>
			<tr valign="top">
				<th class="indent">autoOpen</th>
				<td><?php $o->theYesNoHtml('behave-autoOpen','0','') ?></td>
			</tr>
			<tr valign="top">
				<th class="indent">overwrite</th>
				<td><?php $o->theYesNoHtml('behave-overwrite','0','') ?></td>
			</tr>
			<tr valign="top">
				<th class="indent">autoStrip</th>
				<td><?php $o->theYesNoHtml('behave-autoStrip','0','') ?></td>
			</tr>
			<tr valign="top">
				<th class="indent">autoIndent</th>
				<td><?php $o->theYesNoHtml('behave-autoIndent','0','') ?></td>
			</tr>
			<tr valign="top">
				<th class="indent">tabSize</th>
				<td><?php $o->theInputHtml('behave-tabSize','','small-text',4) ?></td>
			</tr>
		</table>

		<h3>Google Service</h3>
		<table class="form-table">
			<tr valign="top">
				<th>Google Analytics ID</th>
				<td><?php $o->theInputHtml('ga_id') ?></td>
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
		</table>

		<h3>Social Service</h3>
		<table class="form-table">
			<tr valign="top">
				<th>リンクにはてブ件数を表示する</th>
				<td>
					<?php $o->theYesNoHtml('hatebu_users','1','selected') ?><br/>
				</td>
			</tr>
			<tr valign="top">
				<th class="indent">対象リンクの jQuery セレクタ(必須)</th>
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
				<th class="indent">対象リンクの jQuery セレクタ(必須)</th>
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
		</table>
		<p class="submit"><input type="submit" class="button-primary" value="変更を保存" /></p>
	</form>
</div>
