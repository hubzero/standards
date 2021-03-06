<?php
/**
 * Php_Sniffs_WhiteSpace_DisallowSpaceIndentSniff
 *
 * Throws errors if spaces and tabs are incorrectly interspersed for indentation
 *
 * @category  PHP
 * @package   standards
 * @author    Sam Wilson <samwilson@purdue.edu>
 */
class Php_Sniffs_WhiteSpace_DisallowSpaceIndentSniff implements PHP_CodeSniffer_Sniff
{
	/**
	 * Returns an array of tokens for which this test wants to listen
	 *
	 * @return array
	 */
	public function register()
	{
		return array(T_WHITESPACE);
	}

	/**
	 * Processes the test
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document
	 * @param int                  $stackPtr  The position of the current token in
	 *                                        the stack passed in $tokens
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		// Make sure this is whitespace used for indentation
		$line = $tokens[$stackPtr]['line'];
		if ($stackPtr > 0 && $tokens[($stackPtr - 1)]['line'] === $line)
		{
			return;
		}

		if (strpos($tokens[$stackPtr]['content'], ' ') !== false)
		{
			// Can't start with a space
			// Can't have spaces in between tabs
			if (preg_match('/(^[\t]*[ ]+[\t]+)/', $tokens[$stackPtr]['content']))
			{
				$error = 'Tabs must be used to indent lines; improper use of spaces.';
				$phpcsFile->addError($error, $stackPtr, 'SpacesUsed');
			}
			else if (preg_match('/(^ )/', $tokens[$stackPtr]['content']) && $tokens[$stackPtr + 1]['type'] !== 'T_OBJECT_OPERATOR')
			{
				$error = 'Tabs must be used to indent lines; line cannot begin with spaces.';
				$phpcsFile->addError($error, $stackPtr, 'StartsWithSpaces');
			}
		}
	}
}