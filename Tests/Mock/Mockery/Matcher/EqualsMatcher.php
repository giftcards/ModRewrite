<?php
namespace Giftcards\ModRewrite\Tests\Mock\Mockery\Matcher;

use Giftcards\ModRewrite\MatchState;
use Mockery\Matcher\MatcherAbstract;

class EqualsMatcher extends MatcherAbstract
{
	protected $constraint;
	
	
	/**
	 * @param string $expected
	 */
	public function __construct($expected, $delta = 0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false) {

		$this->constraint = new \PHPUnit_Framework_Constraint_IsEqual(
				$expected, $delta, $maxDepth, $canonicalize, $ignoreCase
		);
	}

	/**
	 * @param unkown $actual
	 */
	public function match(&$actual) {

		try {
			
			$this->constraint->evaluate($actual);
			return true;
		} catch (\PHPUnit_Framework_AssertionFailedError $e) {
			return false;
		}
	}
	
	/**
	 * 
	 */
	public function __toString() {

		return '<Equals>';
	}
}
