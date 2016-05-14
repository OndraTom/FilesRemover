<?php

namespace Totem\Tools;

use RecursiveIteratorIterator;
use	RecursiveDirectoryIterator;

/**
 * Files renaming with preg_replace.
 * 
 * @author oto
 */
class FilesRenamer
{
	/**
	 * Selected files directory.
	 * 
	 * @var string
	 */
	protected $directory;
	
	
	/**
	 * Regular expression for file rename.
	 * 
	 * @var string
	 */
	protected $regex;
	
	
	/**
	 * Replacement for regex find.
	 * 
	 * @var string
	 */
	protected $replacement;
	
	
	/**
	 * Maximum possible replacements.
	 * 
	 * @var int
	 */
	protected $limit;
	
	
	/**
	 * Recursive mode - if is true, the directory will be processed recursively.
	 * 
	 * @var bool
	 */
	protected $recursiveMode = false;
	
	
	/**
	 * Debug mode state.
	 * 
	 * @var bool
	 */
	protected $debugMode = false;
	
	
	/**
	 * @param string $directory
	 * @param string $regex
	 * @param string $replacement
	 */
	public function __construct($directory, $regex, $replacement = '', $limit = -1)
	{
		$this->directory	= $directory;
		$this->regex		= $regex;
		$this->replacement	= $replacement;
		$this->limit		= $limit;
	}
	
	
	/**
	 * Returns directory iterator.
	 * 
	 * @return Iterator
	 */
	protected function getDirectoryFiles()
	{
		$directoryIterator = new RecursiveDirectoryIterator(
					$this->directory,
					RecursiveDirectoryIterator::SKIP_DOTS
		);
		
		// If the recursive mode is enabled we create a recursive iterator.
		if ($this->recursiveMode)
		{
			$directoryIterator = new RecursiveIteratorIterator(
					$directoryIterator,
					RecursiveIteratorIterator::CHILD_FIRST
			);
		}
		
		return $directoryIterator;
	}
	
	
	/**
	 * Sets the recursive mode.
	 * 
	 * @param bool $flag
	 */
	public function setRecursiveMode($flag)
	{
		$this->recursiveMode = (bool) $flag;
	}
	
	
	/**
	 * Sets the debug mode.
	 * 
	 * @param bool $flag
	 */
	public function setDebugMode($flag)
	{
		$this->debugMode = (bool) $flag;
	}
	
	
	/**
	 * Renames files based on given criteria
	 */
	public function run()
	{
		foreach ($this->getDirectoryFiles() as $file)
		{
			$newFileName = preg_replace(
					$this->regex, 
					$this->replacement, 
					$file->getFileName(), 
					$this->limit
			);
			
			if ($this->debugMode)
			{
				echo 'Old file name: ' . $file->getFileName() . 
						' | New file name: ' . $newFileName . "\n\n";
			}
			else
			{
				rename($file->getPathname(), $newFileName);
			}
		}
		
		echo 'Done.';
	}
}