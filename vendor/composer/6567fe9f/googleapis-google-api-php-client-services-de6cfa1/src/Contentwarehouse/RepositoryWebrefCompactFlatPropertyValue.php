<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Contentwarehouse;

class RepositoryWebrefCompactFlatPropertyValue extends \Google\Collection
{
  protected $collection_key = 'value';
  /**
   * @var string[]
   */
  public $predicateEncodedMid;
  /**
   * @var string
   */
  public $propertyName;
  protected $valueType = RepositoryWebrefCompactKgValue::class;
  protected $valueDataType = 'array';
  public $value;

  /**
   * @param string[]
   */
  public function setPredicateEncodedMid($predicateEncodedMid)
  {
    $this->predicateEncodedMid = $predicateEncodedMid;
  }
  /**
   * @return string[]
   */
  public function getPredicateEncodedMid()
  {
    return $this->predicateEncodedMid;
  }
  /**
   * @param string
   */
  public function setPropertyName($propertyName)
  {
    $this->propertyName = $propertyName;
  }
  /**
   * @return string
   */
  public function getPropertyName()
  {
    return $this->propertyName;
  }
  /**
   * @param RepositoryWebrefCompactKgValue[]
   */
  public function setValue($value)
  {
    $this->value = $value;
  }
  /**
   * @return RepositoryWebrefCompactKgValue[]
   */
  public function getValue()
  {
    return $this->value;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RepositoryWebrefCompactFlatPropertyValue::class, 'Google_Service_Contentwarehouse_RepositoryWebrefCompactFlatPropertyValue');
