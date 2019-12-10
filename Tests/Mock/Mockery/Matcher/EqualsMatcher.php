<?php
namespace Giftcards\ModRewrite\Tests\Mock\Mockery\Matcher;

use Mockery\Matcher\MatcherAbstract;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\IsEqual;

class EqualsMatcher extends MatcherAbstract
{
	protected $constraint;
	
	
	/**
	 * @param string $expected
	 */
	public function __construct($expected, $delta = 0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false) {

		$this->constraint = new IsEqual(
				$expected, $delta, $maxDepth, $canonicalize, $ignoreCase
		);
		parent::__construct($expected);
	}

	public function match(&$actual) {

		try {
			
			$this->constraint->evaluate($actual);
			return true;
		} catch (AssertionFailedError $e) {
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
