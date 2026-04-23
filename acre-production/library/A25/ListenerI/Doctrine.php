<?php

interface A25_ListenerI_Doctrine
{
	/**
	 * Implementations of this function must check, and act only on the correct
	 * subclass of A25_DoctrineRecord, since this is called during every
	 * initialization.
	 * 
	 * This is fired in A25_DoctrineRecord->setUp().  So if you want a class to
	 * use this, you must make sure that if it overrides setUp(), that it calls
	 * parent::setUp().
	 */
	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord);
}
