<?php

namespace App\classes;

use App\Models\ConfigrationModel;
use Illuminate\Support\Facades\Storage;

/**
 * Class that implements the concept of an LRU Cache
 * using an associative array as a naive hashmap, and a doubly linked list
 * to control the access and insertion order.
 *
 * @author Rogério Vicente
 * @license MIT (see the LICENSE file for details)
 */
class LRUCache
{
    // protected $id = 0;
    // object Node representing the head of the list
    private $head;

    // object Node representing the tail of the list
    private $tail;
    // Array representing a naive hashmap (TODO needs to pass the key through a hash function)
    private $hashmap;

    public function cacheContent()
    {
        return $this->hashmap;
    }
    /**
     * @param int $capacity the max number of elements the cache allows
     */
    public function __construct()
    {
        $this->hashmap = array();
        $this->head = new Node(null, null);
        $this->tail = new Node(null, null);
        $this->head->setNext($this->tail);
        $this->tail->setPrevious($this->head);

        session()->put('hitCount', 0);
        session()->put('missCount', 0);
        session()->put('requestsCount', 0);
        $configData = ConfigrationModel::first();
        session()->put('policy', $configData->replacment_policy);
        session()->put('totalCacheSize', 0);
        session()->put('cacheCapacity', $configData->capacity);
        session()->put('lr_policy',  session()->get('policy'));
    }

    public function getSize()
    {
        return sizeof($this->hashmap);
    }

    /**
     * Get an element with the given key
     * @param string $key the key of the element to be retrieved
     * @return mixed the content of the element to be retrieved
     */
    public function get($key)
    {
        if (!isset($this->hashmap[$key])) {
            return null;
        }

        $node = $this->hashmap[$key];
        if (count($this->hashmap) == 1) {
            return $node->getData()[0];
        }
        // refresh the access
        $this->detach($node);
        $this->attach($this->head, $node);
        return $node->getData()[0];
    }

    protected function encodeImage($image)
    {
        $path = public_path('uploads/' . $image);
        $encodeIimage = base64_encode(file_get_contents($path));
        return $encodeIimage;
    }


    public function getCapacity(){
        return session()->get('cacheCapacity') * pow(10, 5);
    }

    /**
     * Inserts a new element into the cache
     * @param string $key the key of the new element
     * @param string $data the content of the new element
     * @return boolean true on success, false if cache has zero capacity
     */
    public function put($key, $data)
    {
        if ($this->getCapacity() <= 0) {
            return false;
        }
        $imageEncoded = $this->encodeImage($data);
        $imageSize = Storage::disk('uploads')->size($data);
        if (isset($this->hashmap[$key]) && !empty($this->hashmap[$key])) {
            if ($imageSize >= $this->getCapacity()) {
                $this->remove($key);
                return false;
            }
            $node = $this->hashmap[$key];
            $totalSize = session()->get('totalCacheSize');
            $totalSize -= $node->getData()[1];
            $totalSize += $imageSize;
            session()->put('totalCacheSize', $totalSize);
            $this->adjustCacheSize();
            // update data
            $this->detach($node);
            $this->attach($this->head, $node);
            $node->setData([$imageEncoded,$imageSize]);
        } else {
            if ($imageSize >= $this->getCapacity()) {
                return false;
            }
            $totalSize = session()->get('totalCacheSize');
            session()->put('totalCacheSize', $totalSize + $imageSize);
            $this->adjustCacheSize();
            $node = new Node($key, [$imageEncoded,$imageSize]);
            $this->hashmap[$key] = $node;
            $this->attach($this->head, $node);
        }
        return true;
    }

    public function adjustCacheSize()
    {
        while (session()->get('totalCacheSize') > ($this->getCapacity())) {
            if (session()->get('policy') == 2) {
                $nodeToRemove = $this->tail->getPrevious();
                $totalSize = session()->get('totalCacheSize');
                session()->put('totalCacheSize', $totalSize - $nodeToRemove->getData()[1]);
                $this->detach($nodeToRemove);
                unset($this->hashmap[$nodeToRemove->getKey()]);
            } else {
                if ($this->getSize() > 0) {
                    $random_key = array_rand($this->hashmap);
                    $this->remove($random_key);
                }
            }
        }
    }
    /**
     * Removes a key from the cache
     * @param string $key key to remove
     * @return bool true if removed, false if not found
     */
    public function remove($key)
    {
        if (!isset($this->hashmap[$key])) {
            return false;
        }
        $nodeToRemove = $this->hashmap[$key];
        $totalSize = session()->get('totalCacheSize');
        session()->put('totalCacheSize', $totalSize - $nodeToRemove->getData()[1]);
        $this->detach($nodeToRemove);
        unset($this->hashmap[$nodeToRemove->getKey()]);
        return true;
    }

    /**
     * Adds a node to the head of the list
     * @param Node $head the node object that represents the head of the list
     * @param Node $node the node to move to the head of the list
     */
    private function attach($head, $node)
    {
        $node->setPrevious($head);
        $node->setNext($head->getNext());
        $node->getNext()->setPrevious($node);
        $node->getPrevious()->setNext($node);
    }

    /**
     * Removes a node from the list
     * @param Node $node the node to remove from the list
     */
    private function detach($node)
    {
        $node->getPrevious()->setNext($node->getNext());
        $node->getNext()->setPrevious($node->getPrevious());
    }
}

/**
 * Class that represents a node in a doubly linked list
 */
class Node
{
    /**
     * the key of the node, this might seem reduntant,
     * but without this duplication, we don't have a fast way
     * to retrieve the key of a node when we wan't to remove it
     * from the hashmap.
     */
    private $key;

    // the content of the node
    private $data;

    // the next node
    private $next;

    // the previous node
    private $previous;

    /**
     * @param string $key the key of the node
     * @param string $data the content of the node
     */
    public function __construct($key, $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    /**
     * Sets a new value for the node data
     * @param string the new content of the node
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Sets a node as the next node
     * @param Node $next the next node
     */
    public function setNext($next)
    {
        $this->next = $next;
    }

    /**
     * Sets a node as the previous node
     * @param Node $previous the previous node
     */
    public function setPrevious($previous)
    {
        $this->previous = $previous;
    }

    /**
     * Returns the node key
     * @return string the key of the node
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the node data
     * @return mixed the content of the node
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns the next node
     * @return Node the next node of the node
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Returns the previous node
     * @return Node the previous node of the node
     */
    public function getPrevious()
    {
        return $this->previous;
    }
}
