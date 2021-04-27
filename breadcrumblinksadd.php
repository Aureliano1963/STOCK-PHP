<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "breadcrumblinksinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$breadcrumblinks_add = NULL; // Initialize page object first

class cbreadcrumblinks_add extends cbreadcrumblinks {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B36B93AF-B58F-461B-B767-5F08C12493E9}";

	// Table name
	var $TableName = 'breadcrumblinks';

	// Page object name
	var $PageObjName = 'breadcrumblinks_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {

		// $hidden = TRUE;
		$hidden = MS_USE_JAVASCRIPT_MESSAGE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display

			// if (!$hidden)
			//	 $sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			// $html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			// Begin of modification Auto Hide Message, by Masino Sinaga, January 24, 2013

			if (@MS_AUTO_HIDE_SUCCESS_MESSAGE) {

				//$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
				$html .= "<p class=\"alert alert-success msSuccessMessage\" id=\"ewSuccessMessage\">" . $sSuccessMessage . "</p>";
			} else {
				if (!$hidden)
					$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
				$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			}

			// End of modification Auto Hide Message, by Masino Sinaga, January 24, 2013
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}

		// echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
		if (@MS_AUTO_HIDE_SUCCESS_MESSAGE || MS_USE_JAVASCRIPT_MESSAGE==0) {
			echo $html;
		} else {
			if (MS_USE_ALERTIFY_FOR_MESSAGE_DIALOG) {
				if ($html <> "") {
					$html = str_replace("'", "\'", $html);
					echo "<script type='text/javascript'>alertify.alert('".$html."', function (ok) { }, ewLanguage.Phrase('AlertifyAlert'));</script>";
				}
			} else {
				echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
			}
		}
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (breadcrumblinks)
		if (!isset($GLOBALS["breadcrumblinks"]) || get_class($GLOBALS["breadcrumblinks"]) == "cbreadcrumblinks") {
			$GLOBALS["breadcrumblinks"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["breadcrumblinks"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// User table object (users)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'breadcrumblinks', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		if (!isset($_SESSION['table_breadcrumblinks_views'])) { 
			$_SESSION['table_breadcrumblinks_views'] = 0;
		}
		$_SESSION['table_breadcrumblinks_views'] = $_SESSION['table_breadcrumblinks_views']+1;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			$this->Page_Terminate(ew_GetUrl("changepwd.php"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("breadcrumblinkslist.php"));
		}

		// Begin of modification Auto Logout After Idle for the Certain Time, by Masino Sinaga, May 5, 2012
		if (IsLoggedIn() && !IsSysAdmin()) {

			// Begin of modification by Masino Sinaga, May 25, 2012 in order to not autologout after clear another user's session ID whenever back to another page.           
			$UserProfile->LoadProfileFromDatabase(CurrentUserName());

			// End of modification by Masino Sinaga, May 25, 2012 in order to not autologout after clear another user's session ID whenever back to another page.
			// Begin of modification Save Last Users' Visitted Page, by Masino Sinaga, May 25, 2012

			$lastpage = ew_CurrentPage();
			if ($lastpage!='logout.php' && $lastpage!='index.php') {
				$lasturl = ew_CurrentUrl();
				$sFilterUserID = str_replace("%u", ew_AdjustSql(CurrentUserName()), EW_USER_NAME_FILTER);
				ew_Execute("UPDATE ".EW_USER_TABLE." SET Current_URL = '".$lasturl."' WHERE ".$sFilterUserID."");
			}

			// End of modification Save Last Users' Visitted Page, by Masino Sinaga, May 25, 2012
			$LastAccessDateTime = strval(@$UserProfile->Profile[EW_USER_PROFILE_LAST_ACCESSED_DATE_TIME]);
			$nDiff = intval(ew_DateDiff($LastAccessDateTime, ew_StdCurrentDateTime(), "s"));
			$nCons = intval(MS_AUTO_LOGOUT_AFTER_IDLE_IN_MINUTES) * 60;
			if ($nDiff > $nCons) {
				header("Location: logout.php");
			}
		}

		// End of modification Auto Logout After Idle for the Certain Time, by Masino Sinaga, May 5, 2012
		// Update last accessed time

		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {

			// Do nothing since it's a valid user! SaveProfileToDatabase has been handled from IsValidUser method of UserProfile object.
		} else {

			// Begin of modification How to Overcome "User X already logged in" Issue, by Masino Sinaga, July 22, 2014
			// echo $Language->Phrase("UserProfileCorrupted");

			header("Location: logout.php");

			// End of modification How to Overcome "User X already logged in" Issue, by Masino Sinaga, July 22, 2014
		}
		if (@MS_USE_CONSTANTS_IN_CONFIG_FILE == FALSE) {

			// Call this new function from userfn*.php file
			My_Global_Check();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

// Begin of modification Disable/Enable Registration Page, by Masino Sinaga, May 14, 2012
// End of modification Disable/Enable Registration Page, by Masino Sinaga, May 14, 2012
		// Page Load event

		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $breadcrumblinks;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($breadcrumblinks);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values

			// End of modification Permission Access for Export To Feature, by Masino Sinaga, May 5, 2012
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["Page_Title"] != "") {
				$this->Page_Title->setQueryStringValue($_GET["Page_Title"]);
				$this->setKey("Page_Title", $this->Page_Title->CurrentValue); // Set up key
			} else {
				$this->setKey("Page_Title", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("breadcrumblinkslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful

					// Begin of modification Disable Add/Edit Success Message Box, by Masino Sinaga, August 1, 2012
					if (MS_SHOW_ADD_SUCCESS_MESSAGE==TRUE) {
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					}

					// End of modification Disable Add/Edit Success Message Box, by Masino Sinaga, August 1, 2012
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "breadcrumblinksview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Page_Title->CurrentValue = NULL;
		$this->Page_Title->OldValue = $this->Page_Title->CurrentValue;
		$this->Page_URL->CurrentValue = NULL;
		$this->Page_URL->OldValue = $this->Page_URL->CurrentValue;
		$this->Lft->CurrentValue = NULL;
		$this->Lft->OldValue = $this->Lft->CurrentValue;
		$this->Rgt->CurrentValue = NULL;
		$this->Rgt->OldValue = $this->Rgt->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Page_Title->FldIsDetailKey) {
			$this->Page_Title->setFormValue($objForm->GetValue("x_Page_Title"));
		}
		if (!$this->Page_URL->FldIsDetailKey) {
			$this->Page_URL->setFormValue($objForm->GetValue("x_Page_URL"));
		}
		if (!$this->Lft->FldIsDetailKey) {
			$this->Lft->setFormValue($objForm->GetValue("x_Lft"));
		}
		if (!$this->Rgt->FldIsDetailKey) {
			$this->Rgt->setFormValue($objForm->GetValue("x_Rgt"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Page_Title->CurrentValue = $this->Page_Title->FormValue;
		$this->Page_URL->CurrentValue = $this->Page_URL->FormValue;
		$this->Lft->CurrentValue = $this->Lft->FormValue;
		$this->Rgt->CurrentValue = $this->Rgt->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->Page_Title->setDbValue($rs->fields('Page_Title'));
		$this->Page_URL->setDbValue($rs->fields('Page_URL'));
		$this->Lft->setDbValue($rs->fields('Lft'));
		$this->Rgt->setDbValue($rs->fields('Rgt'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Page_Title->DbValue = $row['Page_Title'];
		$this->Page_URL->DbValue = $row['Page_URL'];
		$this->Lft->DbValue = $row['Lft'];
		$this->Rgt->DbValue = $row['Rgt'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Page_Title")) <> "")
			$this->Page_Title->CurrentValue = $this->getKey("Page_Title"); // Page_Title
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Page_Title
		// Page_URL
		// Lft
		// Rgt

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Page_Title
			$this->Page_Title->ViewValue = $this->Page_Title->CurrentValue;
			$this->Page_Title->ViewCustomAttributes = "";

			// Page_URL
			$this->Page_URL->ViewValue = $this->Page_URL->CurrentValue;
			$this->Page_URL->ViewCustomAttributes = "";

			// Lft
			$this->Lft->ViewValue = $this->Lft->CurrentValue;
			$this->Lft->ViewCustomAttributes = "";

			// Rgt
			$this->Rgt->ViewValue = $this->Rgt->CurrentValue;
			$this->Rgt->ViewCustomAttributes = "";

			// Page_Title
			$this->Page_Title->LinkCustomAttributes = "";
			$this->Page_Title->HrefValue = "";
			$this->Page_Title->TooltipValue = "";

			// Page_URL
			$this->Page_URL->LinkCustomAttributes = "";
			$this->Page_URL->HrefValue = "";
			$this->Page_URL->TooltipValue = "";

			// Lft
			$this->Lft->LinkCustomAttributes = "";
			$this->Lft->HrefValue = "";
			$this->Lft->TooltipValue = "";

			// Rgt
			$this->Rgt->LinkCustomAttributes = "";
			$this->Rgt->HrefValue = "";
			$this->Rgt->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Page_Title
			$this->Page_Title->EditAttrs["class"] = "form-control";
			$this->Page_Title->EditCustomAttributes = "";
			$this->Page_Title->EditValue = ew_HtmlEncode($this->Page_Title->CurrentValue);
			$this->Page_Title->PlaceHolder = ew_RemoveHtml($this->Page_Title->FldCaption());

			// Page_URL
			$this->Page_URL->EditAttrs["class"] = "form-control";
			$this->Page_URL->EditCustomAttributes = "";
			$this->Page_URL->EditValue = ew_HtmlEncode($this->Page_URL->CurrentValue);
			$this->Page_URL->PlaceHolder = ew_RemoveHtml($this->Page_URL->FldCaption());

			// Lft
			$this->Lft->EditAttrs["class"] = "form-control";
			$this->Lft->EditCustomAttributes = "";
			$this->Lft->EditValue = ew_HtmlEncode($this->Lft->CurrentValue);
			$this->Lft->PlaceHolder = ew_RemoveHtml($this->Lft->FldCaption());

			// Rgt
			$this->Rgt->EditAttrs["class"] = "form-control";
			$this->Rgt->EditCustomAttributes = "";
			$this->Rgt->EditValue = ew_HtmlEncode($this->Rgt->CurrentValue);
			$this->Rgt->PlaceHolder = ew_RemoveHtml($this->Rgt->FldCaption());

			// Edit refer script
			// Page_Title

			$this->Page_Title->HrefValue = "";

			// Page_URL
			$this->Page_URL->HrefValue = "";

			// Lft
			$this->Lft->HrefValue = "";

			// Rgt
			$this->Rgt->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->Page_Title->FldIsDetailKey && !is_null($this->Page_Title->FormValue) && $this->Page_Title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Page_Title->FldCaption(), $this->Page_Title->ReqErrMsg));
		}
		if (!$this->Page_URL->FldIsDetailKey && !is_null($this->Page_URL->FormValue) && $this->Page_URL->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Page_URL->FldCaption(), $this->Page_URL->ReqErrMsg));
		}
		if (!$this->Lft->FldIsDetailKey && !is_null($this->Lft->FormValue) && $this->Lft->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Lft->FldCaption(), $this->Lft->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->Lft->FormValue)) {
			ew_AddMessage($gsFormError, $this->Lft->FldErrMsg());
		}
		if (!$this->Rgt->FldIsDetailKey && !is_null($this->Rgt->FormValue) && $this->Rgt->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Rgt->FldCaption(), $this->Rgt->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->Rgt->FormValue)) {
			ew_AddMessage($gsFormError, $this->Rgt->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// Page_Title
		$this->Page_Title->SetDbValueDef($rsnew, $this->Page_Title->CurrentValue, "", FALSE);

		// Page_URL
		$this->Page_URL->SetDbValueDef($rsnew, $this->Page_URL->CurrentValue, "", FALSE);

		// Lft
		$this->Lft->SetDbValueDef($rsnew, $this->Lft->CurrentValue, 0, FALSE);

		// Rgt
		$this->Rgt->SetDbValueDef($rsnew, $this->Rgt->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Page_Title']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"]; // v11.0.4
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	//  Build export filter for selected records
	function BuildExportSelectedFilter() {
		global $Language;
		$sWrkFilter = "";
		if ($this->Export <> "") {
			$sWrkFilter = $this->GetKeyFilter();
		}
		return $sWrkFilter;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1); // v11.0.4
		$Breadcrumb->Add("list", $this->TableVar, "breadcrumblinkslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url); // v11.0.4
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($breadcrumblinks_add)) $breadcrumblinks_add = new cbreadcrumblinks_add();

// Page init
$breadcrumblinks_add->Page_Init();

// Page main
$breadcrumblinks_add->Page_Main();

// Begin of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
getCurrentPageTitle(ew_CurrentPage());

// End of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
// Global Page Rendering event (in userfn*.php)

Page_Rendering();

// Global auto switch table width style (in userfn*.php), by Masino Sinaga, January 7, 2015
AutoSwitchTableWidthStyle();

// Page Rendering event
$breadcrumblinks_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var breadcrumblinks_add = new ew_Page("breadcrumblinks_add");
breadcrumblinks_add.PageID = "add"; // Page ID
var EW_PAGE_ID = breadcrumblinks_add.PageID; // For backward compatibility

// Form object
var fbreadcrumblinksadd = new ew_Form("fbreadcrumblinksadd");

// Validate form
fbreadcrumblinksadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_Page_Title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $breadcrumblinks->Page_Title->FldCaption(), $breadcrumblinks->Page_Title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Page_URL");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $breadcrumblinks->Page_URL->FldCaption(), $breadcrumblinks->Page_URL->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Lft");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $breadcrumblinks->Lft->FldCaption(), $breadcrumblinks->Lft->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Lft");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($breadcrumblinks->Lft->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Rgt");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $breadcrumblinks->Rgt->FldCaption(), $breadcrumblinks->Rgt->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Rgt");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($breadcrumblinks->Rgt->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fbreadcrumblinksadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbreadcrumblinksadd.ValidateRequired = true;
<?php } else { ?>
fbreadcrumblinksadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (MS_SHOW_PHPMAKER_BREADCRUMBLINKS) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if (MS_SHOW_MASINO_BREADCRUMBLINKS) { ?>
<?php echo MasinoBreadcrumbLinks(); ?>
<?php } ?>
<?php if (MS_LANGUAGE_SELECTOR_VISIBILITY=="belowheader") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $breadcrumblinks_add->ShowPageHeader(); ?>
<?php
$breadcrumblinks_add->ShowMessage();
?>
<form name="fbreadcrumblinksadd" id="fbreadcrumblinksadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($breadcrumblinks_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $breadcrumblinks_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="breadcrumblinks">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($breadcrumblinks->Page_Title->Visible) { // Page_Title ?>
	<div id="r_Page_Title" class="form-group">
		<label id="elh_breadcrumblinks_Page_Title" for="x_Page_Title" class="col-sm-4 control-label ewLabel"><?php echo $breadcrumblinks->Page_Title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $breadcrumblinks->Page_Title->CellAttributes() ?>>
<span id="el_breadcrumblinks_Page_Title">
<input type="text" data-field="x_Page_Title" name="x_Page_Title" id="x_Page_Title" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($breadcrumblinks->Page_Title->PlaceHolder) ?>" value="<?php echo $breadcrumblinks->Page_Title->EditValue ?>"<?php echo $breadcrumblinks->Page_Title->EditAttributes() ?>>
</span>
<?php echo $breadcrumblinks->Page_Title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($breadcrumblinks->Page_URL->Visible) { // Page_URL ?>
	<div id="r_Page_URL" class="form-group">
		<label id="elh_breadcrumblinks_Page_URL" for="x_Page_URL" class="col-sm-4 control-label ewLabel"><?php echo $breadcrumblinks->Page_URL->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $breadcrumblinks->Page_URL->CellAttributes() ?>>
<span id="el_breadcrumblinks_Page_URL">
<input type="text" data-field="x_Page_URL" name="x_Page_URL" id="x_Page_URL" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($breadcrumblinks->Page_URL->PlaceHolder) ?>" value="<?php echo $breadcrumblinks->Page_URL->EditValue ?>"<?php echo $breadcrumblinks->Page_URL->EditAttributes() ?>>
</span>
<?php echo $breadcrumblinks->Page_URL->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($breadcrumblinks->Lft->Visible) { // Lft ?>
	<div id="r_Lft" class="form-group">
		<label id="elh_breadcrumblinks_Lft" for="x_Lft" class="col-sm-4 control-label ewLabel"><?php echo $breadcrumblinks->Lft->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $breadcrumblinks->Lft->CellAttributes() ?>>
<span id="el_breadcrumblinks_Lft">
<input type="text" data-field="x_Lft" name="x_Lft" id="x_Lft" size="30" placeholder="<?php echo ew_HtmlEncode($breadcrumblinks->Lft->PlaceHolder) ?>" value="<?php echo $breadcrumblinks->Lft->EditValue ?>"<?php echo $breadcrumblinks->Lft->EditAttributes() ?>>
</span>
<?php echo $breadcrumblinks->Lft->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($breadcrumblinks->Rgt->Visible) { // Rgt ?>
	<div id="r_Rgt" class="form-group">
		<label id="elh_breadcrumblinks_Rgt" for="x_Rgt" class="col-sm-4 control-label ewLabel"><?php echo $breadcrumblinks->Rgt->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $breadcrumblinks->Rgt->CellAttributes() ?>>
<span id="el_breadcrumblinks_Rgt">
<input type="text" data-field="x_Rgt" name="x_Rgt" id="x_Rgt" size="30" placeholder="<?php echo ew_HtmlEncode($breadcrumblinks->Rgt->PlaceHolder) ?>" value="<?php echo $breadcrumblinks->Rgt->EditValue ?>"<?php echo $breadcrumblinks->Rgt->EditAttributes() ?>>
</span>
<?php echo $breadcrumblinks->Rgt->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-4 col-sm-8">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fbreadcrumblinksadd.Init();
</script>
<?php
$breadcrumblinks_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php // Begin of modification Add Cancel Button next to Action Button, by Masino Sinaga, August 4, 2014 ?>
<?php if (MS_ADD_CANCEL_BUTTON_NEXT_TO_ACTION_BUTTON == TRUE) { ?>
<script type="text/javascript">
$("#btnAction").after('&nbsp;&nbsp;<button class="btn btn-danger ewButton" name="btnCancel" id="btnCancel" type="Button" onclick="window.history.back()"><?php echo Language()->Phrase("CancelBtn"); ?></button>');
</script>
<?php } ?>
<?php // End of modification Add Cancel Button next to Action Button, by Masino Sinaga, August 4, 2014 ?>
<?php if (MS_ENTER_MOVING_CURSOR_TO_NEXT_FIELD) { ?>
<script type="text/javascript">
$(document).ready(function(){$("#fbreadcrumblinksadd:first *:input[type!=hidden]:first").focus(),$("input").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btnAction").click()}),$("select").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btnAction").click()}),$("radio").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btnAction").click()})});
</script>
<?php } ?>
<?php if ($breadcrumblinks->Export == "") { ?>
<script type="text/javascript">
$('#btnAction').attr('onclick', 'return alertifyAdd(this)'); function alertifyAdd(obj) { <?php global $Language; ?> if (fbreadcrumblinksadd.Validate() == true ) { alertify.set({buttonFocus: 'cancel'});alertify.confirm("<?php echo $Language->Phrase('AlertifyAddConfirm'); ?>", function (e) { if (e) { $(window).unbind('beforeunload'); alertify.success("<?php echo $Language->Phrase('AlertifyAdd'); ?>"); $("#fbreadcrumblinksadd").submit(); } else { alertify.error("<?php echo $Language->Phrase('AlertifyCancel'); ?>"); } }, "<?php echo $Language->Phrase('AlertifyConfirm'); ?>"); } return false; }
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$breadcrumblinks_add->Page_Terminate();
?>
