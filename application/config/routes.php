<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'c_loginpage';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
/* ========== LOGIN AREA ========== */
$route['loginarea'] = 'c_loginpage';
$route['accountcheck'] = 'c_loginpage/loginprocess';
/* ========== DASHBOARD ========== */
$route['testpage'] = 'c_dashboard/testpage';
$route['mainpage'] = 'c_dashboard';
//$route['detail2list'] = 'c_dashboard/getlist_detail2';
//$route['detail2view'] = 'c_dashboard/dashboardcheck';
$route['detail2action'] = 'c_dashboard/approve_package';
$route['dashboardmkt_icon'] = 'c_dashboard/dashboardmkticon';
$route['dashboardcompanylist'] = 'c_dashboard/dashboardicon_company';
$route['dashboardcompanylist_show'] = 'c_dashboard/dashboardicon_company_list';
$route['dashboardpackagelist'] = 'c_dashboard/dashboardicon_package';
$route['dashboardpackagelist_show'] = 'c_dashboard/dashboardicon_package_list';
$route['dashboardexpirelist'] = 'c_dashboard/dashboardicon_expire';
$route['dashboardexpirelist_show'] = 'c_dashboard/dashboardicon_expire_list';
$route['dashboarddraftlist'] = 'c_dashboard/dashboardicon_draft';
$route['dashboarddraftlist_show'] = 'c_dashboard/dashboardicon_draft_list';
$route['dsbspvcheck'] = 'c_dashboard/checkstatus';
$route['dsbspv_approval'] = 'c_dashboard/show_detail2'; // need approval box
$route['dsbspv_comp'] = 'c_dashboard/show_comp';
$route['dsbspv_complist'] = 'c_dashboard/getlist_comp';
$route['dsbspv_package'] = 'c_dashboard/show_package';
$route['dsbspv_packlist'] = 'c_dashboard/getlist_package';
$route['dsbspv_expire'] = 'c_dashboard/show_expire';
$route['dsbspv_expirelist'] = 'c_dashboard/getlist_expire';
/* ========== MASTER DATA ========== */
$route['exammaster'] = 'c_mstrexam';
$route['exammasterlist'] = 'c_mstrexam/getlist';
$route['exammasterupdate'] = 'c_mstrexam/updaterow';
$route['costingmaster'] = 'c_mstrcosting';
$route['costingmasterlist'] = 'c_mstrcosting/getlist';
$route['costingmasteredit'] = 'c_mstrcosting/editrow';
$route['costingfixedcost'] = 'c_mstrcosting/savefixedcost';
$route['costingmasterupdate'] = 'c_mstrcosting/updaterow';
$route['costingmasterlist_xls'] = 'c_mstrcosting/exportexcel';
/* ========== MCU Package ========== */
$route['packcompany'] = 'c_packcompany';
$route['packcompanylist'] = 'c_packcompany/getlist';
$route['packlist/(:any)'] = 'c_packlist/index/$1';
$route['packlistshow/(:any)'] = 'c_packlist/getlist/$1';
$route['packlistview'] = 'c_packlist/viewpack';
$route['packdescription/(:any)'] = 'c_packdescription/show/$1';
$route['packdescriptioncreate'] = 'c_packdescription/createpack';
$route['packremovetmpfile'] = 'c_packdescription/fileRemove';
$route['packattachfile'] = 'c_packdescription/fileUpload';
$route['packremovefile'] = 'c_packdescription/fileRemove';
$route['packitem/(:any)/(:any)'] = 'c_packitem/show/$1/$2';
$route['packitemlist/(:any)/(:any)'] = 'c_packitem/getlist/$1/$2';
$route['packcopylist/(:any)/(:any)'] = 'c_packitem/getpackagelist/$1/$2';
$route['packupdate'] = 'c_packitem/updatepackage';
$route['packdelete'] = 'c_packitem/deletepackage';
$route['packitemselect'] = 'c_packitem/selectitem';
$route['packitemupdate'] = 'c_packitem/updateitempackage';
$route['packitemdelete'] = 'c_packitem/deleteitem';
$route['packpackageselect'] = 'c_packitem/selectpackage';
$route['packprice'] = 'c_packitem/packageprice';
$route['packtopdf/(:any)/(:any)'] = 'c_packitem/pdf_paketmcu/$1/$2';
$route['packapproval'] = 'c_packitem/sendapproval';
$route['packdraft'] = 'c_packitem/savetodraft';
$route['approvalpackage/(:any)/(:any)/(:any)'] = 'c_dashboard/approval_mcupackage/$1/$2/$3';
$route['packagefile'] = 'c_dashboard/attachfile';
$route['downloadfile/(:any)'] = 'c_dashboard/downloadpackfile/$1';
//$route['packpreview'] = 'c_packitem/preview_package';
//$route['submitpackage'] = 'c_packitem/submit_package';
//$route['redirectpackage/(:any)'] = 'c_packdescription/show/$1';
$route['generatepack'] = 'generate_package';
$route['generatepack_getlist'] = 'generate_package/getitemlist';
$route['generatepack_updatecheckbox'] = 'generate_package/updatecheckbox';
/* ========== Master Data ========== */
$route['masterdepartemen'] = 'c_mstrdepartemen';
$route['masterdepartemenlist'] = 'c_mstrdepartemen/getlist';
$route['invreceipt'] = 'c_invreceipt';
$route['invdocnum'] = 'c_invreceipt/getdocnum';
$route['invreceiptlist'] = 'c_invreceipt/getlist';
$route['invreceiptpdf'] = 'c_invreceipt/receiptpdf';
$route['masterjobposition'] = 'c_mstrjobposition';
$route['masterjobpositionlist'] = 'c_mstrjobposition/getlist';
$route['masterjobposexpxls'] = 'c_mstrjobposition/exporttoxls';
$route['masteritem'] = 'c_mstritem';
$route['masteritemlist'] = 'c_mstritem/getlist';
$route['masteritemdnl'] = 'c_mstritem/downloadsap';
$route['masteritembatch'] = 'c_mstritembatch';
$route['masteritembatchlist'] = 'c_mstritembatch/getlist';
$route['masteritembatchdnl'] = 'c_mstritembatch/downloadsap';
$route['masterbp'] = 'c_mstrbp';
$route['masterbplist'] = 'c_mstrbp/getlist';
$route['masterbplist_xls'] = 'c_mstrbp/exportexcel';
/* ========== Item Request Site ========== */
$route['itemreqsite/(:any)'] = 'c_itemreqsite/index/$1';
$route['itemreqsitelist/(:any)'] = 'c_itemreqsite/getlist/$1';
$route['itemreqsitelist_showitem/(:any)'] = 'c_itemreqsite/showitems/$1';
$route['itemreqsitelist_uom/(:any)'] = 'c_itemreqsite/showuom/$1';
$route['itemreqsitelist_selectitem'] = 'c_itemreqsite/selectitem';
$route['itemreqsitelist_selectuom'] = 'c_itemreqsite/selectuom';
$route['itemreqsitelist_updateqty'] = 'c_itemreqsite/updateqty';
$route['itemreqsite_xls/(:any)'] = 'c_itemreqsite/exportexcel/$1';
$route['itemreqsite_posting'] = 'c_itemreqsite/postingreq';
/* ========== Approval Request Site ========== */
$route['apvreqsite/(:any)'] = 'c_apvreqsite/index/$1';
$route['apvreqsitelist'] = 'c_apvreqsite/getlist';
$route['apvreqsitelist_check'] = 'c_apvreqsite/getlist_checkstatus';
$route['apvreqsite_showitem'] = 'c_apvreqsite/showitems';
$route['apvreqsite_getdetail'] = 'c_apvreqsite/getdetail';
$route['apvreqsite_selectitem'] = 'c_apvreqsite/selectitem';
