<?php

namespace App\Repositories\Interface;

interface PageRepositoryInterface
{
    public function getAllPages();
    public function getParentPages();
    public function createPage(array $data);
    public function findPageById($id);
    public function updatePage($id, array $data);
    public function deletePage($id);
    public function rearrangePages(array $orderArr);
}