<?php
namespace Gridder\Persisters;
/**
 *
 * @author Martin
 */
interface IPersister
{
    public function save($key, $data);
    public function get($key);
    public function keyExists($key);
    public function delete($key);
}