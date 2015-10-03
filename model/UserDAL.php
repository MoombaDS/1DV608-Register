<?php

class UserDAL {
	private static $filePath = 'UserData/'; // Filepath should ideally not be accessible to the server
	private static $fileName = '_usercookie.data';
	private static $loginFileName = '_userlogin.data';

	/**
	 * Load a User object from the file which contains the username and hashed token.
	 *
	 * @return a User object containing the username and hashed token, or null if such does not exist
	 */
	public function getUserWithToken(User $user) {
		try {
			$fileContent = @file_get_contents(self::$filePath . $user->getUsername() . self::$fileName);
		} catch (Exception $e) {
			// No file found
			return null;
		}
		if ($fileContent !== FALSE) {
			return unserialize($fileContent);
		}
		return null;
	}

	/**
	 * Save a user and hashed token into the file. Since there's only one user we won't bother with the possibility
	 * of saving multiple users with tokens.
	 *
	 * @return null
	 */
	public function saveUserWithToken(User $user) {
		$content = serialize($user);

		file_put_contents(self::$filePath . $user->getUsername() . self::$fileName, $content);
	}

	public function saveUserRegistration(User $user) {
		if ($this->getRegisteredUser($user) != null) {
			throw new Exception('Cannot register an existing username!');
		} else {
			$content = serialize($user);

			file_put_contents(self::$filePath . $user->getUsername() . self::$loginFileName, $content);
		}
	}

	public function getRegisteredUser(User $user) {
		try {
			$fileContent = @file_get_contents(self::$filePath . $user->getUsername() . self::$loginFileName);
		} catch (Exception $e) {
			// No file found
			return null;
		}
		if ($fileContent !== FALSE) {
			return unserialize($fileContent);
		}
		return null;
	}
}