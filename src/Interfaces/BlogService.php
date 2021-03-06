<?php


namespace Revonia\BlogHub\Interfaces;


interface BlogService
{
    public function create($data);

    public function get($id);

    public function update($id, $data);

    public function delete($id);
}
