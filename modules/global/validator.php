<?php if (!defined('B2')) exit(0);
/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2014-2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 07.11.2022
 * @version: 1.0
 *
 * Validator
 */

class validator {
	// Validate filters
	const FILTER_VALIDATE_BOOLEAN = FILTER_VALIDATE_BOOLEAN; // Returns TRUE for "1", "true", "on" and "yes". Returns FALSE otherwise. If FILTER_NULL_ON_FAILURE is set, FALSE is returned only for "0", "false", "off", "no", and "", and NULL is returned for all non-boolean values.
	const FILTER_VALIDATE_BOOL = FILTER_VALIDATE_BOOL; // Returns TRUE for "1", "true", "on" and "yes". Returns FALSE otherwise. If FILTER_NULL_ON_FAILURE is set, FALSE is returned only for "0", "false", "off", "no", and "", and NULL is returned for all non-boolean values.
	const FILTER_VALIDATE_EMAIL = FILTER_VALIDATE_EMAIL; // Validates whether the value is a valid e-mail address. In general, this validates e-mail addresses against the syntax in RFC 822, with the exceptions that comments and whitespace folding and dotless domain names are not supported.
	const FILTER_VALIDATE_FLOAT = FILTER_VALIDATE_FLOAT; // Validates value as float, and converts to float on success.
	const FILTER_VALIDATE_INT = FILTER_VALIDATE_INT; // Validates value as integer, optionally from the specified range, and converts to int on success.
	const FILTER_VALIDATE_IP = FILTER_VALIDATE_IP; // Validates value as IP address, optionally only IPv4 or IPv6 or not from private or reserved ranges.
	const FILTER_VALIDATE_MAC = FILTER_VALIDATE_MAC; // Validates value as MAC address.
	const FILTER_VALIDATE_REGEXP = FILTER_VALIDATE_REGEXP; // Validates value against regexp, a Perl-compatible regular expression.
	const FILTER_VALIDATE_URL = FILTER_VALIDATE_URL; // Validates value as URL (according to » http://www.faqs.org/rfcs/rfc2396), optionally with required components. Beware a valid URL may not specify the HTTP protocol http:// so further validation may be required to determine the URL uses an expected protocol, e.g. ssh:// or mailto:. Note that the function will only find ASCII URLs to be valid; internationalized domain names (containing non-ASCII characters) will fail.
    // Custom
	const FILTER_VALIDATE_STRING = 10001;			// Validates string. Only used with flags
	const FILTER_VALIDATE_MIN_LENGTH = 10002;		// Returns FALSE if the length of the string value is less than this number of symbols
	const FILTER_VALIDATE_MAX_LENGTH = 10003;		// Returns FALSE if the length of the string value is more than this number of symbols
	const FILTER_VALIDATE_EXACT_LENGTH = 10004;		// Returns FALSE if the length of the string value doesn't match the specified number of symbols
	const FILTER_VALIDATE_SLUG = 10005;				// Returns false if the string cannot be used as URL slug
	const FILTER_VALIDATE_LATIN_LETTERS = 10006;	// Returns false if the string contains any non-latin letters
	const FILTER_VALIDATE_DATE = 10007;				// Returns false if the string is not a valid DATE value in MySQL format
	const FILTER_VALIDATE_TIME = 10008;				// Returns false if the string is not a valid TIME value in MySQL format
	const FILTER_VALIDATE_DATETIME = 10009;			// Returns false if the string is not a valid DATETIME value in MySQL format

	// Sanitize filters
	const FILTER_SANITIZE_EMAIL = FILTER_SANITIZE_EMAIL; // Remove all characters except letters, digits and !#$%&'*+-=?^_`{|}~@.[].
	const FILTER_SANITIZE_ENCODED = FILTER_SANITIZE_ENCODED; // URL-encode string, optionally strip or encode special characters.
	const FILTER_SANITIZE_ADD_SLASHES = FILTER_SANITIZE_ADD_SLASHES; // Apply addslashes().
	const FILTER_SANITIZE_NUMBER_FLOAT = FILTER_SANITIZE_NUMBER_FLOAT; // Remove all characters except digits, +- and optionally .,eE.
	const FILTER_SANITIZE_NUMBER_INT = FILTER_SANITIZE_NUMBER_INT; // Remove all characters except digits, plus and minus sign.
	const FILTER_SANITIZE_SPECIAL_CHARS = FILTER_SANITIZE_SPECIAL_CHARS; // HTML-escape '"<>& and characters with ASCII value less than 32, optionally strip or encode other special characters.
	const FILTER_SANITIZE_FULL_SPECIAL_CHARS = FILTER_SANITIZE_FULL_SPECIAL_CHARS; // Equivalent to calling htmlspecialchars() with ENT_QUOTES set. Encoding quotes can be disabled by setting FILTER_FLAG_NO_ENCODE_QUOTES. Like htmlspecialchars(), this filter is aware of the default_charset and if a sequence of bytes is detected that makes up an invalid character in the current character set then the entire string is rejected resulting in a 0-length string. When using this filter as a default filter, see the warning below about setting the default flags to 0.
	const FILTER_SANITIZE_URL = FILTER_SANITIZE_URL; // Remove all characters except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=.
	const FILTER_UNSAFE_RAW = FILTER_UNSAFE_RAW; // Do nothing, optionally strip or encode special characters. This filter is also aliased to FILTER_DEFAULT.;

	// Other filters
	const FILTER_CALLBACK = FILTER_CALLBACK; // Call user-defined function to filter data.

	// Flags
	const FILTER_FLAG_STRIP_LOW = FILTER_FLAG_STRIP_LOW; // Strips characters that have a numerical value <32. FILTER_SANITIZE_ENCODED, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_STRING, FILTER_UNSAFE_RAW
	const FILTER_FLAG_STRIP_HIGH = FILTER_FLAG_STRIP_HIGH; // Strips characters that have a numerical value >127. FILTER_SANITIZE_ENCODED, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_STRING, FILTER_UNSAFE_RAW
	const FILTER_FLAG_STRIP_BACKTICK = FILTER_FLAG_STRIP_BACKTICK; // Strips backtick characters. FILTER_SANITIZE_ENCODED, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_STRING, FILTER_UNSAFE_RAW
	const FILTER_FLAG_ALLOW_FRACTION = FILTER_FLAG_ALLOW_FRACTION; // Allows a period (.) as a fractional separator in numbers. FILTER_SANITIZE_NUMBER_FLOAT
	const FILTER_FLAG_ALLOW_THOUSAND = FILTER_FLAG_ALLOW_THOUSAND; // Allows a comma (,) as a thousands separator in numbers. FILTER_SANITIZE_NUMBER_FLOAT, FILTER_VALIDATE_FLOAT
	const FILTER_FLAG_ALLOW_SCIENTIFIC = FILTER_FLAG_ALLOW_SCIENTIFIC; // Allows an e or E for scientific notation in numbers. FILTER_SANITIZE_NUMBER_FLOAT
	const FILTER_FLAG_NO_ENCODE_QUOTES = FILTER_FLAG_NO_ENCODE_QUOTES; // If this flag is present, single (') and double (") quotes will not be encoded. FILTER_SANITIZE_STRING
	const FILTER_FLAG_ENCODE_LOW = FILTER_FLAG_ENCODE_LOW; // Encodes all characters with a numerical value <32. FILTER_SANITIZE_ENCODED, FILTER_SANITIZE_STRING, FILTER_SANITIZE_RAW
	const FILTER_FLAG_ENCODE_HIGH = FILTER_FLAG_ENCODE_HIGH; // Encodes all characters with a numerical value >127. FILTER_SANITIZE_ENCODED, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_STRING, FILTER_SANITIZE_RAW
	const FILTER_FLAG_ENCODE_AMP = FILTER_FLAG_ENCODE_AMP; // Encodes ampersands (&). FILTER_SANITIZE_STRING, FILTER_SANITIZE_RAW
	const FILTER_NULL_ON_FAILURE = FILTER_NULL_ON_FAILURE; // Returns NULL for unrecognized boolean values. FILTER_VALIDATE_BOOLEAN
	const FILTER_FLAG_ALLOW_OCTAL = FILTER_FLAG_ALLOW_OCTAL; // Regards inputs starting with a zero (0) as octal numbers. This only allows the succeeding digits to be 0-7. FILTER_VALIDATE_INT
	const FILTER_FLAG_ALLOW_HEX = FILTER_FLAG_ALLOW_HEX; // Regards inputs starting with 0x or 0X as hexadecimal numbers. This only allows succeeding characters to be a-fA-F0-9. FILTER_VALIDATE_INT
	const FILTER_FLAG_EMAIL_UNICODE = FILTER_FLAG_EMAIL_UNICODE; // Allows the local part of the email address to contain Unicode characters. FILTER_VALIDATE_EMAIL
	const FILTER_FLAG_IPV4 = FILTER_FLAG_IPV4; // Allows the IP address to be in IPv4 format. FILTER_VALIDATE_IP
	const FILTER_FLAG_IPV6 = FILTER_FLAG_IPV6; // Allows the IP address to be in IPv6 format. FILTER_VALIDATE_IP
	const FILTER_FLAG_NO_PRIV_RANGE = FILTER_FLAG_NO_PRIV_RANGE; // Fails validation for the following private IPv4 ranges: 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16. Fails validation for the IPv6 addresses starting with FD or FC. FILTER_VALIDATE_IP
	const FILTER_FLAG_NO_RES_RANGE = FILTER_FLAG_NO_RES_RANGE; // Fails validation for the following reserved IPv4 ranges: 0.0.0.0/8, 169.254.0.0/16, 127.0.0.0/8 and 240.0.0.0/4. Fails validation for the following reserved IPv6 ranges: ::1/128, ::/128, ::ffff:0:0/96 and fe80::/10. FILTER_VALIDATE_IP
	const FILTER_FLAG_PATH_REQUIRED = FILTER_FLAG_PATH_REQUIRED; // Requires the URL to contain a path part. FILTER_VALIDATE_URL
	const FILTER_FLAG_QUERY_REQUIRED = FILTER_FLAG_QUERY_REQUIRED; // Requires the URL to contain a query string. FILTER_VALIDATE_URL
	const FILTER_REQUIRE_SCALAR = FILTER_REQUIRE_SCALAR; // Requires the value to be scalar.
	const FILTER_REQUIRE_ARRAY = FILTER_REQUIRE_ARRAY; // Requires the value to be an array.
	const FILTER_FORCE_ARRAY = FILTER_FORCE_ARRAY; // If the value is a scalar, it is treated as array with the scalar value as only element.
	// Custom
	const FILTER_FLAG_NOT_EMPTY = 268435456;	// The value must not to be empty. FILTER_VALIDATE_STRING

	// VARIABLES
	protected array $data_source = [];		// Data source to use
	protected bool $use_names = true;		// To use values names through the localization function or simply show "The value is too..."
	protected string $l10n_prefix = '';		// Localization prefix to use. Ex: 'users.' means that data source array keys will be used
											// like __('users.some_key'). It will be ignored if $use_names is FALSE.
	protected array $errors = [];			// Array of validation errors found. Only one error per source data key

	//------------------------------------------------------------- CONSTRUCTORS --------------------------------------------------------------------
	public function __construct(array $data_source, bool $use_names = false, string $l10n_prefix = '') {
		$this->data_source = $data_source;
		$this->use_names = $use_names;
		$this->l10n_prefix = $l10n_prefix;
	}
	public function validator(array $data_source, bool $use_names = false, string $l10n_prefix = '') : control {
		return self::__construct();
	}

	//--------------------------------------------------------------- GET ERRORS --------------------------------------------------------------------
	public function get_errors() : array { return $this->errors; }

	/**------------------------------------------------------------- CHECK SOURCE -------------------------------------------------------------------
	 * Checks the value of the specified source data array key. Adds errors to the internal errors list
	 * @param string $data_source_key - A key of the data source array. It is also will be used as localization key with the local $l10n_prefix.
	 * Thus, if $l10n_prefix is 'users.', and key is 'name', than error message will contain __('users.' . 'name') or simply __('users.name')
	 * @param int $type - Validation filter type, like validator::FILTER_VALIDATE_FLOAT
	 * @param array $flags - Validation filter flag, like validator::FILTER_FLAG_ALLOW_THOUSAND
	 * @return string|null - NULL if the validation successful or string with error if we found one
	 */
	public function check_source(string $data_source_key, int $type, array $flags = []) : ?string {
		$value_name = $this->use_names ? __($this->l10n_prefix . $data_source_key) : __('controls.the_value');

		// Check NULL value
		if ($this->data_source[$data_source_key] === null) $result = __('controls.validator_must_not_be_empty');
		// Validate the value
		else $result = validator::check($value_name, $this->data_source[$data_source_key], $type, $flags);

		// Save the error. Only one error per source data key supported (that is how PHP arrays addition works)
		if ($result !== null) $this->errors+= [$data_source_key => $result];

		return $result;
	}

	//-------------------------------------------------------------- NOT EMPTY ----------------------------------------------------------------------
	public function not_empty(string $data_source_key) {
		return $this->check_source($data_source_key, validator::FILTER_VALIDATE_STRING, [validator::FILTER_FLAG_NOT_EMPTY]);
	}

	//------------------------------------------------------------- MIN LENGTH ----------------------------------------------------------------------
	public function min_length(string $data_source_key, int $min_length = 1) {
		return $this->check_source($data_source_key, validator::FILTER_VALIDATE_MIN_LENGTH, [$min_length]);
	}

	//------------------------------------------------------------- MAX LENGTH ----------------------------------------------------------------------
	public function max_length(string $data_source_key, int $max_length = 255) {
		return $this->check_source($data_source_key, validator::FILTER_VALIDATE_MAX_LENGTH, [$max_length]);
	}

	//------------------------------------------------------------ EXACT LENGTH ---------------------------------------------------------------------
	public function exact_length(string $data_source_key, int $exact_length = 255) {
		return $this->check_source($data_source_key, validator::FILTER_VALIDATE_EXACT_LENGTH, [$exact_length]);
	}

	//--------------------------------------------------------- ONLY LATIN LETTERS ------------------------------------------------------------------
	public function only_latin_letters(string $data_source_key) {
		return $this->check_source($data_source_key, validator::FILTER_VALIDATE_LATIN_LETTERS);
	}

	//################################################################ STATIC #######################################################################
    /**---------------------------------------------------------------- CHECK -----------------------------------------------------------------------
	 * Checks the value using the specified filter
     * @param string $name - The name of the parameter to use in error messages
     * @param mixed $value - Value to check
     * @param int $type - Validation filter type, like validator::FILTER_VALIDATE_FLOAT
     * @param array $flags - Validation filter flag, like validator::FILTER_FLAG_ALLOW_THOUSAND
     * @return string|null - NULL if the validation successful or string with error if we found one
     */
	public static function check(string $name, mixed $value, int $type, array $flags = []) : ?string {
        $error = null;

		// Validation filters
		if ($type < 512) {
			if (($result = filter_var($value, $type, count($flags) ? $flags : [])) === false) {
				if ($type == validator::FILTER_VALIDATE_BOOL || $type == validator::FILTER_VALIDATE_BOOLEAN) {
					if ($value === 0 || $value === 1 || $value === true || $value === false) return null;
				}
                return $name . __('controls.validator_has_wrong_value');
            } else return null;
		}

		// Sanitize filters
		if ($type < 10000) {
			if (($result = filter_var($value, $type, count($flags) ? $flags : [])) === false) {
                return $name . __('controls.validator_has_wrong_value');
            } else return null;
		}

		// Custom filters
		switch ($type) {
            case self::FILTER_VALIDATE_STRING:
				if (strval($value) != $value) { $error = $name . __('controls.validator_must_be_string'); }
				foreach ($flags as $Item) if ($Item == self::FILTER_FLAG_NOT_EMPTY && $value == '') {
                    $error = $name . __('controls.validator_must_not_be_empty');
                }
				break;
            case self::FILTER_VALIDATE_MIN_LENGTH:
				if (!count($flags)) { $error = __('controls.validator_flag_empty'); break; }
				if (mb_strlen(strval($value), 'utf8') <= intval($flags[0])) {
                    $error = $name . __('controls.validator_is_too_short') . intval($flags[0]) . __('controls.validator_characters');
                }
				break;
            case self::FILTER_VALIDATE_MAX_LENGTH:
				if (!count($flags)) { $error = __('controls.validator_flag_empty'); break; }
				if (mb_strlen(strval($value), 'utf8') <= intval($flags[0])) return null;
                $error = $name . __('controls.validator_is_too_long') . (intval($flags[0])+1) . __('controls.validator_characters');
				break;
            case self::FILTER_VALIDATE_EXACT_LENGTH:
				if (!count($flags)) { $error = __('controls.validator_flag_empty'); break; }
				if (mb_strlen(strval($value), 'utf8') == intval($flags[0])) return null;
                $error = $name . __('controls.validator_is_not_exact_length') . intval($flags[0]) . __('controls.validator_characters');
				break;
            case self::FILTER_VALIDATE_SLUG:
                if (strval($value) != $value) { $error = $name . __('controls.validator_must_be_string'); }
                if (__string_to_slug($value) != $value) { $error = $name . __('controls.validator_must_be_proper_url_segment'); }
                break;
			case self::FILTER_VALIDATE_LATIN_LETTERS:
				if (preg_match("/^[a-zA-Z]+$/", $value) != 1) $error = $name . __('controls.validator_only_latin_letters');
				break;
			case self::FILTER_VALIDATE_DATE:
				if (date('Y-m-d', strtotime($value)) != $value) $error = $name . __('controls.validator_not_valid_date');
				break;
			case self::FILTER_VALIDATE_TIME:
				if (date('H:i:s', strtotime($value)) != $value) $error = $name . __('controls.validator_not_valid_time');
				break;
			case self::FILTER_VALIDATE_DATETIME:
				if (date('Y-m-d H:i:s', strtotime($value)) != $value &&
					date('Y-m-d', strtotime($value)) != $value) $error = $name . __('controls.validator_not_valid_datetime');
				break;
		}

        return $error;
	}

	//----------------------------------------------------------- CHECK NOT EMPTY -------------------------------------------------------------------
	public static function check_not_empty(string $name, string $value) : ?string {
		return validator::check($name, $value, validator::FILTER_VALIDATE_STRING, [validator::FILTER_FLAG_NOT_EMPTY]);
	}

	//---------------------------------------------------------- CHECK MIN LENGTH -------------------------------------------------------------------
	public static function check_min_length(string $name, string $value, $min_length = 1) : ?string {
		return validator::check($name, $value, validator::FILTER_VALIDATE_MIN_LENGTH, [$min_length]);
	}

	//----------------------------------------------------------- CHECK MAX LENGTH ------------------------------------------------------------------
	public static function check_max_length(string $name, string $value, int $max_length = 255) : ?string {
		return validator::check($name, $value, validator::FILTER_VALIDATE_MAX_LENGTH, [$max_length]);
	}

	//---------------------------------------------------------- CHECK EXACT LENGTH -----------------------------------------------------------------
	public static function check_exact_length(string $name, string $value, int $exact_length = 255) : ?string {
		return validator::check($name, $value, validator::FILTER_VALIDATE_EXACT_LENGTH, [$exact_length]);
	}

	//--------------------------------------------------------- ONLY LATIN LETTERS ------------------------------------------------------------------
	public static function check_only_latin_letters(string $name, string $value) {
		return validator::check($name, $value, validator::FILTER_VALIDATE_LATIN_LETTERS);
	}

}

