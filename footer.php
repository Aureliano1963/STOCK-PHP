<?php if (@$gsExport == "") { ?>
<?php if (@!$gbSkipHeaderFooter) { ?>
				<!-- right column (end) -->
				<?php if (isset($gTimer)) $gTimer->Stop() ?>
			</div>
		</div>
	</div>
	<!-- content (end) -->
<?php if (MS_SHOW_ENTIRE_FOOTER) { ?>
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
	<div id="ewFooterRow" class="ewFooterRow">
		<div class="ewFooterText">
			<?php if (MS_SHOW_TEXT_IN_FOOTER) { ?><?php echo $Language->ProjectPhrase("FooterText") ?><?php } else { ?><?php echo "&nbsp;"; } ?>
			<?php if (MS_SHOW_TERMS_AND_CONDITIONS_ON_FOOTER) { ?>
			| <a href="javascript:void(0);" id="tac" onclick="MGJS.goTop();msTACDialogShow();return false;"><?php echo $Language->Phrase("TaCTitle"); ?></a>
			<?php } ?>
			<?php if (MS_SHOW_ABOUT_US_ON_FOOTER) { ?>
			| <a href="javascript:void(0);" id="about" onclick="MGJS.goTop();msAboutDialogShow();return false;"><?php echo $Language->Phrase("AboutUs"); ?></a>
			<?php } ?>
			<?php if (MS_SHOW_BACK_TO_TOP_ON_FOOTER) { ?>
			| <a href="javascript:void(0);" id="gotop" onclick="MGJS.goTop();return false;"><?php echo $Language->Phrase("BackToTop"); ?></a>
			<?php } ?>
		</div>
	</div>
	<!-- footer (end) -->
<?php } ?>
</div>
<?php } ?>
<!-- terms and conditions dialog -->
<?php
if (@MS_USE_CONSTANTS_IN_CONFIG_FILE == FALSE) {
  global $conn;
  $sSql = "SELECT Terms_And_Condition_Text FROM ".MS_LANGUAGES_TABLE."
         WHERE Language_Code = '".$gsLanguage."'";              
  $rs = $conn->Execute($sSql);
  $tactitle = $Language->Phrase("TaCTitle");
  if ($rs && $rs->RecordCount() > 0) {
    $taccontent = $rs->fields("Terms_And_Condition_Text");
  } else {
    $taccontent = $Language->Phrase("TaCContent");
  }
} else {
  $tactitle = $Language->Phrase("TaCTitle");
  $taccontent = $Language->Phrase("TACNotAvailable");
}
?>
<div id="msTACDialog" class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">x</span></button><h4 class="modal-title"><?php echo $tactitle; ?></h4></div><div class="modal-body"><?php echo $taccontent; ?></div><div class="modal-footer">&nbsp;<a href="printtac.php"><?php echo Language()->Phrase("Print"); ?></a>&nbsp;&nbsp;<button type="button" class="btn btn-primary ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- about dialog -->
<?php
  $abouttext = "";

// if (MS_LANGUAGES_TABLE != "") {
if (@MS_USE_CONSTANTS_IN_CONFIG_FILE == FALSE) {
  $sSql = "SELECT About_Text FROM ".MS_LANGUAGES_TABLE."
         WHERE Language_Code = '".$gsLanguage."'";              
  $rs = ew_Execute($sSql);
  if ($rs && $rs->RecordCount() > 0) {
    $abouttext = $rs->fields("About_Text");
  } else {
    $abouttext = $Language->Phrase("AboutUs");
  }
} else {
	$abouttext = $Language->Phrase("AboutUs");
}
?>
<div id="msAboutDialog" class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">x</span></button><h4 class="modal-title"><?php echo $Language->Phrase("AboutUs"); ?></h4></div><div class="modal-body"><?php echo $abouttext; ?></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- help dialog -->
<?php
if (MS_SHOW_HELP_ONLINE == TRUE) {
	$helptext = "";
	$helpcat = "";
	if (@MS_USE_CONSTANTS_IN_CONFIG_FILE == FALSE) {
		global $conn;
		$sSql = "SELECT Topic, Description FROM ".MS_HELP_TABLE."
			 WHERE Display_in_Page = '".ew_CurrentPage()."'
			 AND Language = '".$gsLanguage."'";
		$rs = $conn->Execute($sSql);
		if ($rs && $rs->RecordCount() > 0) {
			$helpcat = $rs->fields("Topic");
			$helptext = $rs->fields("Description");
		} else {
			$helpcat =  $Language->Phrase("Help");
			$helptext =  $Language->Phrase("HelpNotAvailable");
		}
	} else {
		$helpcat =  $Language->Phrase("Help");
		$helptext =  $Language->Phrase("HelpNotAvailable");
	}
?>
	<div id="msHelpDialog" class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">x</span></button><h4 class="modal-title"><?php echo $helpcat; ?></h4></div><div class="modal-body"><?php echo $helptext; ?>
	</div><div class="modal-footer"><a href="javascript:void(0);" class="btn btn-primary ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $Language->Phrase("MessageOK") ?></a></div></div></div></div>
<?php } ?>
<!-- search dialog -->
<div id="ewSearchDialog" class="modal fade"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">x</span></button><h4 class="modal-title"></h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("Search") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!-- add option dialog -->
<div id="ewAddOptDialog" class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">x</span></button><h4 class="modal-title"></h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("AddBtn") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!-- email dialog -->
<div id="ewEmailDialog" class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">x</span></button><h4 class="modal-title"></h4></div>
<div class="modal-body">
<?php include_once "ewemail11.php" ?>
</div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("SendEmailBtn") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<?php // Begin of modification Auto Hide Message, by Masino Sinaga, January 24, 2013 ?>
<?php if (@MS_AUTO_HIDE_SUCCESS_MESSAGE) { ?>
<?php // do not call ew_ShowMessage() function ?>
<?php } else { ?>
<!-- message box -->
<div id="ewMsgBox" class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>
<?php } ?>
<?php // End of modification Auto Hide Message, by Masino Sinaga, January 24, 2013 ?>
<!-- tooltip -->
<div id="ewTooltip"></div>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript">

// Write your global startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php if (@CurrentPage()->ListOptions->UseDropDownButton == TRUE) { ?>
<?php if (@MS_USE_TABLE_SETTING_FOR_DROPUP_LISTOPTIONS == FALSE) { ?>
<script type="text/javascript">
$(document).ready(function() {
	var reccount = "<?php echo CurrentPage()->Recordset->RecordCount(); ?>";
	var rowdropup = "<?php echo @MS_GLOBAL_NUMBER_OF_ROWS_DROPUP_LISTOPTIONS; ?>";
	if (reccount > 6) {
		for ( var i = 0; i <= (rowdropup - 1); i++ ) {
			$('#r' + (reccount - i) + '_<?php echo CurrentPage()->TableName; ?> .ewButtonDropdown').addClass('dropup');
		}
	}
});
</script>
<?php } ?>
<?php } ?>
<?php if (MS_STICKY_MENU_ON_SCROLLING==TRUE) { ?>
<script type="text/javascript">
$(document).ready(function(){var mymenu=$('#ewMenuRow').offset().top;var mynav=$('nav').offset().top;$(window).scroll(function(){var woindowScroll= $(window).scrollTop();(woindowScroll>mymenu)?$('#ewMenuRow').addClass('StickyToTop'):$('#ewMenuRow').removeClass('StickyToTop');(woindowScroll>mynav)?$('nav').addClass('StickyToTop'):$('nav').removeClass('StickyToTop');});});
</script>
<?php } ?>
<?php if (MS_RELOAD_PAGE_FOR_FIRST_VISIT==TRUE) { ?>
<?php if ($_SESSION['php_stock_views'] == 1) { ?>
<script type="text/javascript">
$(document).ready(function(){ if(document.URL.indexOf("#")==-1) { url = document.URL+"#"; location = "#"; location.reload(true); } });
</script> 
<?php } ?>
<?php } ?>
</body>
</html>
