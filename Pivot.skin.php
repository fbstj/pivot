<?php

/**
 * Skin file for Pivot 
 *
 * @file
 * @ingroup Skins
 */
 

class SkinPivot extends SkinTemplate {
	public $skinname = 'pivot', $stylename = 'pivot', $template = 'pivotTemplate', $useHeadElement = true;

	public function initPage(OutputPage $out) {
        parent::initPage($out);
		global $wgLocalStylePath;

		$viewport_meta = 'width=device-width, user-scalable=yes, initial-scale=1.0';
		$out->addMeta('viewport', $viewport_meta);
		$out->addModuleScripts('skins.pivot.js');
	}
	
	public function setupSkinUserCss(OutputPage $out) {
		parent::setupSkinUserCss($out);
    	global $wgLocalStylePath;
		global $wgPivotFeatures;
		$wgPivotFeaturesDefaults = array(
			'showActionsForAnon' => true,
			'fixedNavBar' => false,
			'usePivotTabs' => false,
			'showHelpUnderTools' => true,
			'showRecentChangesUnderTools' => true,
			'wikiName' => &$GLOBALS['wgSitename'],
			'wikiNameDesktop' => &$GLOBALS['wgSitename'],
			'navbarIcon' => false,
			'preloadFontAwesome' => false,
			'showFooterIcons' => false,
		);
		foreach ($wgPivotFeaturesDefaults as $fgOption => $fgOptionValue) {
			if ( !isset($wgPivotFeatures[$fgOption]) ) {
				$wgPivotFeatures[$fgOption] = $fgOptionValue;
			}
		}
		$out->addModuleStyles('skins.pivot.styles');
		if ( $wgPivotFeatures['preloadFontAwesome'] ) {
			$out->addHeadItem('font', '<link rel="preload" href="'.$wgLocalStylePath.'/pivot/assets/fonts/fontawesome-webfont.woff2?v=4.7.0" as="font" type="font/woff2" crossorigin="anonymous" />');
		}
	}

}


class pivotTemplate extends BaseTemplate {
	public function execute() {
		global $wgUser;
		global $wgPivotFeatures;
		wfSuppressWarnings();
		$this->html('headelement');
		switch ($wgPivotFeatures['usePivotTabs']) {
			case true:
			    ob_start();
				$this->html('bodytext');
				$out = ob_get_contents();
				ob_end_clean();
				$markers = array("&lt;a", "&lt;/a", "&gt;");
				$tags = array("<a", "</a", ">");
				$body = str_replace($markers, $tags, $out);
				break;	
			default:
				$body = '';
				break;
		}

?>
<!-- START PIVOTTEMPLATE -->
		<div class="off-canvas-wrap docs-wrap" data-offcanvas="">
			<div class="inner-wrap">
				<?php if ($wgPivotFeatures['fixedNavBar'] != false) echo "<div class='fixed'>"; ?>
				<nav class="tab-bar hide-for-print">
					<section id="left-nav-aside" class="left-small show-for-small">
						<a class="left-off-canvas-toggle"><span id="menu-user"><i class="fa fa-navicon fa-lg"></i></span></a>
					</section>
					
					<section id="middle-nav" class="middle tab-bar-section">
						<h1 class="title"><a href="<?php echo $this->data['nav_urls']['mainpage']['href']; ?>">
					<span class="show-for-medium-up"><?php echo $wgPivotFeatures['wikiNameDesktop']; ?></span>
						<span class="show-for-small-only">
						<?php if ($wgPivotFeatures['navbarIcon'] != false) { ?>
							<img alt="<?php echo $this->text('sitename'); ?>" src="<?php echo $this->text('logopath'); ?>" style="max-width: 64px;height:auto; max-height:36px; display: inline-block; vertical-align:middle;">
								<?php } ?>
						<?php echo $wgPivotFeatures['wikiName']; ?></span></a></h1>
					</section>

<?php $this->render_echo($wgUser); ?>

					<section id="right-nav-aside" class="right-small">
					<a class="right-off-canvas-toggle"><span id="menu-user"><i class="fa <?php if ($wgUser->isLoggedIn()): ?>fa-user<?php else: ?>fa-navicon<?php endif; ?> fa-lg"></i></span></a>
					</section>
				</nav>
				<?php if ($wgPivotFeatures['fixedNavBar'] != false) echo "</div>"; ?>
				    <aside class="left-off-canvas-menu">
      					<ul class="off-canvas-list">
						
								<li class="has-form">
									<form action="<?php $this->text( 'wgScript' ); ?>" id="searchform" class="mw-search">
										<div class="row collapse">
											<div class="small-12 pivot-columns">
												<input type="search" name="search" placeholder="<?php echo wfMessage( 'search' )->text() ?>" title="Search [alt-shift-f]" accesskey="f" id="searchInput-offcanvas" autocomplete="off">
											</div>
										</div>
									</form>
								</li>
								
							<?php $this->renderSidebar() ?>
						</ul>
					</aside>
					
					<aside class="right-off-canvas-menu">
					  <ul class="off-canvas-list">
					<?php if ($wgUser->isLoggedIn()): ?>
						<li id="personal-tools"><label>Personal</label></li>
						<?php foreach ($this->getPersonalTools() as $key => $item) { echo $this->makeListItem($key, $item); } ?>
							<?php else: ?>
								<?php if (isset($this->data['personal_urls']['anonlogin'])): ?>
									<li><a href="<?php echo $this->data['personal_urls']['anonlogin']['href']; ?>"><?php echo wfMessage( 'login' )->text() ?></a></li>
								<?php elseif (isset($this->data['personal_urls']['login'])): ?>
									<li><a href="<?php echo htmlspecialchars($this->data['personal_urls']['login']['href']); ?>"><?php echo wfMessage( 'login' )->text() ?></a></li>
										<?php else: ?>
											<li><?php echo Linker::link(Title::newFromText('Special:UserLogin'), wfMessage( 'login' )->text()); ?></li>
								<?php endif; ?>
							<?php endif; ?>
					  </ul>
					</aside>

					<section id="main-section" class="main-section" <?php if ($wgPivotFeatures['fixedNavBar'] != false) echo "style='margin-top:2.8125em'"; ?>>
					
						<div id="page-content">
							
							<div id="mw-js-message" style="display:none;"></div>

							<div class="row">
								
								<div id="sidebar" class="large-2 medium-3 pivot-columns hide-for-small hide-for-print">
										<ul class="side-nav">
											<li class="name logo">
											<a href="<?php echo $this->data['nav_urls']['mainpage']['href']; ?>">
												<img alt="<?php echo $this->text('sitename'); ?>" src="<?php echo $this->text('logopath') ?>" style="max-width: 100%;height: auto;display: inline-block; vertical-align: middle;"></a>		
											</li>
											<li class="has-form">
												<form action="<?php $this->text( 'wgScript' ); ?>" id="searchform" class="mw-search">
													<div class="row collapse">
														<div class="small-12 pivot-columns">
															<input type="search" name="search" placeholder="<?php echo wfMessage( 'search' )->text() ?>" title="Search [alt-shift-f]" accesskey="f" id="searchInput" autocomplete="off">
														</div>
													</div>
												</form>
											</li>
								
											<?php $this->renderSidebar() ?>
										</ul>
								</div>
								
								<div id="p-cactions" class="large-10 medium-9 pivot-columns">
								
									<?php if ($wgUser->isLoggedIn() || $wgPivotFeatures['showActionsForAnon']): ?>
										<a id="drop" href="#" data-options="align:left" data-dropdown="drop1" class="button secondary small radius pull-right hide-for-print"><i class="fa fa-navicon fa-lg"><span id="page-actions" class="show-for-medium-up">&nbsp;<?php echo wfMessage( 'actions' )->text() ?></span></i></a>
										<ul id="drop1" class="tiny content f-dropdown" data-dropdown-content>
											<?php foreach($this->data['content_actions'] as $key => $tab) { echo preg_replace(array('/\sprimary="1"/', '/\scontext="[a-z]+"/', '/\srel="archives"/'),'',$this->makeListItem($key, $tab)); } ?>
											<?php wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this, true ));  ?>
										</ul>

									<?php endif; ?>


<?= $this->getIndicators() ?>

	<div id="content">

<?= $this->render_title($this->getSkin()->getTitle(), $wgUser) ?>

									<?php if ( $this->data['isarticle'] ) { ?><h3 id="tagline"><?php $this->msg( 'tagline' ) ?></h3><?php } ?>
									<?php if ( $this->html('subtitle') ) { ?><h5 id="sitesub" class="subtitle"><?php $this->html('subtitle') ?></h5><?php } ?>

									<div id="contentSub" class="clear_both"></div>
									<div id="bodyContent" class="mw-bodytext">
									<?php 
									switch ($wgPivotFeatures['usePivotTabs']) {
										case true:
											echo $body;
											break;
										default:
										$this->html('bodytext');
											break;
											}
									?>
									<div class="clear_both"></div>
									</div>
									</div>
<?php $this->html('catlinks'); ?>
<?php $this->render_footer(); ?>

								</div>
						</div>
					</div>

				</section>

			</div>
		</div>


		<div>
			<a class="exit-off-canvas"></a>	
		</div>
		
		
		<?php $this->printTrail(); ?>
		</body>
		</html>

<?php
		wfRestoreWarnings();
		
	}

	function render_footer() { ?>
<footer class="row">
<?php
		foreach ($this->getFooterIcons("iconsonly") as $blockName => $footerIcons) {
?>
	<span class="<?php echo $blockName ?>"><?php
			foreach ($footerIcons as $icon) {
				print $this->getSkin()->makeFooterIcon($icon, "withImage");
			}
	 ?></span><?php
		}
		foreach ($this->getFooterLinks("flat") as $key) {
?>
	<span id="footer-<?= $key ?>"><?php $this->html($key) ?></span>
<?php
		}
		?></footer><?php
	}

	function render_title($title, $user) {
		// add a span around the namespace
		$ns = str_replace('_', ' ', $title->getNsText());
		$displaytitle = str_replace(
				$ns, "<span class=\"ns\">{$ns}</span>",
				$this->data['title']);
		// add an edit link to pages user can edit
		$editlink = "";
		if ($title->userCan('edit', $user)) {
			$editlink = $title->getEditURL();
			$editlink = "<a href=\"{$editlink}\" title=\"Edit page\">edit</a>";
			$editlink = "<span class=\"mw-editsection\">[$editlink]</span>";
		}
		// print title
		?><h2 class="title"><?= $displaytitle ?><?= $editlink ?></h2><?
	}
	
	function render_echo(&$user) {
		if ($user->isLoggedIn()) { ?>
<div id="echo-notifications" class="show-for-medium-up">
	<div id="echo-notifications-alerts"></div>
	<div id="echo-notifications-messages"></div>
	<div id="echo-notifications-notice"></div>
</div><?php
		}
	}

	function renderSidebar() { 
		$sidebar = $this->getSidebar();
		foreach ($sidebar as $boxName => $box) { 
			echo '<li><label class="sidebar" id="'.Sanitizer::escapeId( $box['id'] ).'"';echo Linker::tooltip( $box['id'] ).'>'.htmlspecialchars( $box['header'] ).'</label></li>';
					if ( is_array( $box['content'] ) ) {
							foreach ($box['content'] as $key => $item) { echo $this->makeListItem($key, $item); }
								} 
							}
		return;	}	
}
?>
