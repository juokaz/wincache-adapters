<?php

class App_Cache_WinCache extends Doctrine_Cache_Driver
{
    /**
     * constructor
     *
     * @param array $options    associative array of cache driver options
     */
    public function __construct($options = array())
    {
        if ( ! extension_loaded('wincache')) {
            throw new Doctrine_Cache_Exception('The wincache extension must be loaded for using this backend !');
        }
        parent::__construct($options);
    }

    /**
     * Fetch a cache record from this cache driver instance
     *
     * @param string $id cache id
     * @param boolean $testCacheValidity        if set to false, the cache validity won't be tested
     * @return mixed  Returns either the cached data or false
     */
    protected function _doFetch($id, $testCacheValidity = true)
    {
        $cache = wincache_ucache_get($id, $success);
		
		if ($success === false)
			return false;
			
		return $cache;
    }

    /**
     * Test if a cache record exists for the passed id
     *
     * @param string $id cache id
     * @return mixed false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    protected function _doContains($id)
    {
        return wincache_ucache_exists($id);
    }

    /**
     * Save a cache record directly. This method is implemented by the cache
     * drivers and used in Doctrine_Cache_Driver::save()
     *
     * @param string $id        cache id
     * @param string $data      data to cache
     * @param int $lifeTime     if != false, set a specific lifetime for this cache record (null => infinite lifeTime)
     * @return boolean true if no problem
     */
    protected function _doSave($id, $data, $lifeTime = false)
    {
        return wincache_ucache_add($id, $data, (int) $lifeTime);
    }

    /**
     * Remove a cache record directly. This method is implemented by the cache
     * drivers and used in Doctrine_Cache_Driver::delete()
     *
     * @param string $id cache id
     * @return boolean true if no problem
     */
    protected function _doDelete($id)
    {
        return wincache_ucache_delete($id);
    }

    /**
     * Fetch an array of all keys stored in cache
     *
     * @return array Returns the array of cache keys
     */
    protected function _getCacheKeys()
    {
        $ci = wincache_ucache_info();
        $keys = array();

        foreach ($ci['ucache_entries'] as $entry) {
          $keys[] = $entry['key_name'];
        }
        return $keys;
    }
}