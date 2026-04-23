<?php

namespace Acre\A25\Query;

/**
 * While it seems wasteful to pass $query around as a parameter, it is necessary
 * because this interface can be used to build queries both as strings as well
 * as objects such as Doctrine_Query. Since $query can be a string, and since it
 * could be modified elsewhere between steps, we would have to enforce passing
 * by reference everywhere in order for it to be a class property, and that
 * could cause confusion.  This extra parameter is less costly than confusion
 * over pass-by-reference.
 */
interface QueryInterface
{
  public function select($query);
  public function from($query);
  public function groupBy($query);
}