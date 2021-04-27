<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "a_suppliersinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$a_suppliers_delete = NULL; // Initialize page object first

class ca_suppliers_delete extends ca_suppliers {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{B36B93AF-B58F-461B-B767-5F08C12493E9}";

	// Table name
	var $TableName = 'a_suppliers';

	// Page object name
	var $PageObjName = 'a_suppliers_delete';

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

		// Table object (a_suppliers)
		if (!isset($GLOBALS["a_suppliers"]) || get_class($GLOBALS["a_suppliers"]) == "ca_suppliers") {
			$GLOBALS["a_suppliers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["a_suppliers"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// User table object (users)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'a_suppliers', TRUE);

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
		if (!isset($_SESSION['table_a_suppliers_views'])) { 
			$_SESSION['table_a_suppliers_views'] = 0;
		}
		$_SESSION['table_a_suppliers_views'] = $_SESSION['table_a_suppliers_views']+1;

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("a_supplierslist.php"));
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

			// Process auto fill for detail table 'a_purchases'
			if (@$_POST["grid"] == "fa_purchasesgrid") {
				if (!isset($GLOBALS["a_purchases_grid"])) $GLOBALS["a_purchases_grid"] = new ca_purchases_grid;
				$GLOBALS["a_purchases_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'a_stock_items'
			if (@$_POST["grid"] == "fa_stock_itemsgrid") {
				if (!isset($GLOBALS["a_stock_items_grid"])) $GLOBALS["a_stock_items_grid"] = new ca_stock_items_grid;
				$GLOBALS["a_stock_items_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $a_suppliers;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($a_suppliers);
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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("a_supplierslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in a_suppliers class, a_suppliersinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Begin of modification (20140916): http://www.hkvforums.com/viewtopic.php?f=4&t=35486&p=102440#p102440
		// Load List page SQL

		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"]; // v11.0.4
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Begin of modification (20140916): http://www.hkvforums.com/viewtopic.php?f=4&t=35486&p=102440#p102440
		// Call Recordset Selected event

		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->Supplier_ID->setDbValue($rs->fields('Supplier_ID'));
		$this->Supplier_Number->setDbValue($rs->fields('Supplier_Number'));
		$this->Supplier_Name->setDbValue($rs->fields('Supplier_Name'));
		$this->Address->setDbValue($rs->fields('Address'));
		$this->City->setDbValue($rs->fields('City'));
		$this->Country->setDbValue($rs->fields('Country'));
		$this->Contact_Person->setDbValue($rs->fields('Contact_Person'));
		$this->Phone_Number->setDbValue($rs->fields('Phone_Number'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->Mobile_Number->setDbValue($rs->fields('Mobile_Number'));
		$this->Notes->setDbValue($rs->fields('Notes'));
		$this->Balance->setDbValue($rs->fields('Balance'));
		$this->Is_Stock_Available->setDbValue($rs->fields('Is_Stock_Available'));
		$this->Date_Added->setDbValue($rs->fields('Date_Added'));
		$this->Added_By->setDbValue($rs->fields('Added_By'));
		$this->Date_Updated->setDbValue($rs->fields('Date_Updated'));
		$this->Updated_By->setDbValue($rs->fields('Updated_By'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Supplier_ID->DbValue = $row['Supplier_ID'];
		$this->Supplier_Number->DbValue = $row['Supplier_Number'];
		$this->Supplier_Name->DbValue = $row['Supplier_Name'];
		$this->Address->DbValue = $row['Address'];
		$this->City->DbValue = $row['City'];
		$this->Country->DbValue = $row['Country'];
		$this->Contact_Person->DbValue = $row['Contact_Person'];
		$this->Phone_Number->DbValue = $row['Phone_Number'];
		$this->_Email->DbValue = $row['Email'];
		$this->Mobile_Number->DbValue = $row['Mobile_Number'];
		$this->Notes->DbValue = $row['Notes'];
		$this->Balance->DbValue = $row['Balance'];
		$this->Is_Stock_Available->DbValue = $row['Is_Stock_Available'];
		$this->Date_Added->DbValue = $row['Date_Added'];
		$this->Added_By->DbValue = $row['Added_By'];
		$this->Date_Updated->DbValue = $row['Date_Updated'];
		$this->Updated_By->DbValue = $row['Updated_By'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->Balance->FormValue == $this->Balance->CurrentValue && is_numeric(ew_StrToFloat($this->Balance->CurrentValue)))
			$this->Balance->CurrentValue = ew_StrToFloat($this->Balance->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Supplier_ID

		$this->Supplier_ID->CellCssStyle = "white-space: nowrap;";

		// Supplier_Number
		// Supplier_Name
		// Address
		// City
		// Country
		// Contact_Person
		// Phone_Number
		// Email
		// Mobile_Number
		// Notes
		// Balance
		// Is_Stock_Available
		// Date_Added
		// Added_By
		// Date_Updated
		// Updated_By

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Supplier_Number
			$this->Supplier_Number->ViewValue = $this->Supplier_Number->CurrentValue;
			$this->Supplier_Number->ViewCustomAttributes = "";

			// Supplier_Name
			$this->Supplier_Name->ViewValue = $this->Supplier_Name->CurrentValue;
			$this->Supplier_Name->ViewCustomAttributes = "";

			// Contact_Person
			$this->Contact_Person->ViewValue = $this->Contact_Person->CurrentValue;
			$this->Contact_Person->ViewCustomAttributes = "";

			// Phone_Number
			$this->Phone_Number->ViewValue = $this->Phone_Number->CurrentValue;
			$this->Phone_Number->ViewCustomAttributes = "";

			// Mobile_Number
			$this->Mobile_Number->ViewValue = $this->Mobile_Number->CurrentValue;
			$this->Mobile_Number->ViewCustomAttributes = "";

			// Balance
			$this->Balance->ViewValue = $this->Balance->CurrentValue;
			$this->Balance->ViewValue = ew_FormatCurrency($this->Balance->ViewValue, 2, -2, -2, -2);
			$this->Balance->CellCssStyle .= "text-align: right;";
			$this->Balance->ViewCustomAttributes = "";

			// Is_Stock_Available
			if (ew_ConvertToBool($this->Is_Stock_Available->CurrentValue)) {
				$this->Is_Stock_Available->ViewValue = $this->Is_Stock_Available->FldTagCaption(2) <> "" ? $this->Is_Stock_Available->FldTagCaption(2) : "Y";
			} else {
				$this->Is_Stock_Available->ViewValue = $this->Is_Stock_Available->FldTagCaption(1) <> "" ? $this->Is_Stock_Available->FldTagCaption(1) : "N";
			}
			$this->Is_Stock_Available->ViewCustomAttributes = "";

			// Supplier_Number
			$this->Supplier_Number->LinkCustomAttributes = "";
			$this->Supplier_Number->HrefValue = "";
			$this->Supplier_Number->TooltipValue = "";

			// Supplier_Name
			$this->Supplier_Name->LinkCustomAttributes = "";
			$this->Supplier_Name->HrefValue = "";
			$this->Supplier_Name->TooltipValue = "";

			// Contact_Person
			$this->Contact_Person->LinkCustomAttributes = "";
			$this->Contact_Person->HrefValue = "";
			$this->Contact_Person->TooltipValue = "";

			// Phone_Number
			$this->Phone_Number->LinkCustomAttributes = "";
			$this->Phone_Number->HrefValue = "";
			$this->Phone_Number->TooltipValue = "";

			// Mobile_Number
			$this->Mobile_Number->LinkCustomAttributes = "";
			$this->Mobile_Number->HrefValue = "";
			$this->Mobile_Number->TooltipValue = "";

			// Balance
			$this->Balance->LinkCustomAttributes = "";
			$this->Balance->HrefValue = "";
			$this->Balance->TooltipValue = "";

			// Is_Stock_Available
			$this->Is_Stock_Available->LinkCustomAttributes = "";
			$this->Is_Stock_Available->HrefValue = "";
			$this->Is_Stock_Available->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"]; // v11.0.4
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Supplier_ID'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"]; // v11.0.4
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
		$Breadcrumb->Add("list", $this->TableVar, "a_supplierslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url); // v11.0.4
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($a_suppliers_delete)) $a_suppliers_delete = new ca_suppliers_delete();

// Page init
$a_suppliers_delete->Page_Init();

// Page main
$a_suppliers_delete->Page_Main();

// Begin of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
getCurrentPageTitle(ew_CurrentPage());

// End of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
// Global Page Rendering event (in userfn*.php)

Page_Rendering();

// Global auto switch table width style (in userfn*.php), by Masino Sinaga, January 7, 2015
AutoSwitchTableWidthStyle();

// Page Rendering event
$a_suppliers_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var a_suppliers_delete = new ew_Page("a_suppliers_delete");
a_suppliers_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = a_suppliers_delete.PageID; // For backward compatibility

// Form object
var fa_suppliersdelete = new ew_Form("fa_suppliersdelete");

// Form_CustomValidate event
fa_suppliersdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fa_suppliersdelete.ValidateRequired = true;
<?php } else { ?>
fa_suppliersdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($a_suppliers_delete->Recordset = $a_suppliers_delete->LoadRecordset())
	$a_suppliers_deleteTotalRecs = $a_suppliers_delete->Recordset->RecordCount(); // Get record count
if ($a_suppliers_deleteTotalRecs <= 0) { // No record found, exit
	if ($a_suppliers_delete->Recordset)
		$a_suppliers_delete->Recordset->Close();
	$a_suppliers_delete->Page_Terminate("a_supplierslist.php"); // Return to list
}
?>
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
<?php $a_suppliers_delete->ShowPageHeader(); ?>
<?php
$a_suppliers_delete->ShowMessage();
?>
<form name="fa_suppliersdelete" id="fa_suppliersdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($a_suppliers_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $a_suppliers_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="a_suppliers">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($a_suppliers_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $a_suppliers->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($a_suppliers->Supplier_Number->Visible) { // Supplier_Number ?>
		<th><span id="elh_a_suppliers_Supplier_Number" class="a_suppliers_Supplier_Number"><?php echo $a_suppliers->Supplier_Number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($a_suppliers->Supplier_Name->Visible) { // Supplier_Name ?>
		<th><span id="elh_a_suppliers_Supplier_Name" class="a_suppliers_Supplier_Name"><?php echo $a_suppliers->Supplier_Name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($a_suppliers->Contact_Person->Visible) { // Contact_Person ?>
		<th><span id="elh_a_suppliers_Contact_Person" class="a_suppliers_Contact_Person"><?php echo $a_suppliers->Contact_Person->FldCaption() ?></span></th>
<?php } ?>
<?php if ($a_suppliers->Phone_Number->Visible) { // Phone_Number ?>
		<th><span id="elh_a_suppliers_Phone_Number" class="a_suppliers_Phone_Number"><?php echo $a_suppliers->Phone_Number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($a_suppliers->Mobile_Number->Visible) { // Mobile_Number ?>
		<th><span id="elh_a_suppliers_Mobile_Number" class="a_suppliers_Mobile_Number"><?php echo $a_suppliers->Mobile_Number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($a_suppliers->Balance->Visible) { // Balance ?>
		<th><span id="elh_a_suppliers_Balance" class="a_suppliers_Balance"><?php echo $a_suppliers->Balance->FldCaption() ?></span></th>
<?php } ?>
<?php if ($a_suppliers->Is_Stock_Available->Visible) { // Is_Stock_Available ?>
		<th><span id="elh_a_suppliers_Is_Stock_Available" class="a_suppliers_Is_Stock_Available"><?php echo $a_suppliers->Is_Stock_Available->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$a_suppliers_delete->RecCnt = 0;
$i = 0;
while (!$a_suppliers_delete->Recordset->EOF) {
	$a_suppliers_delete->RecCnt++;
	$a_suppliers_delete->RowCnt++;

	// Set row properties
	$a_suppliers->ResetAttrs();
	$a_suppliers->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$a_suppliers_delete->LoadRowValues($a_suppliers_delete->Recordset);

	// Render row
	$a_suppliers_delete->RenderRow();
?>
	<tr<?php echo $a_suppliers->RowAttributes() ?>>
<?php if ($a_suppliers->Supplier_Number->Visible) { // Supplier_Number ?>
		<td<?php echo $a_suppliers->Supplier_Number->CellAttributes() ?>>
<span id="el<?php echo $a_suppliers_delete->RowCnt ?>_a_suppliers_Supplier_Number" class="form-group a_suppliers_Supplier_Number">
<span<?php echo $a_suppliers->Supplier_Number->ViewAttributes() ?>>
<?php echo $a_suppliers->Supplier_Number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($a_suppliers->Supplier_Name->Visible) { // Supplier_Name ?>
		<td<?php echo $a_suppliers->Supplier_Name->CellAttributes() ?>>
<span id="el<?php echo $a_suppliers_delete->RowCnt ?>_a_suppliers_Supplier_Name" class="form-group a_suppliers_Supplier_Name">
<span<?php echo $a_suppliers->Supplier_Name->ViewAttributes() ?>>
<?php echo $a_suppliers->Supplier_Name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($a_suppliers->Contact_Person->Visible) { // Contact_Person ?>
		<td<?php echo $a_suppliers->Contact_Person->CellAttributes() ?>>
<span id="el<?php echo $a_suppliers_delete->RowCnt ?>_a_suppliers_Contact_Person" class="form-group a_suppliers_Contact_Person">
<span<?php echo $a_suppliers->Contact_Person->ViewAttributes() ?>>
<?php echo $a_suppliers->Contact_Person->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($a_suppliers->Phone_Number->Visible) { // Phone_Number ?>
		<td<?php echo $a_suppliers->Phone_Number->CellAttributes() ?>>
<span id="el<?php echo $a_suppliers_delete->RowCnt ?>_a_suppliers_Phone_Number" class="form-group a_suppliers_Phone_Number">
<span<?php echo $a_suppliers->Phone_Number->ViewAttributes() ?>>
<?php echo $a_suppliers->Phone_Number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($a_suppliers->Mobile_Number->Visible) { // Mobile_Number ?>
		<td<?php echo $a_suppliers->Mobile_Number->CellAttributes() ?>>
<span id="el<?php echo $a_suppliers_delete->RowCnt ?>_a_suppliers_Mobile_Number" class="form-group a_suppliers_Mobile_Number">
<span<?php echo $a_suppliers->Mobile_Number->ViewAttributes() ?>>
<?php echo $a_suppliers->Mobile_Number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($a_suppliers->Balance->Visible) { // Balance ?>
		<td<?php echo $a_suppliers->Balance->CellAttributes() ?>>
<span id="el<?php echo $a_suppliers_delete->RowCnt ?>_a_suppliers_Balance" class="form-group a_suppliers_Balance">
<span<?php echo $a_suppliers->Balance->ViewAttributes() ?>>
<?php echo $a_suppliers->Balance->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($a_suppliers->Is_Stock_Available->Visible) { // Is_Stock_Available ?>
		<td<?php echo $a_suppliers->Is_Stock_Available->CellAttributes() ?>>
<span id="el<?php echo $a_suppliers_delete->RowCnt ?>_a_suppliers_Is_Stock_Available" class="form-group a_suppliers_Is_Stock_Available">
<span<?php echo $a_suppliers->Is_Stock_Available->ViewAttributes() ?>>
<?php echo $a_suppliers->Is_Stock_Available->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$a_suppliers_delete->Recordset->MoveNext();
}
$a_suppliers_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</form>
<script type="text/javascript">
fa_suppliersdelete.Init();
</script>
<?php
$a_suppliers_delete->ShowPageFooter();
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
<?php if ($a_suppliers->Export == "") { ?>
<script type="text/javascript">
$('#btnAction').attr('onclick', 'return alertifyDelete(this)'); function alertifyDelete(obj) { <?php global $Language; ?> if (fa_suppliersdelete.Validate() == true ) { alertify.set({buttonFocus: 'cancel'});alertify.confirm("<?php echo  $Language->Phrase('AlertifyDeleteConfirm'); ?>", function (e) { if (e) {	$(window).unbind('beforeunload'); alertify.success("<?php echo $Language->Phrase('AlertifyDelete'); ?>"); $("#fa_suppliersdelete").submit(); } else { alertify.error("<?php echo $Language->Phrase('AlertifyCancel'); ?>"); } }, "<?php echo $Language->Phrase('AlertifyConfirm'); ?>"); } return false; }
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$a_suppliers_delete->Page_Terminate();
?>
