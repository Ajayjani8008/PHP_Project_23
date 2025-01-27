<?php
// DECLARE GLOBAL VARIABLES
$GLOBALS['admin_site_url'] = "http://localhost/unity-hub/admin/";
// $GLOBALS['admin_site_url'] = "http://localhost/unity-hub/admin/";
$GLOBALS['main_site_url'] = "http://localhost/unity-hub/";
$GLOBALS['new_site_url'] = "http://localhost/unity-hub/";
$GLOBALS['uploads_dir_root'] = "C:/xampp/htdocs/unity-hub/admin/";
$uploads_dir = $_SERVER['DOCUMENT_ROOT'];

define('STRIPE_SecretKey', 'sk_test_zCPQDceujTSHX74Z9TYdhdre00NJR4NDVT');
define('STRIPE_PublishableKey', 'pk_test_doMa9hyND0mLBH1Rc8IZOwis00Fp8hdY8G');



include_once 'Crud.php';
include_once 'DbConfig.php';
$conn = new DbConfig();
$crud = new Crud();

function getMainSiteUrl()
{
	// Determine the protocol (HTTP or HTTPS)
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

	// Get the domain name (host)
	$domainName = $_SERVER['HTTP_HOST'];

	// Get the script directory
	$scriptPath = $_SERVER['SCRIPT_NAME'];
	$scriptDir = str_replace(basename($scriptPath), '', $scriptPath);

	// Combine to form the full URL
	return $protocol . $domainName . $scriptDir . 'admin';
}




//convert stdclass to php array
function cvf_convert_object_to_array($data)
{
	if (is_object($data)) {
		$data = get_object_vars($data);
	}
	if (is_array($data)) {
		return array_map(__FUNCTION__, $data);
	} else {
		return $data;
	}
}

// unserialize form data
function unserializeForm($str)
{
	$strArray = explode("&", $str);
	foreach ($strArray as $item) {
		$array = explode("=", $item);
		$returndata[$array[0]] = $array[1];
	}
	return $returndata;
}

function country_code_to_locale($country_code, $language_code = '')
{
	$locales = array('af-ZA', 'am-ET', 'ar-AE', 'ar-BH', 'ar-DZ', 'ar-EG', 'ar-IQ', 'ar-JO', 'ar-KW', 'ar-LB', 'ar-LY', 'ar-MA', 'arn-CL', 'ar-OM', 'ar-QA', 'ar-SA', 'ar-SY', 'ar-TN', 'ar-YE', 'as-IN', 'az-Cyrl-AZ', 'az-Latn-AZ', 'ba-RU', 'be-BY', 'bg-BG', 'bn-BD', 'bn-IN', 'bo-CN', 'br-FR', 'bs-Cyrl-BA', 'bs-Latn-BA', 'ca-ES', 'co-FR', 'cs-CZ', 'cy-GB', 'da-DK', 'de-AT', 'de-CH', 'de-DE', 'de-LI', 'de-LU', 'dsb-DE', 'dv-MV', 'el-GR', 'en-029', 'en-AU', 'en-BZ', 'en-CA', 'en-GB', 'en-IE', 'en-IN', 'en-JM', 'en-MY', 'en-NZ', 'en-PH', 'en-SG', 'en-TT', 'en-US', 'en-ZA', 'en-ZW', 'es-AR', 'es-BO', 'es-CL', 'es-CO', 'es-CR', 'es-DO', 'es-EC', 'es-ES', 'es-GT', 'es-HN', 'es-MX', 'es-NI', 'es-PA', 'es-PE', 'es-PR', 'es-PY', 'es-SV', 'es-US', 'es-UY', 'es-VE', 'et-EE', 'eu-ES', 'fa-IR', 'fi-FI', 'fil-PH', 'fo-FO', 'fr-BE', 'fr-CA', 'fr-CH', 'fr-FR', 'fr-LU', 'fr-MC', 'fy-NL', 'ga-IE', 'gd-GB', 'gl-ES', 'gsw-FR', 'gu-IN', 'ha-Latn-NG', 'he-IL', 'hi-IN', 'hr-BA', 'hr-HR', 'hsb-DE', 'hu-HU', 'hy-AM', 'id-ID', 'ig-NG', 'ii-CN', 'is-IS', 'it-CH', 'it-IT', 'iu-Cans-CA', 'iu-Latn-CA', 'ja-JP', 'ka-GE', 'kk-KZ', 'kl-GL', 'km-KH', 'kn-IN', 'kok-IN', 'ko-KR', 'ky-KG', 'lb-LU', 'lo-LA', 'lt-LT', 'lv-LV', 'mi-NZ', 'mk-MK', 'ml-IN', 'mn-MN', 'mn-Mong-CN', 'moh-CA', 'mr-IN', 'ms-BN', 'ms-MY', 'mt-MT', 'nb-NO', 'ne-NP', 'nl-BE', 'nl-NL', 'nn-NO', 'nso-ZA', 'oc-FR', 'or-IN', 'pa-IN', 'pl-PL', 'prs-AF', 'ps-AF', 'pt-BR', 'pt-PT', 'qut-GT', 'quz-BO', 'quz-EC', 'quz-PE', 'rm-CH', 'ro-RO', 'ru-RU', 'rw-RW', 'sah-RU', 'sa-IN', 'se-FI', 'se-NO', 'se-SE', 'si-LK', 'sk-SK', 'sl-SI', 'sma-NO', 'sma-SE', 'smj-NO', 'smj-SE', 'smn-FI', 'sms-FI', 'sq-AL', 'sr-Cyrl-BA', 'sr-Cyrl-CS', 'sr-Cyrl-ME', 'sr-Cyrl-RS', 'sr-Latn-BA', 'sr-Latn-CS', 'sr-Latn-ME', 'sr-Latn-RS', 'sv-FI', 'sv-SE', 'sw-KE', 'syr-SY', 'ta-IN', 'te-IN', 'tg-Cyrl-TJ', 'th-TH', 'tk-TM', 'tn-ZA', 'tr-TR', 'tt-RU', 'tzm-Latn-DZ', 'ug-CN', 'uk-UA', 'ur-PK', 'uz-Cyrl-UZ', 'uz-Latn-UZ', 'vi-VN', 'wo-SN', 'xh-ZA', 'yo-NG', 'zh-CN', 'zh-HK', 'zh-MO', 'zh-SG', 'zh-TW', 'zu-ZA');

	foreach ($locales as $locale) {
		$locale_region = locale_get_region($locale);
		$locale_language = locale_get_primary_language($locale);
		$locale_array = array(
			'language' => $locale_language,
			'region' => $locale_region
		);

		if (strtoupper($country_code) == $locale_region && $language_code == '') {
			return locale_compose($locale_array);
		} elseif (strtoupper($country_code) == $locale_region && strtolower($language_code) == $locale_language) {
			return locale_compose($locale_array);
		}
	}

	return 'en-US';
}

function get_language($code)
{
	$all_lan = ['aa' => 'Afar', 'ab' => 'Abkhazian', 'af' => 'Afrikaans', 'am' => 'Amharic', 'ar' => 'Arabic', 'ar-ae' => 'Arabic (U.A.E.)', 'ar-bh' => 'Arabic (Bahrain)', 'ar-dz' => 'Arabic (Algeria)', 'ar-eg' => 'Arabic (Egypt)', 'ar-iq' => 'Arabic (Iraq)', 'ar-jo' => 'Arabic (Jordan)', 'ar-kw' => 'Arabic (Kuwait)', 'ar-lb' => 'Arabic (Lebanon)', 'ar-ly' => 'Arabic (Libya)', 'ar-ma' => 'Arabic (Morocco)', 'ar-om' => 'Arabic (Oman)', 'ar-qa' => 'Arabic (Qatar)', 'ar-sa' => 'Arabic (Saudi Arabia)', 'ar-sy' => 'Arabic (Syria)', 'ar-tn' => 'Arabic (Tunisia)', 'ar-ye' => 'Arabic (Yemen)', 'as' => 'Assamese', 'ay' => 'Aymara', 'az' => 'Azerí', 'ba' => 'Bashkir', 'be' => 'Belarusian', 'bg' => 'Bulgarian', 'bh' => 'Bihari', 'bi' => 'Bislama', 'bn' => 'Bengali', 'bo' => 'Tibetan', 'br' => 'Breton', 'ca' => 'Catalan', 'co' => 'Corsican', 'cs' => 'Czech', 'cy' => 'Welsh', 'da' => 'Danish', 'de' => 'German', 'de-at' => 'German (Austria)', 'de-ch' => 'German (Switzerland)', 'de-li' => 'German (Liechtenstein)', 'de-lu' => 'German (Luxembourg)', 'div' => 'Divehi', 'dz' => 'Bhutani', 'el' => 'Greek', 'en' => 'English', 'en-au' => 'English (Australia)', 'en-bz' => 'English (Belize)', 'en-ca' => 'English (Canada)', 'en-gb' => 'English (United Kingdom)', 'en-ie' => 'English (Ireland)', 'en-jm' => 'English (Jamaica)', 'en-nz' => 'English (New Zealand)', 'en-ph' => 'English (Philippines)', 'en-tt' => 'English (Trinidad)', 'en-us' => 'English (United States)', 'en-za' => 'English (South Africa)', 'en-zw' => 'English (Zimbabwe)', 'eo' => 'Esperanto', 'es' => 'Spanish', 'es-ar' => 'Spanish (Argentina)', 'es-bo' => 'Spanish (Bolivia)', 'es-cl' => 'Spanish (Chile)', 'es-co' => 'Spanish (Colombia)', 'es-cr' => 'Spanish (Costa Rica)', 'es-do' => 'Spanish (Dominican Republic)', 'es-ec' => 'Spanish (Ecuador)', 'es-es' => 'Spanish (España)', 'es-gt' => 'Spanish (Guatemala)', 'es-hn' => 'Spanish (Honduras)', 'es-mx' => 'Spanish (Mexico)', 'es-ni' => 'Spanish (Nicaragua)', 'es-pa' => 'Spanish (Panama)', 'es-pe' => 'Spanish (Peru)', 'es-pr' => 'Spanish (Puerto Rico)', 'es-py' => 'Spanish (Paraguay)', 'es-sv' => 'Spanish (El Salvador)', 'es-us' => 'Spanish (United States)', 'es-uy' => 'Spanish (Uruguay)', 'es-ve' => 'Spanish (Venezuela)', 'et' => 'Estonian', 'eu' => 'Basque', 'fa' => 'Farsi', 'fi' => 'Finnish', 'fj' => 'Fiji', 'fo' => 'Faeroese', 'fr' => 'French', 'fr-be' => 'French (Belgium)', 'fr-ca' => 'French (Canada)', 'fr-ch' => 'French (Switzerland)', 'fr-lu' => 'French (Luxembourg)', 'fr-mc' => 'French (Monaco)', 'fy' => 'Frisian', 'ga' => 'Irish', 'gd' => 'Gaelic', 'gl' => 'Galician', 'gn' => 'Guarani', 'gu' => 'Gujarati', 'ha' => 'Hausa', 'he' => 'Hebrew', 'hi' => 'Hindi', 'hr' => 'Croatian', 'hu' => 'Hungarian', 'hy' => 'Armenian', 'ia' => 'Interlingua', 'id' => 'Indonesian', 'ie' => 'Interlingue', 'ik' => 'Inupiak', 'in' => 'Indonesian', 'is' => 'Icelandic', 'it' => 'Italian', 'it-ch' => 'Italian (Switzerland)', 'iw' => 'Hebrew', 'ja' => 'Japanese', 'ji' => 'Yiddish', 'jw' => 'Javanese', 'ka' => 'Georgian', 'kk' => 'Kazakh', 'kl' => 'Greenlandic', 'km' => 'Cambodian', 'kn' => 'Kannada', 'ko' => 'Korean', 'kok' => 'Konkani', 'ks' => 'Kashmiri', 'ku' => 'Kurdish', 'ky' => 'Kirghiz', 'kz' => 'Kyrgyz', 'la' => 'Latin', 'ln' => 'Lingala', 'lo' => 'Laothian', 'ls' => 'Slovenian', 'lt' => 'Lithuanian', 'lv' => 'Latvian', 'mg' => 'Malagasy', 'mi' => 'Maori', 'mk' => 'FYRO Macedonian', 'ml' => 'Malayalam', 'mn' => 'Mongolian', 'mo' => 'Moldavian', 'mr' => 'Marathi', 'ms' => 'Malay', 'mt' => 'Maltese', 'my' => 'Burmese', 'na' => 'Nauru', 'nb-no' => 'Norwegian (Bokmal)', 'ne' => 'Nepali (India)', 'nl' => 'Dutch', 'nl-be' => 'Dutch (Belgium)', 'nn-no' => 'Norwegian', 'no' => 'Norwegian (Bokmal)', 'oc' => 'Occitan', 'om' => '(Afan)/Oromoor/Oriya', 'or' => 'Oriya', 'pa' => 'Punjabi', 'pl' => 'Polish', 'ps' => 'Pashto/Pushto', 'pt' => 'Portuguese', 'pt-br' => 'Portuguese (Brazil)', 'qu' => 'Quechua', 'rm' => 'Rhaeto-Romanic', 'rn' => 'Kirundi', 'ro' => 'Romanian', 'ro-md' => 'Romanian (Moldova)', 'ru' => 'Russian', 'ru-md' => 'Russian (Moldova)', 'rw' => 'Kinyarwanda', 'sa' => 'Sanskrit', 'sb' => 'Sorbian', 'sd' => 'Sindhi', 'sg' => 'Sangro', 'sh' => 'Serbo-Croatian', 'si' => 'Singhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'sm' => 'Samoan', 'sn' => 'Shona', 'so' => 'Somali', 'sq' => 'Albanian', 'sr' => 'Serbian', 'ss' => 'Siswati', 'st' => 'Sesotho', 'su' => 'Sundanese', 'sv' => 'Swedish', 'sv-fi' => 'Swedish (Finland)', 'sw' => 'Swahili', 'sx' => 'Sutu', 'syr' => 'Syriac', 'ta' => 'Tamil', 'te' => 'Telugu', 'tg' => 'Tajik', 'th' => 'Thai', 'ti' => 'Tigrinya', 'tk' => 'Turkmen', 'tl' => 'Tagalog', 'tn' => 'Tswana', 'to' => 'Tonga', 'tr' => 'Turkish', 'ts' => 'Tsonga', 'tt' => 'Tatar', 'tw' => 'Twi', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'us' => 'English', 'uz' => 'Uzbek', 'vi' => 'Vietnamese', 'vo' => 'Volapuk', 'wo' => 'Wolof', 'xh' => 'Xhosa', 'yi' => 'Yiddish', 'yo' => 'Yoruba', 'zh' => 'Chinese', 'zh-cn' => 'Chinese (China)', 'zh-hk' => 'Chinese (Hong Kong SAR)', 'zh-mo' => 'Chinese (Macau SAR)', 'zh-sg' => 'Chinese (Singapore)', 'zh-tw' => 'Chinese (Taiwan)', 'zu' => 'Zulu'];

	return $all_lan[$code];
}



function alert_message()
{
	if (isset($_SESSION['status'])) :
?>
		<div class="alert alert-success" role="alert">
			<?php echo $_SESSION['status'];
			unset($_SESSION['status']); ?>
		</div>
	<?php endif;

	if (isset($_SESSION['status_error'])) :
	?>
		<div class="alert alert-danger" role="alert">
			<?php echo $_SESSION['status_error'];
			unset($_SESSION['status_error']); ?>
		</div>
	<?php endif;

	if (isset($_SESSION['status_warning'])) :
	?>
		<div class="alert alert-warning" role="alert">
			<?php echo $_SESSION['status_warning'];
			unset($_SESSION['status_warning']); ?>
		</div>
<?php endif;
}

function redirect($url, $status)
{
	$_SESSION['status'] = $status;
	header("Location:" . $url);
	exit();
}


function uploadFile($file, $uploadDir)
{
	$targetDir = rtrim($uploadDir, '/') . '/';
	$fileName = basename($file["name"]);
	$targetFile = $targetDir . $fileName;
	$uploadOk = 1;
	$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
	$response = ['status' => false, 'message' => '', 'file_path' => ''];

	// Check file size (limit to 5MB)
	if ($file["size"] > 500000000) {
		$response['message'] = "Sorry, your file is too large.";
		$uploadOk = 0;
	}

	// // Allow certain file formats
	// $allowedTypes = ['jpg', 'png', 'jpeg', 'gif','svg', 'ai', 'eps', 'pdf'];
	// if (!in_array($fileType, $allowedTypes)) {
	// 	$response['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	// 	$uploadOk = 0;
	// }

	if ($uploadOk == 1) {
		if (move_uploaded_file($file["tmp_name"], $targetFile)) {
			$response['status'] = true;
			$response['message'] = "The file " . $fileName . " has been uploaded.";
			$response['file_path'] = $targetFile;
			$response['file_name'] = $fileName;
		} else {
			$response['message'] = "Sorry, there was an error uploading your file.";
		}
	}

	return $response;
}


function generateSlug($string)
{
	$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
	return $slug;
}

function make_slug_base_name($text, string $divider = '-')
{
	// replace non letter or digits by divider
	$text = preg_replace('~[^\pL\d]+~u', $divider, $text);

	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);

	// trim
	$text = trim($text, $divider);

	// remove duplicate divider
	$text = preg_replace('~-+~', $divider, $text);

	// lowercase
	$text = strtolower($text);

	if (empty($text)) {
		return 'n-a';
	}

	return $text;
}


//make unique slug base post(any post string) name
function isSlugUnique($slug, $table, $crud)
{
    $existingSlug = $crud->getData($table, "slug='".$slug."'", "", "slug");
    return empty($existingSlug);
}


function make_unique_slug_base_name($text, $table, $crud, $divider = '-')
{
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, $divider);
    $text = preg_replace('~-+~', $divider, $text);
    $slug = strtolower($text);

    $uniqueSlug = $slug;
    $counter = 1;

    while (!isSlugUnique($uniqueSlug, $table, $crud)) {
        $uniqueSlug = $slug . $divider . $counter;
        $counter++;
    }

    return $uniqueSlug;
}



function userHasPermission($userId, $permissionName)
{
	global $crud;
	$query = "
        SELECT permissions.permission_name
        FROM users
        JOIN role_permissions ON users.role_id = role_permissions.role_id
        JOIN permissions ON role_permissions.permission_id = permissions.id
        WHERE users.id = ? AND permissions.permission_name = ?
    ";

	$stmt = $crud->connection->prepare($query);
	$stmt->bind_param('is', $userId, $permissionName);
	$stmt->execute();
	$stmt->store_result();

	return $stmt->num_rows > 0;
}
?>