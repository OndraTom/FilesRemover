<?php

declare(strict_types=1);

namespace Totem\Tools;

use Iterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Files renaming tools.
 * 
 * @author Ondøej Tom <info@ondratom.cz>
 */
final class FilesRenamer
{
	/**
	 * File types constants.
	 */
	const ITEM_FILE = 'file';
	const ITEM_DIR	= 'directory';
	
	
	/**
	 * Selected files directory.
	 * 
	 * @var string
	 */
	private $directory;
	
	
	/**
	 * Function for getting the new file name.
	 * 
	 * @var callable
	 */
	private $getNewFileName;
	
	
	/**
	 * Function for getting the new directory name.
	 * 
	 * @var callable
	 */
	private $getNewDirName;
	
	
	/**
	 * Recursive mode - if is true, the directory will be processed recursively.
	 * 
	 * @var bool
	 */
	private $recursiveMode = false;
	
	
	/**
	 * Debug mode state.
	 * 
	 * @var bool
	 */
	private $debugMode = false;
	
	
	/**
	 * @param string	$directory
	 * @param callable	$getNewFileName
	 * @param callable	$getNewDirName
	 */
	public function __construct(string $directory, callable $getNewFileName, callable $getNewDirName = null)
	{
		$this->directory		= $directory;
		$this->getNewFileName	= $getNewFileName;
		$this->getNewDirName	= $getNewDirName;
	}
	
	
	/**
	 * Returns directory iterator.
	 * 
	 * @return Iterator
	 */
	private function getDirectoryFiles(): Iterator
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
	public function setRecursiveMode(bool $flag)
	{
		$this->recursiveMode = $flag;
	}
	
	
	/**
	 * Sets the debug mode.
	 * 
	 * @param bool $flag
	 */
	public function setDebugMode(bool $flag)
	{
		$this->debugMode = $flag;
	}
	
	
	/**
	 * Renames files based on given criteria
	 */
	public function run()
	{
		foreach ($this->getDirectoryFiles() as $file)
		{
			// File
			if ($file->isFile())
			{
				$item			= self::ITEM_FILE;
				$newItemName	= call_user_func($this->getNewFileName, $file->getFileName());
			}
			// Directory + renaming function is set.
			else if ($file->isDir() && isset($this->getNewDirName))
			{
				$item			= self::ITEM_DIR;
				$newItemName	= call_user_func($this->getNewDirName, $file->getFileName());
			}
			else
			{
				continue;
			}
			
			if ($this->debugMode)
			{
				echo 'Old ' . $item . ' name: ' . $file->getFileName() . 
						' | New file name: ' . $newItemName . "\n\n";
			}
			else
			{
				rename($file->getPathname(), $newItemName);
			}
		}
		
		echo 'Done.';
	}
}