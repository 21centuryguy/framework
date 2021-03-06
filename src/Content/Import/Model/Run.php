<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   framework
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Hubzero\Content\Import\Model;

use Hubzero\Database\Relational;
use Hubzero\Utility\Date;
use User;

/**
 * Class for an import run
 */
class Run extends Relational
{
	/**
	 * The table namespace
	 *
	 * @var string
	 */
	protected $namespace = 'import';

	/**
	 * Default order by for model
	 *
	 * @var  string
	 */
	public $orderBy = 'ran_at';

	/**
	 * Default order direction for select queries
	 *
	 * @var  string
	 */
	public $orderDir = 'desc';

	/**
	 * Fields and their validation criteria
	 *
	 * @var  array
	 */
	protected $rules = array(
		'import_id' => 'positive|nonzero'
	);

	/**
	 * Automatic fields to populate every time a row is created
	 *
	 * @var  array
	 **/
	public $initiate = array(
		'ran_at',
		'ran_by'
	);

	/**
	 * Generates automatic created field value
	 *
	 * @return  string
	 * @since   2.0.0
	 **/
	public function automaticRanAt()
	{
		$dt = new Date('now');
		return $dt->toSql();
	}

	/**
	 * Generates automatic created by field value
	 *
	 * @return  int
	 * @since   2.0.0
	 **/
	public function automaticRanBy()
	{
		return User::get('id');
	}

	/**
	 * Get parent import record
	 *
	 * @return  object
	 */
	public function import()
	{
		return $this->belongsToOne('Hubzero\Content\Import\Model\Import', 'import_id');
	}

	/**
	 * Add to the processed number on this run
	 *
	 * @param   integer  $number  Number to increpemnt by
	 * @return  void
	 */
	public function processed($number = 1)
	{
		$this->set('processed', (int)$this->get('processed', 0) + $number);
		$this->save();
	}
}
