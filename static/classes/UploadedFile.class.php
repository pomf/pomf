<?php

class UploadedFile {
	/* Public attributes */
	public $name;
	public $mime;
	public $size;
	public $tempfile;
	public $error;

	/* Cached checksums */
	private $sha1;
	private $crc32;

	/**
	 * Generates the SHA1 or returns the cached SHA1 hash for the file.
	 *
	 * @return string
	 */
	public function get_sha1 () {
		if (!$this->sha1)
			$this->sha1 = sha1_file($this->tempfile);

		return $this->sha1;
	}

	/**
	 * Generates the CRC32 or returns the cached CRC32 hash for the file.
	 *
	 * @return string
	 */
	public function get_crc32 () {
		if (!$this->crc32)
			$this->crc32 = hash_file('crc32b', $this->tempfile);

		return $this->crc32;
	}
}
?>
