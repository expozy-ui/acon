<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

/** =========================================================
 * Class DDos
 * ========================================================== */
class DDos
{
		private const FILEPATH = BASEPATH."core/ddos.php";
			
		public static function check():void {
			$file = self::FILEPATH;
			
			require_once BASEPATH.'/core/saas_key.php';
			require_once 'src/anti-ddos-lib.php';

			if(file_exists(self::FILEPATH) == true ){
					include_once $file;
					
					if( DDOS == 1){
						//activated DDOS
							
							if (!isset($_SERVER['REMOTE_ADDR'])) {
										die();
							}

							$data = [
								'anti_ddos_protection_enable' => true, // Switch to control AntiDDoS state.
								'anti_ddos_debug' => true,             // Activate debug statements.
								'skip_not_rated_ua' => false,          // Test visitors against trusted UserAgent's list.
								'secure_cookie_days' => 180,           // Days to use secure cookie.
								'redirect_delay' => 6,                 // Delay in seconds before redirection to original URL.
								'remote_ip' => $_SERVER['REMOTE_ADDR'],
								'secure_label' => 'ct_anti_ddos_key',
								'test_headless' => true,               //block visitors with headless mode (such a selenium)
								'server_url' => isset($_SERVER['HTTPS']) ? 'https://' . $_SERVER['HTTP_HOST'] : 'http://' . $_SERVER['HTTP_HOST'],

								// Secret key salt to avoid copy/past of the Cookie between visitors.
								// ATTENTION!!!
								// YOU MUST GENERATE NEW $anti_ddos_salt BEFORE USE IT ON YOUR OWN SITE.
								// ATTENTION!!!
								'anti_ddos_salt' =>SAAS_KEY
							];

							if ($data['anti_ddos_protection_enable'] || antiDdosCheckDatFileExist()) {
									antiDdosProtectionMain($data);
							}
							
							//after ddos protection
							$res = Api::cache(false)->get()->settings();
							
							if(isset($res['ddos']) && $res['ddos'] == 0){
								self::change_status(0);
							}
					} else {
						
						$res = Api::cache(false)->get()->settings();
						if(isset($res['ddos']) && $res['ddos'] == 1){
							self::change_status(1);
						}
					}
					
			} else {
				//create_file
				self::create_ddos_file($file);
			}

		}
		
		public static function change_status(int $value):void {
				// get file content
				$content = file_get_contents(self::FILEPATH);

				// replace key
				$newContent = preg_replace('/define\("DDOS",".*?"\);/', 'define("DDOS","' . $value . '");', $content, 1);

				// save new content
				file_put_contents(self::FILEPATH, $newContent);
		}
		
		private static function create_ddos_file(string $path){
			$text = '<?php
			if (!defined("_VALID_PHP")) { die(\'Direct access to this location is not allowed.\'); }
			define("DDOS","0");?>';
			
			file_put_contents($path, $text);
			
		}
	
}

